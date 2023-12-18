<?php
namespace framework\packages\UserPackage\routeMap;

class UserPersonalDataRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'user_handlePersonalData',
                'paramChains' => array(
                    'felhasznalo/szemelyesAdataim' => 'hu',
                    'user/myPersonalData' => 'en'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserController',
                'action' => 'userRegistrationAction',
                'permission' => 'viewUserContent',
                'title' => 'my.personal.data',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'UserPackage/view/widget/MyPersonalDataWidget',
                    // 'left1' => 'UserPackage/view/widget/LoginWidget',
                    // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                ),
                'pageSwitchBehavior' => array(
                    'UserRegistrationWidget' => 'restore'
                )
            ),
            array(
                'name' => 'user_config',
                'paramChains' => array(
                    'user/config' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserController',
                'action' => 'userRegistrationAction',
                'permission' => 'viewUserContent',
                'inMenu' => 'main',
                'title' => 'my.personal.data',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'UserPackage/view/widget/MyPersonalDataWidget',
                    // 'left1' => 'UserPackage/view/widget/LoginWidget',
                    // // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                ),
                // 'pageSwitchBehavior' => array(
                //     'UserRegistrationWidget' => 'restore'
                // )
            ),
            array(
                'name' => 'user_MyPersonalDataWidget',
                'paramChains' => array(
                    'user/MyPersonalDataWidget' => 'default'
                ),
                'controller' => 'framework/packages/UserPackage/controller/UserWidgetController',
                'action' => 'myPersonalDataWidgetAction',
                'permission' => 'viewUserContent'
            ),
        );
    }
}
