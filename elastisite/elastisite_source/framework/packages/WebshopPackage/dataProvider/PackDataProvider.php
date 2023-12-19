<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\UserPackage\entity\User;
use framework\packages\WebshopPackage\dataProvider\interfaces\PackInterface;
use framework\packages\WebshopPackage\entity\Shipment;

class PackDataProvider extends Service
{
    const PERMITTED_USER_TYPE_GUEST = 'Guest';
    const PERMITTED_USER_TYPE_USER = 'User';
    const PERMITTED_USER_TYPE_BOTH = 'Both';

    public static function getRawDataPattern()
    {
        return [
            'customer' => [
                'name' => null,
                'type' => null,
                'note' => null,
                'email' => null,
                'mobile' => null,
                'address' => null,
                'organization' => null
            ],
            'pack' => [
                'id' => null,
                'packItems' => [],
                'code' => null,
                'priority' => null,
                'permittedUserType' => null,
                'permittedForCurrentUser' => null,
                'paymentMethod' => null,
                'createdAt' => null,
                'status' => null,
                'publicStatusText' => null,
                'adminStatusText' => null,
                'payments' => [
                    'active' => null,
                    'successful' => null,
                    'failedForever' => []
                ],
                'currencyCode' => null,
                'confirmationSentAt' => null,
            ],
            'summary' => [
                'sumGrossPriceRounded2' => null,
                'sumGrossPriceFormatted' => null
            ]
        ];
    }

    public static function assembleDataSet(PackInterface $packObject, string $packItemGetter) : array
    {
        $dataSet = self::getRawDataPattern();
        App::getContainer()->wireService('UserPackage/entity/User');
        App::getContainer()->wireService('PaymentPackage/entity/Payment');
        App::getContainer()->wireService('WebshopPackage/entity/Shipment');
        App::getContainer()->wireService('WebshopPackage/dataProvider/AddressDataProvider');
        App::getContainer()->wireService('WebshopPackage/dataProvider/OrganizationDataProvider');
        App::getContainer()->wireService('WebshopPackage/dataProvider/PackItemDataProvider');

        if ($packObject->getTemporaryAccount() && $packObject->getTemporaryAccount()->getTemporaryPerson()) {
            $customerName = $packObject->getTemporaryAccount()->getTemporaryPerson()->getName();
            $recipientName = $packObject->getTemporaryAccount()->getTemporaryPerson()->getRecipientName();
            $customerType = $packObject->getTemporaryAccount()->getTemporaryPerson()->getCustomerType();
            $customerNote = $packObject->getTemporaryAccount()->getTemporaryPerson()->getCustomerNote();
            $customerEmail = $packObject->getTemporaryAccount()->getTemporaryPerson()->getEmail();
            $dataSet['customer']['name'] = $recipientName ? : $customerName;
            $dataSet['customer']['type'] = $customerType;
            $dataSet['customer']['note'] = $customerNote;
            $dataSet['customer']['email'] = $customerEmail;
            $dataSet['customer']['address'] = AddressDataProvider::assembleDataSet($packObject->getTemporaryAccount()->getTemporaryPerson());
            $dataSet['customer']['organization'] = OrganizationDataProvider::assembleDataSet($packObject->getTemporaryAccount()->getTemporaryPerson()->getOrganization());
        }
        $dataSet['pack']['id'] = $packObject->getId();
        if ($packObject instanceof Shipment) {
            $dataSet['pack']['code'] = $packObject->getCode();
            $dataSet['pack']['priority'] = $packObject->getPriority();
            $currentUserType = App::getContainer()->getUser()->getType();
            $permittedUserType = $packObject->getUserAccount() ? self::PERMITTED_USER_TYPE_USER : self::PERMITTED_USER_TYPE_GUEST;
            $dataSet['pack']['permittedUserType'] = $permittedUserType;
            $dataSet['pack']['permittedForCurrentUser'] = $permittedUserType == self::PERMITTED_USER_TYPE_BOTH 
            || (($permittedUserType == self::PERMITTED_USER_TYPE_GUEST && $currentUserType == User::TYPE_GUEST) 
                || ($permittedUserType == self::PERMITTED_USER_TYPE_USER && $currentUserType == User::TYPE_USER));
            $dataSet['pack']['paymentMethod'] = $packObject->getPaymentMethod();
            $dataSet['pack']['createdAt'] = $packObject->getCreatedAt();
            $dataSet['pack']['status'] = $packObject->getStatus();
            $dataSet['pack']['publicStatusText'] = isset(Shipment::$statuses[$packObject->getStatus()]) ? trans(Shipment::$statuses[$packObject->getStatus()]['publicTitle']) : null;
            $dataSet['pack']['adminStatusText'] = isset(Shipment::$statuses[$packObject->getStatus()]) ? trans(Shipment::$statuses[$packObject->getStatus()]['adminTitle']) : null;
            $dataSet['pack']['confirmationSentAt'] = $packObject->getConfirmationSentAt();

            foreach ($packObject->getPayment() as $payment) {
                $assembledPaymentData = PaymentDataProvider::assembleDataSet($payment);
                if (in_array($payment->getStatus(), Payment::PAYMENT_STATUS_COLLECTION_SUCCEEDED)) {
                    $shipmentData['shipment']['payments']['successful'] = $assembledPaymentData;
                    $shipmentData['shipment']['payments']['active'] = $assembledPaymentData;
                } elseif (in_array($payment->getStatus(), Payment::PAYMENT_STATUS_COLLECTION_FAILED_FOREVER)) {
                    $shipmentData['shipment']['payments']['failedForever'][] = $assembledPaymentData;
                } else {
                    $shipmentData['shipment']['payments']['active'] = $assembledPaymentData;
                }
            }
        }

        $sumGrossItemPriceRounded2 = 0;
        foreach ($packObject->$packItemGetter() as $packItem) {
            $packItemData = PackItemDataProvider::assembleDataSet($packItem);
            $dataSet['pack']['packItems'][] = $packItemData;
            $currencyCode = $packItemData['product']['activePrice']['currencyCode'];
            $sumGrossItemPriceRounded2 += $packItemData['product']['activePrice']['priceData']['grossItemPriceRounded2'];
        }

        $dataSet['pack']['currencyCode'] = $currencyCode;
        $dataSet['summary']['sumGrossItemPriceRounded2'] = $sumGrossItemPriceRounded2;
        $dataSet['summary']['sumGrossItemPriceFormatted'] = StringHelper::formatNumber($sumGrossItemPriceRounded2, 2, ',', '.');

        return $dataSet;
    }
}