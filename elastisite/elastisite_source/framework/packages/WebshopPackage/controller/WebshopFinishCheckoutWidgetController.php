<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\WebshopPackage\service\WebshopFinishCheckoutService;

class WebshopFinishCheckoutWidgetController extends WidgetController
{
    /**
    * Route: [name: webshop_checkout_finishCheckout, paramChain: /webshop/checkout/finishCheckout]
    */
    public function webshopCheckoutFinishCheckoutAction()
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopFinishCheckoutService');
        $shipment = WebshopFinishCheckoutService::loadCartDataToShipment();

        $response = [
            'view' => '',
            'data' => [
                'shipmentCode' => $shipment ? $shipment->getCode() : null,
                'success' => true
                // 'organizationId' => $organizationId
            ]
        ];

        return $this->widgetResponse($response);
    }
}