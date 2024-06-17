<?php
namespace framework\packages\FrameworkPackage\routeMap;

class LocaleHandlerRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'localeHandler_switch',
                'paramChains' => array(
                    'localeHandler/switch/{locale}' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/LocaleHandlerWidgetController',
                'action' => 'localeHandlerSwitchAction',
                'permission' => 'viewGuestContent'
            )
        );
    }
}
