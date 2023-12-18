<?php
namespace framework\packages\UserPackage\controller;

use App;
use framework\component\parent\JsonResponse;
use framework\component\parent\PageController;
use framework\packages\UserPackage\service\LogoutService;

class UserController extends PageController
{
    public function logoutAction()
    {
        App::getContainer()->wireService('UserPackage/service/LogoutService');
        LogoutService::logout();

        if (App::getContainer()->isAjax()) {
            // $viewPath = 'framework/packages/UserPackage/view/widget/LoginGuideWidget/logout.php';
            // $response = [
            //     'view' => $this->renderWidget('LoginGuideWidget', $viewPath, [
            //         'container' => $this->getContainer(),
            //     ]),
            //     'data' => []
            // ];    
            // return new JsonResponse($response);
        } else {
            header('Location: /');
        }
    }

    /**
    * Route: [name: user_registration, paramChain: /registration]
    */
    public function userRegistrationAction()
    {
        // dump($this->getContainer()->getFullRouteMap());exit;

        // $im = new \Imagick();
        // $im->setResolution( 300, 300 );
        // $im->readImage( "public_folder/logo/elastilogo.png" );
        // $output = $im->getimageblob();
        // header("Content-type: image/png");
        // echo $output;
        // dump('alma');exit;
        // dump($this->getContainer()->getWidgets());exit;
        // throw new ElastiException('Alma...', ElastiException::ERROR_TYPE_SECRET_PROG);
        // dump($_SERVER['HTTP_X_FORWARDED_FOR']);exit;
        // dump(openssl_get_cipher_methods());
        // dump($this->encrypt('almafácska')); dump($this->encrypt('alma'));
        // dump($this->encrypt('almakörte')); dump($this->encrypt('alma'));exit;
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
    
    /**
    * Route: [name: user_registration_activation, paramChain: /registration/activation]
    */
    public function userRegistrationActivationAction($mixedToken)
    {
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }

    /**
    * Route: [name: user_index, paramChain: /users]
    */
    public function userIndexAction()
    {
        // dump('alma');
        // $this->getContainer()->setService('UserPackage/repository/FBSUserRepository');
        // $repo = $this->getContainer()->getService('FBSUserRepository');
        // $repo->createAdmins();
        // $this->getContainer()->setService('ToolPackage/service/Crypter');
        // $c = $this->getContainer()->getService('Crypter');
        // $text = $c->encrypt('Aprocska kalapocska');
        // $text2 = $c->decrypt('A0J1Oppogsc+oDKA8phNlSyckaE=');
        // dump($text2);exit;
        // dump(BasicUtils::snakeToCamelCase('created_at_anyad'));exit;
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }

    /**
    * Route: [name: admin_FBSUsers, paramChain: /admin/FBSUsers]
    */
    public function adminFBSUsersAction()
    {
        // $repo = $this->getService('FBSUserRepository');
        // $repo->removeBy(['id' => 4]);
        // $users = $repo->findAll();
        // dump($users);exit;
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_userAccounts, paramChain: /admin/userAccounts]
    */
    public function adminUserAccountsAction()
    {
        // $repo = $this->getService('FBSUserRepository');
        // $users = $repo->findAll();
        // dump($users);
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_intrusionAttempts, paramChain: /admin/intrusionAttempts]
    */
    public function adminIntrusionAttemptsAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_user_init, paramChain: /admin/user/init]
    */
    // public function adminInitAction()
    // {
    //     return $this->renderPage([
    //         'container' => $this->getContainer(),
    //         'skinName' => 'Basic'
    //     ]);
    // }

    /**
    * Route: [name: redeemPasswordRecoveryToken, paramChain: /redeemPasswordRecoveryToken/{token}]
    */
    public function redeemPasswordRecoveryTokenAction($token)
    {
        return $this->renderPage([
            'container' => $this->getContainer()
            // 'skinName' => 'Basic'
        ]);
    }

    
    /**
    * Route: [name: admin_login, paramChain: /login{token}]
    */
    public function adminLoginAction()
    {
        // $this->wireService('UserPackage/repository/FBSUserRepository');
        // $FBSUserRepo = new FBSUserRepository();
        // dump($FBSUserRepo->findAll());
        
        return $this->renderPage([
            'container' => $this->getContainer()
            // 'skinName' => 'Basic'
        ]);
    }
}
