<?php
namespace framework\packages\ArticlePackage\routeMap;

class ArticleRouteMap
{
    public static function get()
    {
        return array(
            // array(
            //     'name' => 'article_widget',
            //     'paramChains' => array(
            //         'article/widget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/ArticlePackage/controller/ArticleWidgetController',
            //     'action' => 'articleWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            // array(
            //     'name' => 'article_teaser_widget',
            //     'paramChains' => array(
            //         'article/teaser/widget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/ArticlePackage/controller/ArticleWidgetController',
            //     'action' => 'teaserWidgetAction',
            //     'permission' => 'viewGuestContent'
            // ),
            array(
                'name' => 'article_search_widget',
                'paramChains' => array(
                    'article/search/widget' => 'default'
                ),
                'controller' => 'framework/packages/ArticlePackage/controller/ArticleWidgetController',
                'action' => 'articleSearchWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'article_view',
                'paramChains' => array(
                    'article/{slug}' => 'default'
                ),
                'controller' => 'framework/packages/ArticlePackage/controller/ArticleController',
                'action' => 'articleViewAction',
                'permission' => 'viewGuestContent',
                'title' => 'article',
                // 'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'structure' => 'ArticlePackage/view/structure/article',
                'backgroundTheme' => 'article'
                // 'widgetChanges' => array(
                //     'mainContent' => 'ArticlePackage/view/widget/ArticleWidget',
                //     'leftTopContent' => 'SchedulePackage/view/widget/CalendarWidget'
                // )
            ),
            array(
                'name' => 'admin_article_edit',
                'paramChains' => array(
                    'admin/article/edit' => 'default'
                ),
                'controller' => 'framework/packages/ArticlePackage/controller/ArticleWidgetController',
                'action' => 'adminArticleEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_article_move',
                'paramChains' => array(
                    'admin/article/move' => 'default'
                ),
                'controller' => 'framework/packages/ArticlePackage/controller/ArticleWidgetController',
                'action' => 'adminArticleMoveAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_article_delete',
                'paramChains' => array(
                    'admin/article/delete' => 'default'
                ),
                'controller' => 'framework/packages/ArticlePackage/controller/ArticleWidgetController',
                'action' => 'adminArticleDeleteAction',
                'permission' => 'viewProjectAdminContent'
            )
        );
    }
}
