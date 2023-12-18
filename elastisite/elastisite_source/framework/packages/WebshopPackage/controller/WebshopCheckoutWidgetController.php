<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\BusinessPackage\repository\OrganizationRepository;
use framework\packages\UserPackage\entity\User;
use framework\packages\UserPackage\repository\AddressRepository;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler_Checkout;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\service\WebshopFinishCheckoutService;
use framework\packages\WebshopPackage\service\WebshopTemporaryAccountService;

class WebshopCheckoutWidgetController extends WidgetController
{
    public function __construct()
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        WebshopCartService::checkAndExecuteTriggers();
    }

    /**
    * Route: [name: webshop_WebshopCheckoutWidget, paramChain: /webshop/WebshopCheckoutWidget]
    */
    public function webshopCheckoutWidgetAction()
    {
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        return WebshopResponseAssembler::render(WebshopResponseAssembler::SECTION_CHECKOUT); //exit;
    }

    /**
    * Route: [name: webshop_checkout_addOrganization, paramChain: /webshop/checkout/addOrganization]
    */
    public function webshopCheckoutAddOrganizationAction()
    {
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        return WebshopResponseAssembler::renderSections([WebshopResponseAssembler::SECTION_EDIT_ORGANIZATION_MODAL]); //exit;
    }

    /**
    * Route: [name: webshop_checkout_editOrganization, paramChain: /webshop/checkout/editOrganization]
    */
    public function webshopCheckoutEditOrganizationAction()
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
        $temporaryAccount = WebshopTemporaryAccountService::getTemporaryAccount();
        $temporaryAccountData = WebshopTemporaryAccountService::assembleTemporaryAccountData($temporaryAccount);
        // dump($temporaryAccountData);exit;
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        return WebshopResponseAssembler::renderSections([WebshopResponseAssembler::SECTION_EDIT_ORGANIZATION_MODAL], ['id' => $temporaryAccountData['temporaryPerson']['organization']['id']]); //exit;
    }

    /**
    * Route: [name: webshop_checkout_addAddress, paramChain: /webshop/checkout/addAddress]
    */
    public function webshopCheckoutAddAddressAction()
    {
        // dump('alma');
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        return WebshopResponseAssembler::renderSections([WebshopResponseAssembler::SECTION_EDIT_ADDRESS_MODAL]); //exit;
    }

    /**
    * Route: [name: webshop_checkout_editAddress, paramChain: /webshop/checkout/editAddress]
    */
    public function webshopCheckoutEditAddressAction()
    {
        $idRequest = App::getContainer()->getRequest()->get('id');
        App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
        $temporaryAccount = WebshopTemporaryAccountService::getTemporaryAccount();
        $temporaryAccountData = WebshopTemporaryAccountService::assembleTemporaryAccountData($temporaryAccount);
        $temporaryAddressId = $temporaryAccountData['temporaryPerson']['address']['id'];
        // $temporaryAccount = WebshopTemporaryAccountService::getTemporaryAccount();
        // $temporaryAddress = $temporaryAccount->getTemporaryPerson()->getAddress();

        $id = null;

        if (App::getContainer()->getUser()->getType() == User::TYPE_GUEST) {
            if ($idRequest == $temporaryAddressId) {
                $id = $idRequest;
            } else {
                // dump($idRequest);
                // dump($temporaryAddressId);
                // dump($temporaryAccountData);
                throw new \Exception('Illegal id.');
            }
        }

        if (App::getContainer()->getUser()->getType() == User::TYPE_USER) {
            /**
             * We are checking, if the posted id belongs to the authenticated user (since more address can belong to an authenticated person)
            */
            App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_Checkout');
            $addressesData = WebshopResponseAssembler_Checkout::collectAddressesData();
            $addressFound = false;
            foreach ($addressesData as $addressData) {
                if ($addressData['addressId'] == $idRequest) {
                    $addressFound = true;
                }
            }

            /**
             * If it's an authenticated youser, their posted id must be the temporary, or belong to one which is owned by the user.
            */
            if ($idRequest == $temporaryAddressId || $addressFound) {
                $id = $idRequest;
            } else {
                throw new \Exception('Illegal id.');
            }
        }

        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        return WebshopResponseAssembler::renderSections([WebshopResponseAssembler::SECTION_EDIT_ADDRESS_MODAL], ['id' => $id]); //exit;
    }

    /**
    * Route: [name: webshop_checkout_selectCustomerType, paramChain: /webshop/checkout/selectCustomerType]
    */
    public function webshopCheckoutSelectCustomerTypeAction()
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
        $customerType = App::getContainer()->getRequest()->get('customerType');
        WebshopTemporaryAccountService::setTemporaryPersonData('customerType', $customerType);
        // // dump(App::getContainer()->getRequest()->getAll());exit;
        // $cart = WebshopCartService::getCart();
        // if ($cart && !empty($customerType)) {
        //     $cart->setCustomerType($customerType);
        //     $cart->getRepository()->store($cart);
        // }

        $response = [
            'view' => '',
            'data' => [
                'customerType' => $customerType
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_checkout_selectAddress, paramChain: /webshop/checkout/selectAddress]
    */
    public function webshopCheckoutSelectAddressAction()
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
        App::getContainer()->wireService('UserPackage/repository/AddressRepository');
        $repo = new AddressRepository();
        $addressId = (int)App::getContainer()->getRequest()->get('id');
        $address = $repo->find($addressId);
        WebshopTemporaryAccountService::setAddress($address);

        // $cart = WebshopCartService::getCart();
        // $cart->setAddress($address);
        // $cart = $cart->getRepository()->store($cart);

        $addressId = (int)App::getContainer()->getRequest()->get('id');
        $response = [
            'view' => '',
            'data' => [
                'addressId' => $addressId
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_checkout_selectOrganization, paramChain: /webshop/checkout/selectOrganization]
    */
    public function webshopCheckoutSelectOrganizationAction()
    {
        // App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
        App::getContainer()->wireService('BusinessPackage/repository/OrganizationRepository');
        $repo = new OrganizationRepository();
        $organizationId = (int)App::getContainer()->getRequest()->get('id');
        $organization = $repo->find($organizationId);
        WebshopTemporaryAccountService::setTemporaryPersonData('organization', $organization);

        $response = [
            'view' => '',
            'data' => [
                'organizationId' => $organizationId
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_checkout_acceptTermsAndConditions, paramChain: /webshop/checkout/acceptTermsAndConditions]
    */
    public function webshopCheckoutAcceptTermsAndConditionsAction()
    {
        // App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        // $cart = WebshopCartService::getCart();
        App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
        $termsAndConditionsAccepted = WebshopTemporaryAccountService::getTemporaryPersonData('termsAndConditionsAccepted');
        WebshopTemporaryAccountService::setTemporaryPersonData('termsAndConditionsAccepted', $termsAndConditionsAccepted ? 0 : 1);

        $response = [
            'view' => '',
            'data' => [
                // 'organizationId' => $organizationId
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_checkout_saveDeliveryInformation, paramChain: /webshop/checkout/saveDeliveryInformation]
    */
    public function webshopCheckoutSaveDeliveryInformationAction()
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
        // $cart = WebshopCartService::getCart();
        $recipientName = App::getContainer()->getRequest()->get('recipientName');
        WebshopTemporaryAccountService::setTemporaryPersonData('recipientName', $recipientName);
        $email = App::getContainer()->getRequest()->get('email');
        WebshopTemporaryAccountService::setTemporaryPersonData('email', $email);
        $mobile = App::getContainer()->getRequest()->get('mobile');
        WebshopTemporaryAccountService::setTemporaryPersonData('mobile', $mobile);
        $customerNote = App::getContainer()->getRequest()->get('customerNote');
        WebshopTemporaryAccountService::setTemporaryPersonData('customerNote', $customerNote);
        // $cart->getRepository()->store($cart);

        $response = [
            'view' => '',
            'data' => [
                // 'organizationId' => $organizationId
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_checkout_initCloseCart, paramChain: /webshop/checkout/initCloseCart]
    */
    // public function webshopCheckoutInitCloseCartAction()
    // {
    //     App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
    //     $cart = WebshopCartService::getCart();
    //     $cart->setRecipient(App::getContainer()->getRequest()->get('recipient'));
    //     $cart->setNote(App::getContainer()->getRequest()->get('note'));
    //     $cart->getRepository()->store($cart);

    //     $response = [
    //         'view' => '',
    //         'data' => [
    //             // 'organizationId' => $organizationId
    //         ]
    //     ];

    //     return $this->widgetResponse($response);
    // }


    /**
    * Route: [name: webshop_checkout_saveNote, paramChain: /webshop/checkout/saveNote]
    */
    // public function webshopCheckoutSaveNoteAction()
    // {
    //     App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
    //     $cart = WebshopCartService::getCart();
    // }
}
