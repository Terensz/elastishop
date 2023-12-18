<?php
namespace framework\packages\DevPackage\routeMap;

class DevFormRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'dev_form',
                'paramChains' => array(
                    'dev/form' => 'default'
                ),
                'controller' => 'framework/packages/DevPackage/controller/DevFormController',
                'action' => 'devFormAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.list.backgrounds',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'widgetChanges' => array(
                    'mainContent' => 'DevPackage/view/widget/DevFormWidget'
                )
            ),
            array(
                'name' => 'dev_form_widget',
                'paramChains' => array(
                    'dev/form/widget' => 'default'
                ),
                'controller' => 'framework/packages/DevPackage/controller/DevFormWidgetController',
                'action' => 'devFormWidgetAction',
                'permission' => 'viewProjectAdminContent'
            )
        );
    }
}
