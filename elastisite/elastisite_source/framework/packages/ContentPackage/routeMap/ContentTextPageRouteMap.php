<?php
namespace framework\packages\ContentPackage\routeMap;

class ContentTextPageRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'articleContentText',
                'paramChains' => array(
                    'articleContentText' => 'default',
                    'dokumentum/{slug}' => 'hu',
                    'document/{slug}' => 'en'
                ),
                'controller' => 'ContentPackage/controller/ArticleContentTextPageController',
                'action' => 'articleContentTextAction',
                'permission' => 'viewGuestContent',
                'title' => 'document',
                'structure' => 'FrameworkPackage/view/structure/BasicStructure',
                'widgetChanges' => array(
                    'mainContent' => 'framework/packages/ContentPackage/view/widget/PrefabArticleWidget',
                    // 'left2' => 'LegalPackage/view/widget/UsersDocumentsWidget'
                )
            ),

            // Admin
            array(
                'name' => 'admin_emailContentTexts',
                'paramChains' => array(
                    'admin/emailContentTexts' => 'default'
                ),
                'controller' => 'FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.email.contents',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'ContentPackage/view/widget/AdminContentTextsWidget'
                )
            ),
            array(
                'name' => 'admin_entryContentTexts',
                'paramChains' => array(
                    'admin/entryContentTexts' => 'default'
                ),
                'controller' => 'FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.entry.contents',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'ContentPackage/view/widget/AdminContentTextsWidget'
                )
            ),
            array(
                'name' => 'admin_articleContentTexts',
                'paramChains' => array(
                    'admin/articleContentTexts' => 'default'
                ),
                'controller' => 'FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.article.contents',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'ContentPackage/view/widget/AdminContentTextsWidget'
                )
            )
        );
    }
}
