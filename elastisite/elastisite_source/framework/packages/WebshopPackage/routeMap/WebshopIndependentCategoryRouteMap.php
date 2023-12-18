<?php
namespace framework\packages\WebshopPackage\routeMap;

use framework\kernel\component\Kernel;

class WebshopIndependentCategoryRouteMap extends Kernel
{
    public static function get()
    {
        $productBrowserRoutes = array(
            array(
                'name' => 'independentCategory',
                'paramChains' => array(
                    'category/{categorySlug}' => 'en',
                    'kategoria/{categorySlug}' => 'hu'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopController',
                'action' => 'generalAction',
                'permission' => 'viewGuestContent',
                'title' => 'webshop',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'backgroundColor' => '35aed5',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/WebshopIndependentCategoryWidget'
                )
            ),
            array(
                'name' => 'widget_independentCategoryWidget',
                'paramChains' => array(
                    'widget/independentCategoryWidget' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopIndependentCategoryWidgetController',
                'action' => 'webshopIndependentCategoryWidgetAction',
                'permission' => 'viewGuestContent'
            )
        );

        return $productBrowserRoutes;
    }
}
