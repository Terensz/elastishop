<?php
namespace framework\packages\StaffPackage\routeMap;

class AdminStaffRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_staff_config',
                'paramChains' => array(
                    'admin/staff/config' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.staff.config',
                'skinName' => 'Basic',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'widgetChanges' => array(
                    'mainContent' => 'SiteBuilderPackage/view/widget/AdminStaffConfigWidget',
                )
            ),
            array(
                'name' => 'admin_AdminStaffConfigWidget',
                'paramChains' => array(
                    'admin/AdminStaffConfigWidget' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/AdminStaffWidgetController',
                'action' => 'adminStaffConfigWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_staff_config_list',
                'paramChains' => array(
                    'admin/staff/config/list' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/AdminStaffWidgetController',
                'action' => 'adminStaffConfigListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_staff_config_edit',
                'paramChains' => array(
                    'admin/staff/config/edit' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/AdminStaffWidgetController',
                'action' => 'adminStaffConfigEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            /**
             * 
            */
            array(
                'name' => 'admin_staff_members_list',
                'paramChains' => array(
                    'admin/staff/members/list' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.staff.members.list',
                'skinName' => 'Basic',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'widgetChanges' => array(
                    'mainContent' => 'SiteBuilderPackage/view/widget/AdminStaffMembersListWidget',
                )
            ),
            array(
                'name' => 'admin_AdminStaffMembersListWidget',
                'paramChains' => array(
                    'admin/AdminStaffMembersListWidget' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/AdminStaffWidgetController',
                'action' => 'adminStaffMembersListWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_staff_member_list',
                'paramChains' => array(
                    'admin/staff/member/list' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/AdminStaffWidgetController',
                'action' => 'adminStaffMemberListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_staff_member_new',
                'paramChains' => array(
                    'admin/staff/member/new' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/AdminStaffWidgetController',
                'action' => 'adminStaffMemberNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_staff_member_edit',
                'paramChains' => array(
                    'admin/staff/member/edit' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/AdminStaffWidgetController',
                'action' => 'adminStaffMemberEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_staff_member_delete',
                'paramChains' => array(
                    'admin/staff/member/delete' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/AdminStaffWidgetController',
                'action' => 'adminStaffMemberDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_staff_member_stats_pages',
                'paramChains' => array(
                    'admin/staff/member/stats/pages' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/AdminStaffWidgetController',
                'action' => 'adminStaffMemberStatsPagesAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_staff_member_stats_view',
                'paramChains' => array(
                    'admin/staff/member/stats/view' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/AdminStaffWidgetController',
                'action' => 'adminStaffMemberStatsViewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_staff_members_chart',
                'paramChains' => array(
                    'admin/staff/members/chart' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.staff.members.chart',
                'skinName' => 'Basic',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'widgetChanges' => array(
                    'mainContent' => 'SiteBuilderPackage/view/widget/AdminStaffMembersChartWidget',
                )
            ),
            array(
                'name' => 'admin_AdminStaffMembersChartWidget',
                'paramChains' => array(
                    'admin/AdminStaffMembersChartWidget' => 'default'
                ),
                'controller' => 'framework/packages/StaffPackage/controller/AdminStaffWidgetController',
                'action' => 'adminStaffMembersChartWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
        );
    }
}
