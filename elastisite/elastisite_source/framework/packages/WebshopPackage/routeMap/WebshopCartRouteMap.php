<?php
namespace framework\packages\WebshopPackage\routeMap;

class WebshopCartRouteMap
{
    public static function get()
    {
        return array(
            // array(
            //     'name' => 'webshop_addToCart',
            //     'paramChains' => array(
            //         'webshop/addToCart' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopClassicProductListWidgetController',
            //     'action' => 'webshopAddToCartAction',
            //     'permission' => 'viewGuestContent'
            // ),
            array(
                'name' => 'webshop_setCartItemQuantity',
                'paramChains' => array(
                    'webshop/setCartItemQuantity' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCartWidgetController',
                'action' => 'webshopSetCartItemQuantityAction',
                'permission' => 'viewGuestContent'
            ),
            // array(
            //     'name' => 'webshop_removeFromCart',
            //     'paramChains' => array(
            //         'webshop/removeFromCart' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopClassicProductListWidgetController',
            //     'action' => 'webshopRemoveFromCartAction',
            //     'permission' => 'viewGuestContent'
            // )
        );
    }
}
