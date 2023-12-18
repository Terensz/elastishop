<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\kernel\view\ViewRenderer;
use framework\packages\LegalPackage\controller\CookieConsentService;
use framework\packages\LegalPackage\entity\VisitorConsentAcceptance;
use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\ToolPackage\service\TextAssembler;
use framework\packages\UserPackage\entity\User;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\repository\ShipmentRepository;
use framework\packages\WebshopPackage\service\ShipmentService;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;

class WebshopResponseAssembler_GWOReplyBarion extends Service
{
    public static function assembleResponse($processedRequestData = null)
    {
        App::getContainer()->wireService('PaymentPackage/service/OnlinePaymentService');
        App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('WebshopPackage/entity/Shipment');
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_GWOReply');

        // dump($this->getUrl()->getFullUrl());
        // dump($shipmentCode);
        // $this->wireService('PaymentPackage/repository/PaymentRepository');
        // $paymentRepo = new PaymentRepository();

        // $successResult = false;
        $fullUrlParts = explode('?paymentId=', App::getContainer()->getUrl()->getFullUrl());

        App::getContainer()->wireService('PaymentPackage/service/OnlinePaymentService');
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_ShipmentHandling');

        $viewParams = [];
        // dump($fullUrlParts);exit;
        $paymentStatus = null;
        if (count($fullUrlParts) == 2) {
            $paymentCode = $fullUrlParts[1];
            $shipmentCode = BasicUtils::explodeAndGetElement($fullUrlParts[0], '/', 'last');

            // dump($shipmentCode);

            $shipmentRepo = new ShipmentRepository();
            $shipment = $shipmentRepo->findOneBy(['conditions' => [
                ['key' => 'code', 'value' => $shipmentCode]
            ]]);
            // dump($shipment);exit;
            // dump($shipment);exit;

            if ($shipment) {
                $viewParams = WebshopResponseAssembler_ShipmentHandling::getShipmentHandlingParams(false, $shipment);

                $paymentService = new OnlinePaymentService('Barion', isset($viewParams['shipmentDataSet'][0]) ? $viewParams['shipmentDataSet'][0] : null);
                /**
                 * This automatically sets the Shipment status to SHIPMENT_STATUS_ORDER_PLACED, if the Payment is succeeded, and the Shipment status was in STATUS_COLLECTION_UNPAID_STATUSES.
                */
                $paymentStatus = $paymentService->getAndSavePaymentStatus(true);
                // dump($paymentService);exit;

                if ($paymentService->shipmentDataSet['shipment']['payments']['successful']) {
                    WebshopResponseAssembler_GWOReply::sendConfirmationMail($viewParams);
                    return self::returnContent($viewParams, WebshopResponseAssembler_GWOReply::RESULT_SUCCESS, $paymentStatus);
                } else {
                    return self::returnContent($viewParams, WebshopResponseAssembler_GWOReply::RESULT_ERROR);
                }
                
            } else {
                return self::returnContent($viewParams, WebshopResponseAssembler_GWOReply::RESULT_ERROR);
            }


        } else {
            return self::returnContent($viewParams, WebshopResponseAssembler_GWOReply::RESULT_ERROR);
        }

        // dump('paymentCode: ' . $paymentCode);
        // exit;

        // dump('Sikertelen');exit;






        // $request = trim(file_get_contents("php://input")); 
        // dump($request);
        // dump('shipmentCode: '.$shipmentCode);exit;
    }

    public static function returnContent($viewParams, $result, $paymentStatus = null)
    {
        $viewParams['result'] = $result;
        $viewParams['paymentStatus'] = $paymentStatus;
        $viewPath = 'framework/packages/WebshopPackage/view/Sections/GWOReply/Barion/GWOReplyBarion.php';
        $view = ViewRenderer::renderWidget('WebshopPackage_GWOReplyBarion', $viewPath, $viewParams);

        return [
            'view' => $view,
            'data' => [
            ]
        ];
    }
}