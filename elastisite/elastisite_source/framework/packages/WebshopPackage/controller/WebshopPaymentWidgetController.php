<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler;

// use framework\component\parent\JsonResponse;
// use framework\kernel\utility\BasicUtils;
// use framework\kernel\utility\FileHandler;
// use framework\component\parent\ImageResponse;
// use framework\packages\ToolPackage\service\Uploader;
// use framework\packages\FormPackage\service\FormBuilder;
// use framework\packages\WebshopPackage\repository\ProductRepository;
// use framework\packages\WebshopPackage\entity\ProductCategory;
// use framework\packages\WebshopPackage\repository\ProductCategoryRepository;
// use framework\packages\WebshopPackage\service\WebshopService;
// use framework\packages\WebshopPackage\repository\ProductPriceActiveRepository;
// use framework\packages\WebshopPackage\entity\ProductPriceActive;
// use framework\packages\WebshopPackage\repository\CartRepository;
// use framework\packages\WebshopPackage\repository\CartItemRepository;
// use framework\packages\WebshopPackage\repository\ShipmentRepository;
// use framework\component\exception\ElastiException;
// use framework\packages\ToolPackage\service\Grid\GridFactory;
// use framework\packages\FrameworkPackage\service\GridAjaxInterface;
// use framework\packages\DataGridPackage\service\DataGridBuilder;
// use framework\packages\ToolPackage\service\Mailer;

class WebshopPaymentWidgetController extends WidgetController
{
    public function __construct()
    {
        // $this->getContainer()->setService('WebshopPackage/service/WebshopService');
    }

    /**
    * Route: [name: webshop_WebshopGWOReplyBarionWidget, paramChain: /webshop/WebshopGWOReplyBarionWidget]
    */
    public function webshopGWOReplyBarionWidgetAction()
    {
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        return WebshopResponseAssembler::render(WebshopResponseAssembler::SECTION_GWO_REPLY_BARION); //exit;
    }

    /**
    * Route: [name: webshop_paymentWidget, paramChain: /webshop/paymentWidget]
    */
    // public function webshopPaymentWidgetAction()
    // {
    //     $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopPaymentWidget/widget.php';
    //     $response = [
    //         'view' => $this->renderWidget('WebshopPaymentWidget', $viewPath, [
    //             'container' => $this->getContainer()
    //         ]),
    //         'data' => []
    //     ];

    //     return $this->widgetResponse($response);
    // }
}