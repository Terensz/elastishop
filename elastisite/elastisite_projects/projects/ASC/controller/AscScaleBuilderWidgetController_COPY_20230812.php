<?php
namespace projects\ASC\controller;

use App;
use framework\component\helper\DateUtils;
use framework\component\helper\StringHelper;
use framework\component\parent\StyleSheetResponse;
use framework\component\parent\WidgetController;
use framework\packages\UserPackage\repository\UserAccountRepository;
use projects\ASC\entity\AscEntry;
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
    * Route: [name: asc_scaleBuilder_generalStyleSheet, paramChain: /asc/scaleBuilder/generalStyleSheet]
    */
    public function ascScaleBuilderGeneralStyleSheetAction()
    {
        $viewPath = 'projects/ASC/view/AscScaleBuilder/StyleSheet/generalStyleSheet.php';
        $view = $this->renderWidget('generalStyleSheet', $viewPath, [
            'currentAscScaleAvailable' => false
        ]);

        // dump($response);exit;

        return new StyleSheetResponse($view);
    }

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

        // dump($response);exit;

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
            'ascScale' => $ascScale,
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
            'primarySubjectConfig' => $primarySubjectConfig,
            'ascScale' => $ascScale,
            'currentSubject' => $subject,
            'juxtaposedSubject' => $juxtaposedSubject,
            'currentSubjectTranslationReference' => $currentSubjectTranslationReference,
            'message' => ''
        ]);

        return $view;
    }

    public function createUnitBuilderView()
    {
        App::getContainer()->wireService('projects/ASC/service/AscTechService');
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');
        // App::getContainer()->wireService('projects/ASC/service/ScaleDataService');

        $ascScale = AscRequestService::getScaleFromUrl();
        $currentSubject = AscRequestService::getSubjectFromUrl();
        $juxtaposedSubject = AscRequestService::getJuxtaposedSubjectFromUrl();

        // App::getContainer()->wireService('projects/ASC/service/AscConfigService');
        // if (App::getContainer()->getSession()->get('AscScaleBuilder-primarySubjectBarState') == AscConfigService::PRIMARY_SUBJECTBAR_STATE_CLOSED && !$currentSubject && !$juxtaposedSubject) {
        //     App::getContainer()->getSession()->set('AscScaleBuilder-primarySubjectBarState', AscConfigService::PRIMARY_SUBJECTBAR_STATE_OPENED);
        // }

        /**
         * @todo: checking permissions
        */

        $unitBuilderData = null;
        if ($currentSubject || $juxtaposedSubject) {
            $unitBuilderData = AscUnitBuilderService::getUnitBuilderData($ascScale, $currentSubject, $juxtaposedSubject);
        } else {
            return $this->createDashboardView();
        }

        $primarySubjectConfig = AscTechService::getPrimarySubjectConfig();
        // dump($primarySubjectConfig);exit;

        $viewPath = 'projects/ASC/view/widget/AscScaleBuilderWidget/UnitBuilder.php';
        $view = $this->renderWidget('AscScaleBuilderWidget_UnitBuilder', $viewPath, [
            'primarySubjectConfig' => $primarySubjectConfig,
            'ascScale' => $ascScale,
            'currentSubject' => $currentSubject,
            'juxtaposedSubject' => $juxtaposedSubject,
            // 'currentSubjectTranslationReference' => $currentSubjectTranslationReference,
            // 'juxtaposedSubjectTranslationReference' => $juxtaposedSubjectTranslationReference,
            'unitBuilderData' => $unitBuilderData,
            // 'message' => ''
        ]);

        return $view;
    }

    public function createDashboardView()
    {
        App::getContainer()->wireService('projects/ASC/service/AscCalendarEventService');

        $viewPath = 'projects/ASC/view/AscScaleBuilder/Dashboard/Dashboard.php';
        $view = $this->renderWidget('AscScaleBuilderWidget_Dashboard', $viewPath, AscCalendarEventService::getDashboardData());

        return $view;
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

        // dump($moveResult);exit;

        $views = $this->createViews(false, false, true);

        // if (!$savedUnit) {
        //     dump(App::getContainer()->getRequest()->getAll());
        //     dump($savedUnit);exit;
        // }
        
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
        
        $processedRequestData = AscRequestService::getProcessedRequestData();

        // echo '<pre>';
        // var_dump($processedRequestData);exit;

        $ascUnit = $processedRequestData['ascUnit'];
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
                    'error' => trans('missing.unit')
                ]
            ];
            return $this->widgetResponse($response);
        }

        if ($ascUnit && $ascUnit->getAscEntryHead()) {
            $entryHead = $ascUnit->getAscEntryHead();
            $entry = $ascUnit->getAscEntryHead()->findEntry();
            if ($entry) {
                // $languageCode = $entry->getLanguageCode();
                $title = $entry->getTitle();
                $description = $entry->getDescription();
            }
        }

        // dump($ascUnit);exit;

        if ($entryHead) {
            $entryHead = AscUnitBuilderService::createUnitEntryHead($ascUnit);
        }

        $responsibleObject = $ascUnit->getResponsible();
        $responsible = $responsibleObject ? $responsibleObject->getId() : null;
        $administrationStance = $ascUnit->getAdministrationStance();
        $dueType = $ascUnit->getDueType();
        $recurrencePattern = $ascUnit->getRecurrencePattern();
        $dueDate = $ascUnit->getDueDate();
        $status = $ascUnit->getStatus();
        $dueTimeHours = null;
        $dueTimeMinutes = null;

        if ($ascUnit->getDueTime()) {
            $dueTimeParts = explode(':', $ascUnit->getDueTime());
            $dueTimeHours = $dueTimeParts[0];
            $dueTimeMinutes = $dueTimeParts[1];
        }
        
        $dueErrorStr = '';
        $formIsValid = true;
        if ($processedRequestData['submitted']) {
            $title = App::getContainer()->getRequest()->get('title');
            $description = App::getContainer()->getRequest()->get('description');
            if (!$entry) {
                App::getContainer()->wireService('projects/ASC/entity/AscEntry');
                $entry = new AscEntry();
                $entry->setAscEntryHead($entryHead);
            }
            $entry->setTitle($title);
            $entry->setDescription($description);

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
                // dump(ProjectTeamworkService::validResponsible($responsible));exit;
                // dump($responsible);exit;
                if (ProjectTeamworkService::validResponsible($responsible)) {
                    App::getContainer()->wireService('UserPackage/repository/UserAccountRepository');
                    $userAccountRepository = new UserAccountRepository();
                    $responsibleUserAccount = $userAccountRepository->find($responsible);
                    if (!$responsibleUserAccount) {
                        $responsible = null;
                    } else {
                        $ascUnit->setResponsible($responsibleUserAccount);
                        // $ascUnit->getAscEntryHead()->getAscUnit()->setResponsible($responsibleUserAccount);
                        // $ascUnit = $ascUnit->getRepository()->store($ascUnit);
                        // dump($ascUnit); exit;
                    }
                } else {
                    $responsible = null;
                }
            }

            // dump($responsibleUserAccount);exit;
            $ascUnit->setAdministrationStance($administrationStance);

            $dueType = StringHelper::mendValue(App::getContainer()->getRequest()->get('dueType'));
            $recurrencePattern = StringHelper::mendValue(App::getContainer()->getRequest()->get('recurrencePattern'));
            $dueDate = StringHelper::mendValue(App::getContainer()->getRequest()->get('dueDate'));
            $dueTimeHours = StringHelper::mendValue(App::getContainer()->getRequest()->get('dueTimeHours'));
            $dueTimeMinutes = StringHelper::mendValue(App::getContainer()->getRequest()->get('dueTimeMinutes'));

            if ($dueType == '-') {
                $dueType = null;
            }

            if ($dueTimeHours == '-') {
                $dueTimeHours = null;
            }

            if ($recurrencePattern == '-') {
                $recurrencePattern = null;
            }

            if ($dueDate && DateUtils::isValidDateFormat($dueDate)) {
                $ascUnit->setDueDate($dueDate);
            } else {
                $dueDate = null;
            }

            // dump($dueType);

            if ($dueType) {
                if (!in_array($dueType, AscCalendarEventService::getPossibleDueTypes())) {
                    $dueErrorStr = trans('invalid.due.type');
                    $formIsValid = false;
                }
                if ($dueType == AscCalendarEventService::DUE_TYPE_ONE_TIME) {
                    if (!$dueDate) {
                        $dueErrorStr = trans('due.date.is.required.for.one.time.due.type');
                        $formIsValid = false;
                    }
                }
                if ($dueType == AscCalendarEventService::DUE_TYPE_WEEKLY_RECURRENCE) {
                    $ascUnit->setDueDate(null);
                    if (!$recurrencePattern) {
                        $dueErrorStr = trans('recurrence.pattern.is.required.for.recurring.due.type');
                        $formIsValid = false;
                    }
                    if (!$recurrencePattern || ($recurrencePattern && AscCalendarEventService::isValidRecurrencePatternFormat($dueType, $recurrencePattern))) {
                        $ascUnit->setRecurrencePattern($recurrencePattern);
                    } else {
                        $formIsValid = false;
                        $recurrencePattern = null;
                        $dueErrorStr = trans('recurrence.pattern.is.invalid');
                    }
                    if (!$dueTimeHours || !$dueTimeMinutes) {
                        $formIsValid = false;
                        $dueErrorStr = trans('hour.and.minute.are.required.for.recurring.due.type');
                    }
                }
                $ascUnit->setDueType($dueType);
                // dump($dueTimeHours);exit;
                if ($dueTimeHours) {
                    if (!$dueTimeMinutes) {
                        $dueTimeMinutes = '00';
                    }
                    $dueTime = $dueTimeHours.':'.$dueTimeMinutes;
                    if (DateUtils::isValidTimeFormat($dueTime)) {
                        $ascUnit->setDueTime($dueTime);
                    }
                    // if ($dueTimeHours == '-') {
                    //     $ascUnit->setDueTime(null);
                    //     $dueTimeHours = null;
                    //     $dueTimeMinutes = null;
                    // } else {
                    //     if (!$dueTimeMinutes) {
                    //         $dueTimeMinutes = '00';
                    //     }
                    //     $dueTime = $dueTimeHours.':'.$dueTimeMinutes;
                    //     if (DateUtils::isValidTimeFormat($dueTime)) {
                    //         $ascUnit->setDueTime($dueTime);
                    //     }
                    // }
                } else {
                    $ascUnit->setDueTime(null);
                    $dueTimeHours = null;
                    $dueTimeMinutes = null;
                }
    
                $entry->setLanguageCode(App::getContainer()->getLocale());
                $entry = $entry->getRepository()->store($entry);
                // dump($entry);exit;
            } else {
                $ascUnit->setDueType(null);
                $ascUnit->setRecurrencePattern(null);
                $ascUnit->setDueDate(null);
                $ascUnit->setDueTime(null);
            }

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
                'dueType' => $dueType,
                'recurrencePattern' => $recurrencePattern,
                'dueDate' => $dueDate,
                'dueTimeHours' => $dueTimeHours,
                'dueTimeMinutes' => $dueTimeMinutes,
                'dueErrorStr' => $dueErrorStr,
                'status' => $status
                // 'title'
            ]),
            // 'views' => $views['views'],
            'data' => [
                'unitId' => $ascUnit->getId(),
                'callback' => 'editUnitCallback',
                'closeModal' => $formIsValid ? true : false,
                'modalLabel' => $modalLabel
            ]
        ];

        return $this->widgetResponse($response);
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
}
