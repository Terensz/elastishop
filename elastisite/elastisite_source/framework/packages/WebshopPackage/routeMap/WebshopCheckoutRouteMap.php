<?php
namespace framework\packages\WebshopPackage\routeMap;

class WebshopCheckoutRouteMap
{
    public static function get()
    {
        return array(
            // Checkout
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
                // 'name' => 'webshop_checkout_shipmentCode',
                // 'paramChains' => array(
                //     'webshop/checkout/{shipmentCode}' => 'default'
                // ),
                // 'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                // 'action' => 'generalAction',
                // 'permission' => 'viewGuestContent',
                // 'title' => 'checkout',
                // 'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'backgroundColor' => '35aed5',
                // 'widgetChanges' => array(
                //     'mainContent' => 'WebshopPackage/view/widget/WebshopCheckoutWidget',
                //     // 'left1' => 'WebshopPackage/view/widget/WebshopSideOfferWidget',
                //     // 'left1' => 'WebshopPackage/view/widget/WebshopCheckoutSideWidget',
                //     // 'left2' => 'UserPackage/view/widget/LoginNoRegLinkWidget'
                //     // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                // )
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
                'name' => 'webshop_checkout_selectCustomerType',
                'paramChains' => array(
                    'webshop/checkout/selectCustomerType' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutSelectCustomerTypeAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkout_addOrganization',
                'paramChains' => array(
                    'webshop/checkout/addOrganization' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutAddOrganizationAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkout_editOrganization',
                'paramChains' => array(
                    'webshop/checkout/editOrganization' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutEditOrganizationAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkout_selectOrganization',
                'paramChains' => array(
                    'webshop/checkout/selectOrganization' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutSelectOrganizationAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkout_addAddress',
                'paramChains' => array(
                    'webshop/checkout/addAddress' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutAddAddressAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkout_editAddress',
                'paramChains' => array(
                    'webshop/checkout/editAddress' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutEditAddressAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkout_selectAddress',
                'paramChains' => array(
                    'webshop/checkout/selectAddress' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutSelectAddressAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkout_acceptTermsAndConditions',
                'paramChains' => array(
                    'webshop/checkout/acceptTermsAndConditions' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutAcceptTermsAndConditionsAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkout_saveDeliveryInformation',
                'paramChains' => array(
                    'webshop/checkout/saveDeliveryInformation' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutSaveDeliveryInformationAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkout_saveNote',
                'paramChains' => array(
                    'webshop/checkout/saveNote' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopCheckoutSaveNoteAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_checkout_finishCheckout',
                'paramChains' => array(
                    'webshop/checkout/finishCheckout' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopFinishCheckoutWidgetController',
                'action' => 'webshopCheckoutFinishCheckoutAction',
                'permission' => 'viewGuestContent'
            ),
            // array(
            //     'name' => 'webshop_checkout_initCloseCart',
            //     'paramChains' => array(
            //         'webshop/checkout/initCloseCart' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
            //     'action' => 'webshopCheckoutInitCloseCartAction',
            //     'permission' => 'viewGuestContent'
            // ),
            // array(
            //     'name' => 'webshop_checkout_closeCart',
            //     'paramChains' => array(
            //         'webshop/checkout/closeCart' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
            //     'action' => 'webshopCheckoutCloseCartAction',
            //     'permission' => 'viewGuestContent'
            // ),

            // Finalize order
            // array(
            //     'name' => 'webshop_WebshopFinalizeOrderWidget',
            //     'paramChains' => array(
            //         'webshop/WebshopFinalizeOrderWidget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
            //     'action' => 'webshopFinalizeOrderWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),

            // Payment result
            // array(
            //     'name' => 'webshop_WebshopPaymentResultWidget',
            //     'paramChains' => array(
            //         'webshop/WebshopPaymentResultWidget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
            //     'action' => 'webshopPaymentResultWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),

            // Order successful
            // array(
            //     'name' => 'webshop_WebshopOrderSuccessfulWidget',
            //     'paramChains' => array(
            //         'webshop/WebshopOrderSuccessfulWidget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
            //     'action' => 'webshopOrderSuccessfulWidgetAction',
            //     'permission' => 'viewGuestContent'
            // )
        );
    }
}
