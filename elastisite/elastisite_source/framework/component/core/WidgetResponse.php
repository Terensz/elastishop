<?php

namespace framework\component\core;

use App;
use framework\component\exception\ElastiException;
use framework\component\parent\JsonResponse;
use framework\packages\WordClearingPackage\service\WordExplanationService;

class WidgetResponse
{
    public static function create($response)
    {
        $security = App::getContainer()->getKernelObject('Security');
        $security->finishingSecurity();

        // dump('hello');exit;
        if (App::getContainer()->isAjax()) {
            return new JsonResponse($response);
        } else {
            if (!isset($response['view'])) {
                // dump($response);exit;
                return 'This URL cannot be visited this way.';
            }
            return $response['view'];
        }
    }

    public static function createWithWordExplanation($response)
    {
        App::getContainer()->wireService('WordClearingPackage/service/WordExplanationService');
        $wordExplanationService = new WordExplanationService();
        $response['view'] = $wordExplanationService->processWordExplanations($response['view']);
        $security = App::getContainer()->getKernelObject('Security');
        $security->finishingSecurity();
        // dump('hello');exit;
        if (App::getContainer()->isAjax()) {
            return new JsonResponse($response);
        } else {
            return $response['view'];
        }
    }
}