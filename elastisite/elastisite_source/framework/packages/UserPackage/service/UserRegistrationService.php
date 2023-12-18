<?php
namespace framework\packages\UserPackage\service;

use framework\component\parent\WidgetController;
use framework\packages\FormPackage\service\FormBuilder;

use framework\packages\UserPackage\entity\Address;
use framework\packages\UserPackage\repository\UserAccountRepository;
use framework\packages\ToolPackage\service\Mailer;
use framework\packages\UserPackage\repository\UserAccountRegistrationTokenRepository as RegTokenRepo;
use framework\packages\LegalPackage\service\LegalDocumentFactory;
use framework\packages\BasicPackage\repository\CountryRepository;

class UserRegistrationService extends WidgetController
{
    public function handleRegistration($settings)
    {
        $this->wireService('UserPackage/entity/Address');
        $this->wireService('FormPackage/service/FormBuilder');
        $this->wireService('UserPackage/repository/UserAccountRepository');
        $this->wireService('UserPackage/repository/UserAccountRegistrationTokenRepository');
        $this->wireService('ToolPackage/service/Mailer');
        $this->wireService('LegalPackage/service/LegalDocumentFactory');
        $this->wireService('BasicPackage/repository/CountryRepository');

        // $registerViewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/register/register.php';
        // $successViewPath = 'framework/packages/UserPackage/view/widget/UserRegistrationWidget/success.php';
        // $formViewPath = 'framework/packages/UserPackage/view/widget/UserRegistrationWidget/form.php';

        $email = null;
        $countryRepo = new CountryRepository();

        $tokenRepo = new RegTokenRepo();
        // dump($this->getSession()->get('visitorCode'));
        // dump($tokenRepo->findAll());exit;

        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('UserPackage');
        $formBuilder->setSubject('userRegistration');
        $formBuilder->addExternalPost('id');
        $formBuilder->addExternalPost('UserPackage_userRegistration_legalText');
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();

        $legalDocumentFactory = new LegalDocumentFactory();
        $legalText = $legalDocumentFactory->getCompleteText();
        // $viewName = !$form->isSubmitted() ? 'register' : ($form->isValid() ? 'success' : 'form');

        if ($form->isValid() === true) {
            $userAccountRepo = new UserAccountRepository();
            $userAccount = $form->getEntity();
            // $userAccount->getNewsletterSubscription()->setUserAccount($userAccount);
            // dump($userAccount);exit;
            $userAccount = $userAccountRepo->storeUserRegistration($userAccount);
            // dump($userAccount);exit;
            $token = $tokenRepo->findOneBy(['conditions' => [['key' => 'user_account_id', 'value' => $userAccount->getId()]]]);
            // user_account_registration_token
            if (!$token) {
                dump($userAccount);
                dump($tokenRepo->findAll());
            }
            $activationLink = $settings['activationLinkFormat'];
            $activationLink = str_replace('[httpDomain]', $this->getUrl()->getHttpDomain(), $activationLink);
            $activationLink = str_replace('[userAccountCode]', $userAccount->getCode(), $activationLink);
            $activationLink = str_replace('[regToken]', $token->getToken(), $activationLink);

            $mailer = new Mailer();
            $mailer->setSubject(trans('registration.successful.title').' - '.$userAccount->getPerson()->getFullName());
            $mailer->textAssembler->setPackage('UserPackage');
            $mailer->textAssembler->setReferenceKey('registrationSuccessful');
            $mailer->textAssembler->setPlaceholdersAndValues([
                'name' => $userAccount->getPerson()->getFullName(),
                'domain' => $this->getUrl()->getHttpDomain(),
                'activationLink' => $activationLink,
            ]);
            $mailer->textAssembler->create();
            $mailer->setBody($mailer->textAssembler->getView());
            $email = $userAccount->getPerson()->getEmail();
            $mailer->addRecipient($email, $userAccount->getPerson()->getFullName());
            $mailer->send();
            // dump($mailer);exit;
        }

        // $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/register/'.$viewName.'.php';
        $viewPath = !$form->isSubmitted() ? $settings['registerViewPath'] : ($form->isValid() ? $settings['successViewPath'] : $settings['formViewPath']);
        $response = [
            'view' => $this->renderWidget('userRegistration', $viewPath, [
                'container' => $this->getContainer(),
                'form' => $form,
                'legalText' => $legalText,
                'email' => $email,
                'countries' => $countryRepo->findAllAvailable(),
                'streetSuffixes' => Address::CHOOSABLE_STREET_SUFFIXES
            ]),
            'data' => [
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll()
            ]
        ];

        return $this->widgetResponse($response);
    }
}
