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
        if (isset($viewParams['packDataSet'][0]['pack']) && !$viewParams['packDataSet'][0]['pack']['confirmationSentAt']) {
            // App::WebshopEmailService
            // $shipmentData = $viewParams['packDataSet'][0]['pack'];
            App::getContainer()->wireService('WebshopPackage/service/WebshopEmailSenderService');
            WebshopEmailSenderService::sendMail_orderSuccessful($viewParams['packDataSet']);
        }
        // dump($viewParams);exit;
    }
}