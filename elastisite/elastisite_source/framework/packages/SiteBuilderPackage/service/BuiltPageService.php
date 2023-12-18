<?php
namespace framework\packages\SiteBuilderPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\SiteBuilderPackage\entity\BuiltPage;
use framework\packages\SiteBuilderPackage\repository\BuiltPageRepository;

class BuiltPageService extends Service
{
    const EDITABLE_BUILT_IN_PAGE_ROUTES = [
        'homepage',
        'contact',
        'about_me',
        'about_us'
    ];

    const USABLE_POSITIONS = [
        'main',
        'left'
    ];

    const BASIC_FORBIDDEN_ROUTES = [
        '',
        'admin',
        'widget',
        'ajax',
        'page',
        'oldal',
        'webshop',
        'webaruhaz',
        'dev',
        'prod'
    ];

    const USABLE_ELEMENTS = [
        'mainContent' => [
            'elementIndex' => 0,
            'position' => 'main'
        ],
        'mainContent2' => [
            'elementIndex' => 1,
            'position' => 'main'
        ],
        'left1' => [
            'elementIndex' => 0,
            'position' => 'left'
        ],
        'left2' => [
            'elementIndex' => 1,
            'position' => 'left'
        ]
    ];

    const PUBLIC_WIDGETS = [
        'SplashWidget' => [
            'position' => 'main',
            'fullWidthOnly' => true,
            'fullWidthUse' => true,
            'path' => 'SiteBuilderPackage/view/widget/SplashWidget'
        ],
        'WrappedSplashWidget' => [
            'position' => 'main',
            'fullWidthOnly' => false,
            'fullWidthUse' => false,
            'path' => 'SiteBuilderPackage/view/widget/SplashWidget'
        ],
        'ContactWidget' => [
            'position' => 'main',
            'fullWidthOnly' => false,
            'fullWidthUse' => false,
            'path' => 'projects/{App.webProject}/view/widget/ContactWidget'
        ],
        'UsersDocumentsWidget' => [
            'position' => 'left',
            'fullWidthOnly' => false,
            'fullWidthUse' => false,
            'path' => 'LegalPackage/view/widget/UsersDocumentsWidget'
        ],
    ];

    public static $builtPageRepository;

    public static function getRepository() : BuiltPageRepository
    {
        if (self::$builtPageRepository) {
            return self::$builtPageRepository;
        }
        App::getContainer()->wireService('SiteBuilderPackage/repository/BuiltPageRepository');
        self::$builtPageRepository = new BuiltPageRepository();

        return self::$builtPageRepository;
    }

    public static function getForbiddenRoutes()
    {
        return self::BASIC_FORBIDDEN_ROUTES;
    }

    public static function checkIfEditable(BuiltPage $builtPage) : bool
    {
        return in_array($builtPage->getRouteName(), self::EDITABLE_BUILT_IN_PAGE_ROUTES) ? true : false;
    }

    /*
     Array()
    (0)[name] => contact
    (1)[paramChains] => Array()
        (1)[contact] => en
        (2)[kapcsolat] => hu
    (2)[controller] => projects/ElastiShop/controller/BasicController
    (3)[action] => standardAction
    (4)[permission] => viewGuestContent
    (5)[inMenu] => main
    (6)[title] => contact
    (7)[structure] => FrameworkPackage/view/structure/basic2Panel
    (8)[widgetChanges] => Array()
        (8)[mainContent] => projects/ElastiShop/view/widget/ContactWidget
        (9)[mainContent2] => SiteBuilderPackage/view/widget/WrappedSplashWidget
        (10)[left1] => LegalPackage/view/widget/UsersDocumentsWidget
    (9)[backgroundColor] => 75bd61
    (10)[codeLocation] => projects
    (11)[map] => projects/ElastiShop/routeMap/ProjectRouteMap
    */
    public static function createRouteMapElement(BuiltPage $builtPage) : ? array
    {
        $routeMapElement = isset(App::getContainer()->fullRouteMap[$builtPage->getRouteName()]) ? App::getContainer()->fullRouteMap[$builtPage->getRouteName()] : [
            'name' => $builtPage->getRouteName()
        ];
        if (!isset($routeMapElement['paramChains'])) {
            $routeMapElement['paramChains'] = [
                $builtPage->getRouteName() => 'default'
            ];
        }
        if (!isset($routeMapElement['controller'])) {
            $routeMapElement['controller'] = 'projects/ElastiShop/controller/BasicController';
        }
        if (!isset($routeMapElement['action'])) {
            $routeMapElement['action'] = 'standardAction';
        }
        if (!isset($routeMapElement['permission'])) {
            $routeMapElement['permission'] = $builtPage->getPermission();
        }
        $routeMapElement['title'] = $builtPage->getTitle();
        if (!isset($routeMapElement['structure'])) {
            $routeMapElement['structure'] = $builtPage->getStructure();
        }
        if (!isset($routeMapElement['controllerType'])) {
            $routeMapElement['controllerType'] = 'page';
        }
        // dump();
        // dump(App::getContainer()->getWidgetMap());exit;

        $widgetChanges = [];
        foreach ($builtPage->getBuiltPageWidget() as $builtPageWidget) {
            $element = self::getElementName($builtPageWidget->getPosition(), $builtPageWidget->getElementIndex());
            $widgetName = $builtPageWidget->getWidget();
            if (isset(App::$cache->read('widgetMap')[$widgetName])) {
                $widgetChanges[$element] = App::$cache->read('widgetMap')[$widgetName]['widgetPath'];
            }
            
            // $routeMapElement['widgetChanges'][$element] = $builtPageWidget
		}

        $routeMapElement['widgetChanges'] = $widgetChanges;

        // dump($routeMapElement);exit;
        // $routeMapElement = [
        //     'name' => $builtPage->getRouteName(),
        //     'paramChains' => 
        // ];

        return $routeMapElement;
    }

