<?php
namespace framework\packages\WebshopPackage\routeMap;

class WebshopRouteMap
{
    public static function get()
    {
        return array(
            // array(
            //     'name' => 'webshop_inactive',
            //     'paramChains' => array(
            //         'webshop/inactive' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
            //     'action' => 'webshopInactiveAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'payment.result',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'WebshopPackage/view/widget/WebshopIsInactiveWidget',
            //         // 'left1' => 'WebshopPackage/view/widget/WebshopSideOfferWidget',
            //         // 'left1' => 'WebshopPackage/view/widget/WebshopCheckoutSideWidget',
            //         // 'left2' => 'UserPackage/view/widget/LoginNoRegLinkWidget'
            //         // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'webshop_isInactiveWidget',
            //     'paramChains' => array(
            //         'webshop/isInactiveWidget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopWidgetController',
            //     'action' => 'webshopIsInactiveWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),

            // array(
            //     'name' => 'webshop_orderSuccessful',
            //     'paramChains' => array(
            //         'webshop/orderSuccessful/{shipmentCode}' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
            //     'action' => 'webshopOrderSuccessfulAction',
            //     'permission' => 'viewGuestContent',
            //     'inMenu' => 'main',
            //     'title' => 'payment.result',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'widgetChanges' => array(
            //         'mainContent' => 'WebshopPackage/view/widget/WebshopOrderSuccessfulWidget',
            //         // 'left1' => 'WebshopPackage/view/widget/WebshopSideOfferWidget',
            //         // 'left1' => 'WebshopPackage/view/widget/WebshopCheckoutSideWidget',
            //         // 'left2' => 'UserPackage/view/widget/LoginNoRegLinkWidget'
            //         // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     )
            // ),
            array(
                'name' => 'webshop_myOrders',
                'paramChains' => array(
                    'webshop/myOrders' => 'en',
                    'webaruhaz/rendeleseim' => 'hu'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'webshop',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'ArticlePackage/view/widget/WebshopMyOrdersWidget'
                )
            ),
            array(
                'name' => 'webshop_myOrdersWidget',
                'paramChains' => array(
                    'webshop/myOrdersWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopWidgetController',
                'action' => 'webshopMyOrdersWidgetAction',
                'permission' => 'viewGuestContent'
            ),

            // array(
            //     'name' => 'webshop_sideCartWidget',
            //     'paramChains' => array(
            //         'webshop/sideCartWidget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopWidgetController',
            //     'action' => 'webshopSideCartWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),



            // array(
            //     'name' => 'webshop_categoryWidget',
            //     'paramChains' => array(
            //         'webshop/categoryWidget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopWidgetController',
            //     'action' => 'webshopCategoryWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),

            array(
                'name' => 'webshop_addAddress',
                'paramChains' => array(
                    'webshop/addAddress' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopAddAddressAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_changeAddress',
                'paramChains' => array(
                    'webshop/changeAddress' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopCheckoutWidgetController',
                'action' => 'webshopChangeAddressAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_sideOfferWidget',
                'paramChains' => array(
                    'webshop/sideOfferWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopWidgetController',
                'action' => 'webshopSideOfferWidgetAction',
                'permission' => 'viewGuestContent'
            )
        );
    }
}
