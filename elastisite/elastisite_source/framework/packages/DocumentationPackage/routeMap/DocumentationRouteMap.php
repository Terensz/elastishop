<?php
namespace framework\packages\DocumentationPackage\routeMap;

class DocumentationRouteMap
{
    public static function get()
    {
        // $frameworkDocumentationPermission = 'viewGuestContent';
        // $administratorsDocumentationPermission = 'viewGuestContent';
        // $supportDocumentationPermission = 'viewGuestContent';
        return array(
            array(
                'name' => 'elastisite_documentation_index',
                'paramChains' => array(
                    'documentation' => 'default'
                ),
                'controller' => 'framework/packages/DocumentationPackage/controller/DocumentationPageController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'documentation.main',
                'structure' => 'FrameworkPackage/view/structure/documentation'
            ),
            array(
                'name' => 'elastisite_documentation_document',
                'paramChains' => array(
                    'documentation/{category}/{slug}' => 'default'
                ),
                'controller' => 'framework/packages/DocumentationPackage/controller/DocumentationPageController',
                'action' => 'standardAction',
                'permission' => 'viewGuestContent',
                'title' => 'documentation',
                'structure' => 'FrameworkPackage/view/structure/documentation'
            ),
            array(
                'name' => 'widget_DocumentationSubmenuWidget',
                'paramChains' => array(
                    'widget/DocumentationSubmenuWidget' => 'default'
                ),
                'controller' => 'framework/packages/DocumentationPackage/controller/DocumentationWidgetController',
                'action' => 'documentationSubmenuWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'widget_DocumentationContentWidget',
                'paramChains' => array(
                    'widget/DocumentationContentWidget' => 'default'
                ),
                'controller' => 'framework/packages/DocumentationPackage/controller/DocumentationWidgetController',
                'action' => 'documentationContentWidgetAction',
                'permission' => 'viewGuestContent'
            ),
            array(
                'name' => 'documentation_image',
                'paramChains' => array(
                    'documentation/image/docImage/{fileName}' => 'default'
                ),
                'controller' => 'framework/packages/DocumentationPackage/controller/DocumentationAccessoryController',
                'action' => 'documentationImageAction',
                'permission' => 'viewGuestContent'
            ),
        );
    }
}
