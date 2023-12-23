<?php
namespace framework\packages\UserPackage\controller;

use framework\component\parent\WidgetController;
use framework\kernel\utility\BasicUtils;
// use framework\component\parent\JsonResponse;
use framework\packages\UserPackage\entity\User;
// use framework\packages\UserPackage\entity\UserAccount;
use framework\packages\UserPackage\entity\Person;
use framework\packages\UserPackage\entity\Address;
use framework\packages\UserPackage\repository\UserAccountRepository;
use framework\packages\UserPackage\repository\PersonRepository;
use framework\packages\ToolPackage\service\Mailer;
use framework\packages\UserPackage\repository\UserAccountRegistrationTokenRepository as RegTokenRepo;
use framework\packages\UserPackage\entity\UserPasswordRecoveryToken;
use framework\packages\UserPackage\repository\UserPasswordRecoveryTokenRepository as PwRecTokenRepo;
// use framework\packages\ToolPackage\service\TextProcessor;
use framework\packages\ToolPackage\service\Grid\GridFactory;
use framework\packages\FormPackage\service\FormBuilder;
// use framework\packages\ToolPackage\service\GridAjaxInterface;
use framework\packages\LegalPackage\service\LegalDocumentFactory;
use framework\packages\UserPackage\entity\UserAccount;
use framework\packages\BasicPackage\repository\CountryRepository;
use framework\packages\DataGridPackage\service\DataGridBuilder;

class UserWidgetController extends WidgetController
{
    /**
    * Route: [name: user_registration_widget, paramChain: /user/registration/widget]
    */
    public function userRegistrationWidgetAction()
    {
        $this->setService('UserPackage/service/UserRegistrationService');
        $userRegistrationService = $this->getService('UserRegistrationService');
        return $userRegistrationService->handleRegistration([
            'registerViewPath' => 'framework/packages/UserPackage/view/widget/UserRegistrationWidget/widget.php',
            'successViewPath' => 'framework/packages/UserPackage/view/widget/UserRegistrationWidget/success.php',
            'formViewPath' => 'framework/packages/UserPackage/view/widget/UserRegistrationWidget/form.php',
            'userRegistrationSuccessfulPackageName' => 'UserPackage',
            'userRegistrationSuccessfulReferenceKey' => 'registrationSuccessful',
            'activationLinkFormat' => '[httpDomain]/registration/activation/[userAccountCode]-[regToken]'
        ]);
    }

    /**
    * Route: [name: user_registration_widget, paramChain: /user/registration/widget]
    */
    // public function userRegistrationWidgetAction_OLD()
    // {
    //     $this->wireService('UserPackage/entity/Address');
    //     $this->wireService('FormPackage/service/FormBuilder');
    //     $this->wireService('UserPackage/repository/UserAccountRepository');
    //     $this->wireService('UserPackage/repository/UserAccountRegistrationTokenRepository');
    //     $this->wireService('ToolPackage/service/Mailer');
    //     $this->wireService('LegalPackage/service/LegalDocumentFactory');
    //     $this->wireService('BasicPackage/repository/CountryRepository');
    //     $email = null;

    //     $countryRepo = new CountryRepository();

    //     $formBuilder = new FormBuilder();
    //     $formBuilder->setPackageName('UserPackage');
    //     $formBuilder->setSubject('userRegistration');
    //     $formBuilder->addExternalPost('id');
    //     $formBuilder->addExternalPost('UserPackage_userRegistration_legalText');
    //     $formBuilder->setSaveRequested(false);
    //     $form = $formBuilder->createForm();

    //     $legalDocumentFactory = new LegalDocumentFactory();
    //     $legalText = $legalDocumentFactory->getCompleteText();
    //     $viewName = !$form->isSubmitted() ? 'widget' : ($form->isValid() ? 'success' : 'form');

    //     if ($form->isValid() === true) {
    //         $userAccountRepo = new UserAccountRepository();
    //         $userAccount = $form->getEntity();
    //         $userAccount = $userAccountRepo->storeUserRegistration($userAccount);
    //         $tokenRepo = new RegTokenRepo();
    //         $token = $tokenRepo->findOneBy(['conditions' => [['key' => 'user_account_id', 'value' => $userAccount->getId()]]]);

    //         $mailer = new Mailer();
    //         $mailer->setSubject(trans('registration.successful.title').' - '.$userAccount->getPerson()->getFullName());
    //         $mailer->textAssembler->setPackage('UserPackage');
    //         $mailer->textAssembler->setReferenceKey('userRegistrationSuccessful');
    //         $mailer->textAssembler->setPlaceholdersAndValues([
    //             'name' => $userAccount->getPerson()->getFullName(),
    //             'domain' => $this->getUrl()->getHttpDomain(),
    //             'activationLink' => $this->getUrl()->getHttpDomain().'/regisztracio/aktivalas/'.$userAccount->getCode().'-'.$token->getToken(),
    //         ]);
    //         $mailer->textAssembler->create();
    //         $mailer->setBody($mailer->textAssembler->getView());
    //         $email = $userAccount->getPerson()->getEmail();
    //         $mailer->addRecipient($email, $userAccount->getPerson()->getFullName());
    //         $mailer->send();
    //     }

