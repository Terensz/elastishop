<?php
namespace framework\kernel\routing;

use framework\kernel\utility\BasicUtils;
// use framework\kernel\routing\entity\Routing;
use framework\component\entity\Route;
use framework\component\helper\PHPHelper;
use framework\component\parent\PageController;

class Router extends PageController
{
    public function __construct()
    {
        $actualUrlParamChain = ($this->getKernelObject('Url')->getIsAjax())
            ? $this->getKernelObject('Url')->getAjaxParamChain()
            : $this->getKernelObject('Url')->getParamChain();

        $routingHelper = $this->getContainer()->getKernelObject('RoutingHelper');

        # A container->route -ba a tenyleges cim alapjan talalt route kerul
        // $pageRoute = $routingHelper->searchRoute($this->getKernelObject('Url')->getParamChain());
        // $this->getContainer()->getRouting()->setPageRoute($pageRoute);
        $actualRoute = $routingHelper->searchRoute($actualUrlParamChain);
        // dump($actualRoute);
        $this->getContainer()->getRouting()->setActualRoute($actualRoute);









        // dump($actualUrlParamChain);

        // if ($this->getContainer()->isAjax()) {
            // dump($actualUrlParamChain);
            // dump($this->getContainer()->getRouting()->getActualRoute());
            // dump($this->getContainer()->getRouting()->getPageRoute()); exit;
        // }

        # Az Url->pageRoute -ba pedig a lap URL-je alapjan
        // $url = $this->getContainer()->getUrl();
        $pageRoute = $routingHelper->searchRoute($this->getKernelObject('Url')->getParamChain());
        $this->getContainer()->getRouting()->setPageRoute($pageRoute);
        $this->getContainer()->getUrl()->setPageRoute($pageRoute);
        $urlParamChainParts = explode('/', $this->getKernelObject('Url')->getParamChain());
        // dump($pageRoute);
        $this->getContainer()->getUrl()->setMainRoute($urlParamChainParts[0]);
        if (isset($urlParamChainParts[1])) {
            $this->getContainer()->getUrl()->setSubRoute($urlParamChainParts[1]);
        }

        // dump($this->getSession()->get('maintenanceMode'));//exit;
        
        if (!$this->getContainer()->isAjax() && $this->getSession()->get('maintenanceMode')) {
            $this->getContainer()->setService($actualRoute->getController());
            $controllerName = BasicUtils::explodeAndGetElement($actualRoute->getController(), '/', 'last');
            $controllerObject = $this->getContainer()->getService($controllerName);
            $controllerParent = BasicUtils::explodeAndGetElement(get_parent_class(get_class($controllerObject)), '\\', 'last');
            if (!in_array($this->getContainer()->getRouting()->getPageRoute()->getName(), array('setup', 'ElastiTools_js')) && $controllerParent == 'PageController') {
                // dump($this->getContainer()->getRouting()->getPageRoute()->getName());
                // dump($controllerParent);
                // exit;
                // $visitorLogService = $this->getContainer()->getKernelObject('VisitorLogService');
                // $visitorLogService->init();
                // dump($controllerParent);
                // dump($this->getContainer()->getRouting()->getPageRoute()->getName());exit;

                // dump($this->getContainer()->getRouting());exit;
                PHPHelper::redirect('/setup', 'Router/__construct()');
            }
        }
    }
}
