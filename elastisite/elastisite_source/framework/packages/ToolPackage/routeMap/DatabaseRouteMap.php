<?php
namespace framework\packages\ToolPackage\routeMap;

class DatabaseRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_database_info',
                'paramChains' => array(
                    'admin/database/info' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/DatabaseController',
                'action' => 'adminDatabaseInfoAction',
                'permission' => 'viewSystemAdminContent',
                'title' => 'database.info',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'ToolPackage/view/widget/AdminDatabaseInfoWidget'
                )
            ),
            array(
                'name' => 'admin_database_info_widget',
                'paramChains' => array(
                    'admin/database/info/widget' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/DatabaseWidgetController',
                'action' => 'adminDatabaseInfoWidgetAction',
                'permission' => 'viewSystemAdminContent'
            )
        );
    }
}
