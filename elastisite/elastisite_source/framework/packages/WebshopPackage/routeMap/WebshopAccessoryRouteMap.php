<?php
namespace framework\packages\WebshopPackage\routeMap;

class WebshopAccessoryRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'webshop_image_thumbnail',
                'paramChains' => array(
                    'webshop/image/thumbnail/{slug}' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAccessoryController',
                'action' => 'webshopImageThumbnailAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'webshop_image_big',
                'paramChains' => array(
                    'webshop/image/big/{slug}' => 'default'
                ),
                'controller' => 'framework/packages/WebshopPackage/controller/WebshopAccessoryController',
                'action' => 'webshopImageBigAction',
                'permission' => 'viewGuestContent'
            ),
        );
    }
}
