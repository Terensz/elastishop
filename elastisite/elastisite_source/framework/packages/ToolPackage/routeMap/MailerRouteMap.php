<?php
namespace framework\packages\ToolPackage\routeMap;

class MailerRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_mailer_test',
                'paramChains' => array(
                    'admin/mailer/test' => 'default'
                ),
                'controller' => 'projects/ElastiShop/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewSystemAdminContent',
                'title' => 'admin.mailer.test',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'ToolPackage/view/widget/AdminMailerTestWidget'
                )
            ),
            array(
                'name' => 'admin_mailerTest_widget',
                'paramChains' => array(
                    'admin/mailerTest/widget' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/MailerWidgetController',
                'action' => 'adminMailerTestWidgetAction',
                'permission' => 'viewSystemAdminContent'
            ),
            array(
                'name' => 'admin_mails',
                'paramChains' => array(
                    'admin/mails' => 'default'
                ),
                'controller' => 'projects/ElastiShop/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.webshop.mails',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'WebshopPackage/view/widget/AdminMailsWidget'
                )
            ),
            array(
                'name' => 'admin_mails_widget',
                'paramChains' => array(
                    'admin/mails/widget' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/MailerWidgetController',
                'action' => 'adminMailsWidgetAction',
                'permission' => 'viewProjectAdminContent'
            )
        );
    }
}
