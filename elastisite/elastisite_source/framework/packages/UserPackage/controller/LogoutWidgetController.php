<?php
namespace framework\packages\UserPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\UserPackage\service\LogoutService;
use framework\packages\WebshopPackage\service\WebshopCartService;

/**
 * This entire class is used by the admin login interface 
 * For the modal login methods look for the ModalLoginWidgetController.
*/
class LogoutWidgetController extends WidgetController
{
    /**
    * Route: [name: ajax_logout, paramChain: /ajax/logout]
    */
    public function logoutAction()
    {
        App::getContainer()->wireService('UserPackage/service/LogoutService');
        LogoutService::logout();

        $response = [
            'view' => '',
            'data' => ''
        ];
        return $this->widgetResponse($response);
    }

}
