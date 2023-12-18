<?php
namespace projects\ASC\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\EventPackage\entity\CalendarEventActuality;
use framework\packages\EventPackage\repository\CalendarEventActualityRepository;
use framework\packages\FormPackage\service\FormBuilder;
use projects\ASC\entity\AscScale;
use projects\ASC\repository\AscScaleRepository;
use projects\ASC\service\AscCalendarEventActualityService;
use projects\ASC\service\AscPermissionService;
use projects\ASC\service\AscRequestService;
use projects\ASC\service\ScaleListService;
use projects\ASC\service\AscSituationService;
use projects\ASC\service\AscSaveService;
use projects\ASC\service\AscTranslatorService;
use projects\ASC\service\ProjectTeamworkService;
use projects\ASC\service\ProjectUserService;

class AscMainDashboardWidgetController extends WidgetController
{
    public static $projectUser;

    public function __construct()
    {
        App::getContainer()->wireService('projects/ASC/service/ProjectUserService');
        self::$projectUser = ProjectUserService::getProjectUser();
    }

    public function getProjectUser()
    {
        return self::$projectUser;
    }

    public function createViews($createNewView = false, $createEditView = false)
    {
        $editView = null;
        $closeModal = 'false';
        if ($createNewView || $createEditView) {
            $editViewArray = $createNewView ? $this->createNewView() : $this->createEditView();
            $editView = $editViewArray['view'];
            $closeModal = $editViewArray['data']['closeModal'];
        }

        // dump('createViews');exit;

        return [
            'views' => [
                'ownScaleListView' => $this->createOwnScaleListView(),
                'ownInactiveScaleListView' => $this->createOwnInactiveScaleListView(),
                'teamScaleListView' => $this->createTeamScaleListView(),
                'othersScaleListView' => $this->createOthersScaleListView(),
                'controlPanelView' => $this->createControlPanelView(),
                'eventActualityListView' => $this->createEventActualityListView(),
                'editView' => $editView
            ],
            'data' => [
                'closeModal' => $closeModal
            ]
        ];
    }

    public function createOwnScaleListView()
    {
        App::getContainer()->wireService('projects/ASC/service/ScaleListService');
        $ascScales = ScaleListService::collectAscScales(ScaleListService::LIST_TYPE_OWN_LIST);

        $scaleData = [];
        foreach ($ascScales as $ascScale) {
            $scaleData[] = [
                'ascScale' => $ascScale
            ];
        }

        $viewPath = 'projects/ASC/view/widget/AscScaleListerWidget/ownScaleList.php';
        $view = $this->renderWidget('AscScaleListerWidget_ownScaleList', $viewPath, [
            'scaleData' => $scaleData,
            'message' => ''
        ]);

        return $view;
    }

    public function createOwnInactiveScaleListView()
    {
        App::getContainer()->wireService('projects/ASC/service/ScaleListService');
        $ascScales = ScaleListService::collectAscScales(ScaleListService::LIST_TYPE_OWN_INACTIVE_LIST);

        // dump($ascScales);exit;

        $scaleData = [];
        foreach ($ascScales as $ascScale) {
            $scaleData[] = [
                'ascScale' => $ascScale
            ];
        }

        $viewPath = 'projects/ASC/view/widget/AscScaleListerWidget/ownInactiveScaleList.php';
        $view = $this->renderWidget('AscScaleListerWidget_ownInactiveScaleList', $viewPath, [
            'scaleData' => $scaleData,
            'message' => ''
        ]);

        return $view;
    }

