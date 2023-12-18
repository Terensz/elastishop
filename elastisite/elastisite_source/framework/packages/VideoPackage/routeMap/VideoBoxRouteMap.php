<?php
namespace framework\packages\VideoPackage\routeMap;

class VideoBoxRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'videoBox_VideoBoxWidget',
                'paramChains' => array(
                    'videoBox/VideoBoxWidget' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoWidgetController',
                'action' => 'videoBoxWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'videoPlayer_VideoPlayerWidget',
                'paramChains' => array(
                    'videoPlayer/VideoPlayerWidget' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoWidgetController',
                'action' => 'videoPlayerWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'videoPlayer_play',
                'paramChains' => array(
                    'videoPlayer/play/{fileName}' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoAccessoryController',
                'action' => 'playVideoAction',
                'permission' => 'viewGuestContent'
            )
        );
    }
}
