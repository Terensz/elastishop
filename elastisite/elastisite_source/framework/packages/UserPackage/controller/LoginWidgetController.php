<?php
namespace framework\packages\UserPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\packages\UserPackage\entity\User;
use framework\packages\UserPackage\repository\FBSUserRepository;

/**
 * This entire class is used by the admin login interface 
 * For the modal login methods look for the ModalLoginWidgetController.
*/
class LoginWidgetController extends WidgetController
{
    public function loginView($callingWidgetName, $config)
    {
        $displayRegLink = isset($config['displayRegLink']) ? $config['displayRegLink'] : false;
        $onSuccessScript = isset($config['onSuccessScript']) ? $config['onSuccessScript'] : null;

        // dump($this->getContainer()->getRouting()->getPageRoute());
        // dump($this->getContainer()->getUrl());exit;
        $user = $this->getContainer()->getUser();
        $message = $this->getLoginMessage();
        $includedViewPath = ($this->getSession()->userLoggedIn())
            ? ($this->isGranted('viewTokenRequiredContent')
                ? 'framework/packages/UserPackage/view/widget/Login/token.php'
                : 'framework/packages/UserPackage/view/widget/Login/userDetails.php')
            : 'framework/packages/UserPackage/view/widget/Login/login.php';
            // dump('alma');exit;
        // $viewPath = 'framework/packages/UserPackage/view/widget/LoginWidget/widget.php';
        // dump($includedViewPath);exit;
        // $req = $this->getContainer()->getRequest();
        $viewPath = 'framework/packages/UserPackage/view/widget/Login/widget.php';
        $response = [
            'view' => $this->renderWidget('LoginWidget', $viewPath, [
                'callingWidgetName' => $callingWidgetName,
                'onSuccessScript' => $onSuccessScript,
                'includedViewPath' => $includedViewPath,
                'displayRegLink' => $displayRegLink,
                'container' => $this->getContainer(),
                'widgetName' => 'LoginWidget',
                'documentTitle' => ($this->getSession()->userLoggedIn() ? trans('user.data') : trans('login')),
                'user' => $user,
                'message' => $message
            ]),
            'data' => [
                'messages' => $this->getSystemMessages(),
                'session' => $this->getSession()->userLoggedIn(),
                'message' => $message,
                'freshLogin' => ((isset($message['text']) && $message['text'] == 'login.success') ? true : false),
                'onSuccessRedirectToLink' => '/alma/alma2'
            ]
        ];

        // dump('alma1');exit;

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: widget_LoginWidget, paramChain: /widget/LoginWidget]
    * This route belongs to the admin login interface.
    */
    public function loginWidgetAction()
    {
        return $this->loginView('LoginWidget', ['displayRegLink' => true]);
    }

    /**
    * Route: [name: widget_LoginNoRegLinkWidget, paramChain: /widget/LoginNoRegLinkWidget]
    */
    public function loginNoRegLinkWidgetAction()
    {
        return $this->loginView('LoginNoRegLinkWidget', ['displayRegLink' => false]);
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
    * Route: [name: widget_Login2Widget, paramChain: /widget/Login2Widget]
    */
    public function login2WidgetAction()
    {
        App::getContainer()->wireService('UserPackage/repository/FBSUserRepository');
        $repo = new FBSUserRepository();
        $fbsUsers = $repo->findAll();

        // dump($this->getContainer()->getRouting()->getPageRoute());exit;
        // dump($this->getContainer()->getUrl());exit;
        $user = $this->getContainer()->getUser();
        // dump($user);exit;
        if (!$user) {
            dump($user);exit;
        }
        if ($this->getRequest()->get('LoginWidget_username')) {
            // dump($this->getRequest()->getAll());exit;
        }
        // dump($user);exit;
        $message = $this->getLoginMessage();
        $viewPath = ($this->getSession()->userLoggedIn())
            ? ($this->isGranted('viewTokenRequiredContent')
                ? 'framework/packages/UserPackage/view/widget/Login2Widget/token.php'
                : 'framework/packages/UserPackage/view/widget/Login2Widget/userDetails.php')
            : 'framework/packages/UserPackage/view/widget/Login2Widget/login.php';

        $response = [
            'view' => $this->renderWidget('Login2Widget', $viewPath, [
                'container' => $this->getContainer(),
                'widgetName' => 'Login2Widget',
                'documentTitle' => ($this->getSession()->userLoggedIn() ? trans('user.data') : trans('login')),
                'user' => $user,
                'message' => $message,
                'fbsUsers' => $fbsUsers
            ]),
            'data' => [
                'freshLogin' => ((isset($message['text']) && $message['text'] == 'login.success') ? true : false),
                'message' => $message,
                'u' => $this->getRequest()->get('LoginWidget_username') 
            ]
        ];

        // dump($response);exit;
        // dump($user);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: ajax_logout, paramChain: /ajax/logout]
    */
    // public function logoutAction()
    // {
    //     $this->getSession()->logout();
    //     // $this->getSession()->set('userId', null);
    //     // $this->getContainer()->setUser(new User);
    //     // $this->getContainer()->getUser()->addPermissionGroup('guest');
    //     // dump($this->getContainer()->getUser());
    //     $response = [
    //         'view' => '',
    //         'data' => ''
    //     ];
    //     return $this->widgetResponse($response);
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
