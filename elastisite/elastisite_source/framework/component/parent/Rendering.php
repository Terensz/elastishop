<?php
namespace framework\component\parent;

use framework\kernel\utility\BasicUtils;
use framework\kernel\component\Kernel;
use framework\packages\UXPackage\service\ViewTools;
use framework\component\exception\ElastiException;
use framework\kernel\view\ViewRenderer;

class Rendering extends Kernel
{
    protected $controllerType;

    public function getControllerType()
    {
        return $this->controllerType;
    }

    public function echoView($viewFilePath, $viewData)
    {
        echo $this->renderView($viewFilePath, $viewData);
    }

    public function renderWidget($widgetName, $viewFilePath, $viewData, $debug = false)
    {
        return ViewRenderer::renderWidget($widgetName, $viewFilePath, $viewData, $debug = false);
    }

    public function callRoute($paramChain)
    {
        $route = $this->getKernelObject('RoutingHelper')->searchRoute($paramChain);
        $controller = $this->getContainer()->getRoutingHelper()->getController($route->getController(), $route->getAction());
        $router = $this->getContainer()->getKernelObject('Router');

        $urlParamArray = explode('/', trim($paramChain, '/'));
        $routeParamArray = explode('/', trim($route->getParamChain(), '/'));
        
        return $router->startController(
            $controller,
            ($this->getKernelObject('Url')->getIsAjax() ? false : true),
            false,
            $urlParamArray[0],
            $route->getPermission(),
            $urlParamArray,
            $routeParamArray
        );
    }
}
