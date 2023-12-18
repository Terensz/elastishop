<?php
namespace framework\packages\UserPackage\service;

use App;
use framework\component\core\WidgetResponse;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;

class ModalLoginService extends Service
{
    public static function loginView($callingWidgetName, $config)
    {
        $displayRegLink = isset($config['displayRegLink']) ? $config['displayRegLink'] : false;
        $onSuccessScript = isset($config['onSuccessScript']) ? $config['onSuccessScript'] : null;

        // dump($this->getContainer()->getRouting()->getPageRoute());
        // dump($this->getContainer()->getUrl());exit;
        $user = App::getContainer()->getUser();
        $message = self::getLoginMessage();
        // $includedViewPath = ($this->getSession()->userLoggedIn())
        //     ? ($this->isGranted('viewTokenRequiredContent')
        //         ? 'framework/packages/UserPackage/view/widget/Login/token.php'
        //         : 'framework/packages/UserPackage/view/widget/Login/userDetails.php')
        //     : 'framework/packages/UserPackage/view/widget/LoginGuideWidget/loginModal.php';

        // dump('alma2');exit;

        $viewPath = 'framework/packages/UserPackage/view/widget/LoginGuideWidget/loginModal.php';
        $response = [
            'view' => ViewRenderer::renderWidget('LoginWidget', $viewPath, [
                'callingWidgetName' => $callingWidgetName,
                'onSuccessScript' => $onSuccessScript,
                'usernamePost' => (string)App::getContainer()->getRequest()->get('LoginWidget_username'),
                'passwordPost' => (string)App::getContainer()->getRequest()->get('LoginWidget_password'),
                // 'includedViewPath' => $includedViewPath,
                'displayRegLink' => $displayRegLink,
                'container' => App::getContainer(),
                'widgetName' => 'LoginWidget',
                'documentTitle' => (App::getContainer()->getSession()->userLoggedIn() ? trans('user.data') : trans('login')),
                'user' => $user,
                'message' => $message
            ]),
            'data' => [
                'freshLogin' => ((isset($message['text']) && $message['text'] == 'login.success') ? true : false),
                'modalLabel' => trans('login'),
                'onSuccessRedirectToLink' => isset($config['onSuccessRedirectToLink']) ? $config['onSuccessRedirectToLink'] : null
            ]
        ];

        return WidgetResponse::create($response);
    }

    public static function getLoginMessage($widgetName = 'LoginWidget', $round = 0)
    {
        $messages = App::getContainer()->getSystemMessages(['subject' => 'login']);
        $message = (isset($messages[0])) ? $messages[0] : null;
        if (!$message && App::getContainer()->isAjax() && App::getContainer()->getRequest()->get($widgetName.'_username') === '' && $round < 10) {
            App::getContainer()->addSystemMessage('login.missing.username', 'error', 'login');
            return self::getLoginMessage($widgetName, $round++);
        } else {
            return $message;
        }
    }

    // public function getLoginMessage_OLD($widgetName = 'LoginWidget')
    // {
    //     $messages = $this->getSystemMessages(['subject' => 'login']);
    //     $message = (isset($messages[0])) ? $messages[0] : null;
    //     if (!$message && $this->getContainer()->isAjax() && $this->getRequest()->get($widgetName.'_username') === '') {
    //         $this->addSystemMessage('login.missing.username', 'error', 'login');
    //         return $this->getLoginMessage();
    //     } else {
    //         return $message;
    //     }
    // }
}