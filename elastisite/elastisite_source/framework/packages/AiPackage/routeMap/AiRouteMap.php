<?php
namespace framework\packages\AiPackage\routeMap;

class AiRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'article_view',
                'paramChains' => array(
                    'ai' => 'default'
                ),
                'controller' => 'framework/packages/AiPackage/controller/AiController',
                'action' => 'aiViewAction',
                'permission' => 'viewGuestContent',
                'title' => 'AI',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                // 'structure' => 'ArticlePackage/view/structure/article',
                'backgroundTheme' => 'article',
                'widgetChanges' => array(
                    'mainContent' => 'AiPackage/view/widget/AiWidget',
                )
            ),
            array(
                'name' => 'AiWidget',
                'paramChains' => array(
                    'ai/widget' => 'default'
                ),
                'controller' => 'framework/packages/AiPackage/controller/AiWidgetController',
                'action' => 'aiWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            // array(
            //     'name' => 'admin_article_move',
            //     'paramChains' => array(
            //         'admin/article/move' => 'default'
            //     ),
            //     'controller' => 'framework/packages/ArticlePackage/controller/ArticleWidgetController',
            //     'action' => 'adminArticleMoveAction',
            //     'permission' => 'viewProjectAdminContent'
            // ),
            // array(
            //     'name' => 'admin_article_delete',
            //     'paramChains' => array(
            //         'admin/article/delete' => 'default'
            //     ),
            //     'controller' => 'framework/packages/ArticlePackage/controller/ArticleWidgetController',
            //     'action' => 'adminArticleDeleteAction',
            //     'permission' => 'viewProjectAdminContent'
            // )
        );
    }
}
