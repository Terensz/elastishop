<?php
namespace framework\packages\StaffPackage\routeMap;

class StaffRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'staff_stats_manage_staffMember',
                'paramChains' => array(
                    'staff/stats/manage/staffMember/{code}' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => '',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'SiteBuilderPackage/view/widget/ManageStaffMemberStatsWidget',
                )
            ),
            array(
                'name' => 'staff_stats_manage_statPage',
                'paramChains' => array(
                    'staff/stats/manage/statPage/{code}' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => '',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'SiteBuilderPackage/view/widget/ManageStaffMemberStatsWidget',
                )
            ),
            array(
                'name' => 'widget_ManageStaffMemberStatsWidget',
                'paramChains' => array(
                    'widget/ManageStaffMemberStatsWidget' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/StaffWidgetController',
                'action' => 'manageStaffMemberStatsWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'staffMemberLogin_ModalLoginWidget',
                'paramChains' => array(
                    'staffMemberLogin/ModalLoginWidget' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/ModalLoginWidgetController',
                'action' => 'modalLoginWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'staffMemberStats_saveStat',
                'paramChains' => array(
                    'staffMemberStats/saveStat' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/StaffWidgetController',
                'action' => 'staffMemberStatsSaveStatAction',
                'permission' => 'viewGuestContent'
            ),
        );
    }
}
