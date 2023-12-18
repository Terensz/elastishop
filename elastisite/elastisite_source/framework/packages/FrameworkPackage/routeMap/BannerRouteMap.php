<?php
namespace framework\packages\FrameworkPackage\routeMap;

class BannerRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'widget_BannerWidget',
                'paramChains' => array(
                    'widget/BannerWidget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BannerWidgetController',
                'action' => 'bannerWidgetAction',
                'permission' => 'viewGuestContent'
            )
        );
    }
}
