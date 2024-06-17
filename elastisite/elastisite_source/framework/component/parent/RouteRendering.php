<?php
namespace framework\component\parent;

use App;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\component\parent\Rendering;
use framework\component\parent\Response;
use framework\component\parent\JsonResponse;
use framework\component\entity\Widget;
use framework\component\exception\ElastiException;
use framework\component\helper\PHPHelper;
use framework\kernel\security\SecurityReporting;
use framework\kernel\routing\MetaCollector;
use framework\packages\UserPackage\service\Permission;

// use framework\packages\ToolPackage\service\GeoIpTool;

class RouteRendering extends Rendering
{
    // private $beforeActionResult;

    public function renderSimplePage($viewData = [])
    {
        // dump($viewData);
        $structurePath = $this->getStructurePath();
        // dump($structurePath);
        $documentSkeleton = $this->renderView('framework/packages/FrameworkPackage/view/skeleton/simpleSkeleton.php', $viewData);
        $documentBody = $this->renderView($structurePath, $viewData);
        $document = str_replace('{{ structure }}', $documentBody, $documentSkeleton);
        return $document;
    }

    public function renderPenTesterWarningPage($viewData = [])
    {
        return $this->renderView('framework/packages/FrameworkPackage/view/skeleton/attackerWarningSkeleton.php', $viewData);
    }

    public function renderPenTesterBanPage($viewData = [])
    {
        return $this->renderView('framework/packages/FrameworkPackage/view/skeleton/attackerBanSkeleton.php', $viewData);
    }

    public function renderExceptionPage($env, $viewData = [])
    {
        if (\App::isCLICall() == false) {
            return $this->renderView('framework/packages/FrameworkPackage/view/skeleton/'.lcfirst($env).'ExceptionSkeleton.php', $viewData);
        }
        // return $this->renderView('framework/packages/FrameworkPackage/view/skeleton/devExceptionSkeleton.php', $viewData);
    }

    // public function getCustomStyleSheets($skinName)
    // {
    //     $path = 'public_folder/skin/'.$skinName.'/css/custom/';
    //     $cssIds = FileHandler::getAllFileNames($path, 'remove');
    //     $customStyleSheets = array();
    //     for ($i = 0; $i < count($cssIds); $i++) {
    //         $customStyleSheets[$i]['id'] = $cssIds[$i];
    //         $customStyleSheets[$i]['fileName'] = $cssIds[$i].'.css';
    //     }
    //     return $customStyleSheets;
    // }

    public function renderPage($viewData = [], $ajaxData = [], $skeletonPath = null, $title = null)
	{
        $route = $this->getRouting()->getActualRoute();
        // dump($route);
        $widgetChanges = $this->getRouting()->getActualRoute()->getWidgetChanges();
        $widgetChangeNames = array();
        $reverseWidgetChanges = array();
        foreach ($widgetChanges as $widgetChangeKey => $widgetChangeValue) {
            $widgetChangeName = BasicUtils::explodeAndGetElement($widgetChangeValue, '/', 'last');
            $widgetChangeNames[] = $widgetChangeName;
            $reverseWidgetChanges[$widgetChangeName] = $widgetChangeKey;
        }
        $viewData['skinName'] = $route->getSkinName()
            ? $route->getSkinName()
            : ($this->getContainer()->getSkinData('skinName') 
                ? $this->getContainer()->getSkinData('skinName') 
                : 'Basic');
        // $viewData['backgroundColor'] = $route->getBackgroundColor()
        //     ? $route->getBackgroundColor()
        //     : ($this->getContainer()->getSkinData('backgroundColor') 
        //         ? $this->getContainer()->getSkinData('backgroundColor') 
        //         : 'e4e4e4');
        $this->getContainer()->setSkinData('skinName', $viewData['skinName']);
        // $this->getContainer()->setSkinData('backgroundColor', $viewData['backgroundColor']);
        $ajax = $this->getContainer() && $this->getContainer()->isAjax() ? true : false;

        // dump($widgetChanges);

        $renderedStructurePre = $this->getRenderedStructure($title, $ajax, $viewData, $ajaxData, $skeletonPath, true, $widgetChanges);

        $structurePre = $renderedStructurePre['view'];
        if ($this->getContainer()) {
            $widgets = $this->getWidgets(BasicUtils::getContentBetween($structurePre, '{{', '}}'), $ajax, $widgetChanges);
        }
        else {
            $widgets = [];
        }

        $renderedStructure = $this->getRenderedStructure($title, $ajax, $viewData, $ajaxData, $skeletonPath, false, $widgetChanges);

        $structure = $ajax ? $renderedStructure['view'] : $renderedStructure;
        foreach ($widgets as $widget) {
            $widgetIdentifier = in_array($widget->getName(), $widgetChangeNames)
                ? $reverseWidgetChanges[$widget->getName()]
                : $widget->getName();
            $placeHolder = '{{ '.$widgetIdentifier.' }}';
            if (!$ajax) {
                if (is_array($widget->getContent())) {
                    dump($widget->getContent());
                    throw new \Exception('Error');
                }
                $structure = str_replace($placeHolder, ($widget->getContent() ? : ''), $structure);
            } else {
                $structure = str_replace($placeHolder, '', $structure);
            }
        }

        if (!$ajax) {
            return $structure;
        } else {
            $renderedStructure['view'] = $structure;
            return new JsonResponse($renderedStructure);
        }
    }

