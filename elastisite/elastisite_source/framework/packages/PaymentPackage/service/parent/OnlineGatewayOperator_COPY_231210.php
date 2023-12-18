<?php
namespace framework\packages\PaymentPackage\service\parent;

// use framework\kernel\utility\BasicUtils;
use framework\component\parent\Service;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\PaymentPackage\repository\PaymentRepository;
use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\WebshopPackage\entity\Shipment;

abstract class OnlineGatewayOperator_COPY_231210 extends Service
{
    public $payment;

    public $propertyToKeyConversionMap = [];

    const GATEWAY_PROVIDER = null;

    public function findPayment(Shipment $shipment) : Payment
    {
        // dump($shipment);exit;
        // $repo = new PaymentRepository();
        // $payment = $repo->findOneBy(['conditions' => [['key' => 'shipment_id', 'value' => $shipment->getId()]]]);

        $this->wireService('PaymentPackage/service/OnlinePaymentService');
        $payment = OnlinePaymentService::getPayment($shipment);

        if (!$payment) {
            $payment = new Payment();
            $payment->setShipment($shipment);
            $payment->setStatus(Payment::PAYMENT_STATUS_CREATED);
            // $payment = $payment->getRepository()->store($payment);
            // $payment->setGatewayProvider(static::GATEWAY_PROVIDER);
        }
        
        return $payment;
    }

    public function init() 
    {
        if (!empty($this->propertyToKeyConversionMap)) {
            $this->payment->transaction->propertyToKeyConversionMap = $this->propertyToKeyConversionMap;
        }
    }

    abstract public function refreshPaymentStatus();

    abstract public function preparePayment();
}