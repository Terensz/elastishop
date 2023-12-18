<?php
namespace framework\packages\HintPackage\routeMap;

class HintRouteMap
{
    public static function get()
    {
        return array(
            // array(
            //     'name' => 'admin_hints',
            //     'paramChains' => array(
            //         'admin/hints' => 'default'
            //     ),
            //     'controller' => 'projects/ElastiShop/controller/BasicController',
            //     'action' => 'standardAction',
            //     'permission' => 'viewProjectAdminContent',
            //     'title' => 'admin.newsletters',
            //     'structure' => 'FrameworkPackage/view/structure/admin',
            //     'skinName' => 'Basic',
            //     'backgroundEngine' => 'Simple',
            //     'backgroundTheme' => 'empty',
            //     'widgetChanges' => array(
            //         'mainContent' => 'NewsletterPackage/view/widget/AdminHintsWidget'
            //     )
            // ),
            // array(
            //     'name' => 'admin_hints_widget',
            //     'paramChains' => array(
            //         'admin/hints/widget' => 'default'
            //     ),
            //     'controller' => 'framework/packages/NewsletterPackage/controller/HintsWidgetController',
            //     'action' => 'adminNewslettersWidgetAction',
            //     'permission' => 'viewProjectAdminContent'
            // ),
            array(
                'name' => 'admin_newsletter_processSending_widget',
                'paramChains' => array(
                    'admin/newsletter/processSending/widget' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterDispatchingWidgetController',
                'action' => 'adminNewsletterProcessSendingWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
        );
    }
}