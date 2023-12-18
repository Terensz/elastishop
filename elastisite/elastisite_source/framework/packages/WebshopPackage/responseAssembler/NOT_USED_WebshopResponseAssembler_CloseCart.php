<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\core\WidgetResponse;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;

class WebshopResponseAssembler_CloseCart extends Service
{
    public static function assembleResponse($processedRequestData = null, $data = [])
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
        $cartDataSet = WebshopCartService::assembleCartDataSet();

        // $processedRequestData = $processedRequestData ? : WebshopRequestService::getProcessedRequestData();
        // $locale = App::getContainer()->getSession()->getLocale();

        // dump($cartData); exit;

        $viewParams = [
            'cartDataSet' => $cartDataSet ? : [],
            'checkoutLink' => '/webshop/checkout'
        ];

        $viewPath = 'framework/packages/WebshopPackage/view/Sections/SideCart/SideCart.php';
        $view = ViewRenderer::renderWidget('WebshopPackage_SideCart', $viewPath, $viewParams);

        return [
            'view' => $view,
            'data' => [
            ]
        ];

        // $response = [
        //     'view' => $view,
        //     'data' => [
        //         // 'closeModal' => $form->isValid() ? true : false
        //     ]
        // ];

        // return WidgetResponse::create($response);
    }
}