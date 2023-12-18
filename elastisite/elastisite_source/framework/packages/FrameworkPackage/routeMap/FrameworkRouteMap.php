<?php
namespace framework\packages\FrameworkPackage\routeMap;

use framework\kernel\component\Kernel;

class FrameworkRouteMap extends Kernel
{
    public static function get()
    {
        return array(
            array(
                'name' => 'cp_load',
                'paramChains' => array(
                    'cp/load' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkWidgetController',
                'action' => 'cpLoadAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'cp_loadScripts',
                'paramChains' => array(
                    'cp/loadScripts' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkWidgetController',
                'action' => 'cpLoadScriptsAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'dynamicSkeleton_styleSheet',
                'paramChains' => array(
                    'dynamicSkeleton/styleSheet' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/DynamicSkeletonAccessoryController',
                'action' => 'dynamicSkeletonStyleSheetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'dynamicSkeleton_scripts_head',
                'paramChains' => array(
                    'dynamicSkeleton/scripts/head' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/DynamicSkeletonAccessoryController',
                'action' => 'dynamicSkeletonScriptsHeadAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'dynamicSkeleton_scripts_afterBody',
                'paramChains' => array(
                    'dynamicSkeleton/scripts/afterBody' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/DynamicSkeletonAccessoryController',
                'action' => 'dynamicSkeletonScriptsAfterBodyAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_Left1Widget',
                'paramChains' => array(
                    'widget/Left1Widget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkWidgetController',
                'action' => 'left1WidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_Left2Widget',
                'paramChains' => array(
                    'widget/Left2Widget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkWidgetController',
                'action' => 'left2WidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_MainContentWidget',
                'paramChains' => array(
                    'widget/MainContentWidget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkWidgetController',
                'action' => 'mainContentWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_MainContent2Widget',
                'paramChains' => array(
                    'widget/MainContent2Widget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkWidgetController',
                'action' => 'mainContent2WidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'ElastiTools_js',
                'paramChains' => array(
                    'elastitools/js' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'elastiToolsJsAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'admin_index',
                'paramChains' => array(
                    'admin' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'indexAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.index.title',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'FrameworkPackage/view/widget/AdminIndexWidget'
                )
            ),
            array(
                'name' => 'admin_index_widget',
                'paramChains' => array(
                    'admin/index/widget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkWidgetController',
                'action' => 'adminIndexWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'sourceMapFile',
                'paramChains' => array(
                    'sm/{sourceMapFileName}' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'smAction',
                'permission' => 'loadSetup',
                'title' => 'admin.index.title',
                'structure' => 'FrameworkPackage/view/structure/setup',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'FrameworkPackage/view/widget/SetupMainWidget'
                )
            ),
            array(
                'name' => 'setup',
                'paramChains' => array(
                    'setup' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'setupAction',
                'permission' => 'loadSetup',
                'title' => 'admin.index.title',
                'structure' => 'FrameworkPackage/view/structure/setup',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'FrameworkPackage/view/widget/SetupMainWidget'
                )
            ),
            array(
                'name' => 'setup_MainWidget',
                'paramChains' => array(
                    'setup/MainWidget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkWidgetController',
                'action' => 'setupMainWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'setup_createMissingTables',
                'paramChains' => array(
                    'setup/createMissingTables' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkWidgetController',
                'action' => 'setupCreateMissingTablesAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'widget_SetupMenuWidget',
                'paramChains' => array(
                    'widget/SetupMenuWidget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkWidgetController',
                'action' => 'setupMenuWidgetAction',
                // 'permission' => 'viewProjectAdminContent'
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'errorpage_404',
                'paramChains' => array(
                    'error/404' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'errorpage404Action',
                'permission' => 'viewGuestContent',
                'title' => 'error.404.title',
                'structure' => 'FrameworkPackage/view/structure/error404'
            ),
            array(
                'name' => 'errorpage_403',
                'paramChains' => array(
                    'error/403' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'error403Action',
                'permission' => 'viewGuestContent',
                'title' => 'error.403.title',
                'structure' => 'FrameworkPackage/view/structure/error403'
            ),
            // array(
            //     'name' => '403_widget',
            //     'paramChains' => array(
            //         'widget/403_widget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkWidgetController',
            //     'action' => 'error403WidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            array(
                'name' => 'errorpage_500',
                'paramChains' => array(
                    'error/500' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'error.500.title',
                'structure' => 'FrameworkPackage/view/structure/error500'
            ),
            array(
                'name' => 'widget_ErrorWidget',
                'paramChains' => array(
                    'widget/ErrorWidget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkWidgetController',
                'action' => 'errorWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_AdminLoginMainContentWidget',
                'paramChains' => array(
                    'widget/AdminLoginMainContentWidget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkWidgetController',
                'action' => 'adminLoginMainContentWidgetAction',
                'permission' => 'viewGuestContent'
            ),
        );
    }
}
