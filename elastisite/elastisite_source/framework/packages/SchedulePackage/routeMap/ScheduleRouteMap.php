<?php
namespace framework\packages\SchedulePackage\routeMap;

class ScheduleRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_events',
                'paramChains' => array(
                    'admin/events' => 'default'
                ),
                'controller' => 'framework/packages/SchedulePackage/controller/ScheduleController',
                'action' => 'adminEventsAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.events',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'SchedulePackage/view/widget/AdminEventsWidget'
                )
            ),
            array(
                'name' => 'admin_events_widget',
                'paramChains' => array(
                    'admin/events/widget' => 'default'
                ),
                'controller' => 'framework/packages/SchedulePackage/controller/ScheduleWidgetController',
                'action' => 'adminEventsWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            // array(
            //     'name' => 'admin_calendar_widget',
            //     'paramChains' => array(
            //         'admin/calendar/widget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/SchedulePackage/controller/CalendarController',
            //     'action' => 'adminEventAction',
            //     'permission' => 'viewProjectAdminContent',
            //     'structure' => 'adminEvent'
            // ),
            array(
                'name' => 'admin_event_edit',
                'paramChains' => array(
                    'admin/event/edit' => 'default'
                ),
                'controller' => 'framework/packages/SchedulePackage/controller/ScheduleWidgetController',
                'action' => 'adminEventEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_event_delete',
                'paramChains' => array(
                    'admin/event/delete' => 'default'
                ),
                'controller' => 'framework/packages/SchedulePackage/controller/ScheduleWidgetController',
                'action' => 'adminEventDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),

            array(
                'name' => 'calendar_widget',
                'paramChains' => array(
                    'calendar/widget' => 'default'
                ),
                'controller' => 'framework/packages/SchedulePackage/controller/ScheduleWidgetController',
                'action' => 'calendarWidgetAction',
                'permission' => 'viewGuestContent'
            )

            // array(
            //     'name' => 'admin_event',
            //     'paramChains' => array(
            //         'admin/event' => 'default'
            //     ),
            //     'controller' => 'framework/packages/SchedulePackage/controller/EventController',
            //     'action' => 'adminEventAction',
            //     'permission' => 'viewProjectAdminContent',
            //     'backgroundEngine' => 'Simple',
            //     'backgroundTheme' => 'empty',
            //     'structure' => 'adminEvent'
            // ),
            // array(
            //     'name' => 'event_widget',
            //     'paramChains' => array(
            //         'event/widget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/SchedulePackage/controller/EventWidgetController',
            //     'action' => 'eventWidgetAction',
            //     'permission' => 'viewGuestContent'
            // )
        );
    }
}
