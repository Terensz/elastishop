<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\parent\Service;
use framework\packages\WebshopPackage\service\WebshopEmailSenderService;

class WebshopResponseAssembler_GWOReply extends Service
{
    const RESULT_SUCCESS = 'success';
    const RESULT_ERROR = 'error';

    public static function sendConfirmationMail($viewParams)
    {
        // dump($viewParams);exit;
        if (isset($viewParams['shipmentDataSet'][0]['shipment']) && !$viewParams['shipmentDataSet'][0]['shipment']['confirmationSentAt']) {
            // App::WebshopEmailService
            // $shipmentData = $viewParams['shipmentDataSet'][0]['shipment'];
            App::getContainer()->wireService('WebshopPackage/service/WebshopEmailSenderService');
            WebshopEmailSenderService::sendMail_orderSuccessful($viewParams['shipmentDataSet']);
        }
        // dump($viewParams);exit;
    }
}