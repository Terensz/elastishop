<?php
namespace framework\packages\SiteBuilderPackage\routeMap;

class AdvancedArticleRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'widget_AdvancedArticleWidget',
                'paramChains' => array(
                    'widget/AdvancedArticleWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'advancedArticleWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_WrappedAdvancedArticleWidget',
                'paramChains' => array(
                    'widget/WrappedAdvancedArticleWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'wrappedAdvancedArticleWidgetAction',
                'permission' => 'viewGuestContent'
            ),

            /**
             * AAWEditor
            */
            // Article
            // array(
            //     'name' => 'AAWEditor_addArticle',
            //     'paramChains' => array(
            //         'AAWEditor/addArticle' => 'default'
            //     ),
            //     'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
            //     'action' => 'aawToolbarAddArticleAction',
            //     'permission' => 'viewSiteHelperContent'
            // ),
            // array(
            //     'name' => 'AAWEditor_editArticle',
            //     'paramChains' => array(
            //         'AAWEditor/editArticle' => 'default'
            //     ),
            //     'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
            //     'action' => 'aawToolbarEditArticleAction',
            //     'permission' => 'viewSiteHelperContent'
            // ),
            // array(
            //     'name' => 'AAWEditor_removeArticle',
            //     'paramChains' => array(
            //         'AAWEditor/removeArticle' => 'default'
            //     ),
            //     'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
            //     'action' => 'aawToolbarRemoveArticleAction',
            //     'permission' => 'viewSiteHelperContent'
            // ),
            // ArticleParagraph
            array(
                'name' => 'AAWEditor_addArticleParagraph',
                'paramChains' => array(
                    'AAWEditor/addArticleParagraph' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarAddArticleParagraphAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'AAWEditor_editArticleParagraph',
                'paramChains' => array(
                    'AAWEditor/editArticleParagraph' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarEditArticleParagraphAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'AAWEditor_removeArticleParagraph',
                'paramChains' => array(
                    'AAWEditor/removeArticleParagraph' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarRemoveArticleParagraphAction',
                'permission' => 'viewSiteHelperContent'
            ),
            // ArticleColumn
            array(
                'name' => 'AAWEditor_addArticleColumn',
                'paramChains' => array(
                    'AAWEditor/addArticleColumn' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarAddArticleColumnAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'AAWEditor_editArticleColumn',
                'paramChains' => array(
                    'AAWEditor/editArticleColumn' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarEditArticleColumnAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'AAWEditor_removeArticleColumn',
                'paramChains' => array(
                    'AAWEditor/removeArticleColumn' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarRemoveArticleColumnAction',
                'permission' => 'viewSiteHelperContent'
            ),
            // ArticleBlock
            array(
                'name' => 'AAWEditor_addArticleBlock',
                'paramChains' => array(
                    'AAWEditor/addArticleBlock' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarAddArticleBlockAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'AAWEditor_editArticleBlock',
                'paramChains' => array(
                    'AAWEditor/editArticleBlock' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarEditArticleBlockAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'AAWEditor_removeArticleBlock',
                'paramChains' => array(
                    'AAWEditor/removeArticleBlock' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarRemoveArticleBlockAction',
                'permission' => 'viewSiteHelperContent'
            ),
            // ArticleUnit
            array(
                'name' => 'AAWEditor_addArticleUnit',
                'paramChains' => array(
                    'AAWEditor/addArticleUnit' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarAddArticleUnitAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'AAWEditor_editArticleUnit',
                'paramChains' => array(
                    'AAWEditor/editArticleUnit' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarEditArticleUnitAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'AAWEditor_removeArticleUnit',
                'paramChains' => array(
                    'AAWEditor/removeArticleUnit' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarRemoveArticleUnitAction',
                'permission' => 'viewSiteHelperContent'
            ),
            // ArticleText
            array(
                'name' => 'AAWEditor_addArticleText',
                'paramChains' => array(
                    'AAWEditor/addArticleText' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarAddArticleTextAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'AAWEditor_editArticleText',
                'paramChains' => array(
                    'AAWEditor/editArticleText' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarEditArticleTextAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'AAWEditor_removeArticleText',
                'paramChains' => array(
                    'AAWEditor/removeArticleText' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarRemoveArticleTextAction',
                'permission' => 'viewSiteHelperContent'
            ),
            // ArticleImage
            array(
                'name' => 'AAWEditor_addArticleImage',
                'paramChains' => array(
                    'AAWEditor/addArticleImage' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarAddArticleImageAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'AAWEditor_editArticleImage',
                'paramChains' => array(
                    'AAWEditor/editArticleImage' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarEditArticleImageAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'AAWEditor_uploadArticleImage',
                'paramChains' => array(
                    'AAWEditor/uploadArticleImage' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarUploadArticleImageAction',
                'permission' => 'viewSiteHelperContent'
            ),
            array(
                'name' => 'AAWEditor_removeArticleImage',
                'paramChains' => array(
                    'AAWEditor/removeArticleImage' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/AdvancedArticleWidgetController',
                'action' => 'aawToolbarRemoveArticleImageAction',
                'permission' => 'viewSiteHelperContent'
            )
        );
    }
}
