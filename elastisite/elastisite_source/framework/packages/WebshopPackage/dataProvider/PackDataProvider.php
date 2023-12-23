<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\UserPackage\entity\User;
use framework\packages\WebshopPackage\dataProvider\interfaces\PackInterface;
use framework\packages\WebshopPackage\entity\Cart;
use framework\packages\WebshopPackage\entity\CartItem;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\entity\ShipmentItem;

class PackDataProvider extends Service
{
    const PERMITTED_USER_TYPE_GUEST = 'Guest';
    const PERMITTED_USER_TYPE_USER = 'User';
    const PERMITTED_USER_TYPE_BOTH = 'Both';

    public static function getRawDataPattern()
    {
        App::getContainer()->wireService('WebshopPackage/dataProvider/AddressDataProvider');
        return [
            'customer' => [
                'name' => null,
                'type' => null,
                'note' => null,
                'email' => null,
                'mobile' => null,
                'address' => AddressDataProvider::getRawDataPattern(),
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
                'sumGrossPriceFormatted' => null,
                'sumGrossNonSpecialPriceAccurate' => null,
                // 'sumGrossPriceFormatted' => null,
            ]
        ];
    }

    public static function assembleDataSet(PackInterface $packObject = null) : array
    {
        $dataSet = self::getRawDataPattern();
        if (!$packObject) {
            return $dataSet;
        }
        App::getContainer()->wireService('UserPackage/entity/User');
        App::getContainer()->wireService('PaymentPackage/entity/Payment');
        App::getContainer()->wireService('WebshopPackage/entity/Cart');
        App::getContainer()->wireService('WebshopPackage/entity/CartItem');
        App::getContainer()->wireService('WebshopPackage/entity/Shipment');
        App::getContainer()->wireService('WebshopPackage/entity/ShipmentItem');
        App::getContainer()->wireService('WebshopPackage/dataProvider/AddressDataProvider');
        App::getContainer()->wireService('WebshopPackage/dataProvider/OrganizationDataProvider');
        App::getContainer()->wireService('WebshopPackage/dataProvider/PackItemDataProvider');
        App::getContainer()->wireService('WebshopPackage/dataProvider/PaymentDataProvider');

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
            if ($packObject->getTemporaryAccount()->getTemporaryPerson() && $packObject->getTemporaryAccount()->getTemporaryPerson()->getAddress()) {
                $dataSet['customer']['address'] = AddressDataProvider::assembleDataSet($packObject->getTemporaryAccount()->getTemporaryPerson()->getAddress());
            }
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
                // dump($payment);exit;
                $assembledPaymentData = PaymentDataProvider::assembleDataSet($payment);
                // dump($assembledPaymentData);
                if (in_array($payment->getStatus(), Payment::PAYMENT_STATUS_COLLECTION_SUCCEEDED)) {
                    $dataSet['pack']['payments']['successful'] = $assembledPaymentData;
                    $dataSet['pack']['payments']['active'] = $assembledPaymentData;
                } elseif (in_array($payment->getStatus(), Payment::PAYMENT_STATUS_COLLECTION_FAILED_FOREVER)) {
                    $dataSet['pack']['payments']['failedForever'][] = $assembledPaymentData;
                } else {
                    $dataSet['pack']['payments']['active'] = $assembledPaymentData;
                }
            }
        }

        $sumGrossItemPriceRounded2 = 0;
        $sumGrossNonSpecialPriceAccurate = 0;
        $packItemCollection = [];
        if ($packObject instanceof Cart) {
            $packItemCollection = $packObject->getCartItem();
        } elseif ($packObject instanceof Shipment) {
            $packItemCollection = $packObject->getShipmentItem();
        }
        foreach ($packItemCollection as $packItem) {
            if (empty($packItem->getProduct()->getSpecialPurpose()) || $packItem->getProduct()->getSpecialPurpose() == '') {
                $packItemData = PackItemDataProvider::assembleDataSet($packItem);
                if (empty($packItem->getProduct()->getSpecialPurpose()) && isset($packItemData['product']['activePrice'])) {
                    $sumGrossNonSpecialPriceAccurate += $packItemData['product']['activePrice']['grossItemPriceAccurate'];
                }
                $dataSet['pack']['packItems']['productId-'.$packItemData['product']['id']] = $packItemData;
                if (isset($packItemData['product']['activePrice'])) {
                    $currencyCode = $packItemData['product']['activePrice']['currencyCode'];
                    $sumGrossItemPriceRounded2 += $packItemData['product']['activePrice']['grossItemPriceRounded2'];
                }
            }
        }
        foreach ($packItemCollection as $packItem) {
            if (!empty($packItem->getProduct()->getSpecialPurpose()) && $packItem->getProduct()->getSpecialPurpose() != '') {
                $packItemData = PackItemDataProvider::assembleDataSet($packItem);
                if (empty($packItem->getProduct()->getSpecialPurpose()) && isset($packItemData['product']['activePrice'])) {
                    $sumGrossNonSpecialPriceAccurate += $packItemData['product']['activePrice']['grossItemPriceAccurate'];
                }
                $dataSet['pack']['packItems']['productId-'.$packItemData['product']['id']] = $packItemData;
                if (isset($packItemData['product']['activePrice'])) {
                    $currencyCode = $packItemData['product']['activePrice']['currencyCode'];
                    $sumGrossItemPriceRounded2 += $packItemData['product']['activePrice']['grossItemPriceRounded2'];
                }
            }
        }
        // dump('ennyikeh');exit;

        $dataSet['pack']['currencyCode'] = $currencyCode;
        $dataSet['summary']['sumGrossPriceRounded2'] = $sumGrossItemPriceRounded2;
        $dataSet['summary']['sumGrossPriceFormatted'] = StringHelper::formatNumber($sumGrossItemPriceRounded2, 2, ',', '.');
        $dataSet['summary']['sumGrossNonSpecialPriceAccurate'] = $sumGrossNonSpecialPriceAccurate;
        
        return $dataSet;
    }
}