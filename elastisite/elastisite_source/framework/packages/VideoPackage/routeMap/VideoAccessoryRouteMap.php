<?php
namespace framework\packages\VideoPackage\routeMap;

class VideoAccessoryRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'video_preview',
                'paramChains' => array(
                    'video/preview/{fileName}' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoAccessoryController',
                'action' => 'videoPreviewWidgetAction',
                'permission' => 'viewGuestContent'
            )
        );
    }
}
