<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\core\WidgetResponse;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;
use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\repository\ShipmentRepository;
use framework\packages\WebshopPackage\service\ShipmentService;
use framework\packages\WebshopPackage\service\WebshopProductService;
use framework\packages\WebshopPackage\service\WebshopService;

class WebshopResponseAssembler_ShipmentsInProgress extends Service
{
    public static function assembleResponse($processedRequestData = null)
    {
        App::getContainer()->wireService('WebshopPackage/entity/Shipment');
        App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
        App::getContainer()->wireService('WebshopPackage/service/ShipmentService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        $collection = ShipmentRepository::getShipmentCollectionWithSpecificStatuses(Shipment::STATUS_COLLECTION_SHIPMENTS_IN_PROGRESS, App::getContainer()->getSession()->get('visitorCode'));
        $shipmentDataSet = ShipmentService::assembleShipmentDataSet($collection);

        // dump($collection);

        $viewParams = [
            'shipmentDataSet' => $shipmentDataSet
            // 'productsData' => $productsData,
        ];

        $viewPath = 'framework/packages/WebshopPackage/view/Sections/ShipmentsInProgress/ShipmentsInProgress.php';
        $view = ViewRenderer::renderWidget('WebshopPackage_ShipmentsInProgress', $viewPath, $viewParams);

        return [
            'view' => $view,
            'data' => [
            ]
        ];

        // $response = [
        //     'view' => $view,
        //     'data' => [
        //         // 'closeModal' => $form->isValid() ? true : false
        //     ]
        // ];

        // return WidgetResponse::create($response);
    }

}