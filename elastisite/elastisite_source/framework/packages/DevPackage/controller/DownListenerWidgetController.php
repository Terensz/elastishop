<?php
namespace framework\packages\DevPackage\controller;

use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;

class DownListenerWidgetController extends WidgetController
{
    /**
    * name: down_listener_widget, paramChain: /admin/downListener/widget
    */
    public function downListenerWidgetAction()
    {
        $viewPath = 'framework/packages/DevPackage/view/widget/DownListenerWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('DownListenerWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }
}