    public function createTeamScaleListView()
    {
        // dump('createTeamScaleListView');//exit;
        App::getContainer()->wireService('projects/ASC/service/ScaleListService');
        App::getContainer()->wireService('projects/ASC/service/ProjectTeamworkService');
        App::getContainer()->wireService('projects/ASC/repository/AscScaleRepository');
        $ascScaleRepo = new AscScaleRepository();

        $ascScales = ScaleListService::collectAscScales(ScaleListService::LIST_TYPE_TEAM_LIST);

        $scaleData = [];
        foreach ($ascScales as $ascScale) {
            $projectTeamworkData = $ascScaleRepo->getProjectTeamworkData($ascScale);
            // dump($projectTeamworkData);
            $scaleData[] = [
                'ascScale' => $ascScale,
                'scaleOwnerData' => ProjectTeamworkService::getScaleOwnerData($ascScale),
                'projectTeamworkData' => $projectTeamworkData,
                'scaleTeamUnconfirmedInviteData' => $ascScaleRepo->getScaleTeamUnconfirmedInviteData($ascScale)
            ];
        }

        // dump('/createTeamScaleListView');exit;

        $viewPath = 'projects/ASC/view/widget/AscScaleListerWidget/teamScaleList.php';
        $view = $this->renderWidget('AscScaleListerWidget_teamScaleList', $viewPath, [
            'scaleData' => $scaleData,
            'message' => ''
        ]);

        return $view;
    }

    public function createOthersScaleListView()
    {
        App::getContainer()->wireService('projects/ASC/service/ScaleListService');
        $ascScales = ScaleListService::collectAscScales(ScaleListService::LIST_TYPE_OTHERS_LIST);

        $scaleData = [];
        foreach ($ascScales as $ascScale) {
            $scaleData[] = [
                'ascScale' => $ascScale
            ];
        }

        $viewPath = 'projects/ASC/view/widget/AscScaleListerWidget/othersScaleList.php';
        $view = $this->renderWidget('AscScaleListerWidget_othersList', $viewPath, [
            'scaleData' => $scaleData,
            'message' => ''
        ]);

        return $view;
    }

    public function createControlPanelView()
    {
        $viewPath = 'projects/ASC/view/widget/AscScaleListerWidget/controlPanel.php';
        $view = $this->renderWidget('AscScaleListerWidget_controlPanel', $viewPath, [
            'message' => ''
        ]);

        return $view;
    }

    public function createNewView()
    {
        return $this->createEditView(true);
    }

    public function createEditView($new = false)
    {
        // dump('alma');exit;
        App::getContainer()->wireService('projects/ASC/service/AscTranslatorService');
        App::getContainer()->wireService('projects/ASC/service/AscSituationService');
        App::getContainer()->wireService('projects/ASC/service/AscPermissionService');

        $situations = AscSituationService::getAllSituationData();
        $form = $this->getEditForm();

        $scale = null;
        $id = (int)App::getContainer()->getRequest()->get('id');
        if ($id) {
            App::getContainer()->wireService('projects/ASC/repository/AscScaleRepository');
            $scaleRepo = new AscScaleRepository();
            $scale = $scaleRepo->find($id);
            if ($scale) {
                if (!AscPermissionService::checkScalePermission(AscPermissionService::PERMISSION_EDIT_SCALE, $scale)) {
                    $scale = null;
                }
            }
        } else {
            App::getContainer()->wireService('projects/ASC/entity/AscScale');
            $scale = new AscScale();
        }

        // dump($situations);exit;

        // dump($form->isSubmitted());exit;
        // dump($scale);
        // dump(App::getContainer()->getRequest()->getAll());exit;
        $closeModal = 'false';
        if ($scale && $form->isSubmitted() && $form->isValid()) {
            // dump($form);exit;
            App::getContainer()->wireService('projects/ASC/service/AscSaveService');

            $languageCode = $form->getValueCollector()->getDisplayed('initialLanguage');
            $title = $form->getValueCollector()->getDisplayed('title');
            $description = $form->getValueCollector()->getDisplayed('description');
            $status = $form->getValueCollector()->getDisplayed('status');

            AscSaveService::saveScaleHeader(
                $form->getValueCollector()->getDisplayed('situation'),
                $languageCode,
                $title,
                $description,
                $status,
                $scale
            );

            // dump($form->getValueCollector()->getDisplayed('situation'));
            // dump($form->getValueCollector()->getDisplayed('title'));
            // dump($form->getValueCollector()->getDisplayed('initialLanguage'));
            // dump($form->getValueCollector()->getDisplayed('createdBy')); exit;
            // dump($form);exit;

            // dump('Valid!!!!');exit;

            $closeModal = 'true';
        }

        // dump($form);exit;

        $viewPath = 'projects/ASC/view/widget/AscScaleListerWidget/edit.php';
        $view = $this->renderWidget('AscScaleListerWidget_edit', $viewPath, [
            'new' => $new,
            'form' => $form,
            'activeLanguages' => AscTranslatorService::getActiveLanguages(),
            'situations' => $situations
        ]);

        return [
            'view' => $view,
            'data' => [
                'closeModal' => $closeModal
            ]
        ];
    }

