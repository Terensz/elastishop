<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\core\WidgetResponse;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;

class WebshopResponseAssembler_ShipmentHelper extends Service
{
    const ALERT_TYPE_SUCCESS = 'success';
    const ALERT_TYPE_INFO = 'info';

    const STEP_CONSENTING_BARION_COOKIES = 'ConsentingBarionCookies';
    const STEP_SELECTING_PAYMENT_METHOD = 'SelectingPaymentMethod';
    const STEP_PAYMENT = 'Payment';

    private static $steps = [

    ];

    public static function assembleResponse($processedRequestData = null)
    {
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_ShipmentHandling');
        $viewParams = WebshopResponseAssembler_ShipmentHandling::getShipmentHandlingParams();

        $alerts = [];
        $errorsCount = 0;
        // $flowStopped = false;
        $stepAlert = null;
        $step = self::STEP_SELECTING_PAYMENT_METHOD;

        if ($viewParams['errors']['PaymentMethod']['summary']['errorsCount'] == 0) {
            $alerts[] = self::createAlert(trans('payment.method.selected'), self::ALERT_TYPE_SUCCESS);
            $step = self::STEP_CONSENTING_BARION_COOKIES;
        } else {
            $stepAlert = self::creatNextStepString(trans('selecting.payment.method'));
            $errorsCount++;
            // $flowStopped = true;
        }

        if ($viewParams['errors']['BarionCookieConsent']['summary']['errorsCount'] == 0) {
            /**
             * I think, that should not be noticed, it would generate misunderstandings.
            */
            // $alerts[] = self::createAlert(trans('cookie.consents.checked'), self::ALERT_TYPE_SUCCESS);
            $step = self::STEP_PAYMENT;
        } else {
            $stepAlert = self::creatNextStepString(trans('resetting.cookie.consents'));
            $errorsCount++;
        }

        if ($errorsCount == 0) {
            $stepAlert = self::creatNextStepString(trans('paying.for.the.order'));
            
            // dump($viewParams);exit;
        }

        /**
         * StepAlert is always last. This notifoes the user about their next step to do.
        */
        if ($stepAlert) {
            $alerts[] = $stepAlert;
        }
        $viewParams = array_merge($viewParams, [
            'alerts' => $alerts
        ]);

        $viewPath = 'framework/packages/WebshopPackage/view/Sections/ShipmentHelper/ShipmentHelper.php';
        $view = ViewRenderer::renderWidget('WebshopPackage_Helper', $viewPath, $viewParams);

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



    public static function creatNextStepString($text)
    {
        return self::createAlert(trans('next.step').': <b>'.$text.'</b>', self::ALERT_TYPE_INFO);
    }

    public static function createAlert($text, $type)
    {
        $alert = [
            'text' => $text,
            'type' => $type
        ];

        return $alert;
    }
}