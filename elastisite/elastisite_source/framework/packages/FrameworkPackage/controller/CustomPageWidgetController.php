<?php
namespace framework\packages\FrameworkPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\FrameworkPackage\repository\CustomPageRepository;
use framework\packages\FrameworkPackage\repository\CustomPageOpenGraphRepository;
use framework\packages\BackgroundPackage\repository\FBSBackgroundRepository;
use framework\packages\BackgroundPackage\repository\FBSPageBackgroundRepository;
use framework\packages\BackgroundPackage\entity\FBSPageBackground;
use framework\packages\FrameworkPackage\repository\OpenGraphRepository;

class CustomPageWidgetController extends WidgetController
{
    // private $pageRepository;

    private function getPageTool()
    {
        $this->setService('ToolPackage/service/PageTool');
        return $this->getService('PageTool');
    }

    private function getRepository()
    {
        $this->setService('FrameworkPackage/repository/CustomPageRepository');
        return $this->getService('CustomPageRepository');
    }

    private function getCustomPageService()
    {
        $this->setService('FrameworkPackage/service/CustomPageService');
        return $this->getService('CustomPageService');
    }

    public function getPageKeywordRepository()
    {
        $this->setService('SeoPackage/repository/PageKeywordRepository');
        return $this->getService('PageKeywordRepository');
    }

    // public function cleanUpUnusedFiles($exceptCode, $exceptExtension)
    // {
    //     return $this->getCustomPageService()->cleanUpUnusedFiles($exceptCode, $exceptExtension);
    // }