    public function getWidgets($rawWidgetIdentifiers, $ajax, $widgetChanges)
    {
        $widgetChangeKeys = array_keys($widgetChanges);
        $widgetMapper = $this->getContainer()->getKernelObject('WidgetMapper');
        $widgets = array();
        $widgetActions = array();
        foreach ($this->getContainer()->getFullRouteMap() as $route) {
            foreach ($rawWidgetIdentifiers as $rawWidgetIdentifier) {
                $widgetIdentifier = trim($rawWidgetIdentifier);
                $widgetName = in_array($widgetIdentifier, $widgetChangeKeys) ? $widgetChanges[$widgetIdentifier] : $widgetIdentifier;
                $widgetName = BasicUtils::explodeAndGetElement($widgetName, '/', 'last');
                $widgetDivId = 'widgetContainer-'.$widgetIdentifier;
                $widgetNamePre = str_replace('Widget', '', $widgetName);
                $widgetAction = lcfirst($widgetNamePre) . 'WidgetAction';
                $widgetActions[$widgetIdentifier]['searched'] = $widgetAction;
                if ($route['action'] == $widgetAction) {
                    $widgetActions[$widgetIdentifier]['found'] = $widgetAction;
                    $widget = new Widget();
                    $widget->setDivId($widgetDivId);
                    if (!$ajax) {
                        $controller = $this->getContainer()->getRoutingHelper()->getController($route['controller'], $route['action']);
                        if (Permission::check($route['permission'])) {
                            $widget->setContent($this->initController($controller, true, true));
                        }
                    }
                    $widget->setName($widgetName);
                    if (!$this->getContainer()->getWidgetParams($widgetName)) {
                        $widgetName = ucfirst($widgetName);
                        if (strpos($widgetName, 'Widget') === false) {
                            $widgetName = $widgetName.'Widget';
                        }
                    }
                    $widget->setWidgetPath($this->getContainer()->getWidgetParams($widgetName)['widgetPath']);
                    $widget->setScriptsPath($this->getContainer()->getWidgetParams($widgetName)['scriptsPath']);
                    $widgets[] = $widget;
                    $this->getContainer()->addWidget($widget);
                }
            }
        }

        foreach ($widgetActions as $widgetIdentifier => $widgetAction) {
            if (!isset($widgetAction['found'])) {
                // dump($widgetActions);
                // dump($this->getContainer()->getFullRouteMap());
                throw new ElastiException('Missing action in routeMap: '.$widgetAction['searched'], ElastiException::ERROR_TYPE_SECRET_PROG);
            }
        }

        $this->getContainer()->setWidgetScripts($this->getWidgetScripts());
        return $widgets;
    }

    public function initController($controller, $forceReturn = false, $widget = false)
    {
        if ($this->getKernelObject('Url')->getIsAjax()) {
            $urlParamArray = explode('/', trim($this->getKernelObject('Url')->getAjaxParamChain(), '/'));
        } else {
            $urlParamArray = explode('/', trim($this->getKernelObject('Url')->getParamChain(), '/'));
        }
        $routeParamArray = explode('/', trim($this->getContainer()->getRouting()->getActualRoute()->getParamChain(), '/'));

        // dump($controller['object']); exit;
        if ($controller['object']->getControllerType() == 'APIService') {
            $checkPermission = $controller['object']->checkPermission($this->getContainer()->getRouting()->getActualRoute()->getPermission());
            // dump($checkPermission);exit;
        } elseif ($controller['object']->getControllerType() == 'command') {
            $checkPermission = $controller['object']->isCLICall();
        } else {
            $checkPermission = Permission::check($this->getContainer()->getRouting()->getActualRoute()->getPermission());
        }

        // $controller['beforeActionResult'] = null;
        // if (method_exists($controller['object'], 'beforeAction')) {
        //     $controller['beforeActionResult'] = $controller['object']->beforeAction($controller['action']);
        // }

        return $this->startController(
            $controller,
            $forceReturn,
            $widget,
            $this->getContainer()->getKernelObject('Url')->getMainRouteRequest(),
            $checkPermission,
            $urlParamArray,
            $routeParamArray
        );
    }

