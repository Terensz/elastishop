<?php
namespace framework\packages\SiteBuilderPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\SiteBuilderPackage\entity\MenuItem;
use framework\packages\SiteBuilderPackage\repository\MenuItemRepository;
use framework\packages\WebshopPackage\repository\ProductCategoryRepository;

class MenuService extends Service
{
    const PREDEFINED_OFFERABLE_ROUTES = [
        [
            'routeName' => 'homepage',
            'routePath' => null,
            'title' => 'homepage'
        ],
        [
            'routeName' => 'contact',
            'routePath' => null,
            'title' => 'contact'
        ],
        [
            'routeName' => 'webshop_productList_noFilter',
            'routePath' => null,
            'title' => 'webshop'
        ]
    ];

    public static $menuItemRepository;

    public static function getRepository() : MenuItemRepository
    {
        if (self::$menuItemRepository) {
            return self::$menuItemRepository;
        }
        App::getContainer()->wireService('SiteBuilderPackage/repository/MenuItemRepository');
        self::$menuItemRepository = new MenuItemRepository();

        return self::$menuItemRepository;
    }

    public static function getMenuItemRoutes() : array
    {
        $routes = [];
        $menuItems = self::getRepository()->findBy([
            'conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()]
            ],
            'orderBy' => [
                ['field' => 'sequence_number', 'direction' => 'ASC']
            ]
        ]);

        foreach ($menuItems as $menuItem) {
            $routes[] = [
                'routeName' => $menuItem->getRouteName(),
                'routePath' => $menuItem->getRoutePath(),
                'title' => $menuItem->getTitle()
            ];
        }

        return $routes;
    }

    public static function findOfferedRoutes() : array
    {
        $offeredRoutes = [];

        /**
         * Adding routes from BuiltPages which are not belong to a MenuItem yet.
        */
        App::getContainer()->setService('SiteBuilderPackage/repository/BuiltPageRepository');
        $builtPageRepository = App::getContainer()->getService('BuiltPageRepository');
        $offerableBuiltPages = $builtPageRepository->findOfferableBuiltPages();
        $builtPageRoutes = [];
        foreach ($offerableBuiltPages as $offerableBuiltPage) {
            $offeredRoutes[] = [
                'routeName' => $offerableBuiltPage->getRouteName(),
                'routePath' => null,
                'title' => $offerableBuiltPage->getTitle(),
            ];
            $builtPageRoutes[] = $offerableBuiltPage->getRouteName();
        }

        /**
         * Adding BUILT_IN_MENU_ITEMS, but only those, which are not assigned to a MenuItem yet.
        */
        $existingMenuItems = self::getRepository()->findBy([
            'conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()]
            ]
        ]);
        $menuItemRouteNames = [];
        $menuItemRoutePaths = [];
        foreach ($existingMenuItems as $existingMenuItem) {
            if ($existingMenuItem->getRouteName()) {
                $menuItemRouteNames[] = $existingMenuItem->getRouteName();
            }
            if ($existingMenuItem->getRoutePath()) {
                $menuItemRoutePaths[] = $existingMenuItem->getRoutePath();
            }
        }

        // dump($builtPageRoutes);
        // dump($menuItemRouteNames);
        // dump($menuItemRoutePaths);

        foreach (self::PREDEFINED_OFFERABLE_ROUTES as $predefinedOfferableRoute) {
            // if (!in_array($predefinedOfferableRoute['routeName'], $menuItemRoutePaths) && !in_array($predefinedOfferableRoute['routeName'], $builtPageRoutes)) {
            //     $offeredRoutes[] = $predefinedOfferableRoute;
            // }
            if (!in_array($predefinedOfferableRoute['routeName'], $menuItemRouteNames) && !in_array($predefinedOfferableRoute['routeName'], $builtPageRoutes)) {
                $offeredRoutes[] = $predefinedOfferableRoute;
            }
        }

        App::getContainer()->wireService('WebshopPackage/repository/ProductCategoryRepository');
        $prodCatRepo = new ProductCategoryRepository();
        $independentCategories = $prodCatRepo->findBy([
            'conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                ['key' => 'status', 'value' => 1],
                ['key' => 'is_independent', 'value' => 1]
            ]
        ]);

        foreach ($independentCategories as $independentCategory) {
            $routePath = '/category/'.$independentCategory->getSlug();
            if (!in_array($routePath, $menuItemRoutePaths)) {
                $offeredRoutes[] = [
                    'routeName' => null,
                    'routePath' => $routePath,
                    'title' => $independentCategory->getName(),
                ];
            }
        }

        // dump($independentCategories);exit;

        return $offeredRoutes;
    }

    public static function store($routeName, $title, $routePath)
    {
        $conditions = array();
        $conditions[] = ['key' => 'website', 'value' => App::getWebsite()];
        if ($routeName) {
            $conditions[] = ['key' => 'route_name', 'value' => $routeName];
        } elseif ($routePath) {
            $conditions[] = ['key' => 'route_path', 'value' => $routePath];
        } else {
            return false;
        }

        $menuItem = self::getRepository()->findOneBy([
            'conditions' => $conditions
        ]);

        if (!$menuItem) {
            $menuItem = new MenuItem();
        }

        $menuItem->setRouteName($routeName);
        $menuItem->setTitle($title);
        $menuItem->setRoutePath($routePath);
        $menuItem->setSequenceNumber(self::getRepository()->getNextSequenceNumber());
        $menuItem = self::getRepository()->store($menuItem);

        return $menuItem;
    }

    public static function remove($routeName, $routePath)
    {
        $conditions = array();
        $conditions[] = ['key' => 'website', 'value' => App::getWebsite()];
        if ($routeName) {
            $conditions[] = ['key' => 'route_name', 'value' => $routeName];
        } elseif ($routePath) {
            $conditions[] = ['key' => 'route_path', 'value' => $routePath];
        } else {
            return false;
        }

        $menuItem = self::getRepository()->findOneBy([
            'conditions' => $conditions
        ]);

        if (!$menuItem) {
            return false;
        }

        self::getRepository()->remove($menuItem->getId());

        return true;
    }

    public static function saveTitle($routeName, $title)
    {
        $menuItem = self::getRepository()->findOneBy([
            'conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                ['key' => 'route_name', 'value' => $routeName]
            ]
        ]);

        if (!$menuItem) {
            return null;
        }

        $menuItem->setTitle($title);
        $menuItem = self::getRepository()->store($menuItem);

        return $menuItem;
    }

    public static function sort($routeIds)
    {
        // dump($routeNames);exit;
        $counter = 1;
        foreach ($routeIds as $routeId) {
            $isRoutePathPos = strpos($routeId, ':');
            $conditions = array();
            $conditions[] = ['key' => 'website', 'value' => App::getWebsite()];

            if ($isRoutePathPos === false) {
                $conditions[] = ['key' => 'route_name', 'value' => $routeId];
            } else {
                $routePath = str_replace(':', '', $routeId);
                $routePath = str_replace('.', '/', $routePath);
                $conditions[] = ['key' => 'route_path', 'value' => $routePath];
            }

            $menuItem = self::getRepository()->findOneBy([
                'conditions' => $conditions
            ]);

            // dump($conditions);
            // dump($menuItem);

            $menuItem->setSequenceNumber($counter);
            self::getRepository()->store($menuItem);
            $counter++;
        }
    }
}