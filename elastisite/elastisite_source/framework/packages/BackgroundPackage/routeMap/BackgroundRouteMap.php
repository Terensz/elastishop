<?php
namespace framework\packages\BackgroundPackage\routeMap;

class BackgroundRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'background_slidingStripes',
                'paramChains' => array(
                    'background/SlidingStripes/{theme}' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/SlidingStripesController',
                'action' => 'slidingStripesAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'background_slidingStripes_stripe',
                'paramChains' => array(
                    'background/SlidingStripes/{theme}/{stripeId}' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/SlidingStripesController',
                'action' => 'stripeAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'background_simple',
                'paramChains' => array(
                    'background/Simple/{theme}' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/SimpleController',
                'action' => 'simpleAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'background_image_simple',
                'paramChains' => array(
                    'background/image/Simple/{fileName}' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/SimpleController',
                'action' => 'simpleImageAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'admin_background_bindings',
                'paramChains' => array(
                    'admin/background/bindings' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/BackgroundController',
                'action' => 'adminBackgroundBindingsAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.background.bindings',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'BackgroundPackage/view/widget/AdminBgBindingsWidget'
                )
            ),
            array(
                'name' => 'admin_background_bindings_widget',
                'paramChains' => array(
                    'admin/background/bindings/widget' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/BackgroundWidgetController',
                'action' => 'adminBgBindingsWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_background_binding_edit',
                'paramChains' => array(
                    'admin/background/binding/edit' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/BackgroundWidgetController',
                'action' => 'adminBackgroundBindingEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_background_binding_delete',
                'paramChains' => array(
                    'admin/background/binding/delete' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/BackgroundWidgetController',
                'action' => 'adminBackgroundBindingDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_backgrounds',
                'paramChains' => array(
                    'admin/backgrounds' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/BackgroundController',
                'action' => 'adminBackgroundsAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.list.backgrounds',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'widgetChanges' => array(
                    'mainContent' => 'BackgroundPackage/view/widget/AdminBackgroundsWidget'
                )
            ),
            array(
                'name' => 'admin_backgrounds_widget',
                'paramChains' => array(
                    'admin/backgrounds/widget' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/BackgroundWidgetController',
                'action' => 'adminBackgroundsWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_background_new',
                'paramChains' => array(
                    'admin/background/new' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/BackgroundWidgetController',
                'action' => 'adminBackgroundNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_background_reset',
                'paramChains' => array(
                    'admin/background/reset' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/BackgroundWidgetController',
                'action' => 'adminBackgroundResetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_background_save',
                'paramChains' => array(
                    'admin/background/save' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/BackgroundWidgetController',
                'action' => 'adminBackgroundSaveAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_background_delete',
                'paramChains' => array(
                    'admin/background/delete' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/BackgroundWidgetController',
                'action' => 'adminBackgroundDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_background_rawImage_upload',
                'paramChains' => array(
                    'admin/background/rawImage/upload' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/BackgroundWidgetController',
                'action' => 'adminBackgroundRawImageUploadAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_rawBgImage',
                'paramChains' => array(
                    'admin/rawBgImage/{imageId}' => 'default'
                ),
                'controller' => 'framework/packages/BackgroundPackage/controller/BackgroundAccessoryController',
                'action' => 'adminRawBgImageAction',
                'permission' => 'viewProjectAdminContent'
            )
        );
    }
}
