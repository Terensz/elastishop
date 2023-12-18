<?php
namespace framework\packages\PaymentPackage\gatewayProviders\Barion\controller;

use App;
use framework\component\parent\PageController;
use framework\packages\PaymentPackage\entity\Payment;
// use framework\packages\PaymentPackage\repository\PaymentRepository;
use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\repository\ShipmentRepository;
use framework\packages\WebshopPackage\service\WebshopService;

class PaymentRedirectController extends PageController
{
    /**
    * Route: [name: payment_redirectFromGatewayProvider_{gatewayProviderRefName}, paramChain: /payment/redirectFromGatewayProvider/{gatewayProviderRefName}/{shipmentCode}]
    * @example: http://elastisite/payment/redirectFromGatewayProvider/Barion/cdsq9hgzqz3rnsy69ztw9mt6?paymentId=3bd4d1c98180ed118bea001dd8b71cc4
    */
    public function redirectAction($shipmentCode)
    {
        $this->wireService('PaymentPackage/service/OnlinePaymentService');
        $this->wireService('WebshopPackage/repository/ShipmentRepository');
        $this->wireService('WebshopPackage/service/WebshopService');
        $this->wireService('WebshopPackage/entity/Shipment');

        // $this->wireService('PaymentPackage/repository/PaymentRepository');
        // $paymentRepo = new PaymentRepository();

        // $successResult = false;
        $fullUrlParts = explode('?paymentId=', $this->getUrl()->getFullUrl());




        // dump($fullUrlParts);
        if (count($fullUrlParts) == 2) {
            $paymentCode = $fullUrlParts[1];

            $shipmentRepo = new ShipmentRepository();
            $shipment = $shipmentRepo->findOneBy(['conditions' => [
                ['key' => 'code', 'value' => $shipmentCode]
            ]]);

            // dump($shipment);exit;

            if ($shipment) {
                $paymentParams = OnlinePaymentService::getPaymentParams($shipment);
                
                if ($paymentParams && $paymentParams['paymentCode'] == $paymentCode && $paymentParams['status'] == Payment::PAYMENT_STATUS_SUCCEEDED) {
                    $shipment->setStatus(Shipment::SHIPMENT_STATUS_ORDER_PLACED);
                    $shipmentRepo->store($shipment);

                    // if ($shipment->getStatus() == Shipment::SHIPMENT_STATUS_REQUIRED) {
                    // }
                    // WebshopService::closeOrder($shipment);
                    // dump($paymentParams);



                    # Ezt vissza kell kapcsolni!
                    App::redirect('/webshop/paymentSuccessful/' . $paymentCode);




                }
            } else {
                App::redirect('/webshop/paymentFailed/' . $paymentCode);
            }


        } else {
            
        }

        App::redirect('/webshop');

        // dump('paymentCode: ' . $paymentCode);
        // exit;

        // dump('Sikertelen');exit;






        // $request = trim(file_get_contents("php://input")); 
        // dump($request);
        // dump('shipmentCode: '.$shipmentCode);exit;
    }
}