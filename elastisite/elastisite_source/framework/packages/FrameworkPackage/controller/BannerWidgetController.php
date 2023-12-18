<?php
namespace framework\packages\FrameworkPackage\controller;

use framework\component\parent\WidgetController;

class BannerWidgetController extends WidgetController
{
    /**
    * Route: [name: widget_BannerWidget, paramChain: /widget/BannerWidgett]
    */
    public function bannerWidgetAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/BannerWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('BannerWidget', $viewPath, [
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }
}
