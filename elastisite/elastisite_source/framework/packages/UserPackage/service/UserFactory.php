<?php
namespace framework\packages\UserPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\UserPackage\entity\User;
use framework\packages\UserPackage\repository\UserRepository;
use framework\packages\UserPackage\repository\UserAccountRepository;
use framework\packages\UserPackage\repository\FBSUserRepository;
use framework\component\exception\ElastiException;
use framework\kernel\utility\BasicUtils;
use framework\packages\StaffPackage\repository\StaffMemberRepository;
use framework\packages\WebshopPackage\middleware\loginEventSubscription\Middleware;

class UserFactory extends Service
{
    const STAFF_MEMBER_LOGIN_INDICATOR = 'staffMember';

    public $freshAuth = false;

    public function __construct()
    {
        $this->wireService('BasicPackage/entity/Country');
        $this->wireService('UserPackage/entity/User');
        $this->wireService('UserPackage/entity/FBSUser');
        $this->wireService('UserPackage/entity/UserAccount');
        $this->wireService('UserPackage/entity/Person');
        $this->handleUserSession();
        $this->getSession()->adjustSessionCookie();

        // dump( $this->getSession()->get('user'));
    }

    /**
     * @todo
    */
    public function checkForLoginEventSubscribers()
    {
        App::getContainer()->wireService('WebshopPackage/middleware/loginEventSubscription/Middleware');
        $middleware = new \framework\packages\WebshopPackage\middleware\loginEventSubscription\Middleware();
        $middleware->start();
        // dump(App::getContainer()->getPackageNames());exit;
    }

    public function handleUserSession()
    {
        // unset($_SESSION);
        // dump($_SESSION);
        // if (!isset($_SESSION['alma'])) {
        //     $_SESSION['alma'] = 'korte';
        // }
        // dump($_SESSION['alma']);
        // $route = $this->getContainer()->getRouting()->getPageRoute()->getName();
        // dump($route);exit;

        $this->wireService('UserPackage/repository/UserRepository');
        $userRepo = new UserRepository();
        // $this->wireService('UserPackage/repository/FBSUserRepository');
        // $FBSUserRepo = new FBSUserRepository();
        $this->setService('UserPackage/service/UserService');
        $userService = $this->getService('UserService');

        $user = $this->getSession()->get('user') ? unserialize($this->getSession()->get('user')) : null;
        if ($user && !$this->getContainer()->isAjax() && $this->isGranted('viewTokenRequiredContent', $user)) {
            $this->getSession()->set('user', null);
        }

        // var_dump($this->getRequest()->getAll());exit;
        if ($this->getRequest()->get('LoginWidget_token')) {
            if ($this->isGranted('viewTokenRequiredContent', $user)) {
                $loginTokenCheckRes = $userService->checkLoginToken(
                    $user, $this->getRequest()->get('LoginWidget_token')
                );
                if (!$loginTokenCheckRes['result']) {
                    $this->addSystemMessage($loginTokenCheckRes['message'], 'error', 'login');
                    if ($loginTokenCheckRes['message'] == 'expired.login.token') {
                        $this->resetUserSession();
                    }
                } else {
                    $this->addSystemMessage('login.success', 'success', 'login');
                    $user = $this->findIdentity(['id' => $user->getId()]);
                    $this->getSession()->set('userId', $user->getId());
                    $this->getContainer()->getSession()->set('user', serialize($user));
                }
            } else {
                ## Megy a ban
            }
        }

        if ($this->getSession()->get('user') && unserialize($this->getSession()->get('user')) instanceof User === false) {
            $this->getSession()->set('user', null);
        }

        if (!$this->getSession()->get('user')) {
            $this->resetUserSession();
            // $this->getSession()->set('userId', null);
            // $this->getContainer()->setUser(new User);
            // $this->getSession()->set('userStorageType', null);
        }

        if ($this->getSession()->get('user')) {
            // dump($this->getSession()->get('user'));
            $user = unserialize($this->getSession()->get('user'));
            if ($user->getPermissionGroups() == array()) {
                $this->resetUserSession();
            }

            // dump($user);//exit;
        }

        if (!$this->getSession()->get('userId') || !$this->getSession()->get('userStorageType')) {
            $this->getSession()->set('userId', 0);
            $user = $userRepo->createLoggedOutUser();
            $this->getSession()->set('user', serialize($user));
        }

        if ($this->getSession()->get('userId') && $this->getSession()->get('userStorageType') == 'Db') {
            $user = $this->findIdentity(['id' => $this->getSession()->get('userId')]);
        }

        $justAuthenticated = false;
        if ($this->getRequest()->get('LoginWidget_username') && $this->getRequest()->get('LoginWidget_username') != '') {
            $user = $this->authenticate($userRepo);
            $this->getContainer()->setUser($user);
            $this->getSession()->set('userId', ($user ? $user->getId() : 0));
            $this->getSession()->set('user', serialize($user));
            if ($user->getType() == User::TYPE_USER) {
                $justAuthenticated = true;
                $this->checkForLoginEventSubscribers();
            }

            return true;
        }
        // else {
        //     if ($this->getContainer()->getSession()->get('user')) {
        //         $user = unserialize($this->getContainer()->getSession()->get('user'));
        //     } else {
        //         $user = $this->findIdentity(['id' => $this->getSession()->get('userId')]);
        //     }
        // }
        if ($user instanceof User) {
            if ($this->getSession()->get('userId') != $user->getId()) {
                $this->resetUserSession();
                $this->handleUserSession();
            }
            $this->getContainer()->setUser($user);
            $this->addRecoveryGuestPermission();
            return true;
        }

        if (!$this->getContainer()->getUser()) {
            if (isset($user)) {
                $this->getContainer()->setUser($user);
            }
            $this->getContainer()->setUser(unserialize($this->getContainer()->getSession()->get('user')));

            if (!$this->getContainer()->getUser()) {
                dump('Nincs user');exit;
            }
        }

        if ($justAuthenticated) {
            /**
             * @todo
            */
            // $this->checkForLoginEventSubscribers();
        }

        $this->addRecoveryGuestPermission();
    }

