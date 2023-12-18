<?php
namespace framework\packages\NewsletterPackage\menu;

class AdminMenuSection
{
    public function getConfig()
    {
        return [
            'title' => 'newsletter.administration',
            'items' => [
                // [
                //     'routeName' => 'admin_newsletters_config',
                //     'paramChain' => 'admin/webshop/config',
                //     'title' => 'admin.newsletters.config'
                // ],
                [
                    'routeName' => 'admin_newsletters',
                    'paramChain' => 'admin/newsletters',
                    'title' => 'admin.newsletters'
                ],
                // [
                //     'routeName' => 'admin_newsletter_campaignContents',
                //     'paramChain' => 'admin/newsletter/campaignContents',
                //     'title' => 'admin.newsletter.campaign.contents'
                // ],
                [
                    'routeName' => 'admin_newsletter_campaigns',
                    'paramChain' => 'admin/newsletter/campaigns',
                    'title' => 'admin.newsletter.campaigns'
                ],
                [
                    'routeName' => 'admin_newsletter_dispatchProcesses',
                    'paramChain' => 'admin/newsletter/dispatchProcesses',
                    'title' => 'admin.newsletter.dispatch.processes'
                ],
                [
                    'routeName' => 'admin_newsletter_processSending',
                    'paramChain' => 'admin/newsletter/processSending',
                    'title' => 'admin.newsletter.process.sending'
                ]
            ],
            'active' => false
        ];
    }
}