    //     $viewPath = 'framework/packages/UserPackage/view/widget/UserRegistrationWidget/'.$viewName.'.php';
    //     $response = [
    //         'view' => $this->renderWidget('userRegistration', $viewPath, [
    //             'container' => $this->getContainer(),
    //             'form' => $form,
    //             'legalText' => $legalText,
    //             'email' => $email,
    //             'countries' => $countryRepo->findAllAvailable(),
    //             'streetSuffixes' => Address::CHOOSABLE_STREET_SUFFIXES
    //         ]),
    //         'data' => [
    //             'formIsValid' => $form->isValid(),
    //             'messages' => $form->getMessages(),
    //             'request' => $this->getContainer()->getRequest()->getAll()
    //         ]
    //     ];

    //     return $this->widgetResponse($response);
    // }

    /**
    * Route: [name: ajax_changePassword, paramChain: /ajax/changePassword]
    */
    public function changePasswordAction()
    {
        $viewPath = 'framework/packages/UserPackage/view/widget/Login/forgottenPassword.php';
        $response = [
            'view' => $this->renderWidget('forgottenPassword', $viewPath, [
                'email' => $this->getContainer()->getUser()->getEmail()
            ]),
            'data' => null
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: ajax_forgottenPassword, paramChain: /ajax/forgottenPassword]
    */
    public function forgottenPasswordAction()
    {
        $viewPath = 'framework/packages/UserPackage/view/widget/Login/forgottenPassword.php';
        $response = [
            'view' => $this->renderWidget('forgottenPassword', $viewPath, [
                'userEmail' => $this->getContainer()->getUser()->getEmail(),
                'email' => '',
                'result' => null,
                'errorMessage' => null,
            ]),
            'data' => null
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: ajax_forgottenPassword_send, paramChain: ajax/forgottenPassword/send]
    */
    public function forgottenPasswordSendAction()
    {
        // $this->getContainer()->wireService('ToolPackage/service/TextProcessor');
        $this->getContainer()->wireService('framework/packages/UserPackage/entity/Person');
        $this->getContainer()->wireService('framework/packages/UserPackage/repository/PersonRepository');
        $this->getContainer()->wireService('framework/packages/UserPackage/entity/UserPasswordRecoveryToken');
        $this->getContainer()->wireService('framework/packages/UserPackage/repository/UserPasswordRecoveryTokenRepository');
        $personRepo = new PersonRepository();
        $tokenRepo = new PwRecTokenRepo();

        // $result = 'wrong.email.or.inactive.user';
        $result = false;
        $message = 'wrong.email.or.inactive.user';
        $userEmail = $this->getContainer()->getUser()->getEmail();
        $email = $userEmail ? $userEmail : $this->getContainer()->getRequest()->get('UserPackage_forgottenPassword_email');
        // $email = $this->encrypt($email);
        $person = empty($email) ? null : $personRepo->findOneBy(['conditions' => [['key' => 'email', 'value' => $this->encrypt($email)]]]);
        // dump($person);exit;
        if (is_object($person) && $person instanceof Person && $person->getUserAccount() && $person->getUserAccount()->getStatus() == 1) {
            // $newPassword = BasicUtils::generateRandomString('10');
            // $person->setPassword(md5($newPassword));
            // $personRepo->store($person);
            $token = $tokenRepo->createNewEntity();
            $token->setToken(BasicUtils::generateRandomString('25'));
            $token->setUserAccount($person->getUserAccount());
            $tokenRepo->store($token);

            // $mailViewPath = 'framework/packages/UserPackage/view/widget/LoginWidget/email/recoverPassword_'.$this->getSession()->getLocale().'.html';
            // $textProcessor = new TextProcessor();
            // $textProcessor->setTextLink($mailViewPath);
            // $textProcessor->setPlaceholders(
            //     array(
            //         'letterHeader' => '',
            //         'name' => $person->getFullName(),
            //         'domain' => $this->getUrl()->getHttpDomain(),
            //         'passwordRecoveryLink' => $this->getContainer()->getUrl()->getHttpDomain().'/redeemPasswordRecoveryToken/'.$token->getToken(),
            //         'letterFooter' => $this->getCompanyData('letterFooter_'.$this->getSession()->getLocale())
            //     )
            // );
            // $mailBody = $textProcessor->process();
            // $this->wireService('ToolPackage/service/Mailer');
            // $mailer = new Mailer();
            // $mailer->setSubject($this->getCompanyData('brand').' - '.trans('password.modify.request'));
            // $mailer->setBody($mailBody);
            // $mailer->addRecipient($person->getEmail(), $person->getFullName());
            // $result = $mailer->send() == true ? 'mail.successfully.sent' : 'mail.send.error';

            $mailer = new Mailer();
            $mailer->setSubject($this->getCompanyData('brand').' - '.trans('password.modify.request'));
            $mailer->textAssembler->setPackage('UserPackage');
            $mailer->textAssembler->setReferenceKey('recoverPassword');
            $mailer->textAssembler->setPlaceholdersAndValues([
                'name' => $person->getFullName(),
                'domain' => $this->getUrl()->getHttpDomain(),
                'passwordRecoveryLink' => $this->getContainer()->getUrl()->getHttpDomain().'/redeemPasswordRecoveryToken/'.$token->getToken(),
            ]);
            $mailer->textAssembler->create();
            $mailer->setBody($mailer->textAssembler->getView());
            $mailer->addRecipient($person->getEmail(), $person->getFullName());
            $result = $mailer->send() ? true : false;
            $message = $result == true ? 'mail.successfully.sent' : 'mail.send.error';
        }

        $viewPath = 'framework/packages/UserPackage/view/widget/Login/'.($result ? 'forgottenPasswordSend.php' : 'forgottenPassword.php');
        $response = [
            'view' => $this->renderWidget('forgottenPassword', $viewPath, [
                'container' => $this->getContainer(),
                'result' => $result,
                'message' => $message,
                'userEmail' => $userEmail,
                'email' => $email
            ]),
            'data' => null
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: widget_RedeemPasswordRecoveryTokenWidget, paramChain: /widget/RedeemPasswordRecoveryTokenWidget]
    */
    public function redeemPasswordRecoveryTokenWidgetAction()
    {
        $this->getContainer()->wireService('FormPackage/service/FormBuilder');
        $this->getContainer()->wireService('ToolPackage/service/TextProcessor');
        $this->getContainer()->wireService('framework/packages/UserPackage/entity/Person');
        $this->getContainer()->wireService('framework/packages/UserPackage/entity/UserPasswordRecoveryToken');
        $this->getContainer()->wireService('framework/packages/UserPackage/repository/UserPasswordRecoveryTokenRepository');
        
        $tokenRepo = new PwRecTokenRepo();
        $form = null;

        $viewName = 'error.php';
        $tokenRequest = BasicUtils::explodeAndGetElement($this->getContainer()->getUrl()->getParamChain(), '/', 'last');
        $token = $tokenRepo->findOneBy(['conditions' => [['key' => 'token', 'value' => $tokenRequest]]]);

        if ($token) {
            $createdAt = new \DateTime($token->getCreatedAt());
            $validUntil = clone $createdAt;
            $validUntil->add(new \DateInterval('PT' . UserPasswordRecoveryToken::EXPIRES_IN_MINUTES . 'M'));
            $diff = (($validUntil)->getTimestamp() - ($this->getCurrentTimestamp())->getTimestamp()) / 60;

            if (!$token->getRedeemedAt() && $diff > 0) {
                // $userAccountRepo = new UserAccountRepository();
                $formBuilder = new FormBuilder();
                $formBuilder->setPackageName('UserPackage');
                $formBuilder->setSubject('changePassword');
                $formBuilder->setPrimaryKeyValue($token->getUserAccount()->getId());
                $formBuilder->setSaveRequested(false);
                $form = $formBuilder->createForm();

                if ($form->isValid()) {
                    $password = $form->getValueCollector()->getPosted('UserPackage_changePassword_password');
                    $token->getUserAccount()->getPerson()->setPassword(md5($password));
                    $token->setRedeemedAt(date('Y-m-d H:i:s'));
                    $tokenRepo->store($token);
                }
                $viewName = $form->isValid() ? 'success.php' : 'form.php';
            }
        }
        
        $viewPath = 'framework/packages/UserPackage/view/widget/RedeemPasswordRecoveryTokenWidget/'.$viewName;
        $response = [
            'view' => $this->renderWidget('RedeemPasswordRecoveryTokenWidget', $viewPath, [
                'container' => $this->getContainer(),
                'form' =>  $form
            ]),
            'data' => null
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: user_MyPersonalDataWidget, paramChain: /user/MyPersonalDataWidget]
    */
    public function myPersonalDataWidgetAction()
    {
        $this->wireService('UserPackage/entity/Person');
        $this->wireService('UserPackage/entity/UserAccount');
        $this->wireService('UserPackage/repository/UserAccountRepository');
        $accRepo = new UserAccountRepository();
        $acc = $accRepo->find($this->getContainer()->getUser()->getId());
        $addresses = array();
        if ($acc instanceof UserAccount) {
            // dump('alma!');exit;
            // dump($acc->getPerson());
            $addressGetter = (method_exists('getAddresses', get_class($acc->getPerson()))) ? 'getAddresses' : 'getAddress';
            // dump('alma2!');exit;
            $addresses = $acc->getPerson()->$addressGetter();
            $addresses = is_array($addresses) ? $addresses : array($addresses);
            // dump($addresses);exit;
        }

        $viewPath = 'framework/packages/UserPackage/view/widget/MyPersonalDataWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('MyPersonalDataWidget', $viewPath, [
                'container' => $this->getContainer(),
                'addresses' => $addresses
            ]),
            'data' => null
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: user_RemoveMyPersonalDataWidget, paramChain: /user/RemoveMyPersonalDataWidget]
    */
    public function removeMyPersonalDataWidgetAction()
    {
        $viewFile = 'widget.php';
        // $result = null;
        $passwordErrorMessage = '';
        $agreementErrorMessage = '';
        $this->getContainer()->wireService('framework/packages/UserPackage/repository/PersonRepository');
        $passwordRequest = $this->getContainer()->getRequest()->get('UserPackage_removePersonalData_password');
        $agreementRequest = $this->getContainer()->getRequest()->get('UserPackage_removePersonalData_agreement');
        // dump($agreementRequest);exit;
        if ($this->getContainer()->getRequest()->isSubmitted() && !$agreementRequest) {
            $agreementErrorMessage = trans('missing.agreement');
        }
        if ($this->getContainer()->getRequest()->isSubmitted() && $passwordRequest == '') {
            $passwordErrorMessage = trans('required.field');
        }
        if ($passwordRequest && $passwordRequest != '' && $agreementErrorMessage == '') {
            if ($this->getContainer()->getUser()->getPassword() == md5($passwordRequest)) {
                $personRepo = new PersonRepository();
                // dump($this->getContainer()->getUser()->getUserAccount()->getId());exit;
                $userAccountId = $this->getContainer()->getUser()->getUserAccount()->getId();
                $person = $personRepo->findOneBy(['conditions' => [['key' => 'user_account_id', 'value' => $userAccountId]]]);

                // $this->getSession()->set('userId', null);
                // $this->getContainer()->setUser(new User);
                // $this->getContainer()->getUser()->addPermissionGroup('guest');
                $this->getSession()->logout();
                $person->getUserAccount()->setStatus(0);
                $personRepo->store($person);
                $personRepo->removePerson($person);
                
                $viewFile = 'accountRemoved.php';
            } else {
                $passwordErrorMessage = trans('wrong.password');
            }
        }

        $viewPath = 'framework/packages/UserPackage/view/widget/RemoveMyPersonalDataWidget/'.$viewFile;
        $response = [
            'view' => $this->renderWidget('RemoveMyPersonalDataWidget', $viewPath, [
                'container' => $this->getContainer(),
                'passwordErrorMessage' => $passwordErrorMessage,
                'agreementErrorMessage' => $agreementErrorMessage
            ]),
            'data' => null
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: user_ChangePasswordWidget, paramChain: /user/ChangePasswordWidget]
    */
    // public function changePasswordWidgetAction()
    // {
    //     $viewPath = 'framework/packages/UserPackage/view/widget/ChangePasswordWidget/widget.php';
    //     $response = [
    //         'view' => $this->renderWidget('ChangePasswordWidget', $viewPath, [
    //             'container' => $this->getContainer()
    //         ]),
    //         'data' => null
    //     ];

    //     return $this->widgetResponse($response);
    // }

    /**
    * Route: [name: user_registration_activation_widget, paramChain: /user/registration/activation/widget]
    */
    public function regActivationWidgetAction()
    {
        $this->wireService('UserPackage/repository/UserAccountRepository');
        $this->wireService('UserPackage/repository/UserAccountRegistrationTokenRepository');

        $error = true;
        $mixedToken = $this->getContainer()->getUrl()->getDetails()[0];
        $mixedTokenParts = explode('-', $mixedToken);
        $title = trans('registration.token.redeem.error.title');
        $body = trans('registration.token.redeem.error');

        if (count($mixedTokenParts) == 2) {
            $userAccountRepo = new UserAccountRepository();
            $userAccount = $userAccountRepo->findOneBy(['conditions' => [['key' => 'code', 'value' => $mixedTokenParts[0]]]]);

            $tokenRepo = new RegTokenRepo();
            $token = $tokenRepo->findByUserAccountId($userAccount->getId());

            // dump($token);exit;

            if ($token == $mixedTokenParts[1]) {
                $error = false;
                $configuredStatus = $this->getProjectData('userSelfActivationStatus');
                $configuredStatus = $configuredStatus == 1 ? null : $configuredStatus;
                $status = $configuredStatus ? $configuredStatus : $userAccount::STATUS_ACTIVE;
                $userAccount->setStatus($status);
                $userAccountRepo->store($userAccount);
                $title = trans('registration.token.redeem.success.title');
                $body = trans('registration.token.redeem.success'.($configuredStatus ? '2' : '1'), [['from' => '[username]', 'to' => $userAccount->getPerson()->getUsername()]]);
                $tokenRepo->redeem($userAccount->getId());
            }
        }

        $viewPath = 'framework/packages/UserPackage/view/widget/RegActivationWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('RegActivationWidget', $viewPath, [
                'container' => $this->getContainer(),
                'error' => $error,
                'title' => $title,
                'body' => $body
            ]),
            'data' => null
        ];

        return $this->widgetResponse($response);
    }

