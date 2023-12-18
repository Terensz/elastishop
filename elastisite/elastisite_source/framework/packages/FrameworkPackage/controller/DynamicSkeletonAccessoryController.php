<?php
namespace framework\packages\FrameworkPackage\controller;

use framework\component\parent\AccessoryController;

class DynamicSkeletonAccessoryController extends AccessoryController
{
    /**
    * name: dynamicSkeleton_styleSheet, paramChain: /dynamicSkeleton/styleSheet
    */
    public function dynamicSkeletonStyleSheetAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/DynamicSkeletonAccessories/styleSheet.css';
        $response = [
            'view' => $this->renderWidget('dynamicSkeletonStyleSheet', $viewPath, [
            ]),
            'data' => []
        ];

        // return $this->widgetResponse($response);
        header('Content-Type: text/css');
        echo $this->widgetResponse($response);
        exit;
    }

    /**
    * name: dynamicSkeleton_scripts_head, paramChain: /dynamicSkeleton/scripts/head
    */
    public function dynamicSkeletonScriptsHeadAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/DynamicSkeletonAccessories/scripts_head.js';
        $response = [
            'view' => $this->renderWidget('dynamicSkeletonScriptsHead', $viewPath, [
            ]),
            'data' => []
        ];

        header('Content-Type: application/javascript');
        echo $this->widgetResponse($response);
        exit;
    }

    /**
    * name: dynamicSkeleton_scripts_afterBody, paramChain: /dynamicSkeleton/scripts/afterBody
    */
    public function dynamicSkeletonScriptsAfterBodyAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/DynamicSkeletonAccessories/scripts_afterBody.js';
        $response = [
            'view' => $this->renderWidget('dynamicSkeletonScriptsAfterBody', $viewPath, [
            ]),
            'data' => []
        ];

        header('Content-Type: application/javascript');
        echo $this->widgetResponse($response);
        exit;
    }
}
