<?php
namespace projects\ASC\routeMap;

class AdminRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_ascSubscriptionOffers',
                'paramChains' => array(
                    'admin/ascSubscriptionOffers' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewSiteAdminContent',
                'title' => 'admin.asc.subscription.offers',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/AdminAscSubscriptionOffersWidget'
                )
            ),
            array(
                'name' => 'admin_ascSubscriptionOffers_widget',
                'paramChains' => array(
                    'admin/ascSubscriptionOffers/widget' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscWidgetController',
                'action' => 'adminAscSubscriptionOffersWidgetAction',
                'permission' => 'viewSiteAdminContent'
            ),
            array(
                'name' => 'admin_ascSubscriptionOffer_new',
                'paramChains' => array(
                    'admin/ascSubscriptionOffer/new' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscWidgetController',
                'action' => 'adminAscSubscriptionOfferNewAction',
                'permission' => 'viewSiteAdminContent'
            ),
            array(
                'name' => 'admin_ascSubscriptionOffer_edit',
                'paramChains' => array(
                    'admin/ascSubscriptionOffer/edit' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscWidgetController',
                'action' => 'adminAscSubscriptionOfferEditAction',
                'permission' => 'viewSiteAdminContent'
            ),

            /**
             * Sample scales
            */
            array(
                'name' => 'admin_ascScaleList',
                'paramChains' => array(
                    'admin/ascScaleList' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewSiteAdminContent',
                'title' => 'admin.asc.sample.scales',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/AscScaleListWidget'
                )
            )
        );
    }
}
