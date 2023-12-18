<?php
namespace framework\packages\ToolPackage\controller;

use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;

class ExitIntentWidgetController extends WidgetController
{
    /**
    * Route: [name: exitIntent, paramChain: /exitIntent]
    */
    public function exitIntentAction()
    {
        // $response = [
        //     'view' => $this->renderWidget('ExitIntent', null, [
        //         'container' => $this->getContainer()
        //     ]),
        //     'data' => []
        // ];
        // return $this->widgetResponse($response);

        return new JsonResponse(array(
            'result' => '',
            'content' => ''
        ));
    }
}
