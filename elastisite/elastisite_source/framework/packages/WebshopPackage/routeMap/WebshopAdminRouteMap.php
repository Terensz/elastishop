<?php
namespace framework\packages\WebshopPackage\routeMap;

class WebshopAdminRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_webshop_reset',
                'paramChains' => array(
                    'admin/webshop/reset' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'adminWebshopResetAction',
                'permission' => 'viewSystemAdminContent',
                'title' => 'reset.webshop',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'ToolPackage/view/widget/AdminWebshopResetWidget'
                )
            ),
            array(
                'name' => 'admin_webshop_resetWidget',
                'paramChains' => array(
                    'admin/webshop/resetWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
                'action' => 'adminWebshopResetWidgetAction',
                'permission' => 'viewSystemAdminContent'
            ),
            array(
                'name' => 'admin_webshop_config',
                'paramChains' => array(
                    'admin/webshop/config' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'adminWebshopConfigAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.webshop.config',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'AppearancePackage/view/widget/AdminWebshopConfigWidget'
                )
            ),
            array(
                'name' => 'admin_webshop_config_widget',
                'paramChains' => array(
                    'admin/webshop/config/widget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
                'action' => 'adminWebshopConfigWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_config_list',
                'paramChains' => array(
                    'admin/webshop/config/list' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
                'action' => 'adminWebshopConfigListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_config_edit',
                'paramChains' => array(
                    'admin/webshop/config/edit' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
                'action' => 'adminWebshopConfigEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_productCategories',
                'paramChains' => array(
                    'admin/webshop/productCategories' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'adminWebshopProductCategoriesAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.webshop.product.categories',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'AppearancePackage/view/widget/AdminWebshopProductCategoriesWidget'
                )
            ),
            array(
                'name' => 'admin_webshop_productCategories_widget',
                'paramChains' => array(
                    'admin/webshop/productCategories/widget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductCategoryWidgetController',
                'action' => 'adminWebshopProductCategoriesWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_productCategory_search',
                'paramChains' => array(
                    'admin/webshop/productCategory/search' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductCategoryWidgetController',
                'action' => 'adminWebshopProductCategorySearchAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_productCategory_new',
                'paramChains' => array(
                    'admin/webshop/productCategory/new' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductCategoryWidgetController',
                'action' => 'adminWebshopProductCategoryNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_productCategory_edit',
                'paramChains' => array(
                    'admin/webshop/productCategory/edit' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductCategoryWidgetController',
                'action' => 'adminWebshopProductCategoryEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_productCategory_delete',
                'paramChains' => array(
                    'admin/webshop/productCategory/delete' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductCategoryWidgetController',
                'action' => 'adminWebshopProductCategoryDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_products',
                'paramChains' => array(
                    'admin/webshop/products' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'adminWebshopProductsAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.webshop.products',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'AppearancePackage/view/widget/AdminWebshopProductsWidget'
                )
            ),
            array(
                'name' => 'admin_webshop_products_widget',
                'paramChains' => array(
                    'admin/webshop/products/widget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductWidgetController',
                'action' => 'adminWebshopProductsWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_product_search',
                'paramChains' => array(
                    'admin/webshop/product/search' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductWidgetController',
                'action' => 'adminProductSearchAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_product_new',
                'paramChains' => array(
                    'admin/webshop/product/new' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductWidgetController',
                'action' => 'adminWebshopProductNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_product_edit',
                'paramChains' => array(
                    'admin/webshop/product/edit' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductWidgetController',
                'action' => 'adminWebshopProductEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_product_delete',
                'paramChains' => array(
                    'admin/webshop/product/delete' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductWidgetController',
                'action' => 'adminWebshopProductDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_productPrice_list',
                'paramChains' => array(
                    'admin/webshop/productPrice/list' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductPriceWidgetController',
                'action' => 'adminWebshopProductPriceListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_productPrice_new',
                'paramChains' => array(
                    'admin/webshop/productPrice/new' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductPriceWidgetController',
                'action' => 'adminWebshopProductPriceNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_productPrice_activate',
                'paramChains' => array(
                    'admin/webshop/productPrice/activate' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductPriceWidgetController',
                'action' => 'adminWebshopProductPriceActivateAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_productPrice_delete',
                'paramChains' => array(
                    'admin/webshop/productPrice/delete' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductPriceWidgetController',
                'action' => 'adminWebshopProductPriceDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),

            array(
                'name' => 'admin_webshop_productImage_list',
                'paramChains' => array(
                    'admin/webshop/productImage/list' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductImageWidgetController',
                'action' => 'adminProductImageListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_productImage_new',
                'paramChains' => array(
                    'admin/webshop/productImage/new' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductImageWidgetController',
                'action' => 'adminProductImageNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_productImage_setAsMain',
                'paramChains' => array(
                    'admin/webshop/productImage/setAsMain' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductImageWidgetController',
                'action' => 'adminProductImageSetAsMainAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_productImage_delete',
                'paramChains' => array(
                    'admin/webshop/productImage/delete' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductImageWidgetController',
                'action' => 'adminProductImageDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_productImage_upload',
                'paramChains' => array(
                    'admin/webshop/productImage/upload/{productId}' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/ProductImageWidgetController',
                'action' => 'adminProductImageUploadAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_cartTriggers',
                'paramChains' => array(
                    'admin/webshop/cartTriggers' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.webshop.cart.triggers',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/AdminWebshopCartTriggersWidget'
                )
            ),
            array(
                'name' => 'admin_AdminWebshopCartTriggersWidget',
                'paramChains' => array(
                    'admin/AdminWebshopCartTriggersWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/CartTriggerWidgetController',
                'action' => 'adminWebshopCartTriggersWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_cartTrigger_new',
                'paramChains' => array(
                    'admin/webshop/cartTrigger/new' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/CartTriggerWidgetController',
                'action' => 'adminWebshopCartTriggerNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_cartTrigger_edit',
                'paramChains' => array(
                    'admin/webshop/cartTrigger/edit' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/CartTriggerWidgetController',
                'action' => 'adminWebshopCartTriggerEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_cartTrigger_delete',
                'paramChains' => array(
                    'admin/webshop/cartTrigger/delete' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/CartTriggerWidgetController',
                'action' => 'adminWebshopCartTriggerDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),
            // array(
            //     'name' => 'admin_webshop_storages',
            //     'paramChains' => array(
            //         'admin/webshop/storages' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
            //     'action' => 'adminWebshopStoragesAction',
            //     'permission' => 'viewProjectAdminContent',
            //     'title' => 'admin.webshop.storages',
            //     'structure' => 'FrameworkPackage/view/structure/admin',
            //     'backgroundEngine' => 'Simple',
            //     'backgroundTheme' => 'empty',
            //     'widgetChanges' => array(
            //         'mainContent' => 'AppearancePackage/view/widget/AdminWebshopStoragesWidget'
            //     )
            // ),
            // array(
            //     'name' => 'admin_webshop_storages_widget',
            //     'paramChains' => array(
            //         'admin/webshop/storages/widget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopWidgetController',
            //     'action' => 'adminWebshopStoragesWidgetAction',
            //     'permission' => 'viewProjectAdminContent'
            // ),
            // array(
            //     'name' => 'admin_webshop_inwardProcessing',
            //     'paramChains' => array(
            //         'admin/webshop/inward_processing' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
            //     'action' => 'adminWebshopInwardProcessingAction',
            //     'permission' => 'viewProjectAdminContent',
            //     'title' => 'admin.webshop.inward.processing',
            //     'structure' => 'FrameworkPackage/view/structure/admin',
            //     'backgroundEngine' => 'Simple',
            //     'backgroundTheme' => 'empty',
            //     'widgetChanges' => array(
            //         'mainContent' => 'AppearancePackage/view/widget/AdminWebshopInwardProcessingWidget'
            //     )
            // ),
            // array(
            //     'name' => 'admin_webshop_inwardProcessing_widget',
            //     'paramChains' => array(
            //         'admin/webshop/inward_processing/widget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopWidgetController',
            //     'action' => 'adminWebshopInwardProcessingWidgetAction',
            //     'permission' => 'viewProjectAdminContent'
            // ),
            // array(
            //     'name' => 'admin_webshop_stock',
            //     'paramChains' => array(
            //         'admin/webshop/stock' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
            //     'action' => 'adminWebshopStockAction',
            //     'permission' => 'viewProjectAdminContent',
            //     'title' => 'admin.webshop.stock',
            //     'structure' => 'FrameworkPackage/view/structure/admin',
            //     'backgroundEngine' => 'Simple',
            //     'backgroundTheme' => 'empty',
            //     'widgetChanges' => array(
            //         'mainContent' => 'AppearancePackage/view/widget/AdminWebshopStockWidget'
            //     )
            // ),
            // array(
            //     'name' => 'admin_webshop_stock_widget',
            //     'paramChains' => array(
            //         'admin/webshop/stock/widget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopWidgetController',
            //     'action' => 'adminWebshopStockWidgetAction',
            //     'permission' => 'viewProjectAdminContent'
            // ),
            // array(
            //     'name' => 'admin_webshop_discounts',
            //     'paramChains' => array(
            //         'admin/webshop/discounts' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
            //     'action' => 'adminWebshopDiscountsAction',
            //     'permission' => 'viewProjectAdminContent',
            //     'title' => 'admin.webshop.discounts',
            //     'structure' => 'FrameworkPackage/view/structure/admin',
            //     'backgroundEngine' => 'Simple',
            //     'backgroundTheme' => 'empty',
            //     'widgetChanges' => array(
            //         'mainContent' => 'WebshopPackage/view/widget/AdminWebshopDiscountsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'admin_webshop_discounts_widget',
            //     'paramChains' => array(
            //         'admin/webshop/discounts/widget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopWidgetController',
            //     'action' => 'adminWebshopDiscountsWidgetAction',
            //     'permission' => 'viewProjectAdminContent'
            // ),


            array(
                'name' => 'admin_webshop_shipments',
                'paramChains' => array(
                    'admin/webshop/shipments' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'adminWebshopShipmentsAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.webshop.orders',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/AdminWebshopShipmentsWidget'
                )
            ),
            array(
                'name' => 'admin_webshop_shipmentsWidget',
                'paramChains' => array(
                    'admin/webshop/shipments/widget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
                'action' => 'adminWebshopShipmentsWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_runningOrders',
                'paramChains' => array(
                    'admin/webshop/runningOrders' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.webshop.running.orders',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/AdminWebshopRunningOrdersWidget'
                )
            ),
            array(
                'name' => 'admin_webshop_runningOrdersWidget',
                'paramChains' => array(
                    'admin/webshop/runningOrders/widget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
                'action' => 'adminWebshopRunningOrdersWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            // array(
            //     'name' => 'admin_webshop_runningOrders_getNewListElementIds',
            //     'paramChains' => array(
            //         'admin/webshop/runningOrders/getNewListElementIds' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
            //     'action' => 'adminWebshopRunningOrdersGetNewListElementIdsAction',
            //     'permission' => 'viewProjectAdminContent'
            // ),
            array(
                'name' => 'admin_webshop_runningOrders_getListView',
                'paramChains' => array(
                    'admin/webshop/runningOrders/getListView' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
                'action' => 'adminWebshopRunningOrdersGetListViewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            // array(
            //     'name' => 'admin_webshop_getLastShipmentId',
            //     'paramChains' => array(
            //         'admin/webshop/getLastShipmentId' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
            //     'action' => 'adminWebshopGetLastShipmentIdAction',
            //     'permission' => 'viewProjectAdminContent'
            // ),
            array(
                'name' => 'admin_webshop_getOrderedShipmentIds',
                'paramChains' => array(
                    'admin/webshop/getOrderedShipmentIds' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
                'action' => 'adminWebshopGetOrderedShipmentIdsAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_statistics',
                'paramChains' => array(
                    'admin/webshop/statistics' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.webshop.statistics',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/AdminWebshopStatisticsWidget'
                )
            ),
            array(
                'name' => 'admin_webshop_statisticsWidget',
                'paramChains' => array(
                    'admin/webshop/statisticsWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
                'action' => 'adminWebshopStatisticsWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_statistics_earlierMonth',
                'paramChains' => array(
                    'admin/webshop/statistics/earlierMonth' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
                'action' => 'adminWebshopStatisticsEarlierMonthAction',
                'permission' => 'viewProjectAdminContent'
            ),
            // array(
            //     'name' => 'admin_webshop_shipment_search',
            //     'paramChains' => array(
            //         'admin/webshop/shipment/search' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
            //     'action' => 'adminWebshopShipmentSearchAction',
            //     'permission' => 'viewProjectAdminContent'
            // ),
            array(
                'name' => 'admin_webshop_shipment_edit',
                'paramChains' => array(
                    'admin/webshop/shipment/edit' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
                'action' => 'adminWebshopShipmentEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_webshop_shipment_delete',
                'paramChains' => array(
                    'admin/webshop/shipment/delete' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAdminWidgetController',
                'action' => 'adminWebshopShipmentDeleteAction',
                'permission' => 'viewProjectAdminContent'
            )
        );
    }
}
