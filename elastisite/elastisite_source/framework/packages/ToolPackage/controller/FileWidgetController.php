<?php
namespace framework\packages\ToolPackage\controller;

use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\packages\ToolPackage\repository\FileRepository;
use framework\packages\ToolPackage\service\Uploader;

class FileWidgetController extends WidgetController
{
    /**
    * Route: [name: admin_files_widget, paramChain: /admin/files/widget]
    */
    public function adminAttachmentsWidgetAction()
    {
        $viewPath = 'framework/packages/ToolPackage/view/widget/AdminAttachmentsWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminAttachmentsWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_attachment_edit, paramChain: /admin/attachment/edit]
    */
    public function adminAttachmentEditAction()
    {

    }
}
