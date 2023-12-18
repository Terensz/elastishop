<?php
namespace framework\packages\StaffPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\packages\UserPackage\entity\User;

class ModalLoginWidgetController extends WidgetController
{
    public function loginView($callingWidgetName, $config)
    {
        $displayRegLink = isset($config['displayRegLink']) ? $config['displayRegLink'] : false;
        $onSuccessScript = isset($config['onSuccessScript']) ? $config['onSuccessScript'] : null;

        // dump($this->getContainer()->getRouting()->getPageRoute());
        // dump($this->getContainer()->getUrl());exit;
        $user = $this->getContainer()->getUser();
        $message = $this->getLoginMessage();

        // dump($user);
        // dump($message);exit;
        // $includedViewPath = ($this->getSession()->userLoggedIn())
        //     ? ($this->isGranted('viewTokenRequiredContent')
        //         ? 'framework/packages/UserPackage/view/widget/Login/token.php'
        //         : 'framework/packages/UserPackage/view/widget/Login/userDetails.php')
        //     : 'framework/packages/UserPackage/view/widget/LoginGuideWidget/loginModal.php';

        // dump('alma2');exit;

        $viewPath = 'framework/packages/UserPackage/view/widget/LoginGuideWidget/loginModal.php';
        $response = [
            'view' => $this->renderWidget('LoginWidget', $viewPath, [
                'callingWidgetName' => $callingWidgetName,
                'onSuccessScript' => $onSuccessScript,
                // 'includedViewPath' => $includedViewPath,
                'displayRegLink' => $displayRegLink,
                'container' => $this->getContainer(),
                'widgetName' => 'LoginWidget',
                'documentTitle' => ($this->getSession()->userLoggedIn() ? trans('user.data') : trans('login')),
                'user' => $user,
                'message' => $message
            ]),
            'data' => [
                'freshLogin' => ((isset($message['text']) && $message['text'] == 'login.success') ? true : false),
                'modalLabel' => trans('login'),
                'onSuccessRedirectToLink' => '/alma/alma2'
            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    public function getLoginMessage($widgetName = 'LoginWidget')
    {
        $messages = $this->getSystemMessages(['subject' => 'login']);
        $message = (isset($messages[0])) ? $messages[0] : null;
        if (!$message && $this->getContainer()->isAjax() && $this->getRequest()->get($widgetName.'_username') === '') {
            $this->addSystemMessage('login.missing.username', 'error', 'login');
            return $this->getLoginMessage();
        } else {
            return $message;
        }
    }

    /**
    * Route: [name: staffMemberLogin_ModalLoginWidget, paramChain: /staffMemberLogin/ModalLoginWidget]
    */
    public function modalLoginWidgetAction()
    {
        // dump('ModalLoginWidgetController');
        // dump(App::getContainer()->getRequest()->getAll());exit;
        return $this->loginView('LoginWidget', ['displayRegLink' => true]);
    }

    /**
    * Route: [name: widget_StaffMemberLoginWidget, paramChain: /widget/StaffMemberLoginWidget]
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
