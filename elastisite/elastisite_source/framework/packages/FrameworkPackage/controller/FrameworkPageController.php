<?php
namespace framework\packages\FrameworkPackage\controller;

use App;
use framework\component\parent\PageController;
use framework\component\parent\JavaScript;
use framework\kernel\utility\FileHandler;
use framework\packages\ToolPackage\service\TextAnalist;


class FrameworkPageController extends PageController
{
    const NOT_PUBLIC_MAIN_PARAMS = [
        'dev'
    ];

    const NOT_PUBLIC_ROUTE_NAMES = [
        'admin_login', 'setup'
    ];

    const MAX_ANALYZED_DETAILS = 3;

    public function errorpage404Action()
    {
        App::$projectPathBase = App::getContainer()->getPathBase('projects').'/projects/'.App::getWebProject();
        $pathToMiddlewareSetWebProject = App::$projectPathBase.'/middleware/Error404Handler.php';
        if (FileHandler::fileExists($pathToMiddlewareSetWebProject)) {
            App::getContainer()->setService('projects/'.App::getWebProject().'/middleware/Error404Handler', 'MiddlewareError404Handler');
            $service = App::getContainer()->getService('MiddlewareError404Handler');
            $errorPageHandled = $service->handleErrorPage();

            if (isset($errorPageHandled[$service::KEY_CONTINUE_ERROR_PAGE]) && $errorPageHandled[$service::KEY_CONTINUE_ERROR_PAGE] == true) {
                
            } else {
                exit;
            }
            // dump($service);exit;
        } else {
            App::setWebsite(App::getWebProject('SetWebsite: __construct()'));
        }

        $suggestedRoutes = array();
        $this->getContainer()->wireService('ToolPackage/service/TextAnalist');
        $routeMap = $this->getContainer()->getFullRouteMap();
        $paramChain = $this->getContainer()->getUrl()->getParamChain();
        $details = explode('/', $paramChain);
        // dump($details);exit;

        $detailsAnalyzed = 0;
        foreach ($details as $detail) {
            if ($detailsAnalyzed > self::MAX_ANALYZED_DETAILS) {
                continue;
            }
            $textAnalist = new TextAnalist($detail);
            foreach ($routeMap as $routeMapElement) {
                if (!isset($routeMapElement['paramChains'])) {
                    continue;
                    // dump($routeMapElement);
                }
                foreach (array_keys($routeMapElement['paramChains']) as $paramChain) {
                    $paramChainParts = explode('/', $paramChain);
                    if (isset($paramChainParts[0]) && in_array($paramChainParts[0], self::NOT_PUBLIC_MAIN_PARAMS)) {
                        continue;
                    }
                    $suggestedRoute = $textAnalist->findSimilarities($paramChain);
                    $widgetPos = strpos($paramChain.'*', '/widget*');
                    $variablePos = strpos($paramChain, '{');
                    if ($suggestedRoute && $this->getContainer()->isGranted($routeMapElement['permission'])
                        && $widgetPos === false && isset($routeMapElement['title'])
                        && $variablePos === false && !isset($suggestedRoutes[$routeMapElement['name']])) {
                        if (!in_array($routeMapElement['name'], self::NOT_PUBLIC_ROUTE_NAMES)) {
                            $suggestedRoutes[$routeMapElement['name']] = $routeMapElement;
                        }
                    }
                }
            }

            $detailsAnalyzed++;
        }
        // dump($suggestedRoutes); exit;
        return $this->renderPage([
            'container' => $this->getContainer(),
            'suggestedRoutes' => $suggestedRoutes
        ]);
    }

    public function error403Action()
    {
        // if ($this->getContainer()->isAjax()) {
        //     return 'error403';
        // }
        // dump($this);exit;
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    public function smAction($sourceMapFileName)
    {
        echo ''; exit;
    }

    public function searchInRoutemap($text)
    {

    }

    public function setupAction()
    {
        // dump($this->getContainer()->getUser());exit;
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    public function indexAction()
    {
        // dump($this);exit;
        if (!$this->getContainer()->isGranted('viewProjectAdminContent')) {
            // header('Location: '.$this->getContainer()->getUrl()->getHttpDomain().'/admin/login');
        }
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    public function standardAction()
    {
        // dump($this);exit;
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    public function elastiToolsJsAction()
    {
        $javascript = $this->renderView('framework/packages/FrameworkPackage/view/ElastiTools/ElastiToolsJs.php', [
            'container' => $this->getContainer()
        ]);

        return new JavaScript($javascript);
    }
}
