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

    public static function assembleDataSet(Payment $object = null)
    {
        App::getContainer()->wireService('PaymentPackage/entity/Payment');
        
        $dataSet = self::getRawDataPattern();
        if (!$object) {
            return $dataSet;
        }
        $dataSet['id'] = $object->getId();
        $dataSet['paymentCode'] = $object->getPaymentCode();
        $dataSet['payment']['ipnCalled'] = $object->getIpnCalled();
        $dataSet['gatewayProvider'] = $object->getGatewayProvider();
        $dataSet['gatewayUrl'] = $object->getGatewayUrl();
        $dataSet['paymentMethod'] = $object->getPaymentMethod();
        $dataSet['totalGrossValue'] = $object->getTotalGrossValue();
        $dataSet['currency'] = $object->getCurrency();
        $dataSet['createdAt'] = $object->getCreatedAt();
        $dataSet['redirectedAt'] = $object->getRedirectedAt();
        $dataSet['closedAt'] = $object->getClosedAt();
        $dataSet['status'] = $object->getStatus();
        $dataSet['statusCode'] = $object->getStatus() && in_array($object->getStatus(), Payment::PAYMENT_STATUS_COLLECTION_ALL) ? $object->getStatus() : null;
        $dataSet['translatedStatusText'] = isset(Payment::PAYMENT_STATUS_TRANSLATION_REFERENCES[$object->getStatus()]) ? trans(Payment::PAYMENT_STATUS_TRANSLATION_REFERENCES[$object->getStatus()]) : null;

        return $dataSet;
    }
}