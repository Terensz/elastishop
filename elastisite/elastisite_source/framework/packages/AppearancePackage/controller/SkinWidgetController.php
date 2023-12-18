<?php
namespace framework\packages\AppearancePackage\controller;

use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\component\parent\ImageResponse;
use framework\packages\ToolPackage\service\Uploader;

class SkinWidgetController extends WidgetController
{
    /**
    * name: admin_skins_widget, paramChain: /admin/skins/widget
    */
    public function adminSkinsWidgetAction()
    {
        $viewPath = 'framework/packages/AppearancePackage/view/widget/AdminSkinsWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminSkinsWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }
}
