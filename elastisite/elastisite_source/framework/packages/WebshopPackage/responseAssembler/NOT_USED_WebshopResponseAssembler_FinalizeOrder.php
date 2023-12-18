<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\core\WidgetResponse;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;

class WebshopResponseAssembler_FinalizeOrder extends Service
{
    public static function assembleResponse($processedRequestData = null)
    {
        $viewParams = [];

        $viewPath = 'framework/packages/WebshopPackage/view/Parts/Categories/Categories.php';
        $view = ViewRenderer::renderWidget('WebshopPackage_FinalizeOrder', $viewPath, $viewParams);

        return [
            'view' => $view,
            'data' => [
            ]
        ];

        // $response = [
        //     'view' => $view,
        //     'data' => [
        //         // 'closeModal' => $form->isValid() ? true : false
        //     ]
        // ];

        // return WidgetResponse::create($response);
    }
}