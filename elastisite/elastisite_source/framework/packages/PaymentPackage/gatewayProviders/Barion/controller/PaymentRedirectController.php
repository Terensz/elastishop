<?php
namespace framework\packages\PaymentPackage\gatewayProviders\Barion\controller;

use App;
use framework\component\parent\PageController;
use framework\kernel\utility\BasicUtils;
use framework\packages\PaymentPackage\entity\Payment;
// use framework\packages\PaymentPackage\repository\PaymentRepository;
use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\repository\ShipmentRepository;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler_ShipmentHandling;
use framework\packages\WebshopPackage\service\WebshopService;

class PaymentRedirectController extends PageController
{
    /**
    * Route: [name: payment_redirectFromGatewayProvider_{gatewayProviderRefName}, paramChain: /payment/redirectFromGatewayProvider/{gatewayProviderRefName}/{shipmentCode}]
    * @example: http://elastisite/payment/redirectFromGatewayProvider/Barion/cdsq9hgzqz3rnsy69ztw9mt6?paymentId=3bd4d1c98180ed118bea001dd8b71cc4
    */
    // public function redirectAction($shipmentCode)
    // {
    // }

    // public static function returnFailedContent()
    // {
    //     dump('fail');exit;
    // }

    // public static function returnSuccessfulContent()
    // {
    //     dump('success');exit;
    // }
}