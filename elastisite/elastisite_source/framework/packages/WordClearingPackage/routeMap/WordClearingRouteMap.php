<?php
namespace framework\packages\WordClearingPackage\routeMap;

class WordClearingRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'ajax_wordExplanation',
                'paramChains' => array(
                    'ajax/wordExplanation' => 'default'
                ),
                'controller' => 'framework/packages/WordClearingPackage/controller/WordClearingWidgetController',
                'action' => 'wordExplanationAction',
                'permission' => 'viewGuestContent'
            ),

            array(
                'name' => 'document_wordExplanation',
                'paramChains' => array(
                    'document/wordExplanation' => 'default'
                ),
                'controller' => 'framework/packages/WordClearingPackage/controller/WordClearingController',
                'action' => 'defaultAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'document.word.explanations',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'WordClearingPackage/view/widget/DocumentWordExplanationWidget'
                )
                // 'pageSwitchBehavior' => array(
                //     'ElastiSiteBannerWidget' => 'keep'
                // )
            ),
            array(
                'name' => 'document_wordExplanation_widget',
                'paramChains' => array(
                    'document/wordExplanation/widget' => 'default'
                ),
                'controller' => 'framework/packages/WordClearingPackage/controller/WordClearingWidgetController',
                'action' => 'documentWordExplanationWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_wordExplanation',
                'paramChains' => array(
                    'admin/wordExplanation' => 'default'
                ),
                'controller' => 'framework/packages/WordClearingPackage/controller/WordClearingController',
                'action' => 'defaultAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.word.explanation',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'WordClearingPackage/view/widget/AdminWordExplanationWidget'
                )
                // 'pageSwitchBehavior' => array(
                //     'ElastiSiteBannerWidget' => 'keep'
                // )
            ),
            array(
                'name' => 'admin_wordExplanationWidget',
                'paramChains' => array(
                    'admin/wordExplanationWidget' => 'default'
                ),
                'controller' => 'framework/packages/WordClearingPackage/controller/WordClearingWidgetController',
                'action' => 'adminWordExplanationWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_wordExplanation_new',
                'paramChains' => array(
                    'admin/wordExplanation/new' => 'default'
                ),
                'controller' => 'framework/packages/WordClearingPackage/controller/WordClearingWidgetController',
                'action' => 'adminWordExplanationNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_wordExplanation_edit',
                'paramChains' => array(
                    'admin/wordExplanation/edit' => 'default'
                ),
                'controller' => 'framework/packages/WordClearingPackage/controller/WordClearingWidgetController',
                'action' => 'adminWordExplanationEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_wordExplanation_delete',
                'paramChains' => array(
                    'admin/wordExplanation/delete' => 'default'
                ),
                'controller' => 'framework/packages/WordClearingPackage/controller/WordClearingWidgetController',
                'action' => 'adminWordExplanationDeleteAction',
                'permission' => 'viewProjectAdminContent'
            )
        );
    }
}
