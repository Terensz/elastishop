<?php
namespace framework\packages\SiteBuilderPackage\routeMap;

class MenuRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'widget_MenuWidget',
                'paramChains' => array(
                    'widget/MenuWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/MenuWidgetController',
                'action' => 'menuWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'admin_AdminMenuWidget',
                'paramChains' => array(
                    'admin/AdminMenuWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/MenuWidgetController',
                'action' => 'adminMenuWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_AdminSideMenuWidget',
                'paramChains' => array(
                    'admin/AdminSideMenuWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/MenuWidgetController',
                'action' => 'adminSideMenuWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_userAreaMenu',
                'paramChains' => array(
                    'admin/userAreaMenu' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/SiteBuilderController',
                'action' => 'basicAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.userarea.menu',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'skinName' => 'ElastiShop',
                'widgetChanges' => array(
                    'mainContent' => 'SiteBuilderPackage/view/widget/AdminUserAreaMenuWidget',
                    'left1' => 'SiteBuilderPackage/view/widget/AdminSiteBuilderSideMenuWidget'
                )
            ),
            array(
                'name' => 'admin_AdminUserAreaMenuWidget',
                'paramChains' => array(
                    'admin/AdminUserAreaMenuWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/MenuWidgetController',
                'action' => 'adminUserAreaMenuWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_AdminUserAreaMenuWidget_flexibleContent',
                'paramChains' => array(
                    'admin/AdminUserAreaMenuWidget_flexibleContent' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/MenuWidgetController',
                'action' => 'adminUserAreaMenuWidgetFlexibleContentAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_AdminUserAreaMenuWidget_addToMenu',
                'paramChains' => array(
                    'admin/AdminUserAreaMenuWidget_addToMenu' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/MenuWidgetController',
                'action' => 'adminUserAreaMenuWidgetAddToMenuAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_AdminUserAreaMenuWidget_removeFromMenu',
                'paramChains' => array(
                    'admin/AdminUserAreaMenuWidget_removeFromMenu' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/MenuWidgetController',
                'action' => 'adminUserAreaMenuWidgetRemoveFromMenuAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_AdminUserAreaMenuWidget_saveTitle',
                'paramChains' => array(
                    'admin/AdminUserAreaMenuWidget_saveTitle' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/MenuWidgetController',
                'action' => 'adminUserAreaMenuWidgetSaveTitleAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_AdminUserAreaMenuWidget_sort',
                'paramChains' => array(
                    'admin/AdminUserAreaMenuWidget_sort' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/MenuWidgetController',
                'action' => 'adminUserAreaMenuWidgetSortAction',
                'permission' => 'viewProjectAdminContent'
            )
        );
    }
}
