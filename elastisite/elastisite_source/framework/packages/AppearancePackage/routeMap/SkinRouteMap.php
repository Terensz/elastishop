<?php
namespace framework\packages\AppearancePackage\routeMap;

class SkinRouteMap
{
    public static function get()
    {
        return array(
            // array(
            //     'name' => 'admin_skins',
            //     'paramChains' => array(
            //         'admin/skins' => 'default'
            //     ),
            //     'controller' => 'framework/packages/AppearancePackage/controller/AppearanceController',
            //     'action' => 'adminSkinsAction',
            //     'permission' => 'viewProjectAdminContent',
            //     'title' => 'admin.appearance.skins',
            //     'structure' => 'FrameworkPackage/view/structure/admin',
            //     'backgroundEngine' => 'Simple',
            //     'backgroundTheme' => 'empty',
            //     'widgetChanges' => array(
            //         'mainContent' => 'AppearancePackage/view/widget/AdminSkinsWidget'
            //     ),
            //     'pageSwitchBehavior' => array(
            //         'ElastiSiteBannerWidget' => 'keep'
            //     )
            // ),
            // array(
            //     'name' => 'admin_skins_widget',
            //     'paramChains' => array(
            //         'admin/skins/widget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/AppearancePackage/controller/SkinWidgetController',
            //     'action' => 'adminSkinsWidgetAction',
            //     'permission' => 'viewProjectAdminContent'
            // )
        );
    }
}
