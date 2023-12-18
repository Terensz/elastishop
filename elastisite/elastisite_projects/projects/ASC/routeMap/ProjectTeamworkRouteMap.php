<?php
namespace projects\ASC\routeMap;

class ProjectTeamworkRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'asc_projectTeamwork_scale',
                'paramChains' => array(
                    'asc/projectTeamwork/scale/{scaleId}' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewUserContent',
                'title' => 'project.teams',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    // 'mainContent' => 'projects/ASC/view/widget/ProjectTeamworkWidget'
                    'mainContent' => 'projects/ASC/view/widget/AscScaleBuilderWidget'
                )
            ),
            array(
                'name' => 'widget_ProjectTeamworkWidget',
                'paramChains' => array(
                    'widget/ProjectTeamworkWidget' => 'default'
                ),
                'controller' => 'projects/ASC/controller/ProjectTeamworkWidgetController',
                'action' => 'projectTeamworkWidgetAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'projectTeamwork_newProjectTeam',
                'paramChains' => array(
                    'projectTeamwork/newProjectTeam' => 'default'
                ),
                'controller' => 'projects/ASC/controller/ProjectTeamworkWidgetController',
                'action' => 'newProjectTeamAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'projectTeamwork_editProjectTeam',
                'paramChains' => array(
                    'projectTeamwork/editProjectTeam' => 'default'
                ),
                'controller' => 'projects/ASC/controller/ProjectTeamworkWidgetController',
                'action' => 'editProjectTeamAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'projectTeamwork_deleteProjectTeam',
                'paramChains' => array(
                    'projectTeamwork/deleteProjectTeam' => 'default'
                ),
                'controller' => 'projects/ASC/controller/ProjectTeamworkWidgetController',
                'action' => 'deleteProjectTeamAction',
                'permission' => 'viewUserContent'
            ),

            // User
            array(
                'name' => 'projectTeamwork_newProjectTeamUser',
                'paramChains' => array(
                    'projectTeamwork/newProjectTeamUser' => 'default'
                ),
                'controller' => 'projects/ASC/controller/ProjectTeamworkWidgetController',
                'action' => 'newProjectTeamUserAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'projectTeamwork_editProjectTeamUser',
                'paramChains' => array(
                    'projectTeamwork/editProjectTeamUser' => 'default'
                ),
                'controller' => 'projects/ASC/controller/ProjectTeamworkWidgetController',
                'action' => 'editProjectTeamUserAction',
                'permission' => 'viewUserContent'
            ),

            // Invite
            array(
                'name' => 'projectTeamwork_newProjectTeamInvite',
                'paramChains' => array(
                    'projectTeamwork/newProjectTeamInvite' => 'default'
                ),
                'controller' => 'projects/ASC/controller/ProjectTeamworkWidgetController',
                'action' => 'newProjectTeamInviteAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'projectTeamwork_editProjectTeamInvite',
                'paramChains' => array(
                    'projectTeamwork/editProjectTeamInvite' => 'default'
                ),
                'controller' => 'projects/ASC/controller/ProjectTeamworkWidgetController',
                'action' => 'editProjectTeamInviteAction',
                'permission' => 'viewUserContent'
            ),
        );
    }
}