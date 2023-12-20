<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\ToolPackage\service\Mailer;

class WebshopEmailSenderService extends Service
{
    public static function sendMail(array $packDataSet, $referenceKey)
    {
        $methodName = 'sendMail_'.$referenceKey;
        return self::$methodName();
    }

    public static function sendMail_orderSuccessful(array $packDataSet)
    {
        if (isset($packDataSet[0]['pack'])) {
            $packDataSet = $packDataSet[0];
        }

        if (empty($packDataSet['customer']['email'])) {
            throw new \Exception('We cannot send e-mail if the recipient mail address is missing.');
        }

        App::getContainer()->wireService('ToolPackage/service/Mailer');
        // App::getContainer()->wireService('ToolPackage/service/Mailer');

        // $packDataSet['customer'] Array
        // ['name'] wqe
        // ['type'] Organization
        // ['note'] ewqewqe213123
        // ['email'] null

        $currencyCode = $packDataSet['pack']['currencyCode'];
        $orderedProducts = [];
        foreach ($packDataSet['pack']['packItems'] as $shipmentItemData) {
            $shipmentItemData['product']['productName'];
            $orderedProducts[] = [
                'productName' => $shipmentItemData['product']['productName'],
                'quantity' => $shipmentItemData['product']['actualPrice']['quantity'],
                'itemGross' => $shipmentItemData['product']['actualPrice']['grossItemPriceFormatted'],
                'currency' => $currencyCode
            ];
        }

        App::getContainer()->wireService('ToolPackage/service/Mailer');
        $mailer = new Mailer();
        $mailer->setSubject(App::getContainer()->getCompanyData('brand').' - '.trans('order.successful'));
        $mailer->textAssembler->setPackage('WebshopPackage');
        $mailer->textAssembler->setReferenceKey('orderSuccessful');
        $mailer->textAssembler->setEmbeddedViewKeys(['orderedProducts']);
        // dump($packDataSet);
        $mailer->textAssembler->setPlaceholdersAndValues([
            'name' => $packDataSet['customer']['name'],
            'mobile' => $packDataSet['customer']['mobile'],
            'orderedProducts' => $orderedProducts,
            'currency' => $currencyCode,
            'totalPayable' => $packDataSet['summary']['sumGrossItemPriceFormatted']
        ]);
        $mailer->textAssembler->create();
        $mailer->setBody($mailer->textAssembler->getView());
        $mailer->addRecipient($packDataSet['customer']['email'], $packDataSet['customer']['name']);
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
