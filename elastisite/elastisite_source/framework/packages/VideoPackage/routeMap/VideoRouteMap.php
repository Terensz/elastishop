<?php
namespace framework\packages\VideoPackage\routeMap;

class VideoRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_videos',
                'paramChains' => array(
                    'admin/videos' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.videos',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'AppearancePackage/view/widget/AdminVideosWidget'
                )
                // 'pageSwitchBehavior' => array(
                //     'ElastiSiteBannerWidget' => 'keep'
                // )
            ),
            array(
                'name' => 'admin_videos_widget',
                'paramChains' => array(
                    'admin/videos/widget' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoWidgetController',
                'action' => 'adminVideosWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_video_new',
                'paramChains' => array(
                    'admin/video/new' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoWidgetController',
                'action' => 'adminVideoNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_video_edit',
                'paramChains' => array(
                    'admin/video/edit' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoWidgetController',
                'action' => 'adminVideoEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_video_editForm',
                'paramChains' => array(
                    'admin/video/editForm' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoWidgetController',
                'action' => 'adminVideoEditFormAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_video_upload',
                'paramChains' => array(
                    'admin/video/upload' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoWidgetController',
                'action' => 'adminVideoUploadAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_video_delete',
                'paramChains' => array(
                    'admin/video/delete' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoWidgetController',
                'action' => 'adminVideoDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_video_unbindFile',
                'paramChains' => array(
                    'admin/video/unbindFile' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoWidgetController',
                'action' => 'adminVideoUnbindFileAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_video_bindFile',
                'paramChains' => array(
                    'admin/video/bindFile' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoWidgetController',
                'action' => 'adminVideoBindFileAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_handleVideos',
                'paramChains' => array(
                    'admin/handleVideos' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/FrameworkPageController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.handle.videos',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'AppearancePackage/view/widget/AdminHandleVideosWidget'
                )
            ),
            array(
                'name' => 'admin_handleVideos_vidget',
                'paramChains' => array(
                    'admin/handleVideos/widget' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoWidgetController',
                'action' => 'adminHandleVideosWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_handleVideos_selectVisitorVideo',
                'paramChains' => array(
                    'admin/handleVideos/selectVisitorVideo' => 'default'
                ),
                'controller' => 'framework/packages/VideoPackage/controller/VideoWidgetController',
                'action' => 'adminHandleVideosSelectVisitorVideoAction',
                'permission' => 'viewProjectAdminContent'
            )
            // array(
            //     'name' => 'admin_openGraph_new',
            //     'paramChains' => array(
            //         'admin/openGraph/new' => 'default'
            //     ),
            //     'controller' => 'framework/packages/AppearancePackage/controller/OpenGraphWidgetController',
            //     'action' => 'adminOpenGraphNewAction',
            //     'permission' => 'viewProjectAdminContent'
            // ),
            // array(
            //     'name' => 'admin_openGraph_delete',
            //     'paramChains' => array(
            //         'admin/openGraph/delete' => 'default'
            //     ),
            //     'controller' => 'framework/packages/AppearancePackage/controller/OpenGraphWidgetController',
            //     'action' => 'adminOpenGraphDeleteAction',
            //     'permission' => 'viewProjectAdminContent'
            // )
        );
    }
}
