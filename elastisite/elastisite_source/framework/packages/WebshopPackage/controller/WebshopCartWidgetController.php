<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler;

class WebshopCartWidgetController extends WidgetController
{
    /**
    * Route: [name: webshop_addToCart, paramChain: /webshop/addToCart]
    */
    // public function webshopAddToCartAction()
    // {
    //     App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
    //     $productPriceActiveId = (int)$this->getContainer()->getRequest()->get('offerId');
    //     $newQuantity = $this->getContainer()->getRequest()->get('newQuantity');
    //     if ($newQuantity !== null && !is_numeric($newQuantity)) {
    //         $newQuantity = 0;
    //     }
    //     $addedQuantity = $newQuantity === null ? 1 : null;

    //     $cartItem = WebshopCartService::addToCart($productPriceActiveId, $newQuantity, $addedQuantity);

    //     App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
    //     // $return = WebshopResponseAssembler::renderSections([WebshopResponseAssembler::SECTION_SIDE_CART]); //exit;
    //     $return = WebshopResponseAssembler::renderSections([]); //exit;
    //     // dump($return);exit;
    //     return $return;
    //     // return '';
    // }

    /**
    * Route: [name: webshop_setCartItemQuantity, paramChain: /webshop/setCartItemQuantity]
    */
    public function webshopSetCartItemQuantityAction()
    {
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        // $return = WebshopResponseAssembler::renderSections([WebshopResponseAssembler::SECTION_SIDE_CART]); //exit;
        // dump([WebshopResponseAssembler::SECTION_SET_CART_ITEM_QUANTITY_MODAL]); exit;
        $return = WebshopResponseAssembler::renderSections([WebshopResponseAssembler::SECTION_SET_CART_ITEM_QUANTITY_MODAL]); //exit;

        return $return;
    }
}
