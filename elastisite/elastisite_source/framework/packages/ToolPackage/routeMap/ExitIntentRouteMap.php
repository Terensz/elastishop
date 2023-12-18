<?php
namespace framework\packages\ToolPackage\routeMap;

class ExitIntentRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'ajax_exitIntent',
                'paramChains' => array(
                    'ajax/exitIntent' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/ExitIntentWidgetController',
                'action' => 'exitIntentAction',
                'permission' => 'viewGuestContent'
            )
        );
    }
}
