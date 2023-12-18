<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\PaymentPackage\entity\Payment;

class WebshopPaymentService extends Service
{
    public static function assemblePaymentDataSet(Payment $payment)
    {
        $paymentPattern = [
            'payment' => [
                'id' => null,
                'shipmentId' => null,
                'paymentCode' => null,
                'ipnCalled' => null,
                'gatewayProvider' => null,
                'paymentMethod' => null,
                'totalGrossValue' => null,
                'currency' => null,
                'createdAt' => null,
                'redirectedAt' => null,
                'closedAt' => null,
                'status' => null,
            ]
        ];
    }
}
