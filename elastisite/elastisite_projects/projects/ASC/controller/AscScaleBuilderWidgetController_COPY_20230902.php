<?php
namespace projects\ASC\controller;

use App;
use framework\component\helper\DateUtils;
use framework\component\helper\StringHelper;
use framework\component\parent\StyleSheetResponse;
use framework\component\parent\WidgetController;
use framework\packages\EventPackage\entity\CalendarEvent;
use framework\packages\EventPackage\service\CalendarEventSaver;
use framework\packages\UserPackage\repository\UserAccountRepository;
use Google\Client as Google_Client;
use Google\Service\Calendar as Google_Service_Calendar;
use projects\ASC\entity\AscEntry;
use projects\ASC\entity\AscUnit;
use projects\ASC\service\AscConfigService;
use projects\ASC\service\AscRequestService;
use projects\ASC\service\AscSaveService;
use projects\ASC\service\AscCalendarEventService;
use projects\ASC\service\ProjectTeamworkService;
use projects\ASC\service\AscUnitBuilderService;
use projects\ASC\service\AscTechService;

class AscScaleBuilderWidgetController extends WidgetController
{
    public function createViews($ControlPanelView = true, $PrimarySubjectBarView = true, $UnitBuilderView = true, $test = false)
    {
        self::adjustPrimarySubjectBarState();

        return [
            'views' => [
                'ControlPanelView' => $ControlPanelView ? $this->createControlPanelView() : null,
                'PrimarySubjectBarView' => $PrimarySubjectBarView ? $this->createPrimarySubjectBarView() : null,
                'UnitBuilderView' => $UnitBuilderView ? $this->createUnitBuilderView() : null,
            ],
            'data' => [
                'closeModal' => false
            ]
        ];
    }

