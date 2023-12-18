<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\WidgetController;
use framework\packages\WebshopPackage\repository\ShipmentRepository;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler_ShipmentHandling;

class WebshopShipmentHandlingController extends WidgetController
{
    public function __construct()
    {
    }

    /**
    * Route: [name: webshop_WebshopShipmentHandlingWidget, paramChain: /webshop/WebshopShipmentHandlingWidget]
    */
    public function webshopShipmentHandlingWidgetAction()
    {
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        return WebshopResponseAssembler::render(WebshopResponseAssembler::SECTION_SHIPMENT_HANDLING); //exit;
    }

    /**
    * Route: [name: webshop_shipment_setPaymentMethod, paramChain: /webshop/shipment/setPaymentMethod]
    */
    public function webshopShipmentSetPaymentMethodAction()
    {
        // App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        // App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
        App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_ShipmentHandling');
        $viewParams = WebshopResponseAssembler_ShipmentHandling::getShipmentHandlingParams();
        $requestedPaymentMethod = StringHelper::mendValue(App::getContainer()->getRequest()->get('paymentMethod'));

        // dump($requestedPaymentMethod);
        // dump($viewParams);exit;

        $shipmentData = null;
        if (isset($viewParams['shipmentDataSet']) && isset($viewParams['shipmentDataSet'][0]) && $viewParams['errors']['Page']['summary']['errorsCount'] == 0) {
            $shipmentData = $viewParams['shipmentDataSet'][0]['shipment'];
            $requestedPaymentMethodFound = false;
            foreach ($viewParams['paymentMethods'] as $availablePaymentMethod) {
                if ($availablePaymentMethod['referenceName'] == $requestedPaymentMethod) {
                    $requestedPaymentMethodFound = true;
                }
            }
    
            // dump($requestedPaymentMethod);
            if ($requestedPaymentMethodFound || $requestedPaymentMethod === null) {
                $shipmentRepository = new ShipmentRepository();
                $shipment = $shipmentRepository->find($shipmentData['id']);
                if ($shipment) {
                    $shipment->setPaymentMethod($requestedPaymentMethod);
                    $shipment = $shipmentRepository->store($shipment);
                }
            }
        }

        $response = [
            'view' => '',
            'data' => [
                // 'organizationId' => $organizationId
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_shipment_bindShipmentToAccount, paramChain: /webshop/shipment/bindShipmentToAccount]
    */
    public function webshopShipmentBindShipmentToAccountAction()
    {
        App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
        $shipmentCode = App::getContainer()->getRequest()->get('shipmentCode');
        $shipmentRepository = new ShipmentRepository();
        $shipment = $shipmentRepository->findOneBy(['conditions' => [['key' => 'code', 'value' => $shipmentCode]]]);
        $userAccount = App::getContainer()->getUser()->getUserAccount();

        if ($shipment && !empty($userAccount->getId())) {
            $shipment->setUserAccount($userAccount);
            $shipment = $shipmentRepository->store($shipment);
        }

        $response = [
            'view' => '',
            'data' => [
                // 'organizationId' => $organizationId
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_shipment_bindShipmentToAccount, paramChain: /webshop/shipment/bindShipmentToAccount]
    */
    public function webshopShipmentPaymentModalAction()
    {
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        return WebshopResponseAssembler::renderSections([WebshopResponseAssembler::SECTION_PAYMENT_MODAL]); //exit;

        // $response = [
        //     'view' => '',
        //     'data' => [
        //         // 'organizationId' => $organizationId
        //     ]
        // ];

        // return $this->widgetResponse($response);
    }
}