<?php
namespace projects\ASC\routeMap;

class AscScaleBuilderRouteMap
{
    public static function get()
    {
        return array(
            /**
             * TESTING
            */
            array(
                'name' => 'asc_scaleBuilder_dashboardTest',
                'paramChains' => array(
                    'asc/scaleBuilder/dashboardTest' => 'default'
                ),
                'controller' => 'projects/ASC/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewUserContent',
                'title' => 'scale.builder',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/DashboardTestWidget'
                )
            ),
            array(
                'name' => 'asc_scaleBuilder_DashboardTestWidget',
                'paramChains' => array(
                    'asc/scaleBuilder/DashboardTestWidget' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
                'action' => 'dashboardTestWidgetAction',
                'permission' => 'viewUserContent'
            ),
            /**
             * / TESTING
            */

            /**
             * BUILDER CORE
            */
            /**
             * DASHBOARD
            */
            // array(
            //     'name' => 'widget_AscScaleBuilderDashboardWidget',
            //     'paramChains' => array(
            //         'widget/AscScaleBuilderDashboardWidget' => 'default'
            //     ),
            //     'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
            //     'action' => 'ascScaleBuilderDashboardWidgetAction',
            //     'permission' => 'viewUserContent'
            // ),
            /**
             * / DASHBOARD
            */

            /**
             * AscScaleBuilder main
            */
            array(
                'name' => 'asc_scaleBuilder_scale_dashboard',
                'paramChains' => array(
                    'asc/scaleBuilder/scale/{scaleId}' => 'default'
                ),
                'controller' => 'projects/ASC/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewUserContent',
                'title' => 'scale.builder',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/AscScaleBuilderWidget'
                )
            ),
            // array(
            //     'name' => 'asc_scaleBuilder_columnView_scale_dashboard',
            //     'paramChains' => array(
            //         'asc/scaleBuilder/columnView/scale/{scaleId}' => 'default'
            //     ),
            //     'controller' => 'projects/ASC/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewUserContent',
            //     'title' => 'scale.builder',
            //     'structure' => 'FrameworkPackage/view/structure/BasicStructure',
            //     // 'skinName' => 'Basic',
            //     'backgroundEngine' => 'Simple',
            //     'backgroundTheme' => 'empty',
            //     'widgetChanges' => array(
            //         'mainContent' => 'projects/ASC/view/widget/AscScaleBuilderWidget'
            //     )
            // ),
            array(
                'name' => 'asc_scaleBuilder_subject',
                'paramChains' => array(
                    'asc/scaleBuilder/scale/{scaleId}/subject/{subject}' => 'default'
                ),
                'controller' => 'projects/ASC/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewUserContent',
                'title' => 'scale.builder',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/AscScaleBuilderWidget'
                )
            ),
            array(
                'name' => 'asc_scaleBuilder_parent',
                'paramChains' => array(
                    'asc/scaleBuilder/scale/{scaleId}/parent/{parentId}' => 'default'
                ),
                'controller' => 'projects/ASC/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewUserContent',
                'title' => 'scale.builder',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/AscScaleBuilderWidget'
                )
            ),
            array(
                'name' => 'asc_scaleBuilder_widget',
                'paramChains' => array(
                    'asc/scaleBuilder/widget' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
                'action' => 'ascScaleBuilderWidgetAction',
                'permission' => 'viewUserContent'
            ),

            /**
             * AscScaleBuilder main, column view
            */
            array(
                'name' => 'asc_scaleBuilder_columnView_scale_dashboard',
                'paramChains' => array(
                    'asc/scaleBuilder/columnView/scale/{scaleId}' => 'default'
                ),
                'controller' => 'projects/ASC/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewUserContent',
                'title' => 'scale.builder',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/AscScaleBuilderWidget'
                )
            ),
            array(
                'name' => 'asc_scaleBuilder_columnView_subject',
                'paramChains' => array(
                    'asc/scaleBuilder/columnView/scale/{scaleId}/subject/{subject}' => 'default'
                ),
                'controller' => 'projects/ASC/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewUserContent',
                'title' => 'scale.builder',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/AscScaleBuilderWidget'
                )
            ),
            array(
                'name' => 'asc_scaleBuilder_columnView_child',
                'paramChains' => array(
                    'asc/scaleBuilder/columnView/scale/{scaleId}/child/{childId}' => 'default'
                ),
                'controller' => 'projects/ASC/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewUserContent',
                'title' => 'scale.builder',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/AscScaleBuilderWidget'
                )
            ),

            // Egyelore ezt megsem fogjuk hasznalni
            array(
                'name' => 'asc_scaleBuilder_subject_juxtaposedSubject',
                'paramChains' => array(
                    'asc/scaleBuilder/scale/{scaleId}/subject/{subject}/juxtaposedSubject/{juxtaposedSubject}' => 'default'
                ),
                'controller' => 'projects/ASC/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewUserContent',
                'title' => 'scale.builder',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/AscScaleBuilderWidget'
                )
            ),
            /**
             * / AscScaleBuilder main
            */

            array(
                'name' => 'asc_scaleBuilder_addUnit',
                'paramChains' => array(
                    'asc/scaleBuilder/addUnit' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
                'action' => 'ascScaleBuilderAddUnitAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_scaleBuilder_editUnit',
                'paramChains' => array(
                    'asc/scaleBuilder/editUnit' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
                'action' => 'ascScaleBuilderEditUnitAction',
                'permission' => 'viewUserContent'
            ),
            /**
             * I made this route for an easier debug and development of the AscRequestService and AscPermissionService
            */
            array(
                'name' => 'asc_scaleBuilder_editUnit_unitId',
                'paramChains' => array(
                    'asc/scaleBuilder/editUnit/unitId/{unitId}' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
                'action' => 'ascScaleBuilderEditUnitAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_scaleBuilder_deleteUnit',
                'paramChains' => array(
                    'asc/scaleBuilder/deleteUnit' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
                'action' => 'ascScaleBuilderDeleteUnitAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_scaleBuilder_moveUnit',
                'paramChains' => array(
                    'asc/scaleBuilder/moveUnit' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
                'action' => 'ascScaleBuilderMoveUnitAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_scaleBuilder_addJuxtaposedSubject',
                'paramChains' => array(
                    'asc/scaleBuilder/addJuxtaposedSubject' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
                'action' => 'ascScaleBuilderAddJuxtaposedSubjectAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_scaleBuilder_applySetting',
                'paramChains' => array(
                    'asc/scaleBuilder/applySetting' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
                'action' => 'ascScaleBuilderApplySettingAction',
                'permission' => 'viewUserContent'
            ),

            /**
             * invite user
            */
            array(
                'name' => 'asc_inviteUser_modal',
                'paramChains' => array(
                    'asc/inviteUser/modal' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
                'action' => 'ascInviteUserModalAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_inviteUser_send',
                'paramChains' => array(
                    'asc/inviteUser/send' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
                'action' => 'ascInviteUserSendAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_inviteUser_registration',
                'paramChains' => array(
                    'asc/inviteUser/registration/{inviteToken}' => 'default'
                ),
                'controller' => 'projects/ASC/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'register.for.scale',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/InviteUserRegistrationWidget'
                )
            ),
            array(
                'name' => 'asc_InviteUserRegistrationWidget',
                'paramChains' => array(
                    'asc/InviteUserRegistrationWidget' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
                'action' => 'inviteUserRegistrationWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'asc_inviteUser_join',
                'paramChains' => array(
                    'asc/inviteUser/join/{inviteToken}' => 'default'
                ),
                'controller' => 'projects/ASC/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'join.scale',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/InviteUserRegistrationWidget'
                )
            ),
            // array(
            //     'name' => 'asc_inviteUser_send',
            //     'paramChains' => array(
            //         'asc/inviteUser/send/{ascScaleId}' => 'default'
            //     ),
            //     'controller' => 'projects/ASC/controller/AscScaleBuilderWidgetController',
            //     'action' => 'ascInviteUserSendAction',
            //     'permission' => 'viewUserContent'
            // )
        );
    }
}