<?php
namespace framework\kernel\routing;

use App;
use framework\component\exception\ElastiException;
use framework\component\helper\RouteMapHelper;
use framework\kernel\base\Cache;
use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\packages\FrameworkPackage\repository\CustomPageRepository;
use framework\packages\SiteBuilderPackage\service\BuiltPageService;

class RouteMapper extends Kernel
{
    public $customPageTableExists = false;

    public $customTitles = [];

    public function __construct()
    {
        // dump(App::$cache);exit;
        if (!Cache::cacheRefreshRequired()) {
            $routeMapCache = App::$cache->read('routeMap');
            if (!empty($routeMapCache)) {
                App::getContainer()->fullRouteMap = $routeMapCache;
                $this->overrideFromDatabase();
                return true;
            }
        }

        $this->getContainer()->setService('BackgroundPackage/repository/FBSPageBackgroundRepository');
        $dbm = $this->getDbManager();
        if ($dbm->getConnection() && $dbm->tableExists('custom_page')) {
            $this->getContainer()->wireService('FrameworkPackage/repository/CustomPageRepository');
            $customPageRepo = new CustomPageRepository();
            $this->customTitles = $customPageRepo->getTitles();
        }

        $this->create();
        $this->createCache();
        $this->overrideFromDatabase();
        // dump('created.');exit;
    }

    public function overrideFromDatabase()
    {
        // $pageControllers = [];
        // foreach (App::getContainer()->fullRouteMap as $element) {
        //     if ($element['controllerType'] == 'page') {
        //         $pageControllers[] = $element;
        //     }
        // }
        // dump($pageControllers);exit;

        // dump(App::getContainer()->getUrl());exit;


        // $url = App::getContainer()->getUrl();
        // if (App::getContainer()->isAjax() && $url->getAjaxUrl() != $url->getFullUrl()) {
        //     return true;
        // }
        try {
            $this->getContainer()->wireService('SiteBuilderPackage/service/BuiltPageService');
            // dump(BuiltPageService::builtPageTablesAvailable());
    
            // $this->getContainer()->wireService('framework/kernel/routing/RouteMapHelper');
            $allMappedRouteNamesAndParamChains = RouteMapHelper::getAllMappedRouteNamesAndParamChains();
            if (!App::$cache->read('widgetMap') || !BuiltPageService::builtPageTablesAvailable()) {
                return false;
            }
    
            // $BuiltPageService = NEW BuiltPageService();
            $builtPages = BuiltPageService::findAll();
            foreach ($builtPages as $builtPage) {
                $alreadyOnTheRouteMap = in_array($builtPage->getRouteName(), $allMappedRouteNamesAndParamChains);
                if (!$alreadyOnTheRouteMap || ($alreadyOnTheRouteMap && BuiltPageService::checkIfEditable($builtPage))) {
                    // dump($builtPage);
                    App::getContainer()->fullRouteMap[$builtPage->getRouteName()] = BuiltPageService::createRouteMapElement($builtPage);
                }
                // dump(BuiltPageService::createRouteMapElement($builtPage));
            }
        } catch(ElastiException $e) {
            if ($e->getCode() == 1660) {
                return true;
                // dump($e);exit;
            }
        }

        // dump(RouteMapHelper::getAllMappedParamChains());
        // exit;
// dump(App::getContainer()->fullRouteMap);exit;
        // dump($builtPages);exit;
        // $this->getContainer()->getKernelObject('DbManager')
    }

    public function createCache()
    {
        App::$cache->write('routeMap', App::getContainer()->fullRouteMap);
    }

