<?php
namespace projects\ASC\routeMap;

class CustomUserRegistrationRouteMap
{
    public static function get()
    {
        return array(
            // array(
            //     'name' => 'admin_login',
            //     'paramChains' => array(
            //         'admin_login_43Gh7v91M' => 'default'
            //     ),
            //     'controller' => 'framework/packages/UserPackage/controller/UserController',
            //     'action' => 'adminLoginAction',
            //     'permission' => 'viewGuestContent',
            //     'title' => 'ElastiSite',
            //     'structure' => 'FrameworkPackage/view/structure/2PanelNoMenu',
            //     // 'skinName' => 'ModernObsidian',
            //     'backgroundColor' => '3897c5',
            //     'widgetChanges' => array(
            //         'left1' => 'UserPackage/view/widget/Login2Widget',
            //         // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
            //     ),
            // ),
            array(
                'name' => 'widget_CustomUserRegistrationWidget',
                'paramChains' => array(
                    'widget/CustomUserRegistrationWidget' => 'default'
                ),
                'controller' => 'projects/ASC/controller/CustomUserRegistrationWidgetController',
                'action' => 'customUserRegistrationWidgetAction',
                'permission' => 'viewGuestContent'
            )
        );
    }
}
