<?php
namespace framework\packages\WebshopPackage\controller;

use framework\component\parent\WidgetController;

class WebshopRegistrationWidgetController extends WidgetController
{
    public function __construct()
    {
    }

    public function webshopCheckoutRegistration_OLD()
    {
        $this->getContainer()->setService('UserPackage/controller/UserWidgetController');
        $userWidgetController = $this->getContainer()->getService('UserWidgetController');
        return $userWidgetController->userRegistrationWidgetAction();
    }

    /**
    * Route: [name: webshop_checkout_registration, paramChain: /webshop/checkout/registration]
    */
    public function webshopCheckoutRegistrationAction()
    {
        return $this->webshopCheckoutRegistration();
    }

    public function webshopCheckoutRegistration()
    {
        // dump('alma');exit;
        $this->setService('UserPackage/service/UserRegistrationService');
        $userRegistrationService = $this->getService('UserRegistrationService');
        
        return $userRegistrationService->handleRegistration([
            'registerViewPath' => 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/register/register.php',
            'successViewPath' => 'framework/packages/UserPackage/view/widget/UserRegistrationWidget/success.php',
            'formViewPath' => 'framework/packages/UserPackage/view/widget/UserRegistrationWidget/form.php',
            'userRegistrationSuccessfulPackageName' => 'UserPackage',
            'userRegistrationSuccessfulReferenceKey' => 'registrationSuccessful',
            'activationLinkFormat' => '[httpDomain]/registration/activation/[userAccountCode]-[regToken]'
        ]);
    }

    // public function webshopCheckoutRegistration_OLD()
    // {
    //     $this->wireService('UserPackage/entity/Address');
    //     $this->wireService('FormPackage/service/FormBuilder');
    //     $this->wireService('UserPackage/repository/UserAccountRepository');
    //     $this->wireService('UserPackage/repository/UserAccountRegistrationTokenRepository');
    //     $this->wireService('ToolPackage/service/Mailer');
    //     $this->wireService('LegalPackage/service/LegalDocumentFactory');
    //     $this->wireService('BasicPackage/repository/CountryRepository');

    //     $registerViewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/register/register.php';
    //     $successViewPath = 'framework/packages/UserPackage/view/widget/UserRegistrationWidget/success.php';
    //     $formViewPath = 'framework/packages/UserPackage/view/widget/UserRegistrationWidget/form.php';

    //     $email = null;
    //     $countryRepo = new CountryRepository();

    //     $tokenRepo = new RegTokenRepo();
    //     // dump($this->getSession()->get('visitorCode'));
    //     // dump($tokenRepo->findAll());exit;

    //     $formBuilder = new FormBuilder();
    //     $formBuilder->setPackageName('UserPackage');
    //     $formBuilder->setSubject('userRegistration');
    //     $formBuilder->addExternalPost('id');
    //     $formBuilder->addExternalPost('UserPackage_userRegistration_legalText');
    //     $formBuilder->setSaveRequested(false);
    //     $form = $formBuilder->createForm();

    //     $legalDocumentFactory = new LegalDocumentFactory();
    //     $legalText = $legalDocumentFactory->getCompleteText();
    //     // $viewName = !$form->isSubmitted() ? 'register' : ($form->isValid() ? 'success' : 'form');

    //     if ($form->isValid() === true) {
    //         $userAccountRepo = new UserAccountRepository();
    //         $userAccount = $form->getEntity();
    //         $userAccount = $userAccountRepo->storeUserRegistration($userAccount);
    //         $token = $tokenRepo->findOneBy(['conditions' => [['key' => 'user_account_id', 'value' => $userAccount->getId()]]]);

    //         $mailer = new Mailer();
    //         $mailer->setSubject(trans('registration.successful.title').' - '.$userAccount->getPerson()->getFullName());
    //         $mailer->textAssembler->setPackage('UserPackage');
    //         $mailer->textAssembler->setReferenceKey('registrationSuccessful');
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
    //         // dump($mailer);exit;
    //     }

    //     // $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/register/'.$viewName.'.php';
    //     $viewPath = !$form->isSubmitted() ? $registerViewPath : ($form->isValid() ? $successViewPath : $formViewPath);
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
}