    // public function getBeforeActionResult() 
    // {
    //     return $this->beforeActionResult;
    // }

    public function startController($controller, $forceReturn, $widget, $mainRouteRequest, $routePermission, $urlParamArray, $routeParamArray)
    {
        // dump('hello');exit;
        // $security = $this->getContainer()->getKernelObject('Security');
        // $security->finishingSecurity();
        if (method_exists($controller['object'], $controller['action'])) {
            $requiredArgs = array();
            $method = new \ReflectionMethod($controller['namespace'], $controller['action']);
            $requiredArgsRaw = $method->getParameters();
            for ($i = 0; $i < count($requiredArgsRaw); $i++) {
                $requiredArgs[] = $requiredArgsRaw[$i]->getName();
            }
            if ($this->getContainer()->getRouting()->getActualRoute()->getError()) {
                if (count($requiredArgs) > 0) {
                    throw new ElastiException('A method of an error controller cannot have argument.', ElastiException::ERROR_TYPE_SECRET_PROG);
                }
                $controller['object']->$controller['action']();
                return true;
            }

            # Ellenorzi, hogy nem akarsz-e dev cuccot betolteni prod-on.
            $mainRouteRequest = strtolower($mainRouteRequest);
            if ($this->getContainer()->getEnv() == 'prod' && $mainRouteRequest == 'dev') {
                $this->denyPermission($controller);
            }

            $controllerParent = BasicUtils::explodeAndGetElement(get_parent_class(get_class($controller['object'])), '\\', 'last');
            $this->getContainer()->setControllerParent($controllerParent);
            // $this->getContainer()->setControllerParent(get_class($controller['object']));

            # Preventing page load of ajax routes. Ajax routes can only be loaded by ajax calls.
            $env = App::getContainer()->getEnv();
            // dump($env);
            if ($env != 'dev' && !$widget && !$this->getContainer()->isAjax() && $controllerParent == 'WidgetController') {
                $routePermission = false;
            }

            // dump($routePermission);exit;
            if ($routePermission) {
                if (!$widget) {
                    $args = $this->getActionArgs($requiredArgs, $urlParamArray, $routeParamArray, $widget, $controller);
                    $content = call_user_func_array(array($controller['object'], $controller['action']), $args);
                } else {
                    $content = call_user_func_array(array($controller['object'], $controller['action']), []);
                }

                $reporting = new SecurityReporting();
                if (!$reporting->getSecurityStatus()) {
                    // dump($reporting->getEvents());
                }

                // $security = $this->getContainer()->getKernelObject('Security');
                if (is_object($content) || $forceReturn) {
                    // $security->finishingSecurity();
                    return $content;
                } else {
                    if (is_array($content)) {
                        dump($content);exit;
                    }
                    // $security->finishingSecurity();
                    echo $content;//exit;
                }
            } else {
                switch ($controllerParent):
                    case 'WidgetController';
                        return '';
                    break;
                    default;
                        return $this->denyPermission($controller);
                    break;
                endswitch;
                // if ($controllerParent == 'WidgetController') {
                //     return '';
                // } 
                // if ($controllerParent == 'PageController') {
                //     return $this->denyPermission();
                // }
                // return '';
            }
        } else {
            throw new ElastiException('Method: '.$controller['action'].' not exists.', ElastiException::ERROR_TYPE_SECRET_PROG);
        }
    }

    public function denyPermission($controller)
    {
        // dump($controller['object']);exit;

        if (method_exists($controller['object'], 'errorResult')) {
            return $controller['object']->errorResult('ERROR_AUTHENTICATION_REQUIRED');
        } else {
            return $this->redirectToParamChain('error/403');
        }

        // if ($this->controllerType == 'APIService') {
            
        // } else {
        //     return $this->redirectToParamChain('error/403');
        // }
    }

    public function redirectToParamChain($paramChain)
    {
        PHPHelper::redirect('/'.$paramChain, 'RouteRendering/redirectToParamChain()');
    }

