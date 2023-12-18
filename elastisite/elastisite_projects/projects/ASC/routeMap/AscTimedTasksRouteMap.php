<?php
namespace projects\ASC\routeMap;

/**
 * AKA: CRON
*/
class AscTimedTasksRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'asc_timedTasks',
                'paramChains' => array(
                    'asc/timedTasks' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscTimedTasksController',
                'action' => 'runAllAction',
                'permission' => 'viewGuestContent',
                'title' => 'timed.tasks',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/AdminAscSubscriptionOffersWidget'
                )
            ),
            // array(
            //     'name' => 'admin_ascSubscriptionOffers_widget',
            //     'paramChains' => array(
            //         'admin/ascSubscriptionOffers/widget' => 'default'
            //     ),
            //     'controller' => 'projects/ASC/controller/AscWidgetController',
            //     'action' => 'adminAscSubscriptionOffersWidgetAction',
            //     'permission' => 'viewSiteAdminContent'
            // ),
        );
    }
}