    public function getDefaultCustomPage()
    {
        $defaultCustomPage = null;
        $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        $customPageRepo = new CustomPageRepository();
        $defaultCustomPages = $customPageRepo->findBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'route_name', 'value' => 'reserved_default_route']
        ]]);
        if (is_array($defaultCustomPages) && count($defaultCustomPages) == 1) {
            $defaultCustomPage = $defaultCustomPages[0];
        }

        return $defaultCustomPage;
    }

    /**
    * name: admin_customPages_defaultCustomPagePanel, paramChain: /admin/customPages/defaultCustomPagePanel
    */
    public function adminCustomPagesDefaultCustomPagePanelAction()
    {
        $defaultCustomPage = $this->getDefaultCustomPage();
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminCustomPagesWidget/defaultCustomPagePanel.php';
        $response = [
            'view' => $this->renderWidget('defaultCustomPagePanel', $viewPath, [
                'container' => $this->getContainer(),
                'defaultCustomPage' => $defaultCustomPage
            ]),
            'data' => [
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: admin_customPages_defaultCustomPagePanel, paramChain: /admin/customPages/createDefaultCustomPage
    */
    public function adminCustomPagesCreateDefaultCustomPageAction()
    {
        $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        $customPageRepo = new CustomPageRepository();
        $defaultCustomPages = $customPageRepo->findBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'route_name', 'value' => 'reserved_default_route']
        ]]);

        if (count($defaultCustomPages) > 0) {
            $success = false;
            $id = null;
            if (count($defaultCustomPages) == 1) {
                $defaultCustomPage = $defaultCustomPages[0];
                $id = $defaultCustomPage->getId();
            }
        } else {
            $success = true;
            $defaultCustomPage = $customPageRepo->createNewEntity();
            $defaultCustomPage->setRouteName('reserved_default_route');
            $defaultCustomPage = $customPageRepo->store($defaultCustomPage);
            $id = $defaultCustomPage->getId();
        }

        $response = [
            'view' => '',
            'data' => [
                'success' => $success,
                'id' => $id
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: admin_customPages_widget, paramChain: /admin/customPages/widget
    */
    public function adminCustomPagesWidgetAction()
    {
        // $s = $this->getSettings()->get('alma');
        // dump($s);exit;
        // $response['data']['label'] = 'Alma';
        // $dataGrid = $this->getAdminCustomPagesDataGrid();
        // $renderedDataGrid = $dataGrid->render();
        // dump($dataGrid); exit;
        // $dataGridView = $renderedDataGrid['view'];
        $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        $customPageRepo = new CustomPageRepository();
        $defaultCustomPage = $customPageRepo->findBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'route_name', 'value' => 'reserved_default_route']
        ]]);

        if (count($defaultCustomPage) > 1) {
            foreach ($defaultCustomPage as $defaultCustomPageCopy) {
                $customPageRepo->remove($defaultCustomPageCopy->getId());
            }
        }

        // dump($defaultCustomPage);exit;

        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminCustomPagesWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminCustomPagesWidget', $viewPath, [
                'container' => $this->getContainer(),
                'defaultCustomPage' => $defaultCustomPage == [] ? null : $defaultCustomPage[0]
            ]),
            'data' => [
                'alma' => 'sadsad'
            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * name: admin_customPages_list, paramChain: /admin/customPages/list
    */
    public function adminCustomPagesListAction()
    {
        // var_dump($this->getRequest()->getAll());//exit;
        $dataGrid = $this->getAdminCustomPagesDataGrid();
        $renderedDataGrid = $dataGrid->render();
        // var_dump($dataGrid->getGridData()->getFilter());exit;
        return $this->widgetResponse($renderedDataGrid);
    }

    public function getAdminCustomPagesDataGrid()
    {
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $repo = $this->getRepository();
        $dataGridBuilder = new DataGridBuilder('AdminCustomPagesGrid');
        $dataGrid = $dataGridBuilder->getDataGrid();
        // $dataGrid->setDataGridId('AdminCustomPagesGrid');
        $dataGrid->setPrimaryRepository($repo);
        // $dataGrid = $dataGridBuilder->getDataGrid();
        // $dataGrid->setDataGridId('AdminCustomPagesGrid');
        // $dataGrid->setNewActionUrl('');
        // var_dump($dataGrid->getGridData()->getDataArray());exit;
        return $dataGrid;
    }

    /**
    * name: admin_customPage_new, paramChain: /admin/customPage/new
    */
    public function adminCustomPageNewAction()
    {
        return $this->adminCustomPageEditAction();
    }

    /**
    * name: admin_customPage_edit, paramChain: /admin/customPage/edit
    */
    public function adminCustomPageEditAction()
    {
        // $this->getPageRoutes();
        $customPageId = $this->getContainer()->getRequest()->get('id');
        // dump($customPageId);exit;
        $repo = $this->getRepository();
        $page = $repo->find($customPageId);
        // dump($page);exit;
        $routeName = '';
        $label = trans('new.custom.page');
        if (!$page) {
            $page = $repo->createNewEntity();
        } else {
            $routeName = $page->getRouteName();
            $route = $this->getContainer()->getRoutingHelper()->getRoute($routeName);
            // dump($route);
            if (isset($route['title'])) {
                $label = trans('edit.route', [['from' => '[routeTitle]','to' => trans(isset($route['title']) ? $route['title'] : $routeName)]]);
            } elseif ($routeName == 'reserved_default_route') {
                $label = trans('edit.default.page');
            } else {
                $label = trans('edit.route', [['from' => '[routeTitle]','to' => trans($routeName)]]);
            }
        }

        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminCustomPagesWidget/modal.php';
        $response = [
            'view' => $this->renderWidget('customPageEdit', $viewPath, [
                'customPageId' => $customPageId,
                'routeName' => $routeName,
                'container' => $this->getContainer(),
                'page' => $page
            ]),
            'data' => [
                'customPageId' => $customPageId,
                'label' => $label
            ]
        ];

        return $this->widgetResponse($response);
    }

    // public function getBuiltInPageRoutes()
    // {
    //     $routes = [];
    //     foreach ($this->getContainer()->getFullRouteMap() as $routeMapElement) {
    //         if (isset($routeMapElement['title'])) {
    //             $routes[] = $routeMapElement;
    //         }
    //     }
    //     // dump($routes);exit;
    //     return $routes;
    // }

    // basic tab

    public function getCustomPageBasicEditForm($id = null)
    {
        $this->wireService('FormPackage/service/FormBuilder');
        // dump($this->getContainer()->getRequest()->getAll());//exit;
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('FrameworkPackage');
        $formBuilder->setSubject('customPageBasicEdit');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->addExternalPost('id');
        // $formBuilder->addExternalPost('FrameworkPackage_pageEdit_file');
        $formBuilder->setSaveRequested(false);
        $formBuilder->setAutoSubmit(false);
        $formBuilder->setSubmitted($this->getContainer()->getRequest()->get('submitted') ? : false);
        $form = $formBuilder->createForm();
        // dump($form);exit;
        return $form;
    }

    /**
    * Route: [name: admin_customPage_basic_titleForm, paramChain: /admin/customPage/basic/titleForm]
    */
    public function adminCustomPageBasicTitleFormAction()
    {
        // $id = $this->getContainer()->getRequest()->get('customPageId');
        $success = false;
        $id = (int)$this->getContainer()->getRequest()->get('customPageId');
        // dump($this->getContainer()->getRequest()->getAll());exit;
        $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        $customPageRepo = new CustomPageRepository();
        $customPage = $customPageRepo->findOnWebsite($id);
        if ($customPage) {
            $title = $this->getContainer()->getRequest()->get('title');
            $customPage->setTitle($title);
            $customPageRepo->store($customPage);
            $success = true;
        }

        $response = [
            'view' => null,
            'data' => [
                'success' => $success
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_customPage_basic_editForm, paramChain: /admin/customPage/basic/editForm]
    */
    public function adminCustomPageBasicEditFormAction()
    {
        $this->wireService('FormPackage/service/FormBuilder');
        $id = (int)$this->getContainer()->getRequest()->get('customPageId');
        // dump($id);exit;
        // $id = 35007;

        $form = $this->getCustomPageBasicEditForm($id);
        // if ($form->isSubmitted() || ($this->getContainer()->getRequest()->get('code') != '' && $this->getContainer()->getRequest()->get('extension') != '')) {
        //     $form->getEntity()->setCode($this->getContainer()->getRequest()->get('code'));
        //     $form->getEntity()->setExtension($this->getContainer()->getRequest()->get('extension'));
        // }

        $savedEntity = null;

        $newRouteRequest = $this->getContainer()->getRequest()->get('newRouteRequest');
        if ($newRouteRequest === 'true') {
            $newRouteRequest = true;
        }
        if ($newRouteRequest === 'false') {
            $newRouteRequest = false;
        }

        $submitted = $this->getContainer()->getRequest()->get('submitted');
        if ($submitted === 'true') {
            $submitted = true;
        }
        if ($submitted === 'false') {
            $submitted = false;
        }

        $requestedRouteName = $this->getContainer()->getRequest()->get('routeName');
        if ($requestedRouteName && $requestedRouteName != '') {
            $form->getEntity()->setRouteName($requestedRouteName);
        }
        // dump($this->getContainer()->getRequest()->getAll());exit;
        if ($submitted) {
            $repo = $form->getEntity()->getRepository();
            // $form->getEntity()->setRouteName($requestedRouteName);
            $savedEntity = $repo->store($form->getEntity());
            $form->setEntity($savedEntity);
            // dump($savedEntity);exit;
        }
        
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminCustomPagesWidget/tab_basic_form.php';
        $response = [
            'view' => $this->renderWidget('tab_basic_form', $viewPath, [
                // 'container' => $this->getContainer(),
                'requestedRouteName' => $requestedRouteName,
                // 'customPageId' => $id,
                'newRouteRequest' => $newRouteRequest,
                'formIsValid' => $form->isValid(),
                'form' => $form,
                'customPageId' => $form->getEntity()->getId()
            ]),
            'data' => [
                'submitted' => $form->isSubmitted(),
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll(),
                'customPageId' => $form->getEntity()->getId(),
                'freshlySaved' => $savedEntity ? $savedEntity->getId() : false,
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: admin_customPage_edit_modalTabContent_basic, paramChain: /admin/customPage/edit/modalTabContent/basic
    */
    public function adminCustomPageEditModalTabContentBasicAction()
    {
        // $this->wireService('FormPackage/service/FormBuilder');
        $customPageId = (int)$this->getContainer()->getRequest()->get('customPageId');

        $form = $this->getCustomPageBasicEditForm($customPageId);

        $newRouteRequest = $this->getContainer()->getRequest()->get('newRouteRequest');
        if ($newRouteRequest === 'true') {
            $newRouteRequest = true;
        }
        if ($newRouteRequest === 'false') {
            $newRouteRequest = false;
        }

        $requestedRouteName = $this->getContainer()->getRequest()->get('routeName');
        if ($requestedRouteName && $requestedRouteName != '' && $requestedRouteName != $form->getEntity()->getRouteName()) {
            $form->getEntity()->setRouteName($requestedRouteName);
        }

        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminCustomPagesWidget/tab_basic.php';
        $response = [
            'view' => $this->renderWidget('tab_basic', $viewPath, [
                // 'requests' => $this->getContainer()->getRequest()->getAll(),
                'requestedRouteName' => $requestedRouteName,
                'newRouteRequest' => $newRouteRequest,
                'customPageId' => $customPageId,
                'container' => $this->getContainer(),
                'form' => $form
            ]),
            'data' => [
                'customPageId' => $customPageId
            ]
        ];

        return $this->widgetResponse($response);
    }

    // openGraph tab

    public function getCustomPageOpenGraphEditForm($id = null)
    {
        $this->wireService('FormPackage/service/FormBuilder');
        // dump($this->getContainer()->getRequest()->getAll());exit;
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('FrameworkPackage');
        $formBuilder->setSubject('customPageOpenGraphEdit');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->addExternalPost('id');
        // $formBuilder->addExternalPost('FrameworkPackage_pageEdit_file');
        $formBuilder->setSaveRequested(false);
        $formBuilder->setAutoSubmit(false);
        $formBuilder->setSubmitted($this->getContainer()->getRequest()->get('submitted') ? : false);
        $form = $formBuilder->createForm();
        
        return $form;
    }

    /**
    * name: admin_customPage_edit_modalTabContent_openGraph, paramChain: /admin/customPage/edit/modalTabContent/openGraph
    */
    public function adminCustomPageEditModalTabContentOpenGraphAction()
    {
        return $this->loadAdminCustomPageOpenGraphTabContent('tab_openGraph');
    }

    /**
    * Route: [name: admin_customPage_openGraph_editForm, paramChain: /admin/customPage/openGraph/editForm]
    */
    public function adminCustomPageOpenGraphEditFormAction()
    {
        return $this->loadAdminCustomPageOpenGraphTabContent('tab_openGraph_form');
    }

    public function loadAdminCustomPageOpenGraphTabContent($viewFileName)
    {
        $this->wireService('FormPackage/service/FormBuilder');
        
        $customPageId = (int)$this->getContainer()->getRequest()->get('customPageId');
        $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        $customPageRepo = new CustomPageRepository();
        $customPage = $customPageRepo->find($customPageId);
        if (!$customPage) {
            return false;
        }

        $this->wireService('FrameworkPackage/repository/OpenGraphRepository');
        $openGraphRepo = new OpenGraphRepository();
        $openGraphs = $openGraphRepo->findBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()]
        ]]);

        $this->wireService('FrameworkPackage/repository/CustomPageOpenGraphRepository');
        $customPageOpenGraphRepo = new CustomPageOpenGraphRepository();
        $customPageOpenGraph = $customPageOpenGraphRepo->findOneBy(['conditions' => [
            ['key' => 'custom_page_id', 'value' => $customPageId]
        ]]);

        $form = $this->getCustomPageOpenGraphEditForm($customPageId);

        if ($form->isValid()) {
            $repo = $form->getEntity()->getRepository();
            $repo->store($form->getEntity());
        }
        // dump($form);exit;
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminCustomPagesWidget/'.$viewFileName.'.php';
        $response = [
            'view' => $this->renderWidget($viewFileName, $viewPath, [
                'customPageId' => $customPageId,
                'customPageOpenGraph' => $customPageOpenGraph,
                'openGraphs' => $openGraphs,
                'container' => $this->getContainer(),
                // 'openGraphRepo' => $openGraphRepo,
                // 'customPageId' => $id,
                'formIsValid' => $form->isValid(),
                'form' => $form
            ]),
            'data' => [
                // 'submitted' => $form->isSubmitted(),
                // 'formIsValid' => $form->isValid(),
                // 'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll(),
                'customPageId' => $customPageId
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_customPage_openGraph_setCustomPageOpenGraph, paramChain: /admin/customPage/openGraph/setCustomPageOpenGraph]
    */
    public function adminCustomPageOpenGraphSetCustomPageOpenGraphAction()
    {
        $customPageId = (int)$this->getContainer()->getRequest()->get('customPageId');
        $openGraphId = (int)$this->getContainer()->getRequest()->get('openGraphId');
        // dump($customPageId);
        // dump($openGraphId); exit;

        $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        $customPageRepo = new CustomPageRepository();
        $customPage = $customPageRepo->find($customPageId);
        if (!$customPage) {
            return false;
        }

        $this->wireService('FrameworkPackage/repository/OpenGraphRepository');
        $openGraphRepo = new OpenGraphRepository();
        $openGraph = $openGraphRepo->find($openGraphId);
        if (!$openGraph) {
            return false;
        }

        // dump($customPage);
        // dump($openGraph); exit;

        $this->wireService('FrameworkPackage/repository/CustomPageOpenGraphRepository');
        $customPageOpenGraphRepo = new CustomPageOpenGraphRepository();

        $this->removeAllPageOpenGraphs($customPageId);

        $customPageOpenGraph = $customPageOpenGraphRepo->createNewEntity();
        $customPageOpenGraph->setCustomPage($customPage);
        $customPageOpenGraph->setOpenGraph($openGraph);
        $customPageOpenGraphRepo->store($customPageOpenGraph);

        $response = [
            'view' => '',
            'data' => [
                'customPageOpenGraphId' => $customPageOpenGraph->getId(),
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_customPage_openGraph_removeCustomPageOpenGraph, paramChain: /admin/customPage/openGraph/removeCustomPageOpenGraph]
    */
    public function adminCustomPageOpenGraphRemoveCustomPageOpenGraphAction()
    {
        $customPageId = (int)$this->getContainer()->getRequest()->get('customPageId');
        $this->removeAllPageOpenGraphs($customPageId);
    }

    public function removeAllPageOpenGraphs($customPageId)
    {
        $this->wireService('FrameworkPackage/repository/CustomPageOpenGraphRepository');
        $customPageOpenGraphRepo = new CustomPageOpenGraphRepository();

        $existingCustomPageOpenGraphs = $customPageOpenGraphRepo->findBy(['conditions' => [
            ['key' => 'custom_page_id', 'value' => $customPageId]
        ]]);
        if ($existingCustomPageOpenGraphs) {
            foreach ($existingCustomPageOpenGraphs as $existingCustomPageOpenGraph) {
                $customPageOpenGraphRepo->remove($existingCustomPageOpenGraph->getId());
            }
        }
    }

    // keywords tab

    public function getCustomPageKeywordsEditForm($id = null)
    {
        $this->wireService('FormPackage/service/FormBuilder');
        // dump($this->getContainer()->getRequest()->getAll());exit;
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('FrameworkPackage');
        $formBuilder->setSubject('customPageKeywordsEdit');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->addExternalPost('id');
        // $formBuilder->addExternalPost('FrameworkPackage_pageEdit_file');
        $formBuilder->setSaveRequested(false);
        $formBuilder->setAutoSubmit(false);
        $formBuilder->setSubmitted($this->getContainer()->getRequest()->get('submitted') ? : false);
        $form = $formBuilder->createForm();
        
        return $form;
    }

    /**
    * name: admin_customPage_edit_modalTabContent_keywords, paramChain: /admin/customPage/edit/modalTabContent/keywords
    */
    public function adminCustomPageEditModalTabContentKeywordsAction()
    {
        return $this->loadAdminCustomPageKeywordsTabContent('tab_keywords');
    }

    /**
    * Route: [name: admin_customPage_keywords_editForm, paramChain: /admin/customPage/keywords/editForm]
    */
    public function adminCustomPageKeywordsEditFormAction()
    {
        return $this->loadAdminCustomPageKeywordsTabContent('tab_keywords_form');
    }

    public function getPageKeywords($routeName)
    {
        $repo = $this->getPageKeywordRepository();
        $pageKeywords = $repo->findBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'route_name', 'value' => $routeName]
        ]]);
        return $pageKeywords ? : [];
    }

    public function loadAdminCustomPageKeywordsTabContent($viewFileName)
    {
        $this->wireService('FormPackage/service/FormBuilder');
        
        $customPageId = (int)$this->getContainer()->getRequest()->get('customPageId');

        $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        $customPageRepo = new CustomPageRepository();
        $customPage = $customPageRepo->find($customPageId);
        if (!$customPage) {
            return false;
        }

        $repo = $this->getRepository();
        $page = $repo->find($customPageId);
        if (!$page) {
            return false;
        }
        $routeName = $page->getRouteName();

        $pageKeywords = $this->getPageKeywords($routeName);

        $form = $this->getCustomPageKeywordsEditForm($customPageId);

        if ($form->isValid()) {
            $repo = $form->getEntity()->getRepository();
            $repo->store($form->getEntity());
        }
        // dump($form);exit;
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminCustomPagesWidget/'.$viewFileName.'.php';
        $response = [
            'view' => $this->renderWidget($viewFileName, $viewPath, [
                'customPageId' => $customPageId,
                'description' => $customPage->getDescription(),
                // 'customPageOpenGraph' => $customPageOpenGraph,
                'pageKeywords' => $pageKeywords,
                'container' => $this->getContainer(),
                // 'openGraphRepo' => $openGraphRepo,
                // 'customPageId' => $id,
                'formIsValid' => $form->isValid(),
                'form' => $form
            ]),
            'data' => [
                // 'submitted' => $form->isSubmitted(),
                // 'formIsValid' => $form->isValid(),
                // 'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll(),
                'customPageId' => $customPageId
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_customPage_keywords_list, paramChain: /admin/customPage/keywords/list]
    */
    public function adminCustomPageKeywordsListAction()
    {
        $customPageId = (int)$this->getContainer()->getRequest()->get('customPageId');
        $customPageRepo = $this->getRepository();
        $customPage = $customPageRepo->find($customPageId);
        if (!$customPage) {
            return false;
        }
        $routeName = $customPage->getRouteName();

        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminCustomPagesWidget/tab_keywords_existing.php';
        $response = [
            'view' => $this->renderWidget('tab_keywords_existing', $viewPath, [
                'container' => $this->getContainer(),
                'pageKeywords' => $this->getPageKeywords($routeName)
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_customPage_keywords_add, paramChain: /admin/customPage/keywords/add]
    */
    public function adminCustomPageKeywordsAddAction()
    {
        $repo = $this->getPageKeywordRepository();
        $keyword = $this->getRequest()->get('keyword');
        $keywordExists = false;
        if (!empty($keyword)) {
            $customPageId = (int)$this->getContainer()->getRequest()->get('customPageId');
            $customPageRepo = $this->getRepository();
            $customPage = $customPageRepo->find($customPageId);
            if (!$customPage) {
                return false;
            }
            $routeName = $customPage->getRouteName();
    
            $keywordExists = $repo->findOneBy(['conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                ['key' => 'route_name', 'value' => $routeName],
                ['key' => 'name', 'value' => $keyword]
            ]]);
            
            if (!$keywordExists) {
                $pageKeyword = $repo->createNewEntity();
                $pageKeyword->setRouteName($routeName);
                $pageKeyword->setName($keyword);
                // dump($pageKeyword);exit;
                $repo->store($pageKeyword);
            }
        }
        
        $response = [
            'view' => '',
            'data' => [
                'keywordExists' => $keywordExists
            ]
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_customPage_keywords_delete, paramChain: /admin/customPage/keywords/delete]
    */
    public function adminCustomPageKeywordsDeleteAction()
    {
        $repo = $this->getPageKeywordRepository();
        $repo->remove((int)$this->getRequest()->get('id'));

        $response = [
            'view' => '',
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_customPage_saveDescription, paramChain: /admin/customPage/saveDescription]
    */
    public function adminCustomPageSaveDescriptionAction()
    {
        $customPageId = (int)$this->getContainer()->getRequest()->get('customPageId');
        $description = $this->getContainer()->getRequest()->get('description');

        $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        $customPageRepo = new CustomPageRepository();
        $customPage = $customPageRepo->find($customPageId);
        if (!$customPage) {
            return false;
        }
        
        $customPage->setDescription($description);
        $customPageRepo->store($customPage);

        $response = [
            'view' => '',
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    // background tab

    public function getCustomPageBackgroundEditForm($id = null)
    {
        // $this->wireService('FormPackage/service/FormBuilder');
        // $formBuilder = new FormBuilder();
        // // $formBuilder->setPackageName('BackgroundPackage');
        // // $formBuilder->setSchemaPath('FrameworkPackage/form/EditFBSPageBackgroundSchema');
        // $formBuilder->setPackageName('FrameworkPackage');
        // $formBuilder->setSubject('editFBSPageBackground');
        // $formBuilder->setPrimaryKeyValue($id);
        // $formBuilder->addExternalPost('id');
        // // $formBuilder->setSaveRequested(false);
        // $form = $formBuilder->createForm();

        // return $form;
    }

    /**
    * Route: [name: admin_customPage_background_removeBackground, paramChain: /admin/customPage/background/removeBackground]
    */
    public function adminCustomPageBackgroundRemoveBackgroundAction()
    {
        $customPageId = (int)$this->getContainer()->getRequest()->get('customPageId');

        $pageBackground = $this->getPageBackground($customPageId);

        if ($pageBackground) {
            $pageBackground->setFbsBackgroundTheme(null);
            $pageBackground->getRepository()->store($pageBackground);
        }
    }

    /**
    * Route: [name: admin_customPage_background_selectBackground, paramChain: /admin/customPage/background/selectBackground]
    */
    public function adminCustomPageBackgroundSelectBackgroundAction()
    {
        $customPageId = (int)$this->getContainer()->getRequest()->get('customPageId');
        $backgroundId = (int)$this->getContainer()->getRequest()->get('backgroundId');

        $pageBackground = $this->getPageBackground($customPageId);

        $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundRepository');
        $bgRepo = new FBSBackgroundRepository();

        $background = $bgRepo->findOneBy(['conditions' => [
            ['key' => 'id', 'value' => $backgroundId]
        ]]);

        if (!$pageBackground) {
            $pageBackground = new FBSPageBackground();
            $pageBackground = $pageBackground->getRepository()->store($pageBackground);
        }

        $pageBackground->setRouteName($this->getRouteName($customPageId));

        if ($background) {
            $pageBackground->setFbsBackgroundTheme($background->getTheme());
        }

        $pageBackground->getRepository()->store($pageBackground);
        // dump($pageBackground);exit;
    }

    /**
    * Route: [name: admin_customPage_background_saveBackgroundColor, paramChain: /admin/customPage/background/saveBackgroundColor]
    */
    public function adminCustomPageBackgroundSaveBackgroundColorAction()
    {
        $customPageId = (int)$this->getContainer()->getRequest()->get('customPageId');
        // $originalBackgroundColor = (int)$this->getContainer()->getRequest()->get('originalBackgroundColor');
        $backgroundColor = $this->getContainer()->getRequest()->get('backgroundColor');

        $pageBackground = $this->getPageBackground($customPageId);

        // dump($this->getContainer()->getRequest()->getAll());
        // dump($pageBackground);
        // dump($backgroundColor);exit;

        if ($pageBackground) {
            $pageBackground->setBackgroundColor($backgroundColor);
            $pageBackground->getRepository()->store($pageBackground);
        }

        $response = [
            'data' => [
                // 'req' => $this->getContainer()->getRequest()->getAll()
                // 'submitted' => $form->isSubmitted(),
                // 'formIsValid' => $form->isValid(),
                // 'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll(),
                // 'customPageId' => $form->getEntity()->getId()
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function getRouteName($customPageId)
    {
        $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        $customPageRepo = new CustomPageRepository();
        $customPage = $customPageRepo->find($customPageId);

        if (!$customPage) {
            return null;
        }

        $routeName = $customPage->getRouteName();
        return $routeName;
    }

    public function getPageBackground($customPageId)
    {
        $routeName = $this->getRouteName($customPageId);

        $this->getContainer()->wireService('BackgroundPackage/entity/FBSPageBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSPageBackgroundRepository');
        $pageBgRepo = new FBSPageBackgroundRepository();

        // dump($pageBgRepo->findAll());exit;

        $pageBgRepo->removeBy(['conditions' => [
            ['key' => 'website', 'value' => null],
        ]]);

        $pageBgRepo->removeBy(['conditions' => [
            ['key' => 'routeName', 'value' => null],
        ]]);

        $pageBackground = $pageBgRepo->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'routeName', 'value' => $routeName],
        ]]);

        if (!$pageBackground) {
            // dump($pageBgRepo->findAll());//exit;
            $pageBackground = new FBSPageBackground();
            $pageBackground->setRouteName($routeName);
        }

        return $pageBackground;
    }

    /**
    * Route: [name: admin_customPage_background_editForm, paramChain: /admin/customPage/background/editForm]
    */
    public function adminCustomPageBackgroundEditFormAction()
    {
        $customPageId = (int)$this->getContainer()->getRequest()->get('customPageId');

        // $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        // $customPageRepo = new CustomPageRepository();
        // $customPage = $customPageRepo->find($customPageId);

        // if (!$customPage) {
        //     return false;
        // }

        // $routeName = $customPage->getRouteName();

        // $this->getContainer()->wireService('BackgroundPackage/entity/FBSPageBackground');
        // $this->getContainer()->wireService('BackgroundPackage/repository/FBSPageBackgroundRepository');
        // $pageBgRepo = new FBSPageBackgroundRepository();
        // $pageBackground = $pageBgRepo->findOneBy(['conditions' => [
        //     ['key' => 'website', 'value' => App::getWebsite()],
        //     ['key' => 'routeName', 'value' => $routeName],
        // ]]);

        $pageBackground = $this->getPageBackground($customPageId);

        // $this->setService('FormPackage/service/FormBuilder');
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundRepository');
        $bgRepo = new FBSBackgroundRepository();

        // dump($pageBgRepo->findAll());exit;
        
        if ($pageBackground) {
            $originalBackgroundColor = $pageBackground->getBackgroundColor();
            $backgrounds = null;
            $background = $bgRepo->findOneBy(['conditions' => [
                ['key' => 'theme', 'value' => $pageBackground->getFbsBackgroundTheme()],
                // ['key' => 'routeName', 'value' => $routeName],
            ]]);
            if (!$background) {
                $backgrounds = $bgRepo->findAll();
            }
        } else {
            $originalBackgroundColor = '';
            $backgrounds = $bgRepo->findAll();
            $background = null;
        }
        
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminCustomPagesWidget/tab_background_form.php';
        $response = [
            'view' => $this->renderWidget('tab_background_form', $viewPath, [
                'container' => $this->getContainer(),
                'customPageId' => $customPageId,
                'backgrounds' => $backgrounds,
                'background' => $background,
                'pageBackground' => $pageBackground,
                'originalBackgroundColor' => $originalBackgroundColor,
                // 'pageBgRepo' => $pageBgRepo,
                // 'debug' => $bgRepo->findAll(),
                // 'customPageId' => $id,
                // 'formIsValid' => $form->isValid(),
                // 'form' => $form,
            ]),
            'data' => [
                // 'req' => $this->getContainer()->getRequest()->getAll()
                // 'submitted' => $form->isSubmitted(),
                // 'formIsValid' => $form->isValid(),
                // 'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll(),
                // 'customPageId' => $form->getEntity()->getId()
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: admin_customPage_edit_modalTabContent_background, paramChain: /admin/customPage/edit/modalTabContent/background
    */
    public function adminCustomPageEditModalTabContentBackgroundAction()
    {
        $customPageId = (int)$this->getContainer()->getRequest()->get('customPageId');

        // $form = $this->getCustomPageBackgroundEditForm($customPageId);

        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminCustomPagesWidget/tab_background.php';
        $response = [
            'view' => $this->renderWidget('tab_background', $viewPath, [
                'customPageId' => $customPageId,
                'container' => $this->getContainer()
                // 'form' => $form
            ]),
            'data' => [
                'customPageId' => $customPageId
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_customPage_delete, paramChain: /admin/customPage/delete]
    */
    public function adminCustomPageDeleteAction()
    {
        $repo = $this->getRepository();
        $repo->remove($this->getContainer()->getRequest()->get('id'));

        $response = [
            'view' => ''
        ];

        return $this->widgetResponse($response);
    }
}
