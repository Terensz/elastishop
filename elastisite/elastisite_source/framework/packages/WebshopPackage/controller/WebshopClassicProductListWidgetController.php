<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler;
use framework\packages\WebshopPackage\service\WebshopCartService;

class WebshopClassicProductListWidgetController extends WidgetController
{
    public function __construct()
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        WebshopCartService::checkAndExecuteTriggers();
    }
    
    /**
    * Route: [name: webshop_productListWidget, paramChain: /webshop/productListWidget]
    * Nem igy lesz megoldva.
    */
    public function webshopProductListWidgetAction()
    {
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        return WebshopResponseAssembler::render(WebshopResponseAssembler::SECTION_PRODUCT_LIST); //exit;
    }

    /**
    * Route: [name: webshop_WebshopProductDetailsWidget, paramChain: /webshop/WebshopProductDetailsWidget]
    */
    public function webshopProductDetailsWidgetAction()
    {
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        return WebshopResponseAssembler::render(WebshopResponseAssembler::SECTION_PRODUCT_DETAILS); //exit;
    }

    /**
    * Route: [name: webshop_WebshopProductDetailsModal, paramChain: /webshop/WebshopProductDetailsModal]
    */
    public function webshopProductDetailsModalAction()
    {
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        return WebshopResponseAssembler::renderSections([WebshopResponseAssembler::SECTION_PRODUCT_DETAILS_MODAL]); //exit;
    }

    /**
    * Route: [name: webshop_productWidget, paramChain: /webshop/productWidget]
    * Nem igy lesz megoldva.
    */
    // public function webshopProductWidgetAction()
    // {
    //     App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
    //     return WebshopResponseAssembler::render(WebshopResponseAssembler::SECTION_PRODUCT_INFO); //exit;
    // }

    /**
    * Route: [name: webshop_removeFromCart, paramChain: /webshop/removeFromCart]
    */
    // public function webshopRemoveFromCartAction()
    // {
    // }
}