    public function getStructurePath()
	{
        $container = $this->getContainer();
        $structurePath = $container->getRouting()->getActualRoute()->getStructure();
        $structurePathParts = explode('/', $structurePath);
        $structureId = $structurePathParts[count($structurePathParts) - 1];
        $projectStructurePath = 'projects/'.App::getWebProject('RouteRendering: getStructurePath()').'/view/structure/'.$structureId.'.php';
        $projectStructureLink = FileHandler::completePath($projectStructurePath, 'projects');

        if (count($structurePathParts) == 1 || is_file($projectStructureLink)) {
            // dump($projectStructurePath);exit;

            return $projectStructureLink;
        }
        else {
            $structureLinkParams = $this->getContainer()->getServiceLinkParams($structurePath);

            return $structureLinkParams['pathToFile'];
        }
    }

    public function getWidgetScripts()
    {
        $widgetScripts = '';
        $widgets = $this->getContainer()->getWidgets();
        if (!$widgets) {
            return false;
        }
        foreach ($widgets as $widget) {
            $widgetScripts .= $this->renderView($widget->getScriptsPath().'/scripts.php', array('container' => $this->getContainer()));
        }
        return $widgetScripts;
    }

    public function getFaviconLink()
    {
        $faviconRelPath = 'favicon';
        $faviconDir = FileHandler::completePath($faviconRelPath, 'dynamic');
        $faviconNames = FileHandler::getAllFileNames($faviconDir);
        $currentFaviconName = false;
        // dump($faviconNames);exit;
        foreach ($faviconNames as $faviconName) {
            $websiteName = BasicUtils::explodeAndRemoveElement($faviconName, '.', 'last');
            if ($websiteName == App::getWebProject()) {
                return $faviconRelPath.'/'.$faviconName;
            }
        }
        return false;
    }

    public function formatWidgetChanges($widgetChanges)
    {
        $result = array();
        foreach ($widgetChanges as $key => $widgetPath) {
            $result[$key] = BasicUtils::explodeAndGetElement($widgetPath, '/', 'last');
        }
        return $result;
    }

    public function getRenderedStructure($title, $ajax, $viewData = array(), $ajaxData = [], $skeletonPath = null, $preRender = false, $widgetChanges = [])
	{
        // dump($this->getContainer());exit;
        // $this->getSession()->set('countryClassification', null);exit;
        $skeletonPath = !$skeletonPath ? 'framework/packages/FrameworkPackage/view/skeleton/dynamicSkeleton.php' : $skeletonPath;
        $structurePath = $this->getStructurePath();
        $controllerParent = $this->getContainer()->getControllerParent();

        if ($this->getSession()->get('countryClassification') == 'notTrusted') {
            $skeletonPath = 'framework/packages/FrameworkPackage/view/skeleton/notTrustedSkeleton.php';
            $structurePath = 'framework/packages/FrameworkPackage/view/structure/notTrusted.php';
            // echo $this->getSession()->get('countryClassification');exit;
        }

        $keywords = '';
        $description = '';

        if ($controllerParent == 'PageController') {
            // dump($controllerParent);exit;
            $this->getContainer()->wireService('framework/kernel/routing/MetaCollector');
            // $routeTitle = trans($this->getContainer()->getRouting()->getActualRoute()->getTitle());
            $routeTitle = trans($this->getContainer()->getRouting()->getPageRoute()->getTitle());
            $title = $title ? $title : $routeTitle;
            $this->getContainer()->getRouting()->getPageRoute()->setTitle($title);
            $this->getContainer()->getRouting()->getActualRoute()->setTitle($title);
            $metaCollector = new MetaCollector($this->getContainer()->getRouting()->getPageRoute());
            $meta = $metaCollector->get();
            $keywords = $meta['keywords'];
            $description = $meta['description'];

            $this->getContainer()->setSkinData('backgroundColor', $this->getContainer()->getRouting()->getActualRoute()->getBackgroundColor());
            $viewData['backgroundColor'] = $this->getContainer()->getRouting()->getActualRoute()->getBackgroundColor();
            // dump($this->getContainer()->getSkinData());exit;
        }

        $viewData['faviconLink'] = $this->getFaviconLink();
        // $viewData['pageRoute'] = $this->getContainer()->getRouting()->getPageRoute();
        // $viewData['actualRoute'] = $this->getContainer()->getRouting()->getActualRoute();
        
        if ($preRender) {
            $data = null;
        }
        else {

            // // dump($controllerParent);exit;
            // $this->getContainer()->wireService('framework/kernel/routing/MetaCollector');
            // // $routeTitle = trans($this->getContainer()->getRouting()->getActualRoute()->getTitle());
            // $routeTitle = trans($this->getContainer()->getRouting()->getPageRoute()->getTitle());
            // $title = $title ? $title : $routeTitle;
            // $this->getContainer()->getRouting()->getPageRoute()->setTitle($title);
            // $this->getContainer()->getRouting()->getActualRoute()->setTitle($title);
            // $metaCollector = new MetaCollector($this->getContainer()->getRouting()->getPageRoute());
            // $meta = $metaCollector->get();
            // $keywords = $controllerParent.' - '.$meta['keywords'];
            // $description = $meta['description'];

            $structure = $this->getContainer()->getRouting()->getActualRoute()->getStructure();

            $data = $this->setAdditionalAjaxData($ajaxData, [
                'title' => $title,
                'keywords' => $keywords,
                'skinName' => isset($viewData['skinName']) ? $viewData['skinName'] : null,
                // 'backgroundColor' => isset($viewData['backgroundColor']) ? $viewData['backgroundColor'] : null,
                'backgroundColor' => $this->getContainer()->getRouting()->getActualRoute()->getBackgroundColor(),
                'structureName' => BasicUtils::explodeAndGetElement($structure, '/', 'last'),
                'backgroundEngine' => $this->getContainer()->getRouting()->getActualRoute()->getBackgroundEngine(),
                'backgroundTheme' => $this->getContainer()->getRouting()->getActualRoute()->getBackgroundTheme(),
                'faviconLink' => $viewData['faviconLink'],
                'widgetChanges' => $this->formatWidgetChanges($this->getContainer()->getRouting()->getActualRoute()->getWidgetChanges()),
                'pageSwitchBehavior' => $this->getContainer()->getRouting()->getActualRoute()->getPageSwitchBehavior(),
                'widgetScripts' => $this->getContainer()->getWidgetScripts(),
                'sheetWidthPercent' => $this->getContainer()->getPageProperty('sheetWidthPercent'),
                'sheetMaxWidth' => $this->getContainer()->getPageProperty('sheetMaxWidth'),
                'isAdminPage' => $this->getContainer()->getPageProperty('isAdminPage')
                // 'actualRoute' => $this->getContainer()->getRouting()->getActualRoute(),
                // 'pageRoute' => $this->getContainer()->getRouting()->getPageRoute()
            ]);

            // dump($data);exit;
        }

        if ((bool)$ajax || $preRender) {
            $response = [
                'view' => $this->renderStructureView($structurePath, $viewData, $widgetChanges),
                'data' => $data
            ];
            // dump($response);exit;
            return $response;
        } else {
            $documentSkeleton = $this->renderView($skeletonPath, $viewData);
            $documentBody = $this->renderStructureView($structurePath, $viewData, $widgetChanges);
            $document = str_replace('{{ structure }}', $documentBody, $documentSkeleton);
            // dump($this->getContainer()->getRouting()->getPageRoute()->getTitle());exit;
            $document = str_replace('<!--keywords-->', $keywords, $document);
            $document = str_replace('<!--description-->', $description, $document);
            // <!--keywords-->
            return $document;
        }
    }

