<?php
namespace framework\packages\NewsletterPackage\routeMap;

class NewsletterRouteMap
{
    public static function get()
    {
        return array(
            /**
             * Newsletters
            */
            array(
                'name' => 'admin_newsletters',
                'paramChains' => array(
                    'admin/newsletters' => 'default'
                ),
                'controller' => 'projects/ElastiShop/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.newsletters',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'NewsletterPackage/view/widget/AdminNewslettersWidget'
                )
            ),
            array(
                'name' => 'admin_newsletters_widget',
                'paramChains' => array(
                    'admin/newsletters/widget' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterWidgetController',
                'action' => 'adminNewslettersWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_newsletters_list',
                'paramChains' => array(
                    'admin/newsletters/list' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterWidgetController',
                'action' => 'adminNewslettersListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_newsletter_new',
                'paramChains' => array(
                    'admin/newsletter/new' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterWidgetController',
                'action' => 'adminNewsletterNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_newsletter_edit',
                'paramChains' => array(
                    'admin/newsletter/edit' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterWidgetController',
                'action' => 'adminNewsletterEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_newsletter_delete',
                'paramChains' => array(
                    'admin/newsletter/delete' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterWidgetController',
                'action' => 'adminNewsletterDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),

            /**
             * Campaigns
            */
            array(
                'name' => 'admin_newsletter_campaigns',
                'paramChains' => array(
                    'admin/newsletter/campaigns' => 'default'
                ),
                'controller' => 'projects/ElastiShop/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.newsletter.campaigns',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'NewsletterPackage/view/widget/AdminNewsletterCampaignsWidget'
                )
            ),
            array(
                'name' => 'admin_newsletter_campaigns_widget',
                'paramChains' => array(
                    'admin/newsletter/campaigns/widget' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterCampaignWidgetController',
                'action' => 'adminNewsletterCampaignsWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_newsletter_campaigns_list',
                'paramChains' => array(
                    'admin/newsletter/campaigns/list' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterCampaignWidgetController',
                'action' => 'adminNewsletterCampaignsListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_newsletter_campaign_new',
                'paramChains' => array(
                    'admin/newsletter/campaign/new' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterCampaignWidgetController',
                'action' => 'adminNewsletterCampaignNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_newsletter_campaign_edit',
                'paramChains' => array(
                    'admin/newsletter/campaign/edit' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterCampaignWidgetController',
                'action' => 'adminNewsletterCampaignEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_newsletter_campaign_delete',
                'paramChains' => array(
                    'admin/newsletter/campaign/delete' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterCampaignWidgetController',
                'action' => 'adminNewsletterCampaignDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),

            /**
             * Dispatching
            */
            array(
                'name' => 'admin_newsletter_dispatchProcesses',
                'paramChains' => array(
                    'admin/newsletter/dispatchProcesses' => 'default'
                ),
                'controller' => 'projects/ElastiShop/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.newsletter.dispatch.processes',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'NewsletterPackage/view/widget/AdminNewsletterDispatchProcessesWidget'
                )
            ),
            array(
                'name' => 'admin_newsletter_dispatchProcesses_widget',
                'paramChains' => array(
                    'admin/newsletter/dispatchProcesses/widget' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterDispatchingWidgetController',
                'action' => 'adminNewsletterDispatchProcessesWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_newsletter_dispatchProcesses_list',
                'paramChains' => array(
                    'admin/newsletter/dispatchProcesses/list' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterDispatchingWidgetController',
                'action' => 'adminNewsletterDispatchProcessesListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_newsletter_dispatchProcess_new',
                'paramChains' => array(
                    'admin/newsletter/dispatchProcess/new' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterDispatchingWidgetController',
                'action' => 'adminNewsletterDispatchProcessNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_newsletter_dispatchProcess_edit',
                'paramChains' => array(
                    'admin/newsletter/dispatchProcess/edit' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterDispatchingWidgetController',
                'action' => 'adminNewsletterDispatchProcessEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_newsletter_dispatchProcess_delete',
                'paramChains' => array(
                    'admin/newsletter/dispatchProcess/delete' => 'default'
                ),
                'controller' => 'framework/packages/NewsletterPackage/controller/NewsletterDispatchingWidgetController',
                'action' => 'adminNewsletterDispatchProcessDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),



            array(
                'name' => 'admin_newsletter_processSending',
                'paramChains' => array(
                    'admin/newsletter/processSending' => 'default'
                ),
                'controller' => 'projects/ElastiShop/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.newsletter.process.sending',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'NewsletterPackage/view/widget/AdminNewsletterProcessSendingWidget'
                )
            ),
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