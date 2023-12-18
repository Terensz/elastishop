<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;
use framework\packages\BasicPackage\repository\CountryRepository;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\UserPackage\entity\Address;
use framework\packages\WebshopPackage\service\WebshopService;
use framework\packages\WebshopPackage\service\WebshopTemporaryAccountService;

class WebshopResponseAssembler_EditAddressModal extends Service
{
    const ACTION_TYPE_ADD = 'Add';
    const ACTION_TYPE_EDIT = 'Edit';

    public static function assembleResponse($processedRequestData = null, $data = [])
    {
        App::getContainer()->setService('framework/packages/BasicPackage/repository/CountryRepository');
        // App::getContainer()->setService('framework/packages/UserPackage/repository/UserAccountRepository');
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('FormPackage/service/FormBuilder');
        App::getContainer()->wireService('UserPackage/entity/Address');

        // $countryRepo = App::getContainer()->getService('CountryRepository');
        // $userAccountRepo = App::getContainer()->getService('UserAccountRepository');
        // $userAccount = $userAccountRepo->find(App::getContainer()->getUser()->getId());
        $success = false;
        $userAccount = App::getContainer()->getUser()->getUserAccount();
        $addressId = null;
        $submitted = StringHelper::mendValue(App::getContainer()->getRequest()->get('submitted'));
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('WebshopPackage');
        $formBuilder->setSubject('editAddress');
        $formBuilder->addExternalPost('submitted');
        $actionType = self::ACTION_TYPE_ADD;
        if (isset($data['id']) && $data['id']) {
            $actionType = self::ACTION_TYPE_EDIT;
            $formBuilder->setPrimaryKeyValue(App::getContainer()->getRequest()->get('id'));
        }
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();
        $form->setSubmitted($submitted);
        // dump($form);exit;
        if ($form->isValid()) {
            $address = $form->getEntity();
            $addressRepo = $address->getRepository();

            /**
             * Logged-in user
            */
            if ($userAccount->getId()) {
                $address->setPerson($userAccount->getPerson());
                $address = $addressRepo->store($address);
                // dump($address);exit;
            /**
             * Guest
            */
            } else {
                // dump($address);exit;
                $address = $addressRepo->store($address);
                App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
                $temporaryAccount = WebshopTemporaryAccountService::getTemporaryAccount();
                $temporaryAccount->getTemporaryPerson()->setAddress($address);
                $temporaryAccount->getTemporaryPerson()->getRepository()->store($temporaryAccount->getTemporaryPerson());
            }

            // $address = WebshopService::setTemporaryAddress($address);
            // dump($address);exit;
            $addressId = $address->getId();
            $success = true;
        }

        $countryRepo = new CountryRepository();
        $viewParams = [
            'actionType' => $actionType,
            'form' => $form,
            'requests' => App::getContainer()->getRequest()->getAll(),
            'countries' => $countryRepo->findAllAvailable(),
            'streetSuffixes' => Address::CHOOSABLE_STREET_SUFFIXES
        ];

        $viewPath = 'framework/packages/WebshopPackage/view/Sections/Checkout/EditAddressModal.php';
        $view = ViewRenderer::renderWidget('WebshopResponseAssembler_EditAddressModal', $viewPath, $viewParams);

        return [
            'view' => $view,
            'data' => [
                'actionType' => $actionType,
                'modalLabel' => trans($actionType == self::ACTION_TYPE_ADD ? 'add.new.delivery.address' : 'edit.delivery.address'),
                // 'toastTitle' => trans('system.message'),
                // 'toastBody' => trans('address.saved'),
                'addressId' => $addressId,
                'success' => $success
            ]
        ];
    }

    public static function addAddress()
    {

    }
}