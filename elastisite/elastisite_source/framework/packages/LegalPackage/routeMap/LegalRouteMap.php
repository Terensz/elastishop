<?php
namespace framework\packages\LegalPackage\routeMap;

class LegalRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'widget_UsersDocumentsWidget',
                'paramChains' => array(
                    'widget/UsersDocumentsWidget' => 'default'
                ),
                'controller' => 'framework/packages/LegalPackage/controller/LegalWidgetController',
                'action' => 'usersDocumentsWidgetAction',
                'permission' => 'viewGuestContent'
            )
        );
    }
}