    public function addRecoveryGuestPermission()
    {
        if ($this->getSession()->get('maintenanceMode')) {
            $user = $this->getContainer()->getUser();
            $user->addPermissionGroup('recoveryGuest');
            $this->getContainer()->setUser($user);
        }
        // dump($this->getContainer()->getUser());
        // dump($this->getSession()->get('maintenanceMode')); exit;
    }

    public function resetUserSession()
    {
        // $this->getSession()->logout();
        $this->getSession()->set('userId', null);
        $this->getSession()->set('user', null);
        $this->getSession()->set('userStorageType', null);
    }

    public function findIdentity($params)
    {
        App::getContainer()->wireService('UserPackage/repository/UserRepository');
        $userRepo = new UserRepository();
        App::getContainer()->wireService('UserPackage/repository/UserAccountRepository');
        $userAccountRepo = new UserAccountRepository();

        App::getContainer()->wireService('UserPackage/repository/FBSUserRepository');
        $FBSUserRepo = new FBSUserRepository();
        $user = null;

        App::getContainer()->wireService('StaffPackage/repository/StaffMemberRepository');

        if ($this->getSession()->get('userId') > 0 && $this->getSession()->get('userStorageType')) {
            if ($this->getSession()->get('userStorageType') == 'Db') {
                $user = $userAccountRepo->findUser($params);
            }
            if ($this->getSession()->get('userStorageType') == 'Db/staffMember') {
                if (isset($params['id'])) {
                    $staffMemberRepository = new StaffMemberRepository();
                    $user = $userRepo->createLoggedOutUser();
                    $staffMember = $staffMemberRepository->find($params['id']);
                    $user = StaffMemberRepository::fillUserFromStaffMember($staffMember, $user);
                }
            }
            if ($this->getSession()->get('userStorageType') == 'FBS') {
                $user = $FBSUserRepo->findUser($params);
            }
        } else {
            if (in_array($this->getContainer()->getRouting()->getPageRoute()->getName(), array('admin_login', 'setup'))) {
                $user = $FBSUserRepo->findUser($params);
                // dump($user);
            } else {
                $urlDetails = App::getContainer()->getUrl()->getDetails();
                $foundStaffMemberLogin = false;
                $nextLoopIsStaffMemberCode = false;
                $staffMemberCode = null;
                foreach ($urlDetails as $urlDetail) {
                    if ($urlDetail == self::STAFF_MEMBER_LOGIN_INDICATOR) {
                        $foundStaffMemberLogin = true;
                        $nextLoopIsStaffMemberCode = true;
                    } else {
                        if ($nextLoopIsStaffMemberCode) {
                            $nextLoopIsStaffMemberCode = false;
                            $staffMemberCode = $urlDetail;
                        }
                    }
                }
                
                if ($foundStaffMemberLogin && $staffMemberCode) {
                    if (!isset($params['id']) && isset($params['username']) && isset($params['password'])) {
                        // $staffMemberRepository = new StaffMemberRepository();
                        $user = StaffMemberRepository::findAndAuthStaffMember($staffMemberCode, $params);
                        // if ($staffMember) {
                        //     $user = $userRepo->createLoggedOutUser();
                        //     $user = StaffMemberRepository::fillUserFromStaffMember($staffMember,$user);
                        // }
                    }
                } else {
                    $user = $userAccountRepo->findUser($params);
                }
            }
        }

        if (!$user) {
            $user = $userRepo->createLoggedOutUser();
        }
        // dump($user);exit;

        return $user;
    }

    public function getCsrfTokenPost()
    {
        $postedTokenName = null;
        $postedTokenValue = null;

        if (!$this->getRequest()->get('LoginWidget_csrfToken') && $this->getRequest()->get('Login2Widget_csrfToken')) {
            $postedTokenName = 'Login2Widget_csrfToken';
            $postedTokenValue = $this->getRequest()->get('Login2Widget_csrfToken');
        }
        if ($this->getRequest()->get('LoginWidget_csrfToken')) {
            // return $this->getRequest()->get('LoginWidget_csrfToken');
            $postedTokenName = 'LoginWidget_csrfToken';
            $postedTokenValue = $this->getRequest()->get('LoginWidget_csrfToken');
        }

        return [
            'postedTokenName' => $postedTokenName,
            'postedTokenValue' => $postedTokenValue
        ];
        // return null;
    }

    public function authenticate($userRepo)
    {
        $csrfTokenPost = $this->getCsrfTokenPost();
        if (!$csrfTokenPost['postedTokenValue'] || ($csrfTokenPost['postedTokenValue'] != $this->getSession()->get($csrfTokenPost['postedTokenName']))) {
            $this->addSystemMessage('login.invalid.csrf.token', 'error', 'login');
            return $userRepo->createLoggedOutUser();
        }

        $user = $this->findIdentity([
            'username' => $this->getRequest()->get('LoginWidget_username'),
            'password' => md5($this->getRequest()->get('LoginWidget_password'))
        ]);

        // dump($user);exit;

        if (!$user) {
            $user = $userRepo->createLoggedOutUser();
        } else {
            $this->initLoginAutoLoaders();
        }

        return $user;
    }

    public function initLoginAutoLoaders()
    {
        $this->getContainer()->getKernelObject('AutoLoaderFactory')->initAutoLoaders('LoginAutoLoader');
    }
}
