<?php
namespace framework\packages\ToolPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\FrameworkPackage\repository\CustomPageRepository;

class PageTool extends Service
{
    const DEDICATED_PAGES = [
        'homepage',
        'contact'
    ];

    public const NOT_CUSTOMIZABLE_ROUTES = [
        'admin_login',
        'webshop/paymentTest'
    ];
    
    // private $viewFilePath = 'framework/packages/ToolPackage/view/PageTool/customizablePageRoutes';
    public static function getCustomPageRepository() : CustomPageRepository
    {
        App::getContainer()->setService('FrameworkPackage/repository/CustomPageRepository');

        return App::getContainer()->getService('CustomPageRepository');
    }

    public static function getBuiltInPageRoutes($checkPermission = true, $customizableOnly = true)
    {
        $routes = array();
        foreach (App::getContainer()->getFullRouteMap() as $routeMapElement) {
            if (isset($routeMapElement['title']) && (($checkPermission && App::getContainer()->isGranted($routeMapElement['permission'])) || !$checkPermission)) {
                if (!$customizableOnly || ($customizableOnly && self::isCustomizable($routeMapElement['name']))) {
                    $routes[$routeMapElement['name']] = $routeMapElement;
                }
            }
        }
        // dump($routes);exit;
        return $routes;
        // return $this->getContainer()->getPermittedFullRouteMap();
    }

    public static function isCustomizable($routeName)
    {
        return (!in_array($routeName, self::NOT_CUSTOMIZABLE_ROUTES)) ? true : false;
    }

    public static function getAllBuiltInPageRoutes()
    {
        // $routes = array();
        // foreach ($this->getContainer()->getFullRouteMap() as $routeMapElement) {
        //     if (isset($routeMapElement['title'])) {
        //         $routes[$routeMapElement['name']] = $routeMapElement;
        //     }
        // }
        // // dump($routes);exit;
        // return $routes;
        return self::getBuiltInPageRoutes(false);
    }

    public static function getCustomPageRoutes()
    {
        return self::getCustomPageRepository()->getCustomPageRoutes();
    }
}