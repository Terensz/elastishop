<?php
namespace framework\packages\SiteBuilderPackage\routeMap;

class ContentEditorRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'widget_ContentEditorWidget',
                'paramChains' => array(
                    'widget/ContentEditorWidget' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'ContentEditorWidget_editor_reload',
                'paramChains' => array(
                    'ContentEditorWidget/editor/reload' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetEditorReloadAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'ContentEditorWidget_editor_updateContentEditorUnitCasePosition',
                'paramChains' => array(
                    'ContentEditorWidget/editor/updateContentEditorUnitCasePosition' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetEditorUpdateContentEditorUnitCasePositionAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'ContentEditorWidget_editor_uploadContentEditorBackgroundImage',
                'paramChains' => array(
                    'ContentEditorWidget/editor/uploadContentEditorBackgroundImage' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetEditorUploadContentEditorBackgroundImageAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'contentEditor_showBackgroundImage',
                'paramChains' => array(
                    'contentEditor/showBackgroundImage/{contentEditorId}' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorImageController',
                'action' => 'showContentEditorBackgroundImageAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'ContentEditorWidget_editor_addContentEditorUnit',
                'paramChains' => array(
                    'ContentEditorWidget/editor/addContentEditorUnit' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetEditorAddContentEditorUnitAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'ContentEditorWidget_editor_addContentEditorUnitCase',
                'paramChains' => array(
                    'ContentEditorWidget/editor/addContentEditorUnitCase' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetEditorAddContentEditorUnitCaseAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'ContentEditorWidget_editor_editContentEditorUnit',
                'paramChains' => array(
                    'ContentEditorWidget/editor/editContentEditorUnit' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetEditorEditContentEditorUnitAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'ContentEditorWidget_editor_editContentEditorUnit_form',
                'paramChains' => array(
                    'ContentEditorWidget/editor/editContentEditorUnit/form' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetEditorEditContentEditorUnitFormAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'ContentEditorWidget_editor_editContentEditorUnitCase',
                'paramChains' => array(
                    'ContentEditorWidget/editor/editContentEditorUnitCase' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetEditorEditContentEditorUnitCaseAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'ContentEditorWidget_editor_editContentEditorUnitCase_form',
                'paramChains' => array(
                    'ContentEditorWidget/editor/editContentEditorUnitCase/form' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetEditorEditContentEditorUnitCaseFormAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'ContentEditorWidget_editor_removeContentEditorUnit',
                'paramChains' => array(
                    'ContentEditorWidget/editor/removeContentEditorUnit' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetEditorRemoveContentEditorUnitAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'ContentEditorWidget_editor_removeContentEditorUnitCase',
                'paramChains' => array(
                    'ContentEditorWidget/editor/removeContentEditorUnitCase' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetEditorRemoveContentEditorUnitCaseAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'ContentEditorWidget_editor_sortContentEditorUnitCases',
                'paramChains' => array(
                    'ContentEditorWidget/editor/sortContentEditorUnitCases' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetEditorSortContentEditorUnitCasesAction',
                'permission' => 'viewProjectAdminContent'
            ),
            array(
                'name' => 'ContentEditorWidget_editor_sortContentEditorUnits',
                'paramChains' => array(
                    'ContentEditorWidget/editor/sortContentEditorUnits' => 'default'
                ),
                'controller' => 'framework/packages/SiteBuilderPackage/controller/ContentEditorWidgetController',
                'action' => 'contentEditorWidgetEditorSortContentEditorUnitsAction',
                'permission' => 'viewProjectAdminContent'
            )
        );
    }
}
