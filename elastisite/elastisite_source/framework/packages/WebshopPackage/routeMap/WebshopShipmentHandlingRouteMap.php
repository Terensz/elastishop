<?php
namespace framework\packages\WebshopPackage\routeMap;

class WebshopShipmentHandlingRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'webshop_shipment_handling',
                'paramChains' => array(
                    'webshop/shipment/handling/{shipmentId}' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'checkout',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopShipmentHandlingWidget',
                )
            ),
            array(
                'name' => 'webshop_WebshopShipmentHandlingWidget',
                'paramChains' => array(
                    'webshop/WebshopShipmentHandlingWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopShipmentHandlingController',
                'action' => 'webshopShipmentHandlingWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_shipment_setPaymentMethod',
                'paramChains' => array(
                    'webshop/shipment/setPaymentMethod' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopShipmentHandlingController',
                'action' => 'webshopShipmentSetPaymentMethodAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_shipment_bindShipmentToAccount',
                'paramChains' => array(
                    'webshop/shipment/bindShipmentToAccount' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopShipmentHandlingController',
                'action' => 'webshopShipmentBindShipmentToAccountAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_shipment_paymentModal',
                'paramChains' => array(
                    'webshop/shipment/paymentModal' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopShipmentHandlingController',
                'action' => 'webshopShipmentPaymentModalAction',
                'permission' => 'viewGuestContent'
            )
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
