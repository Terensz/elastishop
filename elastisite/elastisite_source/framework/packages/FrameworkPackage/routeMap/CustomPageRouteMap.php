<?php
namespace framework\packages\FrameworkPackage\routeMap;

class CustomPageRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_customPages',
                'paramChains' => array(
                    'admin/customPages' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'custom.pages',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'FrameworkPackage/view/widget/AdminCustomPagesWidget'
                )
            ),
            array(
                'name' => 'admin_customPages_widget',
                'paramChains' => array(
                    'admin/customPages/widget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPagesWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPages_list', // This is the route of the DataGridBuilder
                'paramChains' => array(
                    'admin/customPages/list' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPagesListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_edit',
                'paramChains' => array(
                    'admin/customPage/edit' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_new',
                'paramChains' => array(
                    'admin/customPage/new' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_delete',
                'paramChains' => array(
                    'admin/customPage/delete' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPages_defaultCustomPagePanel',
                'paramChains' => array(
                    'admin/customPages/defaultCustomPagePanel' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPagesDefaultCustomPagePanelAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPages_createDefaultCustomPage',
                'paramChains' => array(
                    'admin/customPages/createDefaultCustomPage' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPagesCreateDefaultCustomPageAction',
                'permission' => 'viewProjectAdminContent'
            ),

            // Basic
            array(
                'name' => 'admin_customPage_edit_modalTabContent_basic',
                'paramChains' => array(
                    'admin/customPage/edit/modalTabContent/basic' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageEditModalTabContentBasicAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_basic_editForm',
                'paramChains' => array(
                    'admin/customPage/basic/editForm' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageBasicEditFormAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_basic_titleForm',
                'paramChains' => array(
                    'admin/customPage/basic/titleForm' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageBasicTitleFormAction',
                'permission' => 'viewProjectAdminContent'
            ),

            // OpenGraph
            array(
                'name' => 'admin_customPage_edit_modalTabContent_openGraph',
                'paramChains' => array(
                    'admin/customPage/edit/modalTabContent/openGraph' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageEditModalTabContentOpenGraphAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_openGraph_editForm',
                'paramChains' => array(
                    'admin/customPage/openGraph/editForm' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageOpenGraphEditFormAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_openGraph_setCustomPageOpenGraph',
                'paramChains' => array(
                    'admin/customPage/openGraph/setCustomPageOpenGraph' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageOpenGraphSetCustomPageOpenGraphAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_openGraph_removeCustomPageOpenGraph',
                'paramChains' => array(
                    'admin/customPage/openGraph/removeCustomPageOpenGraph' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageOpenGraphRemoveCustomPageOpenGraphAction',
                'permission' => 'viewProjectAdminContent'
            ),

            // Keywords
            array(
                'name' => 'admin_customPage_edit_modalTabContent_keywords',
                'paramChains' => array(
                    'admin/customPage/edit/modalTabContent/keywords' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageEditModalTabContentKeywordsAction',
                'permission' => 'viewProjectAdminContent'
            ),
            // array(
            //     'name' => 'admin_customPage_keywords_editForm',
            //     'paramChains' => array(
            //         'admin/customPage/keywords/editForm' => 'default'
            //     ),
            //     'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
            //     'action' => 'adminCustomPageKeywordsEditFormAction',
            //     'permission' => 'viewProjectAdminContent'
            // ),
            array(
                'name' => 'admin_customPage_keywords_list',
                'paramChains' => array(
                    'admin/customPage/keywords/list' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageKeywordsListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_keywords_add',
                'paramChains' => array(
                    'admin/customPage/keywords/add' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageKeywordsAddAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_keywords_delete',
                'paramChains' => array(
                    'admin/customPage/keywords/delete' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageKeywordsDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_saveDescription',
                'paramChains' => array(
                    'admin/customPage/saveDescription' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageSaveDescriptionAction',
                'permission' => 'viewProjectAdminContent'
            ),

            // Background
            array(
                'name' => 'admin_customPage_edit_modalTabContent_background',
                'paramChains' => array(
                    'admin/customPage/edit/modalTabContent/background' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageEditModalTabContentBackgroundAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_background_editForm',
                'paramChains' => array(
                    'admin/customPage/background/editForm' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageBackgroundEditFormAction',
                'permission' => 'viewProjectAdminContent'
            ),


            array(
                'name' => 'admin_customPage_background_removeBackground',
                'paramChains' => array(
                    'admin/customPage/background/removeBackground' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageBackgroundRemoveBackgroundAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_background_selectBackground',
                'paramChains' => array(
                    'admin/customPage/background/selectBackground' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageBackgroundSelectBackgroundAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_customPage_background_saveBackgroundColor',
                'paramChains' => array(
                    'admin/customPage/background/saveBackgroundColor' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/CustomPageWidgetController',
                'action' => 'adminCustomPageBackgroundSaveBackgroundColorAction',
                'permission' => 'viewProjectAdminContent'
            ),

        );
    }
}
