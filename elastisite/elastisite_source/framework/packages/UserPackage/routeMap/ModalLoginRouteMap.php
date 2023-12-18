<?php
namespace framework\packages\UserPackage\routeMap;

class ModalLoginRouteMap
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
                'name' => 'login_ModalLoginWidget',
                'paramChains' => array(
                    'login/ModalLoginWidget' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/ModalLoginWidgetController',
                'action' => 'modalLoginWidgetAction',
                'permission' => 'viewGuestContent'
            ),
        );
    }
}
