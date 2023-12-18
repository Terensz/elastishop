<?php
namespace framework\packages\SiteBuilderPackage\routeMap;

class SiteBuilderRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'widget_CreateNewSiteWidget',
                'paramChains' => array(
                    'widget/CreateNewSiteWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/SiteBuilderWidgetController',
                'action' => 'createNewSiteWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),

            array(
                'name' => 'admin_AdminSiteBuilderSideMenuWidget',
                'paramChains' => array(
                    'admin/AdminSiteBuilderSideMenuWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/SiteBuilderWidgetController',
                'action' => 'adminSiteBuilderSideMenuWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),

            array(
                'name' => 'admin_builtPages',
                'paramChains' => array(
                    'admin/builtPages' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/SiteBuilderController',
                'action' => 'basicAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.built.pages',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'structure' => 'basic2Panel',
                'skinName' => 'ElastiShop',
                'widgetChanges' => array(
                    'mainContent' => 'SiteBuilderPackage/view/widget/AdminBuiltPagesWidget',
                    'left1' => 'SiteBuilderPackage/view/widget/AdminSiteBuilderSideMenuWidget'
                )
            ),
            array(
                'name' => 'admin_builtPagesWidget',
                'paramChains' => array(
                    'admin/builtPagesWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/SiteBuilderWidgetController',
                'action' => 'adminBuiltPagesWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_builtSite_new',
                'paramChains' => array(
                    'admin/builtSite/new' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/SiteBuilderWidgetController',
                'action' => 'adminBuiltPageNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_builtSite_edit',
                'paramChains' => array(
                    'admin/builtSite/edit' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/SiteBuilderWidgetController',
                'action' => 'adminBuiltPageEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_builtSite_delete',
                'paramChains' => array(
                    'admin/builtSite/delete' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/SiteBuilderWidgetController',
                'action' => 'adminBuiltPageDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),
        );
    }
}
