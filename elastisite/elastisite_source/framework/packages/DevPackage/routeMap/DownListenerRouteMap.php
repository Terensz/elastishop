<?php
namespace framework\packages\DevPackage\routeMap;

class DownListenerRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'down_listener',
                'paramChains' => array(
                    'admin/downListener' => 'default'
                ),
                'controller' => 'framework/packages/DevPackage/controller/DownListenerController',
                'action' => 'downListenerAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.down.listener',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'DevPackage/view/widget/DownListenerWidget'
                )
            ),
            array(
                'name' => 'down_listener_widget',
                'paramChains' => array(
                    'admin/downListener/widget' => 'default'
                ),
                'controller' => 'framework/packages/DevPackage/controller/DownListenerWidgetController',
                'action' => 'downListenerWidgetAction',
                'permission' => 'viewProjectAdminContent'
            )
        );
    }
}