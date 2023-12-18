<?php
namespace projects\ASC\controller;

use App;
use framework\component\parent\WidgetController;
// use framework\packages\ContentCapturePackage\service\HtmldocHelper;
use framework\packages\ContentCapturePackage\service\ContentCaptureHelper;
use projects\AmbiLight\service\AmbiLightContentCapture;

class ContentCaptureWidgetController extends WidgetController 
{
    /**
    * Route: [name: widget_ContentCaptureWidget, paramChain: /widget/ContentCaptureWidget]
    */
    public function contentCaptureWidgetAction()
    {
        App::getContainer()->wireService('projects/AmbiLight/service/AmbiLightContentCapture');
        AmbiLightContentCapture::queryFactories();

        // App::getContainer()->wireService('ContentCapturePackage/service/ContentCaptureHelper');
        // // SimpleHtmlDomHelper::saveImage('https://be.acb.lighting/vsbridge/image/resize/312/312/41/ec/2cc82b31ee59def43d6234bd9679.jpg');
        // // exit;
        // $contentCaptureHelper = new ContentCaptureHelper();
        // $contentCaptureHelper->factoryCode = 'ACB';
        // $contentCaptureHelper->httpDomain = 'https://acb.lighting';
        // $contentCaptureHelper->productListRelativeUrl = 'products';
        // $contentCaptureHelper->productWrapperClass = 'product-item__wrapper';
        // $contentCaptureHelper->productNameContainerClass = 'product-name';
        // $contentCaptureHelper->productDetailsLinkBearerClass = 'product-link';
        // $contentCaptureHelper->productDetailsLinkBearerAttribute = 'href';
        // $contentCaptureHelper->productDetailsLinkContainsArticleNumber = true;
        // $contentCaptureHelper->capture();

        // SimpleHtmlDomHelper::test('https://acb.lighting/products');

        $viewPath = 'projects/ASC/view/widget/ContentCaptureWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('admin_ascSubscriptionOffer_new', $viewPath, [
                'message' => ''
            ]),
            'data' => [
                'label' => 'alma'
            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }
}