<?php
namespace framework\packages\DevPackage\routeMap;

class DevRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'dev',
                'paramChains' => array(
                    'dev' => 'default'
                ),
                'controller' => 'framework/packages/DevPackage/controller/DevController',
                'action' => 'devAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.uploads.images',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'DevPackage/view/widget/DevWidget'
                )
            ),
            array(
                'name' => 'dev_widget',
                'paramChains' => array(
                    'dev/widget' => 'default'
                ),
                'controller' => 'framework/packages/DevPackage/controller/DevWidgetController',
                'action' => 'devWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'dev_inputTest',
                'paramChains' => array(
                    'dev/inputTest' => 'default'
                ),
                'controller' => 'framework/packages/DevPackage/controller/SimpleDevController',
                'action' => 'inputTestAction',
                'permission' => 'viewGuestContent',
                'structure' => 'devInputTest'
            ),
            array(
                'name' => 'dev_noscriptTest',
                'paramChains' => array(
                    'dev/noscriptTest' => 'default'
                ),
                'controller' => 'framework/packages/DevPackage/controller/SimpleDevController',
                'action' => 'noscriptTestAction',
                'permission' => 'viewGuestContent',
                'structure' => 'general2'
            ),
            array(
                'name' => 'dev_users',
                'paramChains' => array(
                    'dev/users' => 'default'
                ),
                'controller' => 'framework/packages/DevPackage/controller/UserDevController',
                'action' => 'devUsersAction',
                'permission' => 'viewGuestContent',
                'structure' => 'devUsers'
            )
        );
    }
}