    public function renderStructureView($skeletonPath, $viewData, $widgetChanges)
    {
        $viewData = array_merge($viewData, ['widgetChanges' => $widgetChanges]);
        
        return $this->renderView($skeletonPath, $viewData);
    }

    public function setAdditionalAjaxData($ajaxData, $additionalAjaxData)
    {
        foreach ($additionalAjaxData as $key => $value) {
            $isValidAjaxData = $this->isValidAjaxData($ajaxData, $key, $value);
            if ($isValidAjaxData) {
                $ajaxData[$key] = $value;
            }
        }

        return $ajaxData;
    }

    public function isValidAjaxData($ajaxData, $newKey, $newValue)
    {
        foreach ($ajaxData as $key => $value) {
            if ($key == $newKey) {
                return false;
            }
        }
        return true;
    }

    public function getActionArgs($requiredArgs, $urlParamArray, $routeParamArray, $widget, $controller)
    {
        $args = array();
        $argsChain = '';
        $return = array();
        for ($i = 0; $i < count($routeParamArray); $i++) {
            if (substr($routeParamArray[$i], 0, 1) == '{') {
                $routeParam = str_replace(array('{', '}'), array('', ''), $routeParamArray[$i]);
                $args[$routeParam] = $urlParamArray[$i];
                $argsChain = ($argsChain == '') ? $routeParam : ', '.$routeParam;
            }
        }

        if (count($requiredArgs) != count($args)) {
            // throw new ElastiException('Controller waits for '.count($requiredArgs).' arguments, but '.count($args).' was given. ('.$argsChain.')', ElastiException::ERROR_TYPE_SECRET_PROG);
        }

        foreach ($args as $key => $value) {
            if (in_array($key, $requiredArgs)) {
                $index = array_search($key, $requiredArgs);
                $return[$index] = $value;
            }
        }
        return $return;
    }
}