    /**
     * @cfg allowedBuiltInRoutes: If value is array(), than all routes allowed. If value is null or false, none of them.
     * @cfg bannedBuiltInRoutes:
     */
    public function create()
    {
        $FBSPageBackgroundRepository = $this->getContainer()->getService('FBSPageBackgroundRepository');
        $pageBgs = $FBSPageBackgroundRepository->findAll();
        $pageBgColors = array();
        if ($pageBgs) {
            foreach ($pageBgs as $pageBg) {
                if ($pageBg->getBackgroundColor()) {
                    $pageBgColors[$pageBg->getRouteName()] = $pageBg->getBackgroundColor();
                }
            }
        }
        $routeMapFiles = $this->getContainer()->searchFileMap(array('classType' => 'routeMap', 'codeLocation' => 'projects'));

        foreach ($routeMapFiles as $routeMapFile) {
            $fullPath = $routeMapFile['path'].'/'.$routeMapFile['className'];
            $this->getContainer()->wireService($fullPath);
            $routeMapClass = $routeMapFile['namespace'];
            $routeMap = $routeMapClass::get();
            $this->addFullRouteMapPart('projects', $fullPath, $routeMap, $pageBgColors);
        }

        $routeMapFiles = $this->getContainer()->searchFileMap(array('classType' => 'routeMap', 'codeLocation' => 'packages'));
        foreach ($routeMapFiles as $routeMapFile) {
            $fullPath = $routeMapFile['path'].'/'.$routeMapFile['className'];
            $this->getContainer()->wireService($fullPath);
            $routeMapClass = $routeMapFile['namespace'];
            $routeMap = $routeMapClass::get();
            $this->addFullRouteMapPart('packages', $fullPath, $routeMap, $pageBgColors);
        }
    }

    public function addFullRouteMapPart($codeLocation, $mapPath, $newRouteMap, $pageBgColors = array())
    {
        $container = App::getContainer();
        for ($i = 0; $i < count($newRouteMap); $i++) {
            if (empty($newRouteMap[$i])) {
                continue;
            }
            if (!isset($newRouteMap[$i]['name'])) {
                dump($newRouteMap[$i]);
            }
            if (isset($pageBgColors[$newRouteMap[$i]['name']])) {
                $newRouteMap[$i]['backgroundColor'] = trim($pageBgColors[$newRouteMap[$i]['name']], '#');
            }
            $newRouteMap[$i]['codeLocation'] = $codeLocation;
            $newRouteMap[$i]['map'] = $mapPath;
            if (isset($container->fullRouteMap[$newRouteMap[$i]['name']])) {
                $resolvedCodeLocationConflict = $container->resolveCodeLocationConflict(
                    $container->fullRouteMap[$newRouteMap[$i]['name']]['codeLocation'],
                    $newRouteMap[$i]['codeLocation']
                );
                if ($resolvedCodeLocationConflict == 'new') {
                    $this->addFullRouteMapElement($newRouteMap[$i]);
                }
            } else {
                $this->addFullRouteMapElement($newRouteMap[$i]);
            }
        }
    }

    public function addFullRouteMapElement($routeMapElement)
    {
        App::getContainer()->setService($routeMapElement['controller']);
        $controllerClassName = BasicUtils::explodeAndGetElement($routeMapElement['controller'], '/', 'last');
        $controller = App::getContainer()->getService($controllerClassName);
        // dump($controller->getControllerType());
        $routeMapElement['controllerType'] = $controller->getControllerType();
        // dump($routeMapElement['controller']);


        // dump($routeMapElement['name']);
        // dump(array_keys($routeMapElement['paramChains']));


        $paramChains = array_keys($routeMapElement['paramChains']);
        foreach ($paramChains as $paramChain) {
            $paramChainParts = explode('/', $paramChain);
            if ($routeMapElement['controllerType'] != 'page' && count($paramChainParts) == 1) {
                // dump($routeMapElement);
                throw new \Exception('Every non-page route (widget, accessory, ajax-call) should contain at least two parts! '.$routeMapElement['name'].' contains only one.');
            }
        }

        $container = App::getContainer();
        if ($routeMapElement['codeLocation'] != 'projects') {
            $allowed = $this->getProjectData('allowedBuiltInRoutes');
            $banned = $this->getProjectData('bannedBuiltInRoutes');
            if (!$banned) {
                $banned = [];
            }
            if (!$allowed || $allowed == 'all' || (is_array($allowed) && in_array($routeMapElement['name'], $allowed))) {
                if (is_array($banned) && !in_array($routeMapElement['name'], $banned)) {
                    $container->fullRouteMap[$routeMapElement['name']] = $routeMapElement;
                }
            }
        } else {
            $container->fullRouteMap[$routeMapElement['name']] = $routeMapElement;
        }

        $title = $this->getCustomTitle($routeMapElement['name'], (isset($routeMapElement['title']) ? $routeMapElement['title'] : null));
        $container->fullRouteMap[$routeMapElement['name']]['title'] = $title;
    }

    public function getCustomTitle($routeName, $title)
    {
        return isset($this->customTitles[$routeName]) ? $this->customTitles[$routeName] : $title;
    }
}
