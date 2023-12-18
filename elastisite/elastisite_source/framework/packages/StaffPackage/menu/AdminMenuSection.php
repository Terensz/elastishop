<?php
namespace framework\packages\StaffPackage\menu;

class AdminMenuSection
{
    public function getConfig()
    {
        return [
            'title' => 'staff.administration',
            'items' => [
                // [
                //     'routeName' => 'admin_staff_config',
                //     'paramChain' => 'admin/staff/config',
                //     'title' => 'admin.staff.config'
                // ],
                [
                    'routeName' => 'admin_staff_members_list',
                    'paramChain' => 'admin/staff/members/list',
                    'title' => 'admin.staff.members.list'
                ],
                [
                    'routeName' => 'admin_staff_members_chart',
                    'paramChain' => 'admin/staff/members/chart',
                    'title' => 'admin.staff.members.chart'
                ]
            ]
        ];
    }
}