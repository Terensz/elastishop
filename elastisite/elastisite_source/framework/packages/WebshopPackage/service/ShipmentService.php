<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\PaymentPackage\service\PaymentDataService;
use framework\packages\UserPackage\entity\User;
use framework\packages\WebshopPackage\dataProvider\PackDataProvider;
use framework\packages\WebshopPackage\dataProvider\ProductListDataProvider;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\repository\ShipmentRepository;

class ShipmentService extends Service
{
    const REQUESTER_TYPE_ADMIN = 'admin';
    const REQUESTER_TYPE_PUBLIC = 'public';

    const PERMITTED_USER_TYPE_GUEST = 'Guest';
    const PERMITTED_USER_TYPE_USER = 'User';
    const PERMITTED_USER_TYPE_BOTH = 'Both';

    // public static function getCurrentVisitorsUnhandledShipments()
    // {
    //     return self::getUnhandledShipments(App::getContainer()->getSession()->get('visitorCode'));
    // }

    // public static function getShipmentCount($status = null)
    // {
    //     App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
    //     // $shipmentRepo = App::getContainer()->getService('ShipmentRepository');

    //     return ShipmentRepository::getShipmentCount($status = null);
    // }

    public static function getShipmentByCode($shipmentCode)
    {
        App::getContainer()->setService('WebshopPackage/repository/ShipmentRepository');
        $shipmentRepo = App::getContainer()->getService('ShipmentRepository');
        $shipment = $shipmentRepo->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'code', 'value' => $shipmentCode],
        ]]);

        return $shipment;
    }

    public static function getShipmentStatusConversionArray($requesterType = self::REQUESTER_TYPE_ADMIN)
    {
        $container = App::getContainer();
        $container->wireService('WebshopPackage/entity/Shipment');
        $result = [];
        foreach (Shipment::$statuses as $key => $titles) {
            $result[$key] = $titles[$requesterType.'Title'];
        }

        return $result;
    }

    public static function getShipmentProductData(array $shipmentIds)
    {
        if (empty($shipmentIds)) {
            return [];
        }
        // App::getContainer()->wireService('WebshopPackage/entity/Shipment');
        App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
        App::getContainer()->wireService('WebshopPackage/dataProvider/ProductListDataProvider');

        // dump($shipmentIds);
        $rawShipmentProductData = ShipmentRepository::getShipmentProductData(App::getContainer()->getSession()->getLocale(), false, $shipmentIds);
        // dump($rawShipmentProductData);
        $arrangedShipmentProductData = ProductListDataProvider::arrangeProductsData($rawShipmentProductData);
        // dump($arrangedShipmentProductData);exit;

        return $arrangedShipmentProductData;
    }

    /**
     * @var $collection - E.g.: ShipmentRepository::createShipmentCollection($idsCollection),
     * where: @var $idsCollection e.g.: ['shipment_id' => 1679, 'shipment_id' => 1753]
     * 
     * Very important note!!! 
     * The sub-dataset 'activeProductPrice' means that productPrice the product was purchased on. Regardless which price is currently active.
    */

    public static function assembleShipmentDataCollection(array $collection, bool $returnOneShipmentData = false) : array
    {
        // dump($collection);exit;
        App::getContainer()->wireService('WebshopPackage/dataProvider/PackDataProvider');
        $packDataCollection = [];
        foreach ($collection['objectCollection'] as $shipment) {
            $packDataCollection[] = PackDataProvider::assembleDataSet($shipment);
        }

        return $packDataCollection;
    }

    // public static function assembleShipmentDataSet(array $collection, bool $returnOneShipmentData = false) : array
    // {
    //     // dump('assembleShipmentDataSet');
    //     App::getContainer()->setService('WebshopPackage/repository/ShipmentRepository');
    //     App::getContainer()->setService('PaymentPackage/service/PaymentDataService');
    //     App::getContainer()->setService('PaymentPackage/entity/Payment');

    //     $shipmentProductData = self::getShipmentProductData($collection['ids']);
    //     // dump($shipmentProductData);exit;

    //     $shipmentItemPattern = [
    //         'shipmentItem' => [
    //             'id' => null,
    //             'product' => [],
    //         ]
    //     ];
    //     $shipmentPattern = [
    //         'customer' => [
    //             'name' => null,
    //             'type' => null,
    //             'note' => null,
    //             'email' => null,
    //             'mobile' => null
    //         ],
    //         'shipment' => [
    //             'id' => null,
    //             'code' => null,
    //             'priority' => null,
    //             'permittedUserType' => null,
    //             'permittedForCurrentUser' => null,
    //             'paymentMethod' => null,
    //             'createdAt' => null,
    //             'status' => null,
    //             'publicStatusText' => null,
    //             'adminStatusText' => null,
    //             'shipmentItems' => [],
    //             'payments' => [
    //                 'active' => null,
    //                 'successful' => null,
    //                 'failedForever' => []
    //             ],
    //             'currencyCode' => null,
    //             'confirmationSentAt' => null,
    //             'summary' => [
    //                 'sumGrossItemPriceRounded2' => null,
    //                 'sumGrossItemPriceFormatted' => null
    //             ]
    //         ]
    //     ];

    //     $dataSet = [];
    //     $currencyCode = null;
    //     foreach ($collection['objectCollection'] as $shipment) {
    //         // dump($shipment); exit;
    //         $shipmentData = $shipmentPattern;
    //         $shipmentData['pack']['id'] = $shipment->getId();
    //         $shipmentData['pack']['code'] = $shipment->getCode();
    //         $shipmentData['pack']['priority'] = $shipment->getPriority();
    //         $shipmentData['pack']['paymentMethod'] = $shipment->getPaymentMethod();
    //         $shipmentData['pack']['createdAt'] = $shipment->getCreatedAt();
    //         $shipmentData['pack']['status'] = $shipment->getStatus();
    //         $shipmentData['pack']['publicStatusText'] = isset(Shipment::$statuses[$shipment->getStatus()]) ? trans(Shipment::$statuses[$shipment->getStatus()]['publicTitle']) : null;
    //         $shipmentData['pack']['adminStatusText'] = isset(Shipment::$statuses[$shipment->getStatus()]) ? trans(Shipment::$statuses[$shipment->getStatus()]['adminTitle']) : null;
    //         $shipmentData['pack']['confirmationSentAt'] = $shipment->getConfirmationSentAt();

    //         if ($shipment->getTemporaryAccount() && $shipment->getTemporaryAccount()->getTemporaryPerson()) {
    //             // dump($shipment->getTemporaryAccount());exit;
    //             $customerName = $shipment->getTemporaryAccount()->getTemporaryPerson()->getName();
    //             $recipientName = $shipment->getTemporaryAccount()->getTemporaryPerson()->getRecipientName();
    //             $customerType = $shipment->getTemporaryAccount()->getTemporaryPerson()->getCustomerType();
    //             $customerNote = $shipment->getTemporaryAccount()->getTemporaryPerson()->getCustomerNote();
    //             $customerEmail = $shipment->getTemporaryAccount()->getTemporaryPerson()->getEmail();
    //             $customerMobile = $shipment->getTemporaryAccount()->getTemporaryPerson()->getMobile();
    //             $shipmentData['customer']['name'] = $recipientName ? : $customerName;
    //             $shipmentData['customer']['type'] = $customerType;
    //             $shipmentData['customer']['note'] = $customerNote;
    //             $shipmentData['customer']['email'] = $customerEmail;
    //             $shipmentData['customer']['mobile'] = $customerMobile;
    //         }

    //         // $onlyRegistratedUsersCanCheckout = WebshopService::getSetting('WebshopPackage_onlyRegistratedUsersCanCheckout');
    //         // dump($onlyRegistratedUsersCanCheckout);
    //         // dump($shipment->getUserAccount());exit;
    //         $currentUserType = App::getContainer()->getUser()->getType();
    //         $permittedUserType = $shipment->getUserAccount() ? self::PERMITTED_USER_TYPE_USER : self::PERMITTED_USER_TYPE_GUEST;
    //         $shipmentData['pack']['permittedUserType'] = $permittedUserType;
    //         $shipmentData['pack']['permittedForCurrentUser'] = $permittedUserType == self::PERMITTED_USER_TYPE_BOTH 
    //             || (($permittedUserType == self::PERMITTED_USER_TYPE_GUEST && $currentUserType == User::TYPE_GUEST) 
    //                 || ($permittedUserType == self::PERMITTED_USER_TYPE_USER && $currentUserType == User::TYPE_USER));

    //         $sumGrossItemPriceRounded2 = 0;
    //         foreach ($shipment->getShipmentItem() as $shipmentItem) {
    //             $shipmentItemData = $shipmentItemPattern;
    //             $shipmentItemData['shipmentItem']['id'] = $shipmentItem->getId();
    //             $shipmentItemData['product'] = isset($shipmentProductData[$shipmentItem->getId()]) ? $shipmentProductData[$shipmentItem->getId()] : null;
    //             $shipmentData['pack']['packItems'][] = $shipmentItemData;
    //             // dump($shipmentItemData['product']['actualPrice']);
    //             $currencyCode = $shipmentItemData['product']['actualPrice']['currencyCode'];
    //             $sumGrossItemPriceRounded2 += $shipmentItemData['product']['actualPrice']['grossItemPriceRounded2'];
    //         }

    //         $shipmentData['summary']['sumGrossItemPriceRounded2'] = $sumGrossItemPriceRounded2;
    //         $shipmentData['summary']['sumGrossItemPriceFormatted'] = StringHelper::formatNumber($sumGrossItemPriceRounded2, 2, ',', '.');
    //         $shipmentData['pack']['currencyCode'] = $currencyCode;

    //         /**
    //          * Payment
    //         */
    //         // $activePayment = null;
    //         // $closedPayments = [];
    //         foreach ($shipment->getPayment() as $payment) {
    //             $assembledPaymentData = PaymentDataService::assemblePaymentData($payment);
    //             // dump($assembledPaymentData);
    //             // dump($assembledPaymentData);
    //             if (in_array($payment->getStatus(), Payment::PAYMENT_STATUS_COLLECTION_SUCCEEDED)) {
    //                 $shipmentData['pack']['payments']['successful'] = $assembledPaymentData;
    //                 $shipmentData['pack']['payments']['active'] = $assembledPaymentData;
    //             } elseif (in_array($payment->getStatus(), Payment::PAYMENT_STATUS_COLLECTION_FAILED_FOREVER)) {
    //                 $shipmentData['pack']['payments']['failedForever'][] = $assembledPaymentData;
    //             } else {
    //                 $shipmentData['pack']['payments']['active'] = $assembledPaymentData;
    //             }
                
    //             // else {
    //             //     $shipmentData['pack']['payments']['failed'][] = $assembledPaymentData;
    //             // }
    //         }

    //         // dump($shipmentData['pack']['payments']);exit;

    //         $dataSet[] = $shipmentData;
    //     }

    //     if ($returnOneShipmentData) {
    //         return count($dataSet) == 1 ? $dataSet[0] : null;
    //     }
    //     // dump($dataSet);exit;

    //     return $dataSet;
    // }
}