    public function getEditForm()
    {
        $id = (int)App::getContainer()->getRequest()->get('id');
        $this->wireService('FormPackage/service/FormBuilder');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('Asc');
        $formBuilder->setSubject('editScale');
        $formBuilder->setSchemaPath('projects/ASC/form/ScaleHeaderFormSchema');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();

        // dump($form);exit;

        return $form;
    }


    /**
    * Route: [name: asc_scaleLister_widget, paramChain: /asc/scaleLister/widget]
    */
    public function ascScaleListerWidgetAction()
    {
        // App::getContainer()->wireService('projects/ASC/repository/AscSampleScaleRepository');
        // App::getContainer()->wireService('projects/ASC/entity/AscSampleScale');
        // $entity = new AscSampleScale();

        $userAccount = App::getContainer()->getUser()->getUserAccount();

        // $repo = new AscSampleScaleRepository();
        // $entity = $repo->find(222006);
        // $entity->setCreatedBy($userAccount);
        // $saved = $repo->store($entity);
        // dump($saved);
        // $entity = $repo->find(222006);
        // dump($entity);exit;


        // dump($this->getEntityManager()->collapseToCollection($entity));
        // exit;

        $viewPath = 'projects/ASC/view/widget/AscScaleListerWidget/widget.php';
        $views = $this->createViews();

        $response = [
            'view' => $this->renderWidget('AscScaleListerWidget', $viewPath, [
                'views' => $views['views']
            ]),
            'data' => [
                'closeModal' => $views['data']['closeModal']
            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: asc_closeEventActuality, paramChain: /asc/closeEventActuality/{closeResult}]
    */
    public function ascCloseEventActualityAction($closeResult)
    {
        // dump(App::getContainer()->getRequest()->get('calendarEventActualityId'));
        // dump($closeResult);exit;

        App::getContainer()->wireService('EventPackage/entity/CalendarEventActuality');
        App::getContainer()->wireService('EventPackage/repository/CalendarEventActualityRepository');
        $repo = new CalendarEventActualityRepository();
        $calendarEventActuality = $repo->find((int)App::getContainer()->getRequest()->get('calendarEventActualityId'));
        if (!$calendarEventActuality) {
            throw new \Exception('Invalid event actuality id');
        }

        $newStatus = null;
        if ($closeResult == 'successful') {
            $newStatus = CalendarEventActuality::STATUS_CLOSED_SUCCESSFUL;
        }
        if ($closeResult == 'failed') {
            $newStatus = CalendarEventActuality::STATUS_CLOSED_FAILED;
        }
        if ($newStatus) {
            $calendarEventActuality->setStatus($newStatus);
            $repo->store($calendarEventActuality);
        }

        $response = [
            'view' => $this->createEventActualityListView(true),
            'data' => [
                'closeModal' => true
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: asc_reopenEventActuality, paramChain: /asc/reopenEventActuality]
    */
    public function ascReopenEventActualityAction()
    {
        // dump(App::getContainer()->getRequest()->get('calendarEventActualityId'));
        // dump($closeResult);exit;

        App::getContainer()->wireService('EventPackage/entity/CalendarEventActuality');
        App::getContainer()->wireService('EventPackage/repository/CalendarEventActualityRepository');
        $repo = new CalendarEventActualityRepository();
        $calendarEventActuality = $repo->find((int)App::getContainer()->getRequest()->get('calendarEventActualityId'));
        if (!$calendarEventActuality) {
            throw new \Exception('Invalid event actuality id');
        }

        $newStatus = CalendarEventActuality::STATUS_ACTIVE;
        $calendarEventActuality->setStatus($newStatus);
        $repo->store($calendarEventActuality);

        $response = [
            'view' => $this->createEventActualityListView(true),
            'data' => [
                'closeModal' => true
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: asc_eventActualityListView, paramChain: /asc/eventActualityListView]
    */
    public function ascEventActualityListViewAction()
    {

        $response = [
            'view' => $this->createEventActualityListView(true),
            'data' => [
                'closeModal' => true,
                ''
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function createEventActualityListView($skipCalendarEventCheck = false)
    {
        App::getContainer()->wireService('projects/ASC/service/AscCalendarEventActualityService');
        // dump('Dashboard');exit;

        $viewPath = 'projects/ASC/view/AscScaleBuilder/MainDashboard/EventActualityList.php';

        $dashboardData = AscCalendarEventActualityService::getDashboardData(App::getContainer()->getUser()->getUserAccount(), $skipCalendarEventCheck);

        // dump($dashboardData);exit;
        // $dashboardData = [];

        $viewData = $dashboardData;
        $viewData['page_priorized'] = App::getContainer()->getRequest()->get('page_priorized') ? : 1;
        $viewData['page_closed'] = App::getContainer()->getRequest()->get('page_closed') ? : 1;
        $viewData['activeCategory'] = App::getContainer()->getRequest()->get('activeCategory') ? : 'priorized';
        $view = $this->renderWidget('AscScaleBuilderWidget_MainDashboard', $viewPath, $viewData);
        // $view = '';

        return $view;
    }

    public function returnAjaxResponse($createNewView = false, $createEditView = false)
    {
        $views = $this->createViews($createNewView, $createEditView);
        $response = [
            'view' => null,
            'views' => $views['views'],
            'data' => [
                'closeModal' => $views['data']['closeModal'],
                'label' => $createNewView ? trans('new.admin.scale.header') : ($createEditView ? trans('edit.admin.scale.header') : null)
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: asc_scaleLister_ownScaleList, paramChain: /asc/scaleLister/ownScaleList]
    */
    public function ascScaleListerOwnScaleListAction()
    {
        return $this->returnAjaxResponse();
    }

    /**
    * Route: [name: asc_scaleLister_othersList, paramChain: /asc/scaleLister/othersScaleList]
    */
    public function ascScaleListerOthersScaleListAction()
    {
        return $this->returnAjaxResponse();
    }

    /**
    * Route: [name: asc_scaleLister_newScale, paramChain: /asc/scaleLister/newScale]
    */
    public function ascScaleListerNewScaleAction()
    {
        return $this->returnAjaxResponse(true, false);
    }

    /**
    * Route: [name: asc_scaleLister_editScale, paramChain: /asc/scaleLister/editScale]
    */
    public function ascScaleListerEditScaleAction()
    {
        return $this->returnAjaxResponse(false, true);
    }

    /**
    * Route: [name: asc_scaleLister_deleteScale, paramChain: /asc/scaleLister/deleteScale]
    */
    public function ascScaleListerDeleteScaleAction()
    {
        App::getContainer()->setService('projects/ASC/repository/AscScaleRepository');
        $ascScaleRepository = App::getContainer()->getService('AscScaleRepository');
        $id = (int)App::getContainer()->getRequest()->get('id');
        if ($ascScaleRepository->isDeletable($id)) {
            $ascScaleRepository->remove($id);
        }

        return $this->returnAjaxResponse();
    }
}