    // FBSUSersWidget

    /**
    * Route: [name: admin_FBSUsers_widget, paramChain: /admin/FBSUsers/widget]
    */
    // public function adminFBSUsersWidgetAction_OLD()
    // {
    //     $this->getContainer()->setService('UserPackage/repository/FBSUserRepository');
    //     $FBSUuserRepo = $this->getContainer()->getService('FBSUserRepository');
    //     $this->getContainer()->setService('ToolPackage/entity/Grid');
    //     $grid = $this->getContainer()->getService('Grid');

    //     $viewPath = 'framework/packages/ToolPackage/view/grid/defaultGridWidget.php';

    //     $grid->setGridName('FBSUser');
    //     $grid->setData($FBSUuserRepo->findAll());
    //     $grid->setProperties([
    //         ['name' => 'id', 'title' => 'id'],
    //         ['name' => 'name', 'title' => trans('name'), 'colWidth' => '2'],
    //         ['name' => 'username', 'title' => trans('username'), 'colWidth' => '2'],
    //         ['name' => 'email', 'title' => trans('email'), 'colWidth' => '4'],
    //     ]);

    //     $this->getContainer()->setService('FrameworkPackage/service/GridAjaxInterface');
    //     $gridAjaxInterface = $this->getContainer()->getService('GridAjaxInterface');
    //     $gridAjaxInterface->setPackageName('User');
    //     $gridAjaxInterface->setSubject('editFBSUser');
    //     $gridAjaxInterface->setEntity($grid->getGridName());
    //     $gridAjaxInterface->setEditActionParamChain('admin/FBSUser/edit');
    //     $gridAjaxInterface->setDeleteActionParamChain('admin/FBSUser/delete');
    //     $gridAjaxInterface->setDeleteResponseScript("AdminFBSUsersWidget.call();");
    //     $response = [
    //         'view' => $this->renderWidget('AdminFBSUsersWidget', $viewPath, [
    //             'container' => $this->getContainer(),
    //             'renderedGrid' => $grid->render(),
    //             'gridAjaxInterface' => $gridAjaxInterface->render()
    //         ]),
    //         'data' => []
    //     ];

