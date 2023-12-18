<?php
namespace framework\packages\ContentPackage\routeMap;

class ContentTextWidgetRouteMap
{
    public static function get()
    {
        return array(
            // array(
            //     'name' => 'ArticleContentWidget',
            //     'paramChains' => array(
            //         'ArticleContentWidget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/ContentPackage/controller/ArticleContentWidgetController',
            //     'action' => 'articleContentWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            array(
                'name' => 'admin_contentTexts_widget',
                'paramChains' => array(
                    'admin/contentTexts/widget' => 'default'
                ),
                'controller' => 'framework/packages/ContentPackage/controller/ContentTextWidgetController',
                'action' => 'adminContentTextsWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_contentText_edit',
                'paramChains' => array(
                    'admin/contentText/edit' => 'default'
                ),
                'controller' => 'framework/packages/ContentPackage/controller/ContentTextWidgetController',
                'action' => 'adminContentTextEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_contentText_reset',
                'paramChains' => array(
                    'admin/contentText/reset' => 'default'
                ),
                'controller' => 'framework/packages/ContentPackage/controller/ContentTextWidgetController',
                'action' => 'adminContentTextResetAction',
                'permission' => 'viewProjectAdminContent'
            )
        );
    }
}
