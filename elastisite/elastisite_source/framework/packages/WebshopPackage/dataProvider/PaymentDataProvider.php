<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\parent\Service;
use framework\packages\PaymentPackage\entity\Payment;

class PaymentDataProvider extends Service
{
    public static function getRawDataPattern()
    {
        return [
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
        ];
    }

    public static function assembleDataSet(Payment $payment = null)
    {
        App::getContainer()->setService('PaymentPackage/entyty/Payment');
        $dataSet = self::getRawDataPattern();
        if (!$payment) {
            return $dataSet;
        }
        $dataSet['id'] = $payment->getId();
        $dataSet['paymentCode'] = $payment->getPaymentCode();
        $dataSet['payment']['ipnCalled'] = $payment->getIpnCalled();
        $dataSet['gatewayProvider'] = $payment->getGatewayProvider();
        $dataSet['gatewayUrl'] = $payment->getGatewayUrl();
        $dataSet['paymentMethod'] = $payment->getPaymentMethod();
        $dataSet['totalGrossValue'] = $payment->getTotalGrossValue();
        $dataSet['currency'] = $payment->getCurrency();
        $dataSet['createdAt'] = $payment->getCreatedAt();
        $dataSet['redirectedAt'] = $payment->getRedirectedAt();
        $dataSet['closedAt'] = $payment->getClosedAt();
        $dataSet['status'] = $payment->getStatus();
        $dataSet['statusCode'] = $payment->getStatus() && in_array($payment->getStatus(), Payment::PAYMENT_STATUS_COLLECTION_ALL) ? $payment->getStatus() : null;
        $dataSet['translatedStatusText'] = isset(Payment::PAYMENT_STATUS_TRANSLATION_REFERENCES[$payment->getStatus()]) ? trans(Payment::PAYMENT_STATUS_TRANSLATION_REFERENCES[$payment->getStatus()]) : null;

        return $dataSet;
    }
}