<?php
namespace framework\component\parent;

use framework\component\core\WidgetResponse;
use framework\kernel\utility\BasicUtils;
use framework\component\parent\Rendering;
// use framework\component\parent\PageController;
use framework\component\parent\JsonResponse;
use framework\packages\WordClearingPackage\service\WordExplanationService;

class WidgetController extends Rendering
{
    protected $controllerType = 'widget';

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

    public function widgetResponseWithWordExplanation($response)
    {
        return WidgetResponse::createWithWordExplanation($response);
        // $this->wireService('WordClearingPackage/service/WordExplanationService');
        // $wordExplanationService = new WordExplanationService();
        // $response['view'] = $wordExplanationService->processWordExplanations($response['view']);
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
