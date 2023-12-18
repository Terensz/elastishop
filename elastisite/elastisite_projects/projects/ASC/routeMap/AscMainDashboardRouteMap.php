<?php
namespace projects\ASC\routeMap;

class AscMainDashboardRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'asc_dashboard',
                'paramChains' => array(
                    'asc/dashboard' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewUserContent',
                'title' => 'admin.asc.sample.scales',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/AscScaleListerWidget'
                )
            ),
            array(
                'name' => 'asc_dashboard_widget',
                'paramChains' => array(
                    'asc/dashboard/widget' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscMainDashboardWidgetController',
                'action' => 'ascScaleListerWidgetAction',
                'permission' => 'viewUserContent'
            ),
            /**
             * asc_dashboardView : quick rendering of the list, without running check, or anything.
            */
            array(
                'name' => 'asc_closeEventActuality',
                'paramChains' => array(
                    'asc/closeEventActuality/{closeResult}' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscMainDashboardWidgetController',
                'action' => 'ascCloseEventActualityAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_reopenEventActuality',
                'paramChains' => array(
                    'asc/reopenEventActuality' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscMainDashboardWidgetController',
                'action' => 'ascReopenEventActualityAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_eventActualityListView',
                'paramChains' => array(
                    'asc/eventActualityListView' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscMainDashboardWidgetController',
                'action' => 'ascEventActualityListViewAction',
                'permission' => 'viewUserContent'
            ),

            array(
                'name' => 'asc_scaleLister_widget',
                'paramChains' => array(
                    'asc/scaleLister/widget' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscMainDashboardWidgetController',
                'action' => 'ascScaleListerWidgetAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_scaleLister_ownScaleList',
                'paramChains' => array(
                    'asc/scaleLister/ownScaleList' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscMainDashboardWidgetController',
                'action' => 'ascScaleListerOwnScaleListAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_scaleLister_othersList',
                'paramChains' => array(
                    'asc/scaleLister/othersList' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscMainDashboardWidgetController',
                'action' => 'ascScaleListerOthersListAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_scaleLister_newScale',
                'paramChains' => array(
                    'asc/scaleLister/newScale' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscMainDashboardWidgetController',
                'action' => 'ascScaleListerNewScaleAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_scaleLister_editScale',
                'paramChains' => array(
                    'asc/scaleLister/editScale' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscMainDashboardWidgetController',
                'action' => 'ascScaleListerEditScaleAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_scaleLister_deleteScale',
                'paramChains' => array(
                    'asc/scaleLister/deleteScale' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscMainDashboardWidgetController',
                'action' => 'ascScaleListerDeleteScaleAction',
                'permission' => 'viewUserContent'
            ),
        );
    }
}