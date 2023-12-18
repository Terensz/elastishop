<?php
namespace framework\packages\ToolPackage\routeMap;

class FileRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'admin_uploads_attachments',
                'paramChains' => array(
                    'admin/uploads/attachments' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/FileController',
                'action' => 'adminUploadsFilesAction',
                'permission' => 'viewProjectAdminContent',
                'title' => 'admin.uploads.images',
                'structure' => 'FrameworkPackage/view/structure/admin',
                'widgetChanges' => array(
                    'mainContent' => 'ToolPackage/view/widget/AdminAttachmentsWidget'
                )
            ),
            array(
                'name' => 'admin_attachments_widget',
                'paramChains' => array(
                    'admin/attachments/widget' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/FileWidgetController',
                'action' => 'adminAttachmentsWidgetAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'admin_attachment_edit',
                'paramChains' => array(
                    'admin/attachment/edit' => 'default'
                ),
                'controller' => 'framework/packages/ToolPackage/controller/FileWidgetController',
                'action' => 'adminAttachmentEditAction',
                'permission' => 'viewProjectAdminContent'
            )
            // array(
            //     'name' => 'admin_attachment_delete',
            //     'paramChains' => array(
            //         'admin/attachment/delete' => 'default'
            //     ),
            //     'controller' => 'framework/packages/ToolPackage/controller/FileWidgetController',
            //     'action' => 'adminAttachmentDeleteAction',
            //     'permission' => 'viewProjectAdminContent'
            // )
        );
    }
}