    //     return $this->widgetResponse($response);
    // }

    /**
    * Route: [name: admin_FBSUsers_widget, paramChain: /admin/FBSUsers/widget]
    */
    public function adminFBSUsersWidgetAction()
    {
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $dataGridBuilder = new DataGridBuilder('AdminUserAccountsGrid');
        $dataGrid = $dataGridBuilder->getDataGrid();
        // $dataGrid->setDataGridId('AdminUserAccountsGrid');
        $dataGrid->setDeleteDisabled(true);
        // $dataGridBuilder->setValueConversion(['event_type' => $eventTypes]);
        $repo = $this->getService('FBSUserRepository');
        $repo->setProperties(['id', 'name', 'username', 'email', 'mobile']);
        $dataGrid->setPrimaryRepository($repo);
        //dump($dataGrid);exit;
        $response = $dataGrid->render();

        // $grid = $this->getListFBSUsersGrid();
        // $this->getContainer()->wireService('FrameworkPackage/service/GridAjaxInterface');
        // $gridAjaxInterface = new GridAjaxInterface();
        // $gridAjaxInterface->setGridName('editFBSUser');
        // $gridAjaxInterface->setEditActionParamChain('admin/FBSUser/edit');
        // $gridAjaxInterface->setDeleteActionParamChain('admin/FBSUser/delete');
        // $gridAjaxInterface->setDeleteResponseScript("UserAccountSearch.search(UserAccountGridPager.page);");
        // $viewPath = 'framework/packages/ToolPackage/view/grid/defaultGridWidget.php';
        // $response = [
        //     'view' => $this->renderWidget('AdminFBSUsersWidget', $viewPath, [
        //         'container' => $this->getContainer(),
        //         'renderedGrid' => $grid->render(),
        //         'gridAjaxInterface' => $gridAjaxInterface->render()
        //     ]),
        //     'data' => []
        // ];

        return $this->widgetResponse($response);
    }

