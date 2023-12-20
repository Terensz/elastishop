<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\core\WidgetResponse;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;
use framework\packages\PaymentPackage\service\OnlinePaymentService;

class WebshopResponseAssembler_PaymentModal extends Service
{
    public static function assembleResponse($processedRequestData = null)
    {
        App::getContainer()->wireService('PaymentPackage/service/OnlinePaymentService');
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_ShipmentHandling');
        $viewParams = WebshopResponseAssembler_ShipmentHandling::getShipmentHandlingParams();
        // dump($viewParams);exit;

        // $viewParams['paymentServiceData'] = null;
        $viewParams['paymentService'] = null;
        if ($viewParams['packDataCollection'] && $viewParams['errors']['Summary']['errorsCount'] == 0) {
            $viewParams['paymentService'] = new OnlinePaymentService('Barion', isset($viewParams['packDataCollection'][0]) ? $viewParams['packDataCollection'][0] : null);
        }
        
        if ($viewParams['errors']['Summary']['errorsCount'] > 0) {
            $viewPath = 'framework/packages/WebshopPackage/view/Sections/PaymentModal/Error/PaymentModalError.php';
            $viewParams = [];
            $success = false;
        } else {
            $viewPath = 'framework/packages/WebshopPackage/view/Sections/PaymentModal/PaymentModal.php';
            $success = true;
        }
        $view = ViewRenderer::renderWidget('WebshopPackage_PaymentModal', $viewPath, $viewParams);

        return [
            'view' => $view,
            'data' => [
                'success' => $success
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