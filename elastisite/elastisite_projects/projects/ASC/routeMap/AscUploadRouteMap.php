<?php
namespace projects\ASC\routeMap;

class AscUploadRouteMap
{
    public static function get()
    {
        return array(
            array(
                'name' => 'asc_upload',
                'paramChains' => array(
                    'asc/upload' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscUploadController',
                'action' => 'ascUploadAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_upload_previewBar',
                'paramChains' => array(
                    'asc/upload/previewBar' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscUploadController',
                'action' => 'ascUploadPreviewBarAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_unitImage',
                'paramChains' => array(
                    'asc/unitImage/{sizeType}/{code}' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscImageController',
                'action' => 'ascUnitImageAction',
                'permission' => 'viewUserContent'
            ),
            array(
                'name' => 'asc_upload_delete',
                'paramChains' => array(
                    'asc/upload/delete' => 'default'
                ),
                'controller' => 'projects/ASC/controller/AscUploadController',
                'action' => 'ascUploadDeleteAction',
                'permission' => 'viewUserContent'
            )
        );
    }
}