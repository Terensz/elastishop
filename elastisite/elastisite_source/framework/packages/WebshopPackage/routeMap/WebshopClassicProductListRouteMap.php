<?php
namespace framework\packages\WebshopPackage\routeMap;

use framework\kernel\component\Kernel;

class WebshopClassicProductListRouteMap extends Kernel
{
    public static function get()
    {
        $productBrowserRoutes = array(
            // array(
            //     'name' => 'webshop_productList',
            //     'paramChains' => array(
            //         'webshop/productList' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopClassicProductListWidgetController',
            //     'action' => 'webshopProductListAction',
            //     'permission' => 'viewGuestContent'
            // ),
            array(
                'name' => 'webshop_showProduct',
                'paramChains' => array(
                    'webshop/show_product/{productSlug}' => 'en',
                    'webaruhaz/termek_info/{productSlug}' => 'hu'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'webshop',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopProductDetailsWidget'
                )
            ),
            array(
                'name' => 'webshop_productDetailsModal',
                'paramChains' => array(
                    'webshop/productDetailsModal' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopClassicProductListWidgetController',
                'action' => 'webshopProductDetailsModalAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_WebshopProductDetailsWidget',
                'paramChains' => array(
                    'webshop/WebshopProductDetailsWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopClassicProductListWidgetController',
                'action' => 'webshopProductDetailsWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            // array(
            //     'name' => 'webshop_productWidget',
            //     'paramChains' => array(
            //         'webshop/productWidget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopClassicProductListWidgetController',
            //     'action' => 'webshopProductWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),

            /**
             * ProductList route maps
            */
            array(
                'name' => 'webshop_productListWidget',
                'paramChains' => array(
                    'webshop/productListWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopClassicProductListWidgetController',
                'action' => 'webshopProductListWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_productList_noFilter',
                'paramChains' => array(
                    'webshop' => 'en',
                    'webaruhaz' => 'hu'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'webshop',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopProductListWidget'
                )
            ),
            array(
                'name' => 'webshop_productList_noFilter_page',
                'paramChains' => array(
                    'webshop/page/{page}' => 'en',
                    'webaruhaz/lap/{page}' => 'hu'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'webshop',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopProductListWidget'
                )
            ),

            /**
             * I disabled these, because it will be a special category instead.
            */
            // array(
            //     'name' => 'webshop_productList_all',
            //     'paramChains' => array(
            //         'webshop/all_products' => 'en',
            //         'webaruhaz/minden_termek' => 'hu'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
            //     'action' => 'generalAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'webshop',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'backgroundColor' => '35aed5',
            //     'widgetChanges' => array(
            //         'mainContent' => 'WebshopPackage/view/widget/WebshopProductListWidget'
            //     )
            // ),
            // array(
            //     'name' => 'webshop_productList_all_page',
            //     'paramChains' => array(
            //         'webshop/all_products/page/{page}' => 'en',
            //         'webaruhaz/minden_termek/lap/{page}' => 'hu'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
            //     'action' => 'generalAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'webshop',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'backgroundColor' => '35aed5',
            //     'widgetChanges' => array(
            //         'mainContent' => 'WebshopPackage/view/widget/WebshopProductListWidget'
            //     )
            // ),

            array(
                'name' => 'webshop_productList_anomalousProducts',
                'paramChains' => array(
                    'webshop/anomalous_products' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'webshop',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopProductListWidget'
                )
            ),

            /**
             * I disabled these, because it will be a special category instead.
            */
            // array(
            //     'name' => 'webshop_productList_discountedProducts',
            //     'paramChains' => array(
            //         'webshop/discounted_products' => 'en',
            //         'webaruhaz/akcios_termekek' => 'hu'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
            //     'action' => 'generalAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'webshop',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'backgroundColor' => '35aed5',
            //     'widgetChanges' => array(
            //         'mainContent' => 'WebshopPackage/view/widget/WebshopProductListWidget',
            //         'left1' => 'WebshopPackage/view/widget/WebshopSideCartWidget'
            //     )
            // ),
            // array(
            //     'name' => 'webshop_productList_discountedProducts_page',
            //     'paramChains' => array(
            //         'webshop/discounted_products/page/{page}' => 'en',
            //         'webaruhaz/akcios_termekek/lap/{page}' => 'hu'
            //     ),
            //     'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
            //     'action' => 'generalAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'webshop',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     'backgroundColor' => '35aed5',
            //     'widgetChanges' => array(
            //         'mainContent' => 'WebshopPackage/view/widget/WebshopProductListWidget',
            //         'left1' => 'WebshopPackage/view/widget/WebshopSideCartWidget'
            //     )
            // ),

            array(
                'name' => 'webshop_productList_category',
                'paramChains' => array(
                    'webshop/category/{category}' => 'en',
                    'webaruhaz/kategoria/{category}' => 'hu'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'webshop',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopProductListWidget'
                )
            ),
            array(
                'name' => 'webshop_productList_category_page',
                'paramChains' => array(
                    'webshop/category/{category}/page/{page}' => 'en',
                    'webaruhaz/kategoria/{category}/lap/{page}' => 'hu'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'webshop',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopProductListWidget'
                )
            ),
            array(
                'name' => 'webshop_productList_searchTerm',
                'paramChains' => array(
                    'webshop/search/{searchTerm}' => 'en',
                    'webaruhaz/kereses/{searchTerm}' => 'hu'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'webshop',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopProductListWidget'
                )
            ),
            array(
                'name' => 'webshop_productList_searchTerm_page',
                'paramChains' => array(
                    'webshop/search/{searchTerm}/page/{page}' => 'en',
                    'webaruhaz/kereses/{searchTerm}/lap/{page}' => 'hu'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'webshop',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopProductListWidget'
                )
            ),
            array(
                'name' => 'webshop_productList_categoryAndSearchTerm',
                'paramChains' => array(
                    'webshop/category/{category}/search/{searchTerm}' => 'en',
                    'webaruhaz/kategoria/{category}/kereses/{searchTerm}' => 'hu'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'webshop',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopProductListWidget'
                )
            ),
            array(
                'name' => 'webshop_productList_categoryAndSearchTerm_page',
                'paramChains' => array(
                    'webshop/category/{category}/search/{searchTerm}/page/{page}' => 'en',
                    'webaruhaz/kategoria/{category}/kereses/{searchTerm}/lap/{page}' => 'hu'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'webshop',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopProductListWidget'
                )
            )
            /**
             * End of ProductList route maps
            */
        );

        return $productBrowserRoutes;
    }
}