    /**
    * Route: [name: asc_scaleBuilder_widget, paramChain: /asc/scaleBuilder/widget]
    */
    public function ascScaleBuilderWidgetAction()
    {
        // dump('vidzsit');exit;
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');
        $ascScale = AscRequestService::getScaleFromUrl();
        // $subject = AscUnitBuilderService::getSubjectFromUrl();

        $views = null;
        if ($ascScale) {
            $views = $this->createViews();
        }

        // dump($views);exit;

        $viewPath = 'projects/ASC/view/widget/AscScaleBuilderWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AscScaleBuilderWidget', $viewPath, [
                'currentAscScaleAvailable' => $ascScale ? true : false,
                'views' => $views ? $views['views'] : null
            ]),
            'data' => [
                'closeModal' => $views ? $views['data']['closeModal'] : null
            ]
        ];

        // var_dump($views);exit;

        return $this->widgetResponse($response);
    }

    public static function adjustPrimarySubjectBarState()
    {
        App::getContainer()->wireService('projects/ASC/service/AscConfigService');

        $configSession = App::getContainer()->getSession()->get('AscScaleBuilder-primarySubjectBarState');
        if (!$configSession) {
            $configSession = AscConfigService::PRIMARY_SUBJECTBAR_STATE_OPENED;
            App::getContainer()->getSession()->set('AscScaleBuilder-primarySubjectBarState', $configSession);
        }
    }

    /**
    * Route: [name: asc_scaleBuilder_applySetting, paramChain: /asc/scaleBuilder/applySetting]
    */
    public function ascScaleBuilderApplySettingAction()
    {
        App::getContainer()->wireService('projects/ASC/service/AscConfigService');
        self::adjustPrimarySubjectBarState();

        foreach (App::getContainer()->getRequest()->getAll() as $requestKey => $requestedValue) {
            $requestedValue = StringHelper::mendValue($requestedValue);
            if ($requestKey == 'swipePrimarySubjectBar') {
                $configSession = App::getContainer()->getSession()->get('AscScaleBuilder-primarySubjectBarState');
                if ($configSession == AscConfigService::PRIMARY_SUBJECTBAR_STATE_OPENED) {
                    $configSession = AscConfigService::PRIMARY_SUBJECTBAR_STATE_CLOSED;
                } else {
                    $configSession = AscConfigService::PRIMARY_SUBJECTBAR_STATE_OPENED;
                }

                App::getContainer()->getSession()->set('AscScaleBuilder-primarySubjectBarState', $configSession);
                // dump($requestKey);
                // dump($requestedValue);
            }
        }


        // dump(StringHelper::mendValue(App::getContainer()->getRequest()->get('swipePrimarySubject')));
        // dump('ascScaleBuilderApplySettingAction');exit;
        // applySettingCallback
        $response = [
            'view' => '',
            'data' => [
                'closeModal' => false,
                'callback' => 'applySettingCallback'
            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    public function createControlPanelView()
    {
        App::getContainer()->wireService('projects/ASC/service/AscTechService');
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');
        $ascScale = AscRequestService::getScaleFromUrl();
        $subject = AscRequestService::getSubjectFromUrl();
        $juxtaposedSubject = AscRequestService::getJuxtaposedSubjectFromUrl();
        $currentSubjectTranslationReference = AscTechService::findSubjectConfigValue($subject, 'translationReferencePlural');
        $juxtaposedSubjectTranslationReference = $juxtaposedSubject ? AscTechService::findSubjectConfigValue($juxtaposedSubject, 'translationReferencePlural') : '';

        $viewPath = 'projects/ASC/view/widget/AscScaleBuilderWidget/ControlPanel.php';
        $view = $this->renderWidget('AscScaleBuilderWidget_ControlPanel', $viewPath, [
            'viewType' => $this->getViewType(),
            'ascScale' => $ascScale,
            'parentAscUnit' => AscRequestService::getParentObjectFromUrl(),
            'currentSubject' => $subject,
            'juxtaposedSubject' => $juxtaposedSubject,
            'currentSubjectTranslationReference' => $currentSubjectTranslationReference,
            'juxtaposedSubjectTranslationReference' => $juxtaposedSubjectTranslationReference,
            'message' => ''
        ]);

        return $view;
    }

    public function createPrimarySubjectBarView()
    {
        App::getContainer()->wireService('projects/ASC/service/AscTechService');
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');
        $primarySubjectConfig = AscTechService::getPrimarySubjectConfig();
        $ascScale = AscRequestService::getScaleFromUrl();
        $subject = AscRequestService::getSubjectFromUrl();
        $juxtaposedSubject = AscRequestService::getJuxtaposedSubjectFromUrl();
        $currentSubjectTranslationReference = AscTechService::findSubjectConfigValue($subject, 'translationReferenceSingular');

        $viewPath = 'projects/ASC/view/widget/AscScaleBuilderWidget/PrimarySubjectBar/PrimarySubjectBar.php';
        $view = $this->renderWidget('AscScaleBuilderWidget_PrimarySubjectBar', $viewPath, [
            'viewType' => $this->getViewType(),
            'primarySubjectConfig' => $primarySubjectConfig,
            'ascScale' => $ascScale,
            'currentSubject' => $subject,
            'juxtaposedSubject' => $juxtaposedSubject,
            'currentSubjectTranslationReference' => $currentSubjectTranslationReference,
            'message' => ''
        ]);

        return $view;
    }

    public static function googleApiTest()
    {
        // Google API hitelesítés és inicializáció
        $client = new Google_Client();
        dump($client);

        // $client->setApplicationName('My Calendar App');
        // $client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
        // $client->setAuthConfig('path/to/your/credentials.json'); // A hitelesítési adatok elérési útvonala
        // $client->setAccessType('offline');
        // $client->setPrompt('select_account consent');

        // $service = new Google_Service_Calendar($client);

        // // Esemény létrehozása
        // $event = new Google_Service_Calendar_Event(array(
        // 'summary' => 'Esemény neve',
        // 'location' => 'Helyszín',
        // 'start' => array(
        // 'dateTime' => '2023-08-19T10:00:00',
        // 'timeZone' => 'Europe/Budapest',
        // ),
        // 'end' => array(
        // 'dateTime' => '2023-08-19T12:00:00',
        // 'timeZone' => 'Europe/Budapest',
        // ),
        // ));

    }


    /**
     * ================
     * MAIN view
     * ================
     * Minden, ami a UnitBuilder reszre kerül.
     * 
     * 1.: elemzi a kapott URL-t. 
     *  a.: van scale, de nincs currentSubject
    */
    public function createUnitBuilderView()
    {
        App::getContainer()->wireService('projects/ASC/service/AscTechService');
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');
        App::getContainer()->wireService('projects/ASC/service/AscUnitBuilderService');
        // App::getContainer()->wireService('projects/ASC/service/ScaleDataService');

        // $ascScale = AscRequestService::getScaleFromUrl();
        // $currentSubject = AscRequestService::getSubjectFromUrl();
        // $juxtaposedSubject = AscRequestService::getJuxtaposedSubjectFromUrl();

        // self::googleApiTest();exit;

        $processedRequestData = AscRequestService::getProcessedRequestData();

        // dump($processedRequestData);exit;

        // App::getContainer()->wireService('projects/ASC/service/AscConfigService');
        // if (App::getContainer()->getSession()->get('AscScaleBuilder-primarySubjectBarState') == AscConfigService::PRIMARY_SUBJECTBAR_STATE_CLOSED && !$currentSubject && !$juxtaposedSubject) {
        //     App::getContainer()->getSession()->set('AscScaleBuilder-primarySubjectBarState', AscConfigService::PRIMARY_SUBJECTBAR_STATE_OPENED);
        // }

        /**
         * @todo: checking permissions
         * Meanwhile I did some steps.
        */
        $viewType = $this->getViewType();
        $listViewData = [];
        $unitDataArray = [];

        /**
         * Csak subject van. 
         * -----------------
         * Felsoroljuk a subject ala tartozo unitokat
        */
        if ($processedRequestData['subject'] && !$processedRequestData['juxtaposedSubject']) {
            if ($viewType == AscRequestService::VIEW_TYPE_LIST_VIEW) {
                $listViewData = AscUnitBuilderService::getUnitDataArrayOfSubject($processedRequestData['ascScale'], $processedRequestData['subject']);
            }
            if ($viewType == AscRequestService::VIEW_TYPE_COLUMN_VIEW) {
                $unitDataArray = AscUnitBuilderService::getColumnUnitDataArrayOfSubject($processedRequestData['ascScale'], $processedRequestData['subject']);
            }

        /**
         * Ez volt regen, de egyelore ez discontinued.
         * -------------------------------------------
        */
        } elseif ($processedRequestData['subject'] && $processedRequestData['juxtaposedSubject']) {
            // Egyelore ez az eset nem fog mukodni.

        /**
         * Csak parent van
         * ---------------
         * Felsoroljuk a parent ala tartozo unitokat
        */
        } elseif ($processedRequestData['parentAscUnit']) {
            if ($viewType == AscRequestService::VIEW_TYPE_LIST_VIEW) {
                $listViewData = AscUnitBuilderService::getUnitDataArrayOfParent($processedRequestData['parentAscUnit']);
            }

        /**
         * Csak unit van
         * ---------------
         * Felsoroljuk a unit fole tartozo parenteket, oszlopnezetben
        */
        } elseif ($processedRequestData['ascUnit']) {
            if ($viewType == AscRequestService::VIEW_TYPE_COLUMN_VIEW) {
                $unitDataArray = AscUnitBuilderService::getColumnViewData($processedRequestData['ascUnit']);
            }
        /**
         * Barmi mas eset
         * --------------
         * Betoljuk a dashboard-ot.
        */ 
        } else {
            return $this->createDashboardView();
        }

        // $primarySubjectConfig = AscTechService::getPrimarySubjectConfig();
        // dump($primarySubjectConfig);exit;
        // dump($unitDataArray);exit;
        // dump($processedRequestData);exit;
        if ($processedRequestData['subject'] == AscTechService::SUBJECT_STAT) {
            $viewPath = 'projects/ASC/view/widget/AscScaleBuilderWidget/Statistics/Statistics.php';
        } else {
            $viewPath = 'projects/ASC/view/widget/AscScaleBuilderWidget/UnitBuilder/'.$viewType.'.php';
        }

        /**
         * Notice that there is a listDataArray and a unitDataArray.
         * Reason: ListView has a totally different data structure.
        */
        $view = $this->renderWidget('AscScaleBuilderWidget_UnitBuilder', $viewPath, [
            // 'primarySubjectConfig' => $primarySubjectConfig,
            'viewType' => $viewType,
            'ascScale' => $processedRequestData['ascScale'],
            'subject' => $processedRequestData['subject'],
            'parentAscUnit' => $processedRequestData['parentAscUnit'],
            'parentAscUnitId' => $processedRequestData['parentAscUnit'] ? $processedRequestData['parentAscUnit']->getId() : null,
            'listViewData' => $listViewData,
            'unitDataArray' => $unitDataArray,
            'juxtaposedSubject' => $processedRequestData['juxtaposedSubject']
        ]);

        return $view;
    }

    public function getViewType()
    {
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');
        $processedRequestData = AscRequestService::getProcessedRequestData();
        $viewType = $processedRequestData['columnView'] ? AscRequestService::VIEW_TYPE_COLUMN_VIEW : AscRequestService::VIEW_TYPE_LIST_VIEW;

        return $viewType;
    }

    public function createDashboardView_OLD()
    {
        App::getContainer()->wireService('projects/ASC/service/AscCalendarEventService');
        $viewPath = 'projects/ASC/view/AscScaleBuilder/Dashboard/Dashboard.php';
        $view = $this->renderWidget('AscScaleBuilderWidget_Dashboard', $viewPath, AscCalendarEventService::getDashboardData(App::getContainer()->getUser()->getUserAccount()));

        return $view;
    }

    public function createDashboardView()
    {
        // App::getContainer()->wireService('projects/ASC/service/AscCalendarEventService');
        $viewPath = 'projects/ASC/view/AscScaleBuilder/Dashboard/blank.php';
        $view = $this->renderWidget('AscScaleBuilderWidget_Dashboard', $viewPath, []);

        return $view;
    }

    /**
    * Route: [name: asc_scaleBuilder_addUnit, paramChain: /asc/scaleBuilder/addUnit]
    */
    public function ascScaleBuilderAddUnitAction()
    {
        // $rawRequestData = [
        //     'scaleId' => 213000,
        //     'subject' => 'Goals',
        //     'unitId' => null,
        //     'parentId' => null,
        //     'position' => null,
        //     'ascScale' => null,
        //     'ascUnit' => null,
        //     'parentAscUnit' => null
        // ];

        App::getContainer()->wireService('projects/ASC/service/AscSaveService');
        $savedUnit = AscSaveService::savePrimarySubjectUnit();

        $views = $this->createViews(false, false, true);
        // dump($savedUnit);exit;
        $response = [
            'view' => '',
            'views' => $views['views'],
            'data' => [
                'unitId' => $savedUnit ? $savedUnit->getId() : null,
                'callback' => 'addUnitCallback',
                'savedUnitId' => $savedUnit ? $savedUnit->getId() : null,
                'closeModal' => $views['data']['closeModal']
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: asc_scaleBuilder_moveUnit, paramChain: /asc/scaleBuilder/moveUnit]
    */
    public function ascScaleBuilderMoveUnitAction()
    {
        App::getContainer()->wireService('projects/ASC/service/AscSaveService');
        // public static function moveUnitTo(int $movedAscUnitId, int $toAscUnitId, int $toParentAscUnitId, string $aheadOrBehind = AscUnitRepository::AHEAD)
        // $movedAscUnitId = App::getContainer()->getRequest()->get('movedUnitId');
        // $toAscUnitId = App::getContainer()->getRequest()->get('toUnitId');
        // $toParentAscUnitId = App::getContainer()->getRequest()->get('toParentId');
        // $newSubject = App::getContainer()->getRequest()->get('newSubject');

        // dump(App::getContainer()->getRequest()->getAll());exit;

        // if (empty($toParentAscUnitId)) {
        //     $toParentAscUnitId = null;
        // }
        // $aheadOrBehind = App::getContainer()->getRequest()->get('aheadOrBehind');
        $moveResult = AscSaveService::moveUnitTo();
        $savedUnit = $moveResult['savedUnit'];

        $views = $this->createViews(false, false, true);
        
        $response = [
            'view' => '',
            'views' => $views['views'],
            'data' => [
                'unitId' => App::getContainer()->getRequest()->get('unitId'),
                'callback' => 'moveUnitCallback',
                'savedUnitId' => $savedUnit ? $savedUnit->getId() : null,
                'closeModal' => $views['data']['closeModal'],
                'success' => $moveResult['success'],
                'errorMessage' => $moveResult['errorMessage']
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: asc_scaleBuilder_deleteUnit, paramChain: /asc/scaleBuilder/deleteUnit]
    */
    public function ascScaleBuilderDeleteUnitAction()
    {
        App::getContainer()->setService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepository = App::getContainer()->getService('AscUnitRepository');
        $id = (int)App::getContainer()->getRequest()->get('id');
        if ($ascUnitRepository->isDeletable($id)) {
            $ascUnitRepository->remove($id);
        }

        $views = $this->createViews(false, false, true);

        $response = [
            'view' => '',
            'views' => $views['views'],
            'data' => [
                'unitId' => $id,
                'callback' => 'deleteUnitCallback',
                'closeModal' => true,
                'modalLabel' => null
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: asc_scaleBuilder_editUnit, paramChain: /asc/scaleBuilder/editUnit]
    */
    public function ascScaleBuilderEditUnitAction()
    {
        // dump(App::getContainer()->getRequest()->getAll()); exit;
        // $unitId = (int)App::getContainer()->getRequest()->get('unitId');
        // $submitted = StringHelper::mendValue(App::getContainer()->getRequest()->get('submitted'));

        // dump(App::getContainer()->getRequest()->getAll());exit;

        App::getContainer()->wireService('projects/ASC/service/AscRequestService');
        App::getContainer()->wireService('projects/ASC/service/AscCalendarEventService');
        App::getContainer()->wireService('projects/ASC/service/ProjectTeamworkService');
        App::getContainer()->wireService('projects/ASC/service/AscUnitBuilderService');
        
        $processedRequestData = AscRequestService::getProcessedRequestData();

        // echo '<pre>';
        // dump($processedRequestData);exit;

        $ascUnit = $processedRequestData['ascUnit'];

        // $erm = $this->getERM();
        // $rel = $erm::getProcessedRelations($ascUnit, $ascUnit->getPropertyMap(), true);
        // dump($rel);
        // dump($ascUnit);exit;

        // if ($submitted) {

        // }
        // dump($processedRequestData['ascUnit']->getAscEntryHead());exit;

        $title = null;
        $description = null;
        $entryHead = null;

        if (!$ascUnit) {
            $response = [
                'view' => '',
                'data' => [
                    'unitId' => null,
                    'callback' => 'editUnitCallback',
                    'closeModal' => true,
                    'modalLabel' => null,
                    'submitted' => true,
                    'error' => trans('missing.unit')
                ]
            ];
            return $this->widgetResponse($response);
        }

        if ($ascUnit && $ascUnit->getAscEntryHead()) {
            $entryHead = $ascUnit->getAscEntryHead();
            // dump($entryHead);exit;
            $entry = $ascUnit->getAscEntryHead()->findEntry();
            if ($entry) {
                // $languageCode = $entry->getLanguageCode();
                $title = $entry->getTitle();
                $description = $entry->getDescription();
            }
        }

        // dump($ascUnit);exit;

        if (!$entryHead) {
            $entryHead = AscUnitBuilderService::createUnitEntryHead($ascUnit);
            // dump($ascUnit->getId());
            // dump($entryHead);exit;
        }

        $responsibleObject = $ascUnit->getResponsible();
        $responsible = $responsibleObject ? $responsibleObject->getId() : null;
        $administrationStance = $ascUnit->getAdministrationStance();
        // $dueType = $ascUnit->getDueType();
        // $recurrencePattern = $ascUnit->getRecurrencePattern();
        $status = $ascUnit->getStatus();
        // $dueTimeHours = null;
        // $dueTimeMinutes = null;

        // if ($ascUnit->getDueTime()) {
        //     $dueTimeParts = explode(':', $ascUnit->getDueTime());
        //     $dueTimeHours = $dueTimeParts[0];
        //     $dueTimeMinutes = $dueTimeParts[1];
        // }
        
        $dueErrorStr = '';
        $formIsValid = true;
        if ($processedRequestData['submitted']) {

            $removeCalendarEvent = StringHelper::mendValue(App::getContainer()->getRequest()->get('removeCalendarEvent'));
            // dump($removeCalendarEvent);exit;

            $title = App::getContainer()->getRequest()->get('title');
            $description = App::getContainer()->getRequest()->get('description');
            if (!$entry) {
                App::getContainer()->wireService('projects/ASC/entity/AscEntry');
                $entry = new AscEntry();
                $entry->setAscEntryHead($entryHead);
            }
            $entry->setTitle($title);
            $entry->setDescription($description);
            $entry->setLanguageCode(App::getContainer()->getLocale());
            $entry = $entry->getRepository()->store($entry);
            // dump($entry);exit;

            $status = App::getContainer()->getRequest()->get('status');
            $ascUnit->setStatus($status);

            $responsible = StringHelper::mendValue(App::getContainer()->getRequest()->get('responsible'));
            $administrationStance = StringHelper::mendValue(App::getContainer()->getRequest()->get('administrationStance'));

            if ($responsible == '-') {
                $responsible = null;
            }

            if ($administrationStance == '-') {
                $administrationStance = null;
            }

            $responsibleUserAccount = null;
            if ($responsible) {
                if (ProjectTeamworkService::validResponsible($responsible)) {
                    App::getContainer()->wireService('UserPackage/repository/UserAccountRepository');
                    $userAccountRepository = new UserAccountRepository();
                    $responsibleUserAccount = $userAccountRepository->find($responsible);
                    if (!$responsibleUserAccount) {
                        $responsible = null;
                    }
                } else {
                    $responsible = null;
                }
            }

            $ascUnit->setResponsible($responsibleUserAccount);
            // if ($responsible) {
            //     // dump(ProjectTeamworkService::validResponsible($responsible));exit;
            //     // dump($responsible);exit;
            //     if (ProjectTeamworkService::validResponsible($responsible)) {
            //         App::getContainer()->wireService('UserPackage/repository/UserAccountRepository');
            //         $userAccountRepository = new UserAccountRepository();
            //         $responsibleUserAccount = $userAccountRepository->find($responsible);
            //         if (!$responsibleUserAccount) {
            //             $responsible = null;
            //         } else {
            //             $ascUnit->setResponsible($responsibleUserAccount);
            //             // $ascUnit->getAscEntryHead()->getAscUnit()->setResponsible($responsibleUserAccount);
            //             // $ascUnit = $ascUnit->getRepository()->store($ascUnit);
            //             // dump($ascUnit); exit;
            //         }
            //     } else {
            //         $responsible = null;
            //     }
            // }

            // dump($responsibleUserAccount);exit;
            $ascUnit->setAdministrationStance($administrationStance);

            $ascUnit = $this->processDueDatePosts($ascUnit, $removeCalendarEvent);



            // dump($ascUnit);exit;
            if ($formIsValid) {
                $ascUnit = $ascUnit->getRepository()->store($ascUnit);
            }

            // dump($ascUnit);exit;
        }

        $modalLabel = '';
        if ($ascUnit->getSubject() && is_string($ascUnit->getSubject())) {
            $modalLabel = trans('edit.'.strtolower($ascUnit->getSubject()));
        }

        /**
         * Mivel a modal becsukasakor mindenkeppen ujratoltjuk a lapot, így ezt innen kivettem.
        */
        // $views = $this->createViews(false, false, $processedRequestData['submitted']);

        // $closeModal = false;

        // dump($ascUnit);exit;
        // if ($submitted) {
        //     dump($entryHead);
        //     dump($ascUnit);exit;
        // }

        // dump($dueDate);exit;
        $viewPath = 'projects/ASC/view/widget/AscScaleBuilderWidget/editUnit.php';
        $response = [
            'view' => $this->renderWidget('AscScaleBuilderWidget_UnitBuilder', $viewPath, [
                'ascUnit' => $ascUnit,
                'teamMembers' => ProjectTeamworkService::getTeamMembers(),
                'unitId' => $processedRequestData['unitId'],
                'title' => $title,
                'description' => $description,
                'responsible' => $responsible,
                'administrationStance' => $administrationStance,
                // 'dueType' => $dueType,
                // 'recurrencePattern' => $recurrencePattern,
                // 'dueDate' => $ascUnit->getDueEventFactory()->getStartDate(),
                // 'dueTimeHours' => $dueTimeHours,
                // 'dueTimeMinutes' => $dueTimeMinutes,
                // 'dueErrorStr' => $dueErrorStr,
                'status' => $status
                // 'title'
            ]),
            // 'views' => $views['views'],
            'data' => [
                'unitId' => $ascUnit->getId(),
                'callback' => 'editUnitCallback',
                'closeModal' => $formIsValid ? true : false,
                'modalLabel' => $modalLabel,
                'submitted' => $processedRequestData['submitted'],
                'error' => null
            ]
        ];

        return $this->widgetResponse($response);
    }

    private function processDueDatePosts(AscUnit $ascUnit, $removeCalendarEvent) : AscUnit
    {
        $calendarEvent = $ascUnit->getCalendarEvent();
        $calendarEvent = $calendarEvent ? : new CalendarEvent();

        // dump($removeCalendarEvent);
        if ($removeCalendarEvent) {
            $ascUnit->setCalendarEvent(null);
            $ascUnit = $ascUnit->getRepository()->store($ascUnit);
            // dump($ascUnit);exit;
            return $ascUnit;
        }

        // dump(App::getContainer()->getRequest()->getAll());exit;

        App::getContainer()->wireService('projects/ASC/entity/AscUnit');
        App::getContainer()->wireService('EventPackage/entity/CalendarEvent');
        App::getContainer()->wireService('EventPackage/service/CalendarEventSaver');

        $frequencyType = StringHelper::mendValue(App::getContainer()->getRequest()->get('frequencyType'));
        $recurrenceInterval = StringHelper::mendValue(App::getContainer()->getRequest()->get('recurrenceInterval'));
        $recurrenceUnit = StringHelper::mendValue(App::getContainer()->getRequest()->get('recurrenceUnit'));
        $entireDay = StringHelper::mendValue(App::getContainer()->getRequest()->get('entireDay'));
        $dueDate = StringHelper::mendValue(App::getContainer()->getRequest()->get('dueDate'));
        $dueTimeHours = App::getContainer()->getRequest()->get('dueTimeHours');
        $dueTimeMinutes = StringHelper::booleanToInt(App::getContainer()->getRequest()->get('dueTimeMinutes'));
        $recurrenceDayMon = StringHelper::booleanToInt(App::getContainer()->getRequest()->get('recurrenceDayMon'));
        $recurrenceDayTue = StringHelper::booleanToInt(App::getContainer()->getRequest()->get('recurrenceDayTue'));
        $recurrenceDayWed = StringHelper::booleanToInt(App::getContainer()->getRequest()->get('recurrenceDayWed'));
        $recurrenceDayThu = StringHelper::booleanToInt(App::getContainer()->getRequest()->get('recurrenceDayThu'));
        $recurrenceDayFri = StringHelper::booleanToInt(App::getContainer()->getRequest()->get('recurrenceDayFri'));
        $recurrenceDaySat = StringHelper::booleanToInt(App::getContainer()->getRequest()->get('recurrenceDaySat'));
        $recurrenceDaySun = StringHelper::booleanToInt(App::getContainer()->getRequest()->get('recurrenceDaySun'));

        $params = [
            'frequencyType' => $frequencyType,
            'eventType' => $ascUnit::DUE_CALENDAR_EVENT_TYPE,
            'recurrenceInterval' => $recurrenceInterval,
            'recurrenceUnit' => $recurrenceUnit,
            'entireDay' => $entireDay,
            'dueDate' => $dueDate,
            'dueTimeHours' => $dueTimeHours,
            'dueTimeMinutes' => $dueTimeMinutes,
            'recurrenceDayMon' => $recurrenceDayMon,
            'recurrenceDayTue' => $recurrenceDayTue,
            'recurrenceDayWed' => $recurrenceDayWed,
            'recurrenceDayThu' => $recurrenceDayThu,
            'recurrenceDayFri' => $recurrenceDayFri,
            'recurrenceDaySat' => $recurrenceDaySat,
            'recurrenceDaySun' => $recurrenceDaySun,
            'status' => $ascUnit->getStatus()
        ];

        $calendarEventSaver = new CalendarEventSaver();
        $calendarEvent = $calendarEventSaver->save($calendarEvent, $params);

        // dump($entireDay);exit;

        // FREQUENCY_TYPE_ONE_TIME esetén nincs további beállítás


        $ascUnit->setCalendarEvent($calendarEvent);
        $ascUnit = $ascUnit->getRepository()->store($ascUnit);

        // dump($ascUnit);exit;
        // Visszatérünk az elkészített entitással
        // return $calendarEvent;

        return $ascUnit;
    }

    /**
    * Route: [name: asc_scaleBuilder_imageGallery, paramChain: /asc/scaleBuilder/imageGallery]
    */
    public function ascScaleBuilderImageGalleryAction()
    {
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');
        $processedRequestData = AscRequestService::getProcessedRequestData();

        $viewPath = 'projects/ASC/view/widget/AscScaleBuilder/ImageGallery/html/ImageGallery.php';
        $response = [
            'view' => $this->renderWidget('AscScaleBuilderWidget_UnitBuilder', $viewPath, [
                // 'unitId' => $processedRequestData['unitId'],
                // 'title' => $title,
                // 'description' => $description,
                // 'dueDate' => $dueDate,
                // 'dueTimeHours' => $dueTimeHours,
                // 'dueTimeMinutes' => $dueTimeMinutes
                // 'title'
            ]),
            // 'views' => $views['views'],
            'data' => [
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
     * Less importance
    */

    /**
    * Route: [name: asc_scaleBuilder_DashboardTestWidget, paramChain: /asc/scaleBuilder/DashboardTestWidget]
    */
    public function dashboardTestWidgetAction()
    {
        $viewPath = 'projects/ASC/view/widget/DashboardTestWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AscScaleBuilderWidget', $viewPath, [
            ]),
            'data' => [
            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: asc_scaleBuilder_addJuxtaposedSubject, paramChain: /asc/scaleBuilder/addJuxtaposedSubject]
    */
    public function ascScaleBuilderAddJuxtaposedSubjectAction()
    {
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');
        // App::getContainer()->wireService('projects/ASC/service/ScaleDataService');

        $ascScale = AscRequestService::getScaleFromUrl();
        $currentSubject = AscRequestService::getSubjectFromUrl();
        $juxtaposedSubject = AscRequestService::getJuxtaposedSubjectFromUrl();

        App::getContainer()->wireService('projects/ASC/service/AscTechService');
        $primarySubjectConfig = AscTechService::getPrimarySubjectConfig();

        $viewPath = 'projects/ASC/view/widget/AscScaleBuilderWidget/juxtaposedSubjectSelector.php';
        $response = [
            'view' => $this->renderWidget('AscScaleBuilderWidget', $viewPath, [
                'primarySubjectConfig' => $primarySubjectConfig,
                'ascScale' => $ascScale,
                'currentSubject' => $currentSubject,
                'juxtaposedSubject' => $juxtaposedSubject,
            ]),
            'data' => [
                'closeModal' => true,
                'callback' => 'addJuxtaposedSubjectCallback'
            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }
}
