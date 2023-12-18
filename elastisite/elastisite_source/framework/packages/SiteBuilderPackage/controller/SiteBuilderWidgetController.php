<?php
namespace framework\packages\SiteBuilderPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\kernel\utility\FileHandler;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\SiteBuilderPackage\repository\BuiltPageRepository;
use framework\packages\SiteBuilderPackage\service\BuiltPageService;
use framework\packages\ToolPackage\service\ViewTools\PageToolView;

use framework\packages\UserPackage\repository\UserAccountRegistrationTokenRepository as RegTokenRepo;
use framework\packages\UserPackage\repository\UserAccountRepository;
use framework\packages\SiteBuilderPackage\repository\MenuItemRepository;
use framework\packages\ToolPackage\repository\ImageFileRepository;
use PHPMailer\PHPMailer\PHPMailer;

class SiteBuilderWidgetController extends WidgetController
{
    public function __construct()
    {
        App::getContainer()->wireService('SiteBuilderPackage/service/BuiltPageService');
    }

    /**
    * Route: [name: widget_CreateNewSiteWidget, paramChain: /widget/CreateNewSiteWidget]
    */
    public function createNewSiteWidgetAction()
    {

        $a = 2;

        $alma = (
            $a == 1 ? "one" 
            : ($a == 2 ? "two" 
            : ($a == 3 ? "three" 
            : "other"))
        );

        dump($alma);exit;
        // $this->wireService('ToolPackage/repository/ImageFileRepository');
        // $imageFileRepo = new ImageFileRepository();
        // $imageFiles = $imageFileRepo->findAll();
        // dump($imageFiles);

        // var_dump(base64_encode('honlapzsenismtpszerver'));
        // var_dump(base64_encode('gWsn224ObE4T(z'));

        // var_dump(base64_decode('aG9ubGFwenNlbmlzbXRwc3plcnZlcg=='));
        // var_dump(base64_decode('Z1dzbjIyNE9iRTRUKHo='));

        // FileHandler::includeFileOnce('thirdparty/PHPMailer/Exception.php', 'source');
        // FileHandler::includeFileOnce('thirdparty/PHPMailer/PHPMailer.php', 'source');
        // FileHandler::includeFileOnce('thirdparty/PHPMailer/SMTP.php', 'source');
        // $mail = new PHPMailer(true);
        // $mail->CharSet = "iso-8859-2";
        // $mail->WordWrap = 75;
        // $mail->Sender = "bounce@angolnyelvtan.info";
        // $mail->SetFrom( "info@angolnyelvtan.info", "AngolNyelvtan.info .gyf.lszolg.lat" );
        // $mail->AddAddress( "terencecleric@gmail.com" );
        // // $mail->AddAddress( "test-2v4acu9zb@srv1.mail-tester.com" );
        // $mail->Subject = "TESZT subj";
        // $mail->Body = "TESZT body";
        // $mail->SMTPAuth = true;
        // $mail->Host = 'www.smtp.honlapzseni.hu';
        // $mail->Username = 'honlapzsenismtpszerver';
        // $mail->Password = 'gWsn224ObE4T(z';
        // $mail->Port = 587;
        // $result = $mail->Send();
        // dump($result);
        // dump($mail);

        // $this->wireService('UserPackage/repository/UserAccountRepository');
        // $userAccountRepo = new UserAccountRepository();
        // $userAccounts = $userAccountRepo->findBy([], 'result');
        // dump($userAccounts);exit;

        // echo '<pre>';
        // var_dump($userAccounts);//exit;
        // echo '</pre>';

        // $this->wireService('UserPackage/repository/UserAccountRegistrationTokenRepository');
        // $tokenRepo = new RegTokenRepo();
        // $token = $tokenRepo->findBy(['conditions' => [['key' => 'user_account_id', 'value' => 1302]]], 'result');

        // dump($token);
        // dump($tokenRepo->findAll());exit;

        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/CreateNewSiteWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('CreateNewSiteWidget', $viewPath, [
                // 'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_AdminSiteBuilderSideMenuWidget, paramChain: /admin/AdminSiteBuilderSideMenuWidget]
    */
    public function adminSiteBuilderSideMenuWidgetAction()
    {
        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/AdminSiteBuilderSideMenuWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminSiteBuilderSideMenuWidget', $viewPath, [
                // 'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_builtPagesWidget, paramChain: /admin/builtPagesWidget]
    */
    public function adminBuiltPagesWidgetAction()
    {
        // $builtPages = BuiltPageService::findAllOnWebsite();
        // $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/AdminBuiltPagesWidget/widget.php';
        // $response = [
        //     'view' => $this->renderWidget('AdminBuiltPagesWidget', $viewPath, [
        //         'builtPages' => $builtPages
        //     ]),
        //     'data' => []
        // ];

        // $arr = ['alma' => 'körte', 'szilva' => 'banán'];
        // dump(App::$cache);
        // dump(App::$cache->write('korte', $arr)); 
        // dump(App::$cache->read('korte'));
        // exit;

        // return $this->widgetResponse($response);

        $this->wireService('SiteBuilderPackage/repository/BuiltPageRepository');
        // $this->setService('WebshopPackage/service/WebshopService');
        // $webshopService = $this->getService('WebshopService');
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $dataGridBuilder = new DataGridBuilder('AdminFinanceInvoicesDataGrid');
        $dataGridBuilder->setValueConversion(['isMenuItem' => [
            '0' => trans('no'),
            '1' => trans('yes')
        ]]);
        $dataGridBuilder->addUseUnprocessedAsInputValue('isMenuItem');
        $dataGridBuilder->addPropertyInputType('isMenuItem', 'multiselect');
        $dataGridBuilder->setPrimaryRepository(new BuiltPageRepository());
        $dataGridBuilder->setDeleteDisabled(false);
        $dataGrid = $dataGridBuilder->getDataGrid();
        $dataGrid->setNewActionUrl($this->getUrl()->getHttpDomain().'/admin/builtSite/new');
        $dataGrid->setEditActionUrl($this->getUrl()->getHttpDomain().'/admin/builtSite/edit');
        $dataGrid->setDeleteActionUrl($this->getUrl()->getHttpDomain().'/admin/builtSite/delete');
        $whatIsWebpageText = trans('what.is.webpage');
        $preloadHtml = '';
        if ($whatIsWebpageText != 'what.is.webpage') {
            $preloadHtml .= '<div class="widgetWrapper-info">'.$whatIsWebpageText.'</div>';
        }
        // $preloadHtml .= '<div class="widgetWrapper-info">'.trans('webpages.info').'</div>';

        $dataGrid->setPreloadRenderedHtml($preloadHtml);
        $response = $dataGrid->render();

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_builtSite_new, paramChain: /admin/builtSite/new]
    */
    public function adminBuiltPageNewAction()
    {
        return $this->adminBuiltPageEditAction();
    }

    /**
    * Route: [name: admin_builtSite_edit, paramChain: /admin/builtSite/edit]
    */
    public function adminBuiltPageEditAction()
    {
        $this->wireService('FormPackage/service/FormBuilder');
        // $this->wireService('SiteBuilderPackage/repository/BuiltPageRepository');
        // $repo = new BuiltPageRepository();
        // $id = (int)$this->getContainer()->getRequest()->get('id');

        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('SiteBuilderPackage');
        $formBuilder->setSubject('editBuiltPage');
        $formBuilder->setSchemaPath('SiteBuilderPackage/form/EditBuiltPageSchema');
        $formBuilder->setPrimaryKeyValue($this->getContainer()->getRequest()->get('id'));
        $formBuilder->addExternalPost('id');

        $form = $formBuilder->createForm();

        // 
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getEntity();
            $entity->setStructure('FrameworkPackage/view/structure/BasicStructure');
            $entity->setPermission('viewGuestContent');
            $entity->setStatus(1);
            $form->setEntity($entity->getRepository()->store($entity));
            // dump($form);exit;
        }

        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/AdminBuiltPagesWidget/edit.php';
        $response = [
            'view' => $this->renderWidget('editProductCategory', $viewPath, [
                'container' => $this->getContainer(),
                'editableBuiltInRouteSelectorView' => $this->renderEditableBuiltInRouteSelectorView(),
                'form' => $form,
                // 'structures' => $this->getStructures()
            ]),
            'data' => [
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll(),
                'label' => !$form->getEntity()->getId() ? trans('create.new.webpage') : trans('edit.webpage').' ('.$form->getEntity()->getRouteName().')'
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function renderEditableBuiltInRouteSelectorView()
    {
        $this->wireService('SiteBuilderPackage/service/BuiltPageService');
        $routeMap = App::getContainer()->fullRouteMap;
        $editableRoutes = [];
        foreach (BuiltPageService::EDITABLE_BUILT_IN_PAGE_ROUTES as $routeName) {
            // dump($routeMap);exit;
            if (isset($routeMap[$routeName])) {
                $editableRoutes[] = $routeMap[$routeName];
            }
        }

        // $this->wireService('ToolPackage/service/ViewTools/PageToolView');
        // $pageToolView = new PageToolView();

        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/AdminBuiltPagesWidget/editableBuiltInRouteSelector.php';
        return $this->renderWidget('editableBuiltInRouteSelectorView', $viewPath, [
            'editableRoutes' => $editableRoutes,
            // 'pageToolView' => $pageToolView
        ]);
    }

    /**
    * Route: [name: admin_builtSite_delete, paramChain: /admin/builtSite/delete]
    */
    public function adminBuiltPageDeleteAction()
    {
        $id = (int)$this->getContainer()->getRequest()->get('id');
        $this->wireService('SiteBuilderPackage/repository/BuiltPageRepository');
        $repo = new BuiltPageRepository();
        $repo->remove($id);

        $response = [
            'view' => ''
            ,
            'data' => [
                'id' => $id
            ]
        ];

        return $this->widgetResponse($response);
    }

    // public function getStructures()
    // {
    //     return [
    //         '' => ''
    //     ];
    // }
}
