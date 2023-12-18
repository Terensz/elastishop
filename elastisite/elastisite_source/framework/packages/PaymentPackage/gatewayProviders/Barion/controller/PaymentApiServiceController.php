<?php
namespace framework\packages\PaymentPackage\gatewayProviders\Barion\controller;

use framework\component\parent\APIServiceController;
use framework\packages\PaymentPackage\repository\PaymentRepository;
use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\WebshopPackage\repository\ShipmentRepository;

class PaymentApiServiceController extends APIServiceController
{
    public function __construct()
    {
        $this->getContainer()->setService('UserPackage/repository/UserAccountRepository');
        $this->identityRepository = $this->getContainer()->getService('UserAccountRepository');
        parent::__construct();
    }

    public function ipnAction($shipmentCode)
    {
        $this->wireService('PaymentPackage/service/OnlinePaymentService');
        $this->wireService('PaymentPackage/repository/PaymentRepository');
        $paymentRepo = new PaymentRepository();

        $successResult = false;
        $fullUrlParts = explode('?paymentId=', $this->getUrl()->getFullUrl());
        if (count($fullUrlParts) == 2) {
            $paymentCode = $fullUrlParts[1];

            $shipmentRepo = new ShipmentRepository();
            $shipment = $shipmentRepo->findOneBy(['conditions' => [
                ['key' => 'code', 'value' => $shipmentCode]
            ]]);

            if ($shipment) {
                $payment = OnlinePaymentService::getPayment($shipment, $paymentCode);
                
                if ($payment) {
                    $payment->setIpnCalled(1);
                    $paymentRepo->store($payment);
                    $successResult = true;
                }
            }
        }

        return $this->successResult(['successResult' => $successResult]);
    }
}