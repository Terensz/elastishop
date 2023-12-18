<?php
namespace framework\packages\WebshopPackage\routeMap;

class WebshopCheckoutRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'webshop_checkout',
                'paramChains' => array(
                    'webshop/checkout' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'checkout',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopCheckoutWidget',
                    // 'left1' => 'WebshopPackage/view/widget/WebshopSideOfferWidget',
                    // 'left1' => 'WebshopPackage/view/widget/WebshopCheckoutSideWidget',
                    // 'left2' => 'UserPackage/view/widget/LoginNoRegLinkWidget'
                    // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            array(
                'name' => 'webshop_WebshopCheckoutWidget',
                'paramChains' => array(
                    'webshop/WebshopCheckoutWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkoutFlexibleContent',
                'paramChains' => array(
                    'webshop/checkoutFlexibleContent' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutFlexibleContentAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkoutSideWidget',
                'paramChains' => array(
                    'webshop/checkoutSideWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutSideWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkout_addToCart',
                'paramChains' => array(
                    'webshop/checkout/addToCart' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutAddToCartAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkout_removeFromCart',
                'paramChains' => array(
                    'webshop/checkout/removeFromCart' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutRemoveFromCartAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_registerAndCheckout',
                'paramChains' => array(
                    'webshop/registerAndCheckout' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'checkout',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopRegisterAndCheckoutWidget',
                    'leftTopContent' => 'WebshopPackage/view/widget/WebshopSideCartWidget'
                )
            ),
            array(
                'name' => 'webshop_registerAndCheckoutWidget',
                'paramChains' => array(
                    'webshop/registerAndCheckoutWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopRegisterAndCheckoutWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkout_registration',
                'paramChains' => array(
                    'webshop/checkout/registration' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopRegistrationWidgetController',
                'action' => 'webshopCheckoutRegistrationAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_cancelOrder',
                'paramChains' => array(
                    'webshop/cancelOrder/{orderCode}' => 'default'
                    // 'webaruhaz/rendeleseim' => 'hu'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'webshop',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'ArticlePackage/view/widget/WebshopCancelOrderWidget',
                    'left1' => 'WebshopPackage/view/widget/WebshopSideCartWidget',
                    // 'left3' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),
            array(
                'name' => 'webshop_cancelOrderWidget',
                'paramChains' => array(
                    'webshop/cancelOrderWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCancelOrderWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_finalizeOrderWidget',
                'paramChains' => array(
                    'webshop/finalizeOrderWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopFinalizeOrderWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_paymentResultWidget',
                'paramChains' => array(
                    'webshop/paymentResultWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopPaymentResultWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_orderSuccessfulWidget',
                'paramChains' => array(
                    'webshop/orderSuccessfulWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopOrderSuccessfulWidgetAction',
                'permission' => 'viewGuestContent'
            )
        );
    }
}