    // public function getListFBSUsersGrid($filter = null, $page = 1)
    // {
    //     $this->getContainer()->wireService('ToolPackage/service/Grid/GridFactory');
    //     $gridFactory = new GridFactory();
    //     $gridFactory->setUsePager(false);
    //     $gridFactory->setGridName('editFBSUser');
    //     $gridFactory->setRepositoryServiceLink('UserPackage/repository/FBSUserRepository');
    //     $gridFactory->setProperties([
    //         ['name' => 'id', 'title' => 'id'],
    //         ['name' => 'name', 'title' => trans('name'), 'colWidth' => '2'],
    //         ['name' => 'username', 'title' => trans('username'), 'colWidth' => '2'],
    //         ['name' => 'email', 'title' => trans('email'), 'colWidth' => '4'],
    //         ['name' => 'highestPermissionGroup', 'title' => trans('highest.permission.group'), 'colWidth' => '4']
    //     ]);
    //     $grid = $gridFactory->create($filter, $page);
    //     return $grid;
    // }

    /**
    * Route: [name: admin_FBSUser_new, paramChain: /admin/FBSUser/new]
    */
    public function adminFBSUserNewAction()
    {
        return $this->adminFBSUserEditAction(true);
    }

    /**
    * Route: [name: admin_FBSUser_edit, paramChain: /admin/FBSUser/edit]
    */
    public function adminFBSUserEditAction($new = false)
    {
        // dump('alma');//exit;
        $this->setService('UserPackage/repository/FBSUserRepository');
        $repo = $this->getService('FBSUserRepository');
        // dump($repo->findAll());exit;
        $this->wireService('FormPackage/service/FormBuilder');

        if ($new) {
            $userId = null;
        } else {
            $userId = $this->getContainer()->getRequest()->get('id');
        }
// dump($this->getContainer()->getRequest()->getAll());
// dump($userId);exit;
// dump('adminUserAccountEditAction');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('UserPackage');
        $formBuilder->setSubject('editFBSUser');
        $formBuilder->setPrimaryKeyValue($userId);
        $formBuilder->setSaveRequested(false);
        $formBuilder->addExternalPost('id');
        $form = $formBuilder->createForm();
        // 6880b0b99fc49e050ca8529d45b41545
        // 6880b0b99fc49e050ca8529d45b41545

        if ($form->isSubmitted()) {
            // dump($form->getEntity());exit;
            // $form->getEntity()->setStatus(1);
            $form->getEntity()->getRepository()->store($form->getEntity());
        }

        // $form = $this->getService('FormBuilder')->createForm(
        //     'UserPackage',
        //     'editFBSUser',
        //     $this->getContainer()->getRequest()->get('FBSUserId')
        // );

        // dump($this->getContainer()->getRequest()->getAll());exit;

        $guestSelectedStr = '';
        $userSelectedStr = '';
        $projectAdminSelectedStr = '';
        $systemAdminSelectedStr = '';
        // dump($repo);//exit;
        $user = $repo->find($userId);
        // dump($user);exit;
        if (!$user) {
            # new user
            $user = new User();
        }

        // $permissionGroups = $user->getPermissionGroups();
        // dump($permissionGroups);

        // if (in_array('systemAdmin', $permissionGroups)) {
        //     $systemAdminSelectedStr = ' selected';
        // }
        // else {
        //     if (in_array('projectAdmin', $permissionGroups)) {
        //         $projectAdminSelectedStr = ' selected';
        //     }
        //     else {
        //         if (in_array('user', $permissionGroups)) {
        //             $userSelectedStr = ' selected';
        //         }
        //         else {
        //             if (in_array('guest', $permissionGroups)) {
        //                 $guestSelectedStr = ' selected';
        //             }
        //         }
        //     }
        // }

        // $viewPath = 'framework/packages/UserPackage/view/widget/AdminFBSUsersWidget/form.php';
        $viewPath = 'framework/packages/UserPackage/view/widget/AdminFBSUsersWidget/editFBSUser.php';
        $response = [
            'view' => $this->renderWidget('FBSUserEditForm', $viewPath, [
                'container' => $this->getContainer(),
                'ownUserHighestPermissionGroup' => $repo->find($this->getContainer()->getUser()->getId())->getHighestPermissionGroup(),
                'form' => $form,
                'userId' => $userId,
                'guestSelectedStr' => $guestSelectedStr,
                'userSelectedStr' => $userSelectedStr,
                'projectAdminSelectedStr' => $projectAdminSelectedStr,
                'systemAdminSelectedStr' => $systemAdminSelectedStr
            ]),
            'data' => [
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll()
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_FBSUser_delete, paramChain: /admin/user/delete]
    */
    public function adminFBSUserDeleteAction()
    {

    }

    /**
    * Route: [name: admin_intrusionAttempts_widget, paramChain: /admin/intrusionAttempts/widget]
    */
    public function adminIntrusionAttemptsWidgetAction()
    {
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $this->wireService('framework/kernel/security/repository/SecurityEventRepository');
        $this->setService('framework/kernel/security/SecurityEventHandler');
        $seh = $this->getService('SecurityEventHandler');

        // dump($this->getSession()->get('geoIpInfo'));exit;
        // dump($seh->events);
        $eventTypes = [];
        foreach ($seh->events as $eventType => $eventTypeSpecs) {
            $eventTypes[$eventType] = trans(BasicUtils::constantToTranslationFormat($eventType));
        }
        // dump($eventTypes);
        $dataGridBuilder = new DataGridBuilder('AdminIntrusionAttemptsDataGrid');
        $dataGridBuilder->setDeleteDisabled(true);
        $dataGridBuilder->setValueConversion(['event_type' => $eventTypes]);
        $dataGridBuilder->setPrimaryRepository($this->getService('SecurityEventRepository'));
        $dataGrid = $dataGridBuilder->getDataGrid();
        //dump($dataGrid);exit;
        $response = $dataGrid->render();
        // $viewPath = 'framework/packages/UserPackage/view/widget/AdminIntrusionAttemptsWidget/widget.php';
        // $response = [
        //     'view' => $this->renderWidget('FBSUserEditForm', $viewPath, [
        //         'container' => $this->getContainer()
        //     ]),
        //     'data' => []
        // ];
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_intrusionAttempt_edit, paramChain: /admin/intrusionAttempt/edit]
    */
    public function adminIntrusionAttemptEditAction()
    {
        $this->setService('UserPackage/repository/SecurityEventRepository');
        $repo = $this->getService('SecurityEventRepository');
        $viewPath = 'framework/packages/UserPackage/view/widget/AdminIntrusionAttemptsWidget/intrusionAttemptEdit.php';
        $id = $this->getContainer()->getRequest()->get('id');
        $secEvent = $repo->find($id);
        $response = [
            'view' => $this->renderWidget('adminIntrusionAttemptEdit', $viewPath, [
                'container' => $this->getContainer(),
                'secEvent' => $secEvent
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    // UsersWidget

    /**
    * Route: [name: admin_userAccounts_widget, paramChain: /admin/userAccounts/widget]
    */
    public function adminUserAccountsWidgetAction()
    {
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $dataGridBuilder = new DataGridBuilder('AdminUserAccountsDataGrid');
        $dataGridBuilder->setValueConversion(['status' => UserAccount::STATUS_CODE_CONVERSIONS]);
        $dataGridBuilder->addPropertyValueProcessStrategy('username', 'decrypt');
        $dataGridBuilder->addPropertyValueProcessStrategy('fullName', 'decrypt');
        $dataGridBuilder->addPropertyValueProcessStrategy('email', 'decrypt');
        $dataGridBuilder->addPropertyValueProcessStrategy('mobile', 'decrypt');
        $dataGridBuilder->setDeleteDisabled(true);
        $dataGridBuilder->setValueConversion(['isTester' => [
            '0' => trans('no'),
            '1' => trans('yes')
        ]]);
        $dataGridBuilder->addUseUnprocessedAsInputValue('isTester');
        // $dataGridBuilder->setValueConversion(['is_tester' => [
        //     '0' => trans('no'),
        //     '1' => trans('yes')
        // ]]);
        $dataGridBuilder->addPropertyInputType('isTester', 'multiselect');
        $dataGridBuilder->setPrimaryRepository($this->getService('UserAccountRepository'));
        //$dataGridBuilder->setEditActionRoute('admin_webshop_shipment_edit');
        $dataGrid = $dataGridBuilder->getDataGrid();

        // dump($dataGrid);exit;
        $response = $dataGrid->render();

        return $this->widgetResponse($response);


        // $grid = $this->getListUserAccountsGrid();
        // $this->getContainer()->wireService('FrameworkPackage/service/GridAjaxInterface');
        // $gridAjaxInterface = new GridAjaxInterface();
        // $gridAjaxInterface->setGridName('editUserAccount');
        // $gridAjaxInterface->setEditActionParamChain('admin/userAccount/edit');
        // $gridAjaxInterface->setDeleteActionParamChain('admin/userAccount/delete');
        // $gridAjaxInterface->setCallResponseScript("UserAccountSearch.search(UserAccountGridPager.page);");
        // $viewPath = 'framework/packages/UserPackage/view/widget/AdminUserAccountsWidget/widget.php';
        // $response = [
        //     'view' => $this->renderWidget('AdminUserAccountsWidget', $viewPath, [
        //         'container' => $this->getContainer(),
        //         'renderedGrid' => $grid->render(),
        //         'gridAjaxInterface' => $gridAjaxInterface->render()
        //     ]),
        //     'data' => []
        // ];
        // return $this->widgetResponse($response);
    }

    // public function getListUserAccountsGrid($filter = null, $page = 1)
    // {
    //     $this->getContainer()->wireService('ToolPackage/service/Grid/GridFactory');
    //     $gridFactory = new GridFactory();
    //     $gridFactory->setGridName('editUserAccount');
    //     $gridFactory->setRepositoryServiceLink('UserPackage/repository/UserAccountRepository');
    //     $gridFactory->setProperties([
    //         ['name' => 'id', 'title' => 'id'],
    //         ['name' => 'name', 'title' => trans('name'), 'colWidth' => '3'],
    //         ['name' => 'username', 'title' => trans('username'), 'colWidth' => '3'],
    //         ['name' => 'email', 'title' => trans('email'), 'colWidth' => '6'],
    //         ['name' => 'status', 'title' => null, 'colWidth' => null]
    //     ]);
    //     $filter['conditions'][] = ['key' => 'person.user_account_id', 'operator' => '>', 'value' => '0'];

    //     $grid = $gridFactory->create($filter, $page);
    //     //dump($filter);exit;
    //     // dump($grid);exit;
    //     return $grid;
    // }

    // public function getListUserAccountsGrid($filter = null, $page = 1)
    // {
    //     $this->getContainer()->wireService('UserPackage/repository/UserAccountRepository');
    //     $userAccountRepo = new UserAccountRepository();
    //     $this->getContainer()->setService('ToolPackage/entity/Grid');
    //     $grid = $this->getContainer()->getService('Grid');
    //     // $grid->setFormName('UserPackage_userAccountSearch_form');
    //     $grid->setGridName('userAccount');
    //     $grid->setPage($page);
    //     $grid->setTotalCount($userAccountRepo->getTotalCount($filter));
    //     $grid->setLimit(10);
    //     $grid->setAllowCreateNew(false);
    //     $grid->setData($userAccountRepo->getFilteredResult($filter, ['limit' => $grid->getLimit(), 'page' => $page]));
    //     // $grid->setViewPath('framework/packages/UserPackage/view/widget/AdminUserAccountsWidget/grid.php');
    //     $grid->setAddClassBy(array(
    //         array(
    //             'column' => 'status',
    //             'value' => 0,
    //             'class' => 'grid-body-row-disabled'
    //         ),
    //         array(
    //             'column' => 'status',
    //             'value' => 2,
    //             'class' => 'grid-body-row-proven'
    //         )
    //     ));
    //     $grid->setProperties([
    //         ['name' => 'id', 'title' => 'id'],
    //         ['name' => 'name', 'title' => trans('name'), 'colWidth' => '3'],
    //         ['name' => 'username', 'title' => trans('username'), 'colWidth' => '3'],
    //         ['name' => 'email', 'title' => trans('email'), 'colWidth' => '6'],
    //         ['name' => 'status', 'title' => null, 'colWidth' => null]
    //     ]);
    //     // $grid->setTotalPageCount();
    //     // dump($grid);exit;
    //     return $grid;
    // }

    /**
    * Route: [name: admin_user_search, paramChain: /admin/user/search]
    */
    // public function adminUserSearchAction()
    // {
    //     $searchAccountCode = $this->getRequest()->get('UserPackage_userAccountSearch_accountCode');
    //     $searchName = $this->getRequest()->get('UserPackage_userAccountSearch_name');
    //     $searchEmail = $this->getRequest()->get('UserPackage_userAccountSearch_email');
    //     $searchUsername = $this->getRequest()->get('UserPackage_userAccountSearch_username');
    //     $searchStatus = $this->getRequest()->get('UserPackage_userAccountSearch_status');
    //     $page = $this->getRequest()->get('page') ? $this->getRequest()->get('page') : 1;

    //     $filter = ['conditions' => [
    //         ['key' => 'code', 'value' => $searchAccountCode],
    //         ['key' => 'full_name', 'value' => $searchName],
    //         ['key' => 'email', 'value' => $searchEmail],
    //         ['key' => 'username', 'value' => $searchUsername],
    //         ['key' => 'status', 'value' => $searchStatus]
    //     ]];
    //     // dump($filter);exit;
    //     // $viewPath = 'framework/packages/UserPackage/view/widget/AdminUsersWidget/grid.php';
    //     $grid = $this->getListUserAccountsGrid($filter, $page);
    //     // dump($grid);exit;
    //     $response = [
    //         'view' => $grid->render(),
    //         'data' => []
    //     ];
    //     // dump($response);exit;
    //     return $this->widgetResponse($response);
    // }

    /**
    * Route: [name: admin_userAccount_edit, paramChain: /admin/userAccount/edit]
    */
    public function adminUserAccountEditAction()
    {
        $this->wireService('UserPackage/entity/UserAccount');
        $this->wireService('UserPackage/entity/Person');
        // $this->wireService('UserPackage/repository/UserAccountRepository');
        // $repo = new UserAccountRepository();
        $this->wireService('FormPackage/service/FormBuilder');
        $userAccountId = (int)$this->getContainer()->getRequest()->get('id');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('UserPackage');
        $formBuilder->setSubject('editUserAccount');
        $formBuilder->setPrimaryKeyValue($userAccountId);
        $formBuilder->addExternalPost('id');
        $form = $formBuilder->createForm();
        // dump($form);exit;
        $viewPath = 'framework/packages/UserPackage/view/widget/AdminUserAccountsWidget/editUserAccount.php';
        $response = [
            'view' => $this->renderWidget('EditUserAccountForm', $viewPath, [
                'container' => $this->getContainer(),
                'form' => $form,
                'userAccountId' => $userAccountId
                // 'guestSelectedStr' => $guestSelectedStr,
                // 'userSelectedStr' => $userSelectedStr,
                // 'projectAdminSelectedStr' => $projectAdminSelectedStr,
                // 'systemAdminSelectedStr' => $systemAdminSelectedStr
            ]),
            'data' => [
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                'label' => trans('edit.user')
                // 'request' => $this->getContainer()->getRequest()->getAll()
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: user_delete, paramChain: /user_delete]
    */
    public function adminPersonDeleteAction()
    {

    }
}
