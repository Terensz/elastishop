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

    // public static function getShipmentProductData(array $shipmentIds)
    // {
    //     if (empty($shipmentIds)) {
    //         return [];
    //     }
    //     // App::getContainer()->wireService('WebshopPackage/entity/Shipment');
    //     App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
    //     App::getContainer()->wireService('WebshopPackage/dataProvider/ProductListDataProvider');

    //     // dump($shipmentIds);
    //     $rawShipmentProductData = ShipmentRepository::getShipmentProductData(App::getContainer()->getSession()->getLocale(), false, $shipmentIds);
    //     // dump($rawShipmentProductData);
    //     $arrangedShipmentProductData = ProductListDataProvider::arrangeProductsData($rawShipmentProductData);
    //     // dump($arrangedShipmentProductData);exit;

    //     return $arrangedShipmentProductData;
    // }

    public static function assembleShipmentDataCollection(array $collection) : array
    {
        // dump($collection);exit;
        App::getContainer()->wireService('WebshopPackage/dataProvider/PackDataProvider');
        $packDataCollection = [];
        foreach ($collection['objectCollection'] as $shipment) {
            $packDataCollection[] = PackDataProvider::assembleDataSet($shipment);
        }

        return $packDataCollection;
    }
}
