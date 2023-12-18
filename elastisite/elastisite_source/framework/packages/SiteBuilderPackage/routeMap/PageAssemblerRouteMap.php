<?php
namespace framework\packages\SiteBuilderPackage\routeMap;

class PageAssemblerRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_pageAssembler_distantViewEditor_base',
                'paramChains' => array(
                    'admin/pageAssembler/distantViewEditor/base' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/PageAssemblerWidgetController',
                'action' => 'adminPageAssemblerDistantViewEditorBaseAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'admin_pageAssembler_distantViewEditor_addLeftPanel',
                'paramChains' => array(
                    'admin/pageAssembler/distantViewEditor/addLeftPanel' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/PageAssemblerWidgetController',
                'action' => 'adminPageAssemblerDistantViewEditorAddLeftPanelAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'admin_pageAssembler_distantViewEditor_removeLeftPanel',
                'paramChains' => array(
                    'admin/pageAssembler/distantViewEditor/removeLeftPanel' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/PageAssemblerWidgetController',
                'action' => 'adminPageAssemblerDistantViewEditorRemoveLeftPanelAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'admin_pageAssembler_distantViewEditor_addWidget',
                'paramChains' => array(
                    'admin/pageAssembler/distantViewEditor/addWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/PageAssemblerWidgetController',
                'action' => 'adminPageAssemblerDistantViewEditorAddWidgetAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'admin_pageAssembler_distantViewEditor_removeWidget',
                'paramChains' => array(
                    'admin/pageAssembler/distantViewEditor/removeWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/PageAssemblerWidgetController',
                'action' => 'adminPageAssemblerDistantViewEditorRemoveWidgetAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'admin_pageAssembler_distantViewEditor_sortWidgets',
                'paramChains' => array(
                    'admin/pageAssembler/distantViewEditor/sortWidgets' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/PageAssemblerWidgetController',
                'action' => 'adminPageAssemblerDistantViewEditorSortWidgetsAction',
                'permission' => 'viewSiteHelperContent'
            )
        );
    }
}
