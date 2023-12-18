<?php
namespace projects\elastishop\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\UserPackage\service\ModalLoginService;
// use framework\component\parent\JsonResponse;
// use framework\packages\UserPackage\entity\User;

class ModalLoginWidgetController extends WidgetController
{
    /**
    * Route: [name: login_ModalLoginWidget, paramChain: /login/ModalLoginWidget]
    */
    public function modalLoginWidgetAction()
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
        App::getContainer()->wireService('UserPackage/service/ModalLoginService');

        // $pageRoute = App::getContainer()->getRouting()->getPageRoute();
        // $pageRouteName = App::getContainer()->getRouting()->getPageRoute()->getName();
        // $locale = App::getContainer()->getSession()->getLocale();
        // dump($url);exit;

        $url = App::getContainer()->getUrl();
        return ModalLoginService::loginView('LoginWidget', [
            'displayRegLink' => true,
            'onSuccessRedirectToLink' => '/'.$url->getParamChain()
        ]);
    }

    /**
    * Route: [name: widget_LoginNoRegLinkWidget, paramChain: /widget/LoginNoRegLinkWidget]
    */
    // public function loginNoRegLinkWidgetAction()
    // {
    //     App::getContainer()->wireService('UserPackage/service/ModalLoginService');

    //     return ModalLoginService::loginView('LoginNoRegLinkWidget', [
    //         'displayRegLink' => false,
    //         'onSuccessRedirectToLink' => '/'
    //     ]);
    // }

    /**
    * Route: [name: widget_LoginGuideWidget, paramChain: /widget/LoginGuideWidget]
    */
    // public function loginGuideWidgetAction()
    // {
    //     $viewPath = 'framework/packages/UserPackage/view/widget/LoginGuideWidget/widget.php';
    //     $response = [
    //         'view' => $this->renderWidget('LoginGuideWidget', $viewPath, [
    //             'container' => $this->getContainer(),
    //         ]),
    //         'data' => []
    //     ];
        
    //     return $this->widgetResponse($response);
    // }

}
