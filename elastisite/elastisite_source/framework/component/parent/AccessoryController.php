<?php
namespace framework\component\parent;

use framework\component\core\WidgetResponse;
use framework\kernel\utility\BasicUtils;
use framework\component\parent\Rendering;
// use framework\component\parent\PageController;
use framework\component\parent\JsonResponse;

class AccessoryController extends Rendering
{
    protected $controllerType = 'accessory';

    public function widgetResponse($response)
    {
        return WidgetResponse::create($response);
        // $security = $this->getContainer()->getKernelObject('Security');
        // $security->finishingSecurity();
        // // dump('hello');exit;
        // if ($this->getContainer()->isAjax()) {
        //     return new JsonResponse($response);
        // } else {
        //     return $response['view'];
        // }
    }
}
