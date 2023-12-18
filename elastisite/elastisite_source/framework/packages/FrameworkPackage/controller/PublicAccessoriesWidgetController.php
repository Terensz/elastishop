<?php
namespace framework\packages\FrameworkPackage\controller;

use framework\component\parent\WidgetController;
use framework\packages\ToolPackage\service\ImageService;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\component\parent\ImageResponse;

class PublicAccessoriesWidgetController extends WidgetController
{
    /**
    * Route: [name: widget_ElastiSiteFooterWidget, paramChain: /widget/ElastiSiteFooterWidget]
    */
    public function elastiSiteFooterWidgetAction()
    {
        $viewPath = 'framework/packages/ElastiSitePackage/view/widget/ElastiSiteFooterWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('ElastiSiteFooterWidget', $viewPath, [
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: widget_ElastiSiteBannerWidget, paramChain: /widget/ElastiSiteBannerWidget]
    */
    public function elastiSiteBannerWidgetAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/ElastiSiteBannerWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('ElastiSiteBannerWidget', $viewPath, [
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: widget_ElastiShopBannerWidget, paramChain: /widget/ElastiShopBannerWidget]
    */
    public function elastiShopBannerWidgetAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/ElastiShopBannerWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('ElastiShopBannerWidget', $viewPath, [
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: widget_ElastiShopFooterWidget, paramChain: /widget/ElastiShopFooterWidget]
    */
    public function elastiShopFooterWidgetAction()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/ElastiShopFooterWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('ElastiShopFooterWidget', $viewPath, [
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }
}
