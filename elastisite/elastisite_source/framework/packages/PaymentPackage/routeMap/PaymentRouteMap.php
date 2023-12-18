<?php
namespace framework\packages\PaymentPackage\routeMap;

use framework\component\parent\Service;
use framework\kernel\base\Container;
use framework\packages\PaymentPackage\service\OnlinePaymentService;

class PaymentRouteMap
{
    public static function getContainerObject()
    {
        return Container::getSelfObject();
    }

    public static function get()
    {
        (self::getContainerObject())->wireService('PaymentPackage/service/OnlinePaymentService');
        $activeGatewayProviders = OnlinePaymentService::getAvailableGatewayProviders();

        $routeArray = [];
        /*
        array(
            'name' => 'webshop_shipment/handling',
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
            'name' => 'webshop_WebshopGWOReplyBarionWidget',
            'paramChains' => array(
                'webshop/WebshopGWOReplyBarionWidget' => 'default'
            ),
            'controller' => 'framework/packages/WebshopPackage/controller/WebshopShipmentHandlingController',
            'action' => 'webshopShipmentHandlingWidgetAction',
            'permission' => 'viewGuestContent'
        ),
        */

        foreach ($activeGatewayProviders as $activeGatewayProvider) {
            $routeArray[] = array(
                'name' => 'payment_redirectFromGatewayProvider_'.$activeGatewayProvider['referenceName'],
                'paramChains' => array(
                    'payment/redirectFromGatewayProvider/'.$activeGatewayProvider['referenceName'].'/{shipmentCode}' => 'default'
                ),
                // 'controller' => 'framework/packages/PaymentPackage/gatewayProviders/'.$activeGatewayProvider['referenceName'].'/controller/PaymentRedirectController',
                // 'action' => 'redirectAction',
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopGWOReplyBarionWidget',
                )
            );
            $routeArray[] = array(
                'name' => 'api_service_'.$activeGatewayProvider['referenceName'].'_payment_ipn',
                'paramChains' => array(
                    'api/service/'.$activeGatewayProvider['referenceName'].'/payment/ipn/{shipmentCode}' => 'default'
                ),
                'controller' => 'framework/packages/PaymentPackage/gatewayProviders/'.$activeGatewayProvider['referenceName'].'/controller/PaymentApiServiceController',
                'action' => 'ipnAction',
                'permission' => 'viewGuestContent'
            );
        }
        // $routeArray[] = array(
        //     'name' => 'webshop_WebshopGWOReplyBarionWidget',
        //     'paramChains' => array(
        //         'webshop/WebshopGWOReplyBarionWidget' => 'default'
        //     ),
        //     'controller' => 'framework/packages/WebshopPackage/gatewayProviders/'.$activeGatewayProvider['referenceName'].'/controller/PaymentApiServiceController',
        //     'action' => 'ipnAction',
        //     'permission' => 'viewGuestContent'
        // );

        return $routeArray;
    }
}
