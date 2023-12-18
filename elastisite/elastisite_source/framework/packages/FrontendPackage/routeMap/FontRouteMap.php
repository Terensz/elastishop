<?php
namespace framework\packages\FrontendPackage\routeMap;

class FontRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'font_loader',
                'paramChains' => array(
                    'font/loader.css' => 'default'
                ),
                'controller' => 'framework/packages/FrontendPackage/controller/FontController',
                'action' => 'fontLoaderAction',
                'permission' => 'viewGuestContent'
            )
        );
    }
}
