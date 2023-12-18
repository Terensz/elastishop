<?php
namespace framework\packages\ToolPackage\routeMap;

class ImageRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_uploads_images',
                'paramChains' => array(
                    'admin/uploads/images' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/ImageController',
                'action' => 'adminUploadsImagesAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.uploads.images',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'widgetChanges' => array(
                    'mainContent' => 'ToolPackage/view/widget/AdminImagesWidget'
                )
            ),
            array(
                'name' => 'upload_image',
                'paramChains' => array(
                    'upload/image' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/ImageAccessoryController',
                'action' => 'uploadImageAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'image',
                'paramChains' => array(
                    'image/{fileName}' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/ImageAccessoryController',
                'action' => 'showUploadedImageAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'background_image_big',
                'paramChains' => array(
                    'image/background/big/{backgroundEngine}/{imageFileName}' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/ImageAccessoryController',
                'action' => 'showBackgroundImageBigAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'background_image_thumbnail',
                'paramChains' => array(
                    'image/background/thumbnail/{thumbnailFileName}' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/ImageAccessoryController',
                'action' => 'showBackgroundImageThumbnailAction',
                'permission' => 'viewGuestContent'
            ),
            // array(
            //     'name' => 'favicon',
            //     'paramChains' => array(
            //         'favicon/{fileName}' => 'default'
            //     ),
            //     'controller' => 'framework/packages/ToolPackage/controller/ImageWidgetController',
            //     'action' => 'faviconAction',
            //     'permission' => 'viewGuestContent'
            // ),
            array(
                'name' => 'project_image',
                'paramChains' => array(
                    'image/{type}/{imageId}' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/ImageAccessoryController',
                'action' => 'showProjectImageAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'admin_images_widget',
                'paramChains' => array(
                    'admin/images/widget' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/ImageWidgetController',
                'action' => 'adminImagesWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_image_search',
                'paramChains' => array(
                    'admin/image/search' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/ImageWidgetController',
                'action' => 'adminImageSearchAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_image_edit',
                'paramChains' => array(
                    'admin/image/edit' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/ImageWidgetController',
                'action' => 'adminImageEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_image_delete',
                'paramChains' => array(
                    'admin/image/delete' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/ImageWidgetController',
                'action' => 'adminImageDeleteAction',
                'permission' => 'viewProjectAdminContent'
            )
        );
    }
}
