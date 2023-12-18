<?php
namespace framework\packages\SiteBuilderPackage\controller;

use App;
use framework\component\exception\ElastiException;
use framework\component\helper\RouteMapHelper;
use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\packages\SiteBuilderPackage\service\BuiltPageService;
use framework\packages\SiteBuilderPackage\service\MenuService;

class MenuWidgetController extends WidgetController
{
    public static $menuService;

    public static function getMenuService() : MenuService
    {
        if (!self::$menuService) {
            App::getContainer()->wireService('SiteBuilderPackage/service/MenuService');
            self::$menuService = new MenuService();
        }

        return self::$menuService;
    }

    /**
    * Route: [name: widget_MenuWidget, paramChain: /widget/MenuWidget]
    */
    public function menuWidgetAction()
    {
        try {
            App::getContainer()->wireService('SiteBuilderPackage/service/MenuService');
            $menuItemRoutes = MenuService::getMenuItemRoutes();
            $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/MenuWidget/widget.php';

            // dump($menuItemRoutes);exit;
    
            $response = [
                'view' => $this->renderWidget('MenuWidget', $viewPath, [
                    // 'container' => $this->getContainer(),
                    'menuItemRoutes' => $menuItemRoutes
                ]),
                'data' => []
            ];
    
            // dump($response);exit;
        } catch(ElastiException $e) {
            if ($e->getCode() == 1660) {
                $response = [
                    'view' => '',
                    'data' => []
                ];
            }
        }

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin/AdminMenuWidget, paramChain: /admin/AdminMenuWidget]
    */
    public function adminMenuWidgetAction()
    {
        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/AdminMenuWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('AdminMenuWidget', $viewPath, [
                'container' => $this->getContainer(),
                'documentTitle' => '',
                'message' => ''
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_AdminSideMenuWidget, paramChain: /admin/AdminSideMenuWidget]
    */
    public function adminSideMenuWidgetAction()
    {
        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/AdminSideMenuWidget/widget.php';
        $mappedMenuDirs = $this->getContainer()->searchFileMap(['classType' => 'menu']);
        $adminMenuSections = [];
        foreach ($mappedMenuDirs as $mappedMenuDir) {
            //dump($mappedMenuDir); exit;
            if ($mappedMenuDir['className'] == 'AdminMenuSection') {
                $this->getContainer()->wireService($mappedMenuDir['path'].'/'.$mappedMenuDir['className']);
                $namespace = $mappedMenuDir['namespace'];
                $object = new $namespace();
                // dump($object->getConfig());
                $config = $object->getConfig();
                if (!isset($config['active']) || (isset($config['active']) && $config['active'] == true)) {
                    $adminMenuSections[] = $object->getConfig();
                }
            }
        }

        $response = [
            'view' => $this->renderWidget('AdminSideMenuWidget', $viewPath, [
                // 'container' => $this->getContainer(),
                'viewSystemAdminContentGranted' => $this->getContainer()->isGranted('viewSystemAdminContent'),
                'isWebshopPackageInstalled' => $this->getContainer()->packageInstalled('WebshopPackage'),
                'documentTitle' => '',
                'message' => '',
                'adminMenuSections' => $adminMenuSections
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_AdminUserAreaMenuWidget, paramChain: /admin/AdminUserAreaMenuWidget]
    */
    public function adminUserAreaMenuWidgetAction()
    {
        // dump(RouteMapHelper::getAllMappedParamChains());exit;
        $flexibleContent = $this->adminUserAreaMenuWidgetFlexibleContentAction('view');
        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/AdminUserAreaMenuWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('AdminUserAreaMenuWidget', $viewPath, [
                'flexibleContent' => $flexibleContent
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_AdminUserAreaMenuWidget_flexibleContent, paramChain: /admin/AdminUserAreaMenuWidget_flexibleContent]
    */
    public function adminUserAreaMenuWidgetFlexibleContentAction($returnAs = 'widgetResponse', $menuItem = null)
    {
        App::getContainer()->wireService('SiteBuilderPackage/service/MenuService');

        $menuItemRoutes = MenuService::getMenuItemRoutes();
        $offeredRoutes = MenuService::findOfferedRoutes();

        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/AdminUserAreaMenuWidget/flexibleContent.php';

        $response = [
            'view' => $this->renderWidget('AdminUserAreaMenuWidget', $viewPath, [
                'menuItemRoutes' => $menuItemRoutes,
                'offeredRoutes' => $offeredRoutes,
                'menuItem' => $menuItem
            ]),
            'data' => []
        ];

        return $returnAs == 'widgetResponse' ? $this->widgetResponse($response) : $response['view'];
    }

    /**
    * Route: [name: admin_AdminUserAreaMenuWidget_addToMenu, paramChain: /admin/AdminUserAreaMenuWidget_addToMenu]
    */
    public function adminUserAreaMenuWidgetAddToMenuAction()
    {
        App::getContainer()->wireService('SiteBuilderPackage/service/MenuService');
        $routeName = App::getContainer()->getRequest()->get('routeName');
        $title = App::getContainer()->getRequest()->get('title');
        $routePath = App::getContainer()->getRequest()->get('routePath');
        if ($routeName == '') {
            $routeName = null;
        }
        if ($routePath == '') {
            $routePath = null;
        }
        $menuItem = MenuService::store($routeName, $title, $routePath);
        // dump($menuItem);

        return $this->adminUserAreaMenuWidgetFlexibleContentAction('widgetResponse', $menuItem);
    }

    /**
    * Route: [name: admin_AdminUserAreaMenuWidget_removeFromMenu, paramChain: /admin/AdminUserAreaMenuWidget_removeFromMenu]
    */
    public function adminUserAreaMenuWidgetRemoveFromMenuAction()
    {
        App::getContainer()->wireService('SiteBuilderPackage/service/MenuService');
        $routeName = App::getContainer()->getRequest()->get('routeName');
        $routePath = App::getContainer()->getRequest()->get('routePath');
        // $title = App::getContainer()->getRequest()->get('title');
        $menuItem = MenuService::remove($routeName, $routePath);
        // dump($menuItem);

        return $this->adminUserAreaMenuWidgetFlexibleContentAction('widgetResponse', $menuItem);
    }

    /**
    * Route: [name: admin_AdminUserAreaMenuWidget_saveTitle, paramChain: /admin/AdminUserAreaMenuWidget_saveTitle]
    */
    public function adminUserAreaMenuWidgetSaveTitleAction()
    {
        App::getContainer()->wireService('SiteBuilderPackage/service/MenuService');
        $routeName = App::getContainer()->getRequest()->get('routeName');
        $title = App::getContainer()->getRequest()->get('title');
        $menuItem = MenuService::saveTitle($routeName, $title);
        // dump($menuItem);

        return $this->adminUserAreaMenuWidgetFlexibleContentAction('widgetResponse', $menuItem);
    }

    /**
    * Route: [name: admin_AdminUserAreaMenuWidget_sort, paramChain: /admin/AdminUserAreaMenuWidget_sort]
    */
    public function adminUserAreaMenuWidgetSortAction()
    {
        App::getContainer()->wireService('SiteBuilderPackage/service/MenuService');
        $routeIds = App::getContainer()->getRequest()->get('routeIds');
        MenuService::sort($routeIds);
        // dump($routeNames);exit;

        return $this->adminUserAreaMenuWidgetFlexibleContentAction('widgetResponse', null);
    }
}
