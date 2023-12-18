<?php
namespace projects\ASC\menu;

class AdminMenuSection
{
    public function getConfig()
    {
        return [
            'title' => 'asc.administration',
            'items' => [
                [
                    'routeName' => 'admin_ascSubscriptionOffers',
                    'paramChain' => 'admin/ascSubscriptionOffers',
                    'title' => 'admin.asc.subscription.offers'
                ],
                [
                    'routeName' => 'admin_ascScaleList',
                    'paramChain' => 'admin/ascScaleList',
                    'title' => 'admin.asc.sample.scales'
                ],
                [
                    'routeName' => 'admin_webshop_products',
                    'paramChain' => 'admin/webshop/products',
                    'title' => 'admin.webshop.products'
                ],
                // [
                //     'routeName' => 'admin_webshop_storages',
                //     'paramChain' => 'admin/webshop/storages',
                //     'title' => 'admin.webshop.storages'
                // ],
                // [
                //     'routeName' => 'admin_webshop_inwardProcessing',
                //     'paramChain' => 'admin/webshop/inward_processing',
                //     'title' => 'admin.webshop.inward.processing'
                // ],
                // [
                //     'routeName' => 'admin_webshop_stock',
                //     'paramChain' => 'admin/webshop/stock',
                //     'title' => 'admin.webshop.stock'
                // ],
                // [
                //     'routeName' => 'admin_webshop_discounts',
                //     'paramChain' => 'admin/webshop/discounts',
                //     'title' => 'admin.webshop.discounts'
                // ],
                // [
                //     'routeName' => 'admin_webshop_runningOrders',
                //     'paramChain' => 'admin/webshop/runningOrders',
                //     'title' => 'admin.webshop.running.orders'
                // ],
                // [
                //     'routeName' => 'admin_webshop_shipments',
                //     'paramChain' => 'admin/webshop/shipments',
                //     'title' => 'admin.webshop.orders'
                // ],
                // [
                //     'routeName' => 'admin_webshop_statistics',
                //     'paramChain' => 'admin/webshop/statistics',
                //     'title' => 'admin.webshop.statistics'
                // ]
            ]
        ];
    }
}