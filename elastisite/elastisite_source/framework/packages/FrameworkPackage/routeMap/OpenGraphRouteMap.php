<?php
namespace framework\packages\FrameworkPackage\routeMap;

class OpenGraphRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_openGraphs',
                'paramChains' => array(
                    'admin/openGraphs' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/BasicController',
                'action' => 'standardAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.open.graph',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'skinName' => 'Basic',
                'backgroundEngine' => 'Simple',
                'backgroundTheme' => 'empty',
                'widgetChanges' => array(
                    'mainContent' => 'FrameworkPackage/view/widget/AdminOpenGraphsWidget'
                )
            ),
            array(
                'name' => 'admin_openGraphs_widget',
                'paramChains' => array(
                    'admin/openGraphs/widget' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphsWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraphs_list',
                'paramChains' => array(
                    'admin/openGraphs/list' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphsListAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraph_edit',
                'paramChains' => array(
                    'admin/openGraph/edit' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphEditAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraph_editForm',
                'paramChains' => array(
                    'admin/openGraph/editForm' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphEditFormAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraph_new',
                'paramChains' => array(
                    'admin/openGraph/new' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraph_delete',
                'paramChains' => array(
                    'admin/openGraph/delete' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'openGraph_image',
                'paramChains' => array(
                    'openGraph/image/{fileName}' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphAccessoryController',
                'action' => 'openGraphImageAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'admin_openGraph_uploadImage',
                'paramChains' => array(
                    'admin/openGraph/uploadImage' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphUploadImageAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraph_unbindImage',
                'paramChains' => array(
                    'admin/openGraph/unbindImage' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphUnbindImageAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraph_bindImage',
                'paramChains' => array(
                    'admin/openGraph/bindImage' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphBindImageAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraph_deleteGalleryImage',
                'paramChains' => array(
                    'admin/openGraph/deleteGalleryImage' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphDeleteGalleryImageAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraph_new',
                'paramChains' => array(
                    'admin/openGraph/new' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphNewAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraph_delete',
                'paramChains' => array(
                    'admin/openGraph/delete' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphDeleteAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraph_imageGallery',
                'paramChains' => array(
                    'admin/openGraph/imageGallery' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphImageGalleryAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraph_selectorGallery',
                'paramChains' => array(
                    'admin/openGraph/selectorGallery' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphSelectorGalleryAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraph_selectingImage',
                'paramChains' => array(
                    'admin/openGraph/selectingImage' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphSelectingImageAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_openGraph_getImageContainer',
                'paramChains' => array(
                    'admin/openGraph/getImageContainer' => 'default'
                ),
                'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphWidgetController',
                'action' => 'adminOpenGraphGetImageContainerAction',
                'permission' => 'viewProjectAdminContent'
            ),
            // array(
            //     'name' => 'openGraph_displayImage',
            //     'paramChains' => array(
            //         'openGraph/displayImage/{imageId}' => 'default'
            //     ),
            //     'controller' => 'framework/packages/FrameworkPackage/controller/OpenGraphAccessoryController',
            //     'action' => 'openGraphDisplayImageAction',
            //     'permission' => 'viewGuestContent'
            // )

        );
    }
}
