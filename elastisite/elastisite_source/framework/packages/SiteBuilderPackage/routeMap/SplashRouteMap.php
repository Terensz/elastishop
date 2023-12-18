<?php
namespace framework\packages\SiteBuilderPackage\routeMap;

class SplashRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'widget_SplashWidget',
                'paramChains' => array(
                    'widget/SplashWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/SplashWidgetController',
                'action' => 'splashWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_WrappedSplashWidget',
                'paramChains' => array(
                    'widget/WrappedSplashWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/SplashWidgetController',
                'action' => 'wrappedSplashWidgetAction',
                'permission' => 'viewGuestContent'
            ),
        );
    }
}
