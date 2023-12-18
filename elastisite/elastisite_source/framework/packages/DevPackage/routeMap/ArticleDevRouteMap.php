<?php
namespace framework\packages\DevPackage\routeMap;

class ArticleDevRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'article_dev_add',
                'paramChains' => array(
                    'dev/add' => 'default'
                ),
                'controller' => 'framework/packages/DevPackage/controller/ArticleDevController',
                'action' => 'articleDevAddAction',
                'permission' => 'viewGuestContent'
            )
        );
    }
}
