<?php
namespace framework\packages\VisitorPackage\routeMap;

use framework\kernel\component\Kernel;
use framework\kernel\base\Container;

class VisitorRouteMap extends Kernel
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_visitsAndPageLoads',
                'paramChains' => array(
                    'admin/visitsAndPageLoads' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'visits.and.page.loads',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'VisitorPackage/view/widget/AdminVisitsAndPageLoadsWidget'
                    // 'mainContent2' => 'VisitorPackage/view/widget/AdminVisitChartWidget'
                    // 'mainContent' => 'AdminVisitWidget',
                    // 'mainContent2' => 'AdminVisitChartWidget'
                )
            ),
            array(
                'name' => 'admin_visitsAndPageLoads_widget',
                'paramChains' => array(
                    'admin/visitsAndPageLoads/widget' => 'default'
                ),
                'controller' => 'framework/packages/VisitorPackage/controller/VisitorWidgetController',
                'action' => 'adminVisitsAndPageLoadsWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_visitsAndPageLoads_earlierMonth',
                'paramChains' => array(
                    'admin/visitsAndPageLoads/earlierMonth' => 'default'
                ),
                'controller' => 'framework/packages/VisitorPackage/controller/VisitorWidgetController',
                'action' => 'adminVisitsAndPageLoadsEarlierMonthAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_mostUsedKeywords',
                'paramChains' => array(
                    'admin/mostUsedKeywords' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'most.used.keywords',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'VisitorPackage/view/widget/AdminMostUsedKeywordsWidget'
                    // 'mainContent2' => 'VisitorPackage/view/widget/AdminVisitChartWidget'
                    // 'mainContent' => 'AdminVisitWidget',
                    // 'mainContent2' => 'AdminVisitChartWidget'
                )
            ),
            array(
                'name' => 'admin_mostUsedKeywords_widget',
                'paramChains' => array(
                    'admin/mostUsedKeywords/widget' => 'default'
                ),
                'controller' => 'framework/packages/VisitorPackage/controller/VisitorWidgetController',
                'action' => 'adminMostUsedKeywordsWidgetAction',
                'permission' => 'viewProjectAdminContent'
            )
            // array(
            //     'name' => 'admin_visitChart_widget',
            //     'paramChains' => array(
            //         'admin/visitChart/widget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/VisitorPackage/controller/VisitorWidgetController',
            //     'action' => 'adminVisitChartWidgetAction',
            //     'permission' => 'viewProjectAdminContent'
            // )
        );
    }
}
