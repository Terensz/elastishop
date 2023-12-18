<?php
namespace framework\packages\WebshopPackage\middleware\loginEventSubscription;

use App;
use framework\component\parent\Service;
use framework\packages\WebshopPackage\service\WebshopCartService;

class Middleware extends Service
{
    public function __construct()
    {
        // dump('heloleo');exit;
    }

    public function start()
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        // $cart = WebshopCartService::getCart();
        WebshopCartService::updateTemporaryPersonWithAuthenticatedUserData();
        // dump($cart);
    }
}