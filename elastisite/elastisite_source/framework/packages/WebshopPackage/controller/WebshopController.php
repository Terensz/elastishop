<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\helper\PHPHelper;
use framework\component\parent\PageController;
use framework\packages\FinancePackage\service\InvoiceService;
use framework\packages\FinancePackage\service\VATProfileHandler;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\service\WebshopService;

class WebshopController extends PageController
{
    /**
    * Route: [name: webshop_inactive, paramChain: /webshop/inactive]
    */
    public function webshopInactiveAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            // 'skinName' => 'Basic'
        ]);
    }
    
    /**
    * Route: [name: webshop_paymentSuccessful, paramChain: /webshop/paymentSuccessful/{paymentCode}]
    * @example http://meheszellato/webshop/paymentSuccessful/0e89100bb8d3ed118bec001dd8b71cc4
    */
    // public function webshopPaymentSuccessfulAction($paymentCode)
    // {
    //     $this->wireService('WebshopPackage/service/WebshopService');
    //     $this->wireService('WebshopPackage/entity/Shipment');
    //     $this->wireService('PaymentPackage/entity/Payment');
    //     $paymentResult = WebshopService::getPaymentResult();

    //     // http://elastisite/payment/redirectFromGatewayProvider/Barion/otx15ksnsw57q9gecxcyj8b9?paymentId=75007c5a7c8aed118bea001dd8b71cc4

    //     $payment = WebshopService::getPaymentByCode($paymentCode);

    //     // dump($payment);

    //     if ($payment && $payment->getStatus() == Payment::PAYMENT_STATUS_SUCCEEDED && $payment->getShipment() && $payment->getShipment()->getStatus() == Shipment::SHIPMENT_STATUS_CONFIRMED) {
    //         // dump($payment->getShipment()->getStatus());exit;
    //         // dump($paymentResult['pageTitle']);
    //         $this->getContainer()->getRouting()->getPageRoute()->setTitle($paymentResult['pageTitle']);

    //         // if (!$payment->getReportedAt()) {
    //         //     // dump('feljelentes');
    //         //     $this->wireService('FinancePackage/service/InvoiceService');
    //         //     InvoiceService::createAndReportInvoice($payment->getShipment()->getId());
    //         // }
    //         $orderClosable = false;
    //         $this->wireService('FinancePackage/service/VATProfileHandler');
    //         $vatProfileHandler = new VATProfileHandler(App::getContainer()->getConfig()->getProjectData('VATProfile'));
    //         // dump($vatProfileHandler); exit;

    //         if ($vatProfileHandler->invoiceCreation) {
    //             $this->wireService('FinancePackage/service/InvoiceService');
    //             $invoiceCreator = InvoiceService::createAndReportInvoice($payment->getShipment()->getId());
    //             if ($invoiceCreator->invoiceHeader->getTaxOfficeCommStatus() == $invoiceCreator->invoiceHeader::COMM_STATUS_OK) {
    //                 $orderClosable = true;
    //             }
    //         } else {
    //             $orderClosable = true;
    //         }


    //         // dump($invoiceCreator);exit;
    //         # Ezt vissza kell commentezni. A NAV lejelentes fejlesztese miatt vettem ki.
    //         if ($orderClosable) {
    //             WebshopService::closeOrder($payment->getShipment());
    //         }
    //         else {
    //             /**
    //              * @todo!!!!
    //             */
    //             // dump($invoiceCreator->invoiceHeader->getTaxOfficeCommStatus());
    //         }
    //         // WebshopService::closeOrder($payment->getShipment());
    //         // dump($invoiceCreator->invoiceHeader->getTaxOfficeCommStatus() == '');

    //         // dump('=============');exit;


    //         return $this->renderPage([
    //             'container' => $this->getContainer(),
    //             'skinName' => 'Basic'
    //         ]);
    //     } else {
    //         // dump($payment);exit;
    //         App::redirect('/webshop');
    //     }
    // }
    
    /**
    * Route: [name: webshop_orderSuccessful, paramChain: /webshop/orderSuccessful/{shipmentCode}]
    * @example http://meheszellato/webshop/orderSuccessful/0e89100bb8d3ed118bec001dd8b71cc4
    */
    // public function webshopOrderSuccessfulAction($shipmentCode)
    // {
    //     // $this->wireService('WebshopPackage/service/WebshopService');
    //     // $this->wireService('WebshopPackage/entity/Shipment');
    //     // $this->wireService('PaymentPackage/entity/Payment');
    //     // // $paymentResult = WebshopService::getPaymentResult();

    //     // // http://elastisite/payment/redirectFromGatewayProvider/Barion/otx15ksnsw57q9gecxcyj8b9?paymentId=75007c5a7c8aed118bea001dd8b71cc4

    //     // $shipment = WebshopService::getShipmentByCode($shipmentCode);

    //     // dump($shipment);exit;

    //     return $this->renderPage([
    //         'container' => $this->getContainer(),
    //         // 'skinName' => 'Basic'
    //     ]);
    // }

    /**
    * Route: [name: webshop_paymentFailed, paramChain: /webshop/paymentFailed/{paymentCode}]
    */
    // public function webshopPaymentFailedAction($paymentCode)
    // {
    //     $this->wireService('WebshopPackage/service/WebshopService');
    //     $this->wireService('WebshopPackage/entity/Shipment');
    //     $this->wireService('PaymentPackage/entity/Payment');
    //     $paymentResult = WebshopService::getPaymentResult();

    //     // http://elastisite/payment/redirectFromGatewayProvider/Barion/otx15ksnsw57q9gecxcyj8b9?paymentId=75007c5a7c8aed118bea001dd8b71cc4

    //     $payment = WebshopService::getPaymentByCode($paymentCode);

    //     // $this->wireService('WebshopPackage/service/WebshopService');
    //     // $paymentResult = WebshopService::getPaymentResult();

    //     if (!WebshopService::hasUnconfirmedOrder()) {
    //         // return $this->returnFinalizeOrder();
    //         App::redirect('/webshop');
    //     }

    //     $this->getContainer()->getRouting()->getPageRoute()->setTitle($paymentResult['pageTitle']);
    //     // $this->getContainer()->getRouting()->getActualRoute()->setTitle($paymentResult['pageTitle']);

    //     return $this->renderPage([
    //         'container' => $this->getContainer(),
    //         // 'skinName' => 'Basic'
    //     ]);
    // }

    /**
    * Basic action to set into a route. Because it arranges few basic things.
    */
    public function generalAction($category = null)
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        // $session = $this->getSession()->getAll();
        // dump($session);exit;
        // dump(WebshopService::getCart());exit;

        $cart = WebshopCartService::getCart();
        if ($cart) {
            $shipment = $cart->getShipment();
            if ($shipment) {
                // We should do something with orders?
                // -----------------------------------
                // if (!in_array($shipment->getStatus(), [Shipment::SHIPMENT_STATUS_ORDERED])) {
                //     // WebshopService::closeOrder($shipment, false);
                // }

                // OLD, but: not gold :-( solution:
                // --------------------------------
                // dump($shipment->getStatus());exit;
                // if ($shipment->getStatus() == Shipment::SHIPMENT_STATUS_REQUIRED) {
                //     WebshopService::closeOrder($shipment, false);
                // }
            }
        }

        // dump(WebshopService::getSetting('WebshopPackage_webshopIsActive'));exit;

        if (!WebshopService::getSetting('WebshopPackage_webshopIsActive')) {
            // dump('alma');exit;
            PHPHelper::redirect('/webshop/inactive', 'WebshopController/generalAction()');
        }

        return $this->renderPage([
            'container' => $this->getContainer()
            // 'skinName' => 'Basic'
        ]);
    }

    /**
    * 
    */
    // public function webshopAction($category = null)
    // {
    //     return $this->renderPage([
    //         'container' => $this->getContainer(),
    //         'skinName' => 'Basic'
    //     ]);
    // }

    /**
    * Route: [name: admin_webshop_reset, paramChain: /admin/webshop/reset]
    */
    public function adminWebshopResetAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_webshop_config, paramChain: /admin/webshop/config]
    */
    public function adminWebshopConfigAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_webshop_productCategories, paramChain: /admin/webshop/productCategories]
    */
    public function adminWebshopProductCategoriesAction()
    {
        // dump('alma');
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_webshop_products, paramChain: /admin/webshop/products]
    */
    public function adminWebshopProductsAction()
    {
        // $this->getContainer()->wireService('WebshopPackage/repository/ProductImageRepository');
        // $productImageRepo = new ProductImageRepository();
        // $productImage = $productImageRepo->find(4028);
        // dump($productImage);
        // $productImage->setMain(2);
        // $productImageRepo->store($productImage);
        // dump($productImage);exit;


        // $this->getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        // $productRepo = new ProductRepository();
        // $product = $productRepo->findOneBy(['id' => 4]);
        // dump($product);
        // $product->setNameEn('alma83');
        // $productRepo->store($product);
        // dump($product);exit;

        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_webshop_storages, paramChain: /admin/webshop/storages]
    */
    public function adminWebshopStoragesAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_webshop_inwardProcessing, paramChain: /admin/webshop/inward_processing]
    */
    public function adminWebshopInwardProcessingAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_webshop_stock, paramChain: /admin/webshop/stock]
    */
    public function adminWebshopStockAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_webshop_discounts, paramChain: /admin/webshop/discounts]
    */
    public function adminWebshopDiscountsAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_webshop_shipments, paramChain: /admin/webshop/shipments]
    */
    public function adminWebshopShipmentsAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: webshop_checkout, paramChain: /webshop/checkout]
    */
    // public function webshopCheckoutAction()
    // {
    //     // $this->setService('UserPackage/service/UserService');
    //     // $userService = $this->getService('UserService');
    //     // $userService->removeTemporaryAccount();
        
    //     return $this->renderPage([
    //         'container' => $this->getContainer(),
    //         'skinName' => 'Basic'
    //     ]);
    // }

    /**
    * Route: [name: webshop_registerAndCheckout, paramChain: /webshop/registerAndCheckout]
    */
    // public function webshopRegisterAndCheckoutAction()
    // {
    //     return $this->renderPage([
    //         'container' => $this->getContainer(),
    //         'skinName' => 'Basic'
    //     ]);
    // }

    /**
    * Route: [name: webshop_myOrders, paramChain: /webshop/myOrders]
    */
    // public function webshopMyOrdersAction()
    // {
    //     return $this->renderPage([
    //         'container' => $this->getContainer(),
    //         'skinName' => 'Basic'
    //     ]);
    // }

    /**
    * Route: [name: webshop_cancelOrder, paramChain: /webshop/cancelOrder]
    */
    // public function webshopCancelOrderAction()
    // {
    //     return $this->renderPage([
    //         'container' => $this->getContainer(),
    //         'skinName' => 'Basic'
    //     ]);
    // }
}
