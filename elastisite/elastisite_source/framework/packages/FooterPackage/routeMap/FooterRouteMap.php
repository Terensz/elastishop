<?php
namespace framework\packages\FooterPackage\routeMap;

class FooterRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'widget_FooterWidget',
                'paramChains' => array(
                    'widget/FooterWidget' => 'default'
                ),
                'controller' => 'framework/packages/FooterPackage/controller/FooterWidgetController',
                'action' => 'footerWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'admin_footer',
                'paramChains' => array(
                    'admin/footer' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.footer',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'AppearancePackage/view/widget/AdminFooterWidget'
                )
            ),
            array(
                'name' => 'admin_AdminFooterWidget',
                'paramChains' => array(
                    'admin/AdminFooterWidget' => 'default'
                ),
                'controller' => 'framework/packages/FooterPackage/controller/FooterWidgetController',
                'action' => 'adminFooterWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_AdminFooterWidget_list',
                'paramChains' => array(
                    'admin/AdminFooterWidget/list' => 'default'
                ),
                'controller' => 'framework/packages/FooterPackage/controller/FooterWidgetController',
                'action' => 'adminFooterWidgetListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_AdminFooterWidget_edit',
                'paramChains' => array(
                    'admin/AdminFooterWidget/edit' => 'default'
                ),
                'controller' => 'framework/packages/FooterPackage/controller/FooterWidgetController',
                'action' => 'adminFooterWidgetEditAction',
                'permission' => 'viewProjectAdminContent'
            )
        );
    }
}
