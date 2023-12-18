<?php
namespace framework\packages\StaffPackage\controller;

use App;
use framework\component\exception\ElastiException;
use framework\component\helper\StringHelper;
use framework\component\parent\WidgetController;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\StaffPackage\repository\StaffMemberRepository;
use framework\packages\StaffPackage\service\StaffMemberStatService;
use framework\packages\StaffPackage\service\StaffSettingService;

class AdminStaffWidgetController extends WidgetController
{
    public function __construct()
    {
        // App::getContainer()->wireService('StaffPackage/service/ContentEditorService');
        // App::getContainer()->wireService('StaffPackage/service/ContentEditorDisplayTool');
    }

    public function getStaffMemberRepository() : StaffMemberRepository
    {
        App::getContainer()->setService('StaffPackage/repository/StaffMemberRepository');

        return App::getContainer()->getService('StaffMemberRepository');
    }

    /**
    * Route: [name: admin_AdminStaffConfigWidget, paramChain: /admin/AdminStaffConfigWidget]
    */
    public function adminStaffConfigWidgetAction()
    {
        $viewPath = 'framework/packages/StaffPackage/view/widget/AdminStaffConfigWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('', $viewPath, [
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_staff_config_list, paramChain: /admin/staff/config/list]
    */
    public function adminStaffConfigListAction()
    {
        App::getContainer()->wireService('StaffPackage/service/StaffSettingService');
        $staffSettings = StaffSettingService::getStaffSettingsArray();

        $viewPath = 'framework/packages/StaffPackage/view/widget/AdminStaffConfigWidget/list.php';
        $response = [
            'view' => $this->renderWidget('AdminStaffConfigWidget', $viewPath, [
                // 'container' => $this->getContainer()
                'staffSettings' => $staffSettings
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }


    /**
    * Route: [name: admin_staff_config_edit, paramChain: /admin/staff/config/edit]
    */
    public function adminStaffConfigEditAction()
    {
        App::getContainer()->wireService('StaffPackage/service/StaffSettingService');
        $staffSettings = StaffSettingService::getStaffSettingsArray();
        $this->setService('FrameworkPackage/service/SettingsService');
        $settings = $this->getService('SettingsService');

        // var_dump($this->getRequest()->getAll());exit;

        $submitted = $this->getContainer()->getRequest()->get('submitted');
        if ($submitted == 'true') {
            $settings->processPosts(['StaffPackage_editConfig_submit']);
        }

        $viewPath = 'framework/packages/StaffPackage/view/widget/AdminStaffConfigWidget/modal.php';
        $response = [
            'view' => $this->renderWidget('AdminStaffConfigWidget_edit', $viewPath, [
                // 'container' => $this->getContainer()
                'staffSettings' => $staffSettings
            ]),
            'data' => [
                'label' => trans('edit.staff.settings')
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_AdminStaffMembersListWidget, paramChain: /admin/AdminStaffMembersListWidget]
    */
    public function adminStaffMembersListWidgetAction()
    {
        // return $this->adminStaffMemberListAction(true);
        // dump('AdminStaffMembersListWidget');exit;
        $viewPath = 'framework/packages/StaffPackage/view/widget/AdminStaffMembersListWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('', $viewPath, [
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_staff_member_list, paramChain: /admin/staff/member/list]
    */
    public function adminStaffMemberListAction($widgetResponse = true)
    {
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $repo = $this->getStaffMemberRepository();
        $dataGridBuilder = new DataGridBuilder('AdminStaffMembersDataGrid');
        $dataGridBuilder->setValueConversion(['status' => [
            '0' => trans('disabled'),
            '1' => trans('active')
        ]]);
        $dataGridBuilder->setCreateNewText(trans('create.new.staff.member'));
        $dataGridBuilder->addPropertyValueProcessStrategy('username', 'decrypt');
        $dataGridBuilder->addPropertyValueProcessStrategy('fullName', 'decrypt');
        $dataGridBuilder->addPropertyValueProcessStrategy('email', 'decrypt');
        $dataGridBuilder->addPropertyValueProcessStrategy('mobile', 'decrypt');
        // $dataGridBuilder->setDataGridId('AdminOpenGraphsGrid');
        $dataGridBuilder->setPrimaryRepository($repo);
        $dataGrid = $dataGridBuilder->getDataGrid();
        $dataGrid->setListActionUrl($this->getUrl()->getHttpDomain().'/admin/staff/member/list');
        $dataGrid->setNewActionUrl($this->getUrl()->getHttpDomain().'/admin/staff/member/new');
        $dataGrid->setEditActionUrl($this->getUrl()->getHttpDomain().'/admin/staff/member/edit');
        $dataGrid->setDeleteActionUrl($this->getUrl()->getHttpDomain().'/admin/staff/member/delete');
        // $dataGrid->setJavaScriptOnDeleteConfirmed('OpenGraphImageHandler.loadGallery();');
        $response = $dataGrid->render();

        // dump($response);exit;

        return $widgetResponse ? $this->widgetResponse($response) : $response;
    }

    /**
    * Route: [name: admin_staff_member_new, paramChain: /admin/staff/member/new]
    */
    public function adminStaffMemberNewAction()
    {
        return $this->adminStaffMemberEditAction(true);
    }

    /**
    * Route: [name: admin_staff_member_edit, paramChain: /admin/staff/member/edit]
    */
    public function adminStaffMemberEditAction($new = false)
    {
        $idPost = $this->getContainer()->getRequest()->get('id');
        if (!$idPost) {
            $new = true;
        }
        $id = $new ? null : $idPost;
        // dump($this->getContainer()->getRequest()->getAll());
        $allRequests = $this->getContainer()->getRequest()->getAll();
        $submitted = !$allRequests || ($allRequests && array_keys($allRequests) == ['id']) ? false : true;
        $form = $this->getEditStaffMemberForm($id, $submitted);
        
        $viewPath = 'framework/packages/StaffPackage/view/widget/AdminStaffMembersListWidget/modal.php';
        $response = [
            'view' => $this->renderWidget('AdminStaffMembersListWidget_edit', $viewPath, [
                'new' => $new,
                'form' => $form
            ]),
            'data' => [
                'label' => $new ? trans('new.staff.member') : trans('edit.staff.member'),
                'formIsValid' => $form->isValid()
            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    public function getEditStaffMemberForm($id = null, $submitted = false)
    {
        $this->wireService('FormPackage/service/FormBuilder');
        $formBuilder = new FormBuilder();
        $formBuilder->setSchemaPath('StaffPackage/form/EditStaffMemberSchema');
        $formBuilder->setPackageName('StaffPackage');
        $formBuilder->setSubject('EditStaffMember');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->addExternalPost('id');
        $formBuilder->setAutoSubmit(false);
        $formBuilder->setSubmitted($submitted);
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();

        if ($submitted && $form->isValid()) {
            // dump($form->getEntity());exit;
            $person = $form->getEntity()->getPerson();
            $person->setUserAccount(null);
            $person = $person->getRepository()->store($person);
            $form->getEntity()->setPerson($person);
            $entity = $form->getEntity()->getRepository()->store($form->getEntity());
            $form->setEntity($entity);
        }

        return $form;
    }

    /**
    * Route: [name: admin_staff_member_stats_pages paramChain: /admin/staff/member/stats/pages]
    */
    public function adminStaffMemberStatsPagesAction()
    {
        App::getContainer()->wireService('StaffPackage/repository/StaffMemberRepository');
        $repo = new StaffMemberRepository();
        $staffMember = $repo->find(App::getContainer()->getRequest()->get('staffMemberId'));

        $viewPath = 'framework/packages/StaffPackage/view/widget/AdminStaffMembersListWidget/statsPages.php';
        $response = [
            'view' => $this->renderWidget('AdminStaffMembersListWidget_statsPages', $viewPath, [
                'link' => '<a href="/staff/stats/manage/staffMember/'.$staffMember->getCode().'" target="_blank">/staff/stats/manage/staffMember/'.$staffMember->getCode().'</a>'
            ]),
            'data' => [

            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_staff_member_stats_view paramChain: /admin/staff/member/stats/view]
    */
    public function adminStaffMemberStatsViewAction()
    {
        App::getContainer()->wireService('StaffPackage/repository/StaffMemberRepository');
        App::getContainer()->wireService('StaffPackage/service/StaffMemberStatService');
        $repo = new StaffMemberRepository();
        $staffMember = $repo->find(App::getContainer()->getRequest()->get('staffMemberId'));
        $staffMemberService = new StaffMemberStatService($staffMember);
        $staffMemberStats = $staffMemberService->getStaffMemberStats([], true);
        // $currentYear = DateUtils::getCurrentYear();
        // dump($staffMemberStats);exit;

        $viewPath = 'framework/packages/StaffPackage/view/StaffMemberStats/StatListView.php';
        $renderedWidget = $this->renderWidget('', $viewPath, [
            'staffMemberStats' => $staffMemberStats
        ]);

        // return $renderedWidget;

        $viewPath = 'framework/packages/StaffPackage/view/widget/AdminStaffMembersListWidget/statsView.php';
        $response = [
            'view' => $this->renderWidget('AdminStaffMembersListWidget_statsView', $viewPath, [
                'staffMemberStats' => $staffMemberStats
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_StaffMembersChartWidget, paramChain: /admin/StaffMembersChartWidget]
    */
    public function adminStaffMembersChartWidgetAction()
    {
        App::getContainer()->wireService('StaffPackage/repository/StaffMemberRepository');
        App::getContainer()->wireService('StaffPackage/service/StaffMemberStatService');
        $repo = new StaffMemberRepository();
        $staffMembersRaw = $repo->findAll();

        $staffMembers = [];
        foreach ($staffMembersRaw as $staffMemberRaw) {
            $staffMembers[$staffMemberRaw->getId()] = $staffMemberRaw;
        }

        $staffMembersStats = [];
        foreach ($staffMembers as $staffMember) {
            $staffMemberStatService = new StaffMemberStatService($staffMember);
            $staffMemberStats = $staffMemberStatService->getStaffMemberStats([], true);
            $staffMembersStats[$staffMember->getId()] = $staffMemberStats;
        }

        $viewPath = 'framework/packages/StaffPackage/view/widget/AdminStaffMembersChartWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminStaffMembersChartWidget', $viewPath, [
                'staffMembersStats' => $staffMembersStats,
                'staffMembers' => $staffMembers
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }
}
