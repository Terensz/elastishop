<?php
namespace framework\kernel\ClassManager;

use framework\component\parent\PageController;
use framework\kernel\utility\BasicUtils;

class ControllerLoader extends PageController
{
    public function __construct()
    {
        // dump($this->getContainer()->getRouting());
        $controller = $this->getContainer()->getRoutingHelper()->getController(
            $this->getContainer()->getRouting()->getActualRoute()->getController(),
            $this->getContainer()->getRouting()->getActualRoute()->getAction()
        );

        // dump($this->getContainer()->isAjax());exit;
        if (BasicUtils::explodeAndGetElement(get_parent_class($controller['object']), '\\', 'last') == 'WidgetController' 
            && !$this->getContainer()->isAjax()) {
        }

        // var_dump($controller);exit;

        // if (method_exists($controller['object'], 'beforeAction')) {
        //     $this->beforeActionResult = $controller['object']->beforeAction();
        // }
        
        // else {
        //     dump($controller['object']);
        //     dump('beforeAction not exists');
        //     $controller['object']->beforeAction();
        // }

        $this->initController($controller);
    }
}
