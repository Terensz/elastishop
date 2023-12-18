<?php
namespace framework\packages\PaymentPackage\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\PaymentPackage\entity\Payment;

class PaymentDataService extends Service
{
    /*
    Example rawData:
    $rawData = [
    ];
    */
    public static function assemblePaymentData(Payment $payment = null)
    {
        App::getContainer()->setService('PaymentPackage/entyty/Payment');
        $paymentData = [
            'payment' => [
                'id' => null,
                'paymentCode' => null,
                'ipnCalled' => null,
                'gatewayProvider' => null,
                'gatewayUrl' => null,
                'paymentMethod' => null,
                'totalGrossValue' => null,
                'currency' => null,
                'createdAt' => null,
                'redirectedAt' => null,
                'closedAt' => null,
                'status' => null,
                'statusCode' => null,
                'translatedStatusText' => null
            ]
        ];

        if (!$payment) {
            return $paymentData;
        }
        $paymentData['payment']['id'] = $payment->getId();
        $paymentData['payment']['paymentCode'] = $payment->getPaymentCode();
        $paymentData['payment']['ipnCalled'] = $payment->getIpnCalled();
        $paymentData['payment']['gatewayProvider'] = $payment->getGatewayProvider();
        $paymentData['payment']['gatewayUrl'] = $payment->getGatewayUrl();
        $paymentData['payment']['paymentMethod'] = $payment->getPaymentMethod();
        $paymentData['payment']['totalGrossValue'] = $payment->getTotalGrossValue();
        $paymentData['payment']['currency'] = $payment->getCurrency();
        $paymentData['payment']['createdAt'] = $payment->getCreatedAt();
        $paymentData['payment']['redirectedAt'] = $payment->getRedirectedAt();
        $paymentData['payment']['closedAt'] = $payment->getClosedAt();
        $paymentData['payment']['status'] = $payment->getStatus();
        $paymentData['payment']['statusCode'] = $payment->getStatus() && in_array($payment->getStatus(), Payment::PAYMENT_STATUS_COLLECTION_ALL) ? $payment->getStatus() : null;
        $paymentData['payment']['translatedStatusText'] = isset(Payment::PAYMENT_STATUS_TRANSLATION_REFERENCES[$payment->getStatus()]) ? trans(Payment::PAYMENT_STATUS_TRANSLATION_REFERENCES[$payment->getStatus()]) : null;

        return $paymentData;
    }
}