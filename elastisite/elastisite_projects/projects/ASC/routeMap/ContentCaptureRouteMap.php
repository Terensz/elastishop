<?php
namespace projects\ASC\routeMap;

class ContentCaptureRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'contentCapture_1',
                'paramChains' => array(
                    'contentCapture/1' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'content.capture',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/ContentCaptureWidget'
                )
            ),
            array(
                'name' => 'contentCapture_2',
                'paramChains' => array(
                    'contentCapture/2' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'content.capture',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'projects/ASC/view/widget/ContentCaptureWidget'
                )
            ),
            array(
                'name' => 'widget_ContentCaptureWidget',
                'paramChains' => array(
                    'widget/ContentCaptureWidget' => 'default'
                ),
                'controller' => 'projects/ASC/controller/ContentCaptureWidgetController',
                'action' => 'contentCaptureWidgetAction',
                'permission' => 'viewGuestContent'
            )
        );
    }
}