    // public static function alma()
    // {
    //     $builtPageWidgets = [];
	// 	foreach ($this->builtPageWidget as $builtPageWidget) {
	// 		$builtPageWidgets[$builtPageWidget->getElementIndex()] = $builtPageWidget;
	// 	}
	// 	ksort($builtPageWidgets);
    // }

    public static function uniqueRouteName($routeName)
    {
        $result = self::getRepository()->findBy([
            'conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                ['key' => 'route_name', 'value' => $routeName]
            ]
        ]);

        return count($result) > 1 ? false : true;
    }

    // public static function findAllOnWebsite()
    // {
    //     return self::getRepository()->findAllOnWebsite();
    // }

    public static function findMenuItems() : ? BuiltPage
    {
        return self::getRepository()->findOneBy([
            'conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                ['key' => 'is_menu_item', 'value' => 1]
            ]
        ]);
    }

    public static function find($id) : ? BuiltPage
    {
        return self::getRepository()->findOneBy([
            'conditions' => [
                ['key' => 'id', 'value' => $id],
                ['key' => 'website', 'value' => App::getWebsite()]
            ]
        ]);
    }

    public static function findByRouteName($routeName) : ? BuiltPage
    {
        return self::getRepository()->findOneBy([
            'conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                ['key' => 'route_name', 'value' => $routeName]
            ]
        ]);
    }

    public static function findAll() : ? array
    {
        return self::getRepository()->findBy([
            'conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()]
            ]
        ]);
    }

    public static function getWidgetsOnBuiltPage(BuiltPage $builtPage) : array
    {
        $widgetDetails = [];
        $numberOfPanels = $builtPage->getNumberOfPanels();
        $total = [];
        $sortedOut = [];
        foreach (BuiltPageService::USABLE_POSITIONS as $usablePositionIndex => $usablePosition) {
            if ($usablePositionIndex < $numberOfPanels) {
                $sortedOut[$usablePosition] = [];
            }
        }

        $elements = $builtPage->getBuiltPageWidget();
        foreach ($elements as $element) {
            $total[] = $element->getWidget();
            $sortedOut[$element->getPosition()][$element->getElementIndex()] = $element->getWidget();
            $widgetDetails[$element->getWidget()] = [
                'position' => $element->getPosition(),
                'elementIndex' => $element->getElementIndex()
            ];
        }

        foreach ($sortedOut as $position => $elementsOfPosition) {
            ksort($sortedOut[$position]);
        }

        /**
         * Giving back the elementName instead elementIndex
        */
        // $reindexedSortedOut = [];
        // foreach ($sortedOut as $position => $elementsOfPosition) {
        //     foreach ($elementsOfPosition as $elementIndex => $widgetName) {
        //         // dump($elementsOfPosition);
        //         // dump(self::getElementName($position, $elementIndex));
        //         $reindexedSortedOut[$position][self::getElementName($position, $elementIndex)] = $widgetName;
        //     }
        // }

        return [
            'total' => $total,
            'sortedOut' => $sortedOut,
            'widgetDetails' => $widgetDetails
        ];
    }

    // public static function getElementIndex($position, $element) : ? int
    // {
    //     $counter = 0;
    //     foreach (self::USABLE_ELEMENTS as $usableElement => $usableElementProperties) {
    //         if ($position == $usableElementProperties['position']) {
    //             if ($element == $usableElement) {
    //                 return $counter;
    //             }
    //             $counter++;
    //         }
    //     }

    //     // return 'ALMA!';
    // }

    public static function getElementName($position, $elementIndex) : ? string
    {
        $counter = 0;
        foreach (self::USABLE_ELEMENTS as $usableElement => $usableElementProperties) {
            if ($position == $usableElementProperties['position']) {
                if ($counter == $elementIndex) {
                    return $usableElement;
                }
                $counter++;
            }
        }
    }

    public static function builtPageTablesAvailable()
    {
        $dbm = App::getContainer()->getKernelObject('DbManager');
        // dump($dbm->tableExists('built_page'));
        if (!$dbm->tableExists('built_page') || !$dbm->tableExists('built_page_param_chain') || !$dbm->tableExists('built_page_widget')) {
            return false;
        }

        return true;
    }
}