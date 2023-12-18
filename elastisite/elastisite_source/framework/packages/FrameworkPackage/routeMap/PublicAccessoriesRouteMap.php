<?php
namespace framework\packages\FrameworkPackage\routeMap;

class PublicAccessoriesRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'widget_ElastiSiteFooterWidget',
                'paramChains' => array(
                    'widget/ElastiSiteFooterWidget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/PublicAccessoriesWidgetController',
                'action' => 'elastiSiteFooterWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_ElastiSiteBannerWidget',
                'paramChains' => array(
                    'widget/ElastiSiteBannerWidget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/PublicAccessoriesWidgetController',
                'action' => 'elastiSiteBannerWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_ElastiShopBannerWidget',
                'paramChains' => array(
                    'widget/ElastiShopBannerWidget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/PublicAccessoriesWidgetController',
                'action' => 'elastiShopBannerWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_ElastiShopFooterWidget',
                'paramChains' => array(
                    'widget/ElastiShopFooterWidget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/PublicAccessoriesWidgetController',
                'action' => 'elastiShopFooterWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'elastisite_image',
                'paramChains' => array(
                    'elastisite/image/{type}/{fileName}' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/PublicAccessoryController',
                'action' => 'elastiSiteImageAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'logo_image',
                'paramChains' => array(
                    'logo/{fileName}' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/PublicAccessoryController',
                'action' => 'logoImageAction',
                'permission' => 'viewGuestContent'
            ),
        );
    }
}
