<?php
namespace projects\elastishop\menu;

class AdminMenuSection
{
    public function getConfig()
    {
        return [
            'title' => 'elastisite.administration',
            'items' => [
                // [
                //     'routeName' => 'admin_webshop_config',
                //     'paramChain' => 'admin/webshop/config',
                //     'title' => 'admin.webshop.config'
                // ],
            ]
        ];
    }
}