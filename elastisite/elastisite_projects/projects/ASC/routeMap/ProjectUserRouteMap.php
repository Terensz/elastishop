<?php
namespace projects\ASC\routeMap;

class ProjectUserRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'loginOrRegister',
                'paramChains' => array(
                    'loginOrRegister' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewLoggedOutContent',
                'title' => 'login.or.register',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'framework/packages/UserPackage/view/widget/LoginGuideWidget'
                )
            ),
        );
    }
}