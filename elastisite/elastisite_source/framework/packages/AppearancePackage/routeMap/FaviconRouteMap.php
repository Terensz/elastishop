<?php
namespace framework\packages\AppearancePackage\routeMap;

class FaviconRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'accessory_favicon',
                'paramChains' => array(
                    'accessory/favicon' => 'default'
                ),
                'controller' => 'framework/packages/AppearancePackage/controller/FaviconAccessoryController',
                'action' => 'faviconAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'accessory_favicon_random',
                'paramChains' => array(
                    'accessory/favicon/{random}' => 'default'
                ),
                'controller' => 'framework/packages/AppearancePackage/controller/FaviconAccessoryController',
                'action' => 'faviconRandomAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'admin_favicon',
                'paramChains' => array(
                    'admin/favicon' => 'default'
                ),
                'controller' => 'framework/packages/AppearancePackage/controller/AppearanceController',
                'action' => 'adminFaviconAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.favicon',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'AppearancePackage/view/widget/AdminFaviconWidget'
                )
            ),
            array(
                'name' => 'admin_favicon_widget',
                'paramChains' => array(
                    'admin/favicon/widget' => 'default'
                ),
                'controller' => 'framework/packages/AppearancePackage/controller/FaviconWidgetController',
                'action' => 'adminFaviconWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_favicon_uploadModal',
                'paramChains' => array(
                    'admin/favicon/uploadModal' => 'default'
                ),
                'controller' => 'framework/packages/AppearancePackage/controller/FaviconWidgetController',
                'action' => 'adminFaviconUploadModalAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_favicon_upload',
                'paramChains' => array(
                    'admin/favicon/upload' => 'default'
                ),
                'controller' => 'framework/packages/AppearancePackage/controller/FaviconWidgetController',
                'action' => 'adminFaviconUploadAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_favicon_cropModal',
                'paramChains' => array(
                    'admin/favicon/cropModal' => 'default'
                ),
                'controller' => 'framework/packages/AppearancePackage/controller/FaviconWidgetController',
                'action' => 'adminFaviconCropModalAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_favicon_uploadCroppedCanvas',
                'paramChains' => array(
                    'admin/favicon/uploadCroppedCanvas' => 'default'
                ),
                'controller' => 'framework/packages/AppearancePackage/controller/FaviconWidgetController',
                'action' => 'adminFaviconUploadCroppedCanvasAction',
                'permission' => 'viewProjectAdminContent'
            )
        );
    }
}
