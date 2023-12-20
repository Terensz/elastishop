<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\ToolPackage\service\Mailer;

class WebshopEmailSenderService extends Service
{
    public static function sendMail(array $shipmentDataSet, $referenceKey)
    {
        $methodName = 'sendMail_'.$referenceKey;
        return self::$methodName();
    }

    public static function sendMail_orderSuccessful(array $shipmentDataSet)
    {
        if (isset($shipmentDataSet[0]['shipment'])) {
            $shipmentDataSet = $shipmentDataSet[0];
        }

        if (empty($shipmentDataSet['customer']['email'])) {
            throw new \Exception('We cannot send e-mail if the recipient mail address is missing.');
        }

        App::getContainer()->wireService('ToolPackage/service/Mailer');
        // App::getContainer()->wireService('ToolPackage/service/Mailer');

        // $shipmentDataSet['customer'] Array
        // ['name'] wqe
        // ['type'] Organization
        // ['note'] ewqewqe213123
        // ['email'] null

        $currencyCode = $shipmentDataSet['shipment']['currencyCode'];
        $orderedProducts = [];
        foreach ($shipmentDataSet['shipment']['shipmentItems'] as $shipmentItemData) {
            $shipmentItemData['shipmentItem']['product']['productName'];
            $orderedProducts[] = [
                'productName' => $shipmentItemData['shipmentItem']['product']['productName'],
                'quantity' => $shipmentItemData['shipmentItem']['product']['activeProductPrice']['quantity'],
                'itemGross' => $shipmentItemData['shipmentItem']['product']['activeProductPrice']['grossItemPriceFormatted'],
                'currency' => $currencyCode
            ];
        }

        App::getContainer()->wireService('ToolPackage/service/Mailer');
        $mailer = new Mailer();
        $mailer->setSubject(App::getContainer()->getCompanyData('brand').' - '.trans('order.successful'));
        $mailer->textAssembler->setPackage('WebshopPackage');
        $mailer->textAssembler->setReferenceKey('orderSuccessful');
        $mailer->textAssembler->setEmbeddedViewKeys(['orderedProducts']);
        // dump($shipmentDataSet);
        $mailer->textAssembler->setPlaceholdersAndValues([
            'name' => $shipmentDataSet['customer']['name'],
            'mobile' => $shipmentDataSet['customer']['mobile'],
            'orderedProducts' => $orderedProducts,
            'currency' => $currencyCode,
            'totalPayable' => $shipmentDataSet['shipment']['summary']['sumGrossItemPriceFormatted']
        ]);
        $mailer->textAssembler->create();
        $mailer->setBody($mailer->textAssembler->getView());
        $mailer->addRecipient($shipmentDataSet['customer']['email'], $shipmentDataSet['customer']['name']);
        $mailer->send();

        // $mailer->setSubject(App::getContainer()->getCompanyData('brand').' - '.trans('order.successful'));
        // $mailer->textAssembler->setPackage('WebshopPackage');
        // $mailer->textAssembler->setReferenceKey('orderSuccessful');
        // $mailer->textAssembler->setEmbeddedViewKeys(['orderedProducts']);
        // $mailer->textAssembler->setPlaceholdersAndValues([
        //     'name' => $recipient,
        //     'mobile' => $mobile,
        //     'orderedProducts' => $orderedProducts,
        //     'currency' => $currency,
        //     'totalPayable' => $totalPayable
        // ]);
        // $mailer->textAssembler->create();
        // $mailer->setBody($mailer->textAssembler->getView());
        // $mailer->addRecipient($email, $recipient);
        // $mailer->send();
    }
}
