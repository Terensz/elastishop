<?php
namespace framework\packages\UserPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\WebshopPackage\service\WebshopCartService;

class LogoutService extends Service
{
    public static function logout()
    {
        App::getContainer()->getSession()->logout();
        if (App::getContainer()->packageInstalled('WebshopPackage')) {
            App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
            WebshopCartService::identifyCart();
            WebshopCartService::removeObsoleteCarts();
        }
    }
}