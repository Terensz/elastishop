<?php
namespace framework\packages\DevPackage\routeMap;

class DevDbRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'dev_db',
                'paramChains' => array(
                    'dev/db' => 'default'
                ),
                'controller' => 'framework/packages/DevPackage/controller/SimpleDevController',
                'action' => 'devDbAction',
                'permission' => 'viewGuestContent',
                'title' => 'admin.admins.title',
                'structure' => 'devDb'
            ),
            array(
                'name' => 'dev_mailtest',
                'paramChains' => array(
                    'dev/mailtest' => 'default'
                ),
                'controller' => 'framework/packages/DevPackage/controller/SimpleDevController',
                'action' => 'devMailTestAction',
                'permission' => 'viewGuestContent',
                'title' => 'admin.admins.title',
                'structure' => 'devDb'
            ),
            array(
                'name' => 'dev_ormtest',
                'paramChains' => array(
                    'dev/ormtest' => 'default'
                ),
                'controller' => 'framework/packages/DevPackage/controller/SimpleDevController',
                'action' => 'devOrmTestAction',
                'permission' => 'viewGuestContent',
                'title' => 'admin.admins.title',
                'structure' => 'devDb'
            )
        );
    }
}
