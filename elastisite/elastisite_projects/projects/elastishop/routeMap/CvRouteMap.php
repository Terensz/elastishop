<?php
namespace projects\elastishop\routeMap;

class CvRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'cv_PappFerenc',
                'paramChains' => array(
                    'cv/PappFerenc' => 'default'
                ),
                'controller' => 'projects/elastishop/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'inMenu' => 'main',
                'title' => 'Papp Ferenc - CV',
                'structure' => 'FrameworkPackage/view/structure/CVStructure',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ElastiSite/view/widget/CVPappFerencContentWidget',
                    // 'left1' => 'UserPackage/view/widget/CVPappFerencMenuWidget',
                ),
                'pageSwitchBehavior' => array(
                    'UserRegistrationWidget' => 'restore'
                )
            ),
            array(
                'name' => 'cv_PappFerencContentWidget',
                'paramChains' => array(
                    'cv/PappFerencContentWidget' => 'default'
                ),
                'controller' => 'projects/elastishop/controller/CvWidgetController',
                'action' => 'cVPappFerencContentWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'cv_PappFerenc_menuWidget',
                'paramChains' => array(
                    'cv/PappFerencMenuWidget' => 'default'
                ),
                'controller' => 'projects/elastishop/controller/CvWidgetController',
                'action' => 'cVPappFerencMenuWidgetAction',
                'permission' => 'viewGuestContent'
            ),
        );
    }
}