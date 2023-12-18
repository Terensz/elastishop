<?php
namespace framework\packages\WebshopPackage\routeMap;

class WebshopPaymentRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'webshop_WebshopGWOReplyBarionWidget',
                'paramChains' => array(
                    'webshop/WebshopGWOReplyBarionWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopPaymentWidgetController',
                'action' => 'webshopGWOReplyBarionWidgetAction',
                'permission' => 'viewGuestContent'
            )
            // array(
            //     'name' => 'webshop_paymentTest',
            //     'paramChains' => array(
            //         'webshop/paymentTest' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
            //     'action' => 'generalAction',
            //     'permission' => 'viewSystemAdminContent',
            //     'title' => 'payment.test',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'WebshopPackage/view/widget/WebshopPaymentWidget'
            //     )
            // ),
            // array(
            //     'name' => 'webshop_paymentWidget',
            //     'paramChains' => array(
            //         'webshop/paymentWidget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopPaymentWidgetController',
            //     'action' => 'webshopPaymentWidgetAction',
            //     'permission' => 'viewSystemAdminContent'
            // ),

            // array(
            //     'name' => 'webshop_paymentSuccessful',
            //     'paramChains' => array(
            //         'webshop/paymentSuccessful/{paymentCode}' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
            //     'action' => 'webshopPaymentSuccessfulAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'payment.result',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'WebshopPackage/view/widget/WebshopPaymentResultWidget',
            //         // 'left1' => 'WebshopPackage/view/widget/WebshopSideOfferWidget',
            //         // 'left1' => 'WebshopPackage/view/widget/WebshopCheckoutSideWidget',
            //         // 'left2' => 'UserPackage/view/widget/LoginNoRegLinkWidget'
            //         // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'webshop_paymentFailed',
            //     'paramChains' => array(
            //         'webshop/paymentFailed/{paymentCode}' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
            //     'action' => 'webshopPaymentFailedAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'payment.failed',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'WebshopPackage/view/widget/WebshopPaymentResultWidget',
            //         // 'left1' => 'WebshopPackage/view/widget/WebshopSideOfferWidget',
            //         // 'left1' => 'WebshopPackage/view/widget/WebshopCheckoutSideWidget',
            //         // 'left2' => 'UserPackage/view/widget/LoginNoRegLinkWidget'
            //         // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
        );
    }
}
