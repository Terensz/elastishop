<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\core\WidgetResponse;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;

class WebshopResponseAssembler_SideCartSummary extends Service
{
    public static function assembleResponse($processedRequestData = null)
    {
        $viewParams = [
            // 'sideCartData' => $sideCartData,
        ];

        $viewPath = 'framework/packages/WebshopPackage/view/Sections/Checkout/SideCartSummary.php';
        $view = ViewRenderer::renderWidget('WebshopPackage_SideCartSummary', $viewPath, $viewParams);

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