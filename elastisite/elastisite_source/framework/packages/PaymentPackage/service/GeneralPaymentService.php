<?php
namespace framework\packages\PaymentPackage\service;

use App;
use framework\kernel\utility\BasicUtils;
use framework\component\parent\Service;
use framework\kernel\base\Container;
use framework\kernel\utility\FileHandler;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\PaymentPackage\repository\PaymentRepository;
// use framework\packages\PaymentPackage\entity\OnlineGateway;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\service\WebshopService;

/*
1.: OnlinePaymentService:
- 
*/
class GeneralPaymentService extends Service
{
    public static function getDefaultCurrency()
    {        
        return App::getContainer()->getConfig()->getProjectData('defaultCurrency');
    }

    public static function getActiveCurrency()
    {        
        return self::getDefaultCurrency();
    }
}