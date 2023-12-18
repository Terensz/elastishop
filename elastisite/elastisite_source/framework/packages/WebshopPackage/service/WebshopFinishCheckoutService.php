<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\UserPackage\entity\TemporaryPerson;
use framework\packages\UserPackage\entity\User;
use framework\packages\UserPackage\form\UserRegistrationCustomValidator;
use framework\packages\WebshopPackage\entity\Cart;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\entity\ShipmentItem;
use framework\packages\WebshopPackage\repository\CartRepository;
use framework\packages\WebshopPackage\repository\ShipmentItemRepository;
use framework\packages\WebshopPackage\repository\ShipmentRepository;

class WebshopFinishCheckoutService extends Service
{
    public static function loadCartDataToShipment()
    {
        App::getContainer()->wireService('WebshopPackage/entity/Shipment');
        App::getContainer()->wireService('WebshopPackage/entity/ShipmentItem');
        App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
        App::getContainer()->wireService('WebshopPackage/repository/ShipmentItemRepository');
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
        
        $cart = WebshopCartService::getCart();
        if (!$cart) {
            throw new \Exception('No cart, checkout is not permitted');
        }
        $errors = self::assembleCartErrors($cart);
        if (!$errors['summary']['checkoutPermitted']) {
            throw new \Exception('Invalid cart, checkout is not permitted');
        }
        if (count($cart->getCartItem()) < 1) {
            throw new \Exception('No cart items, checkout is not permitted');
        }

        $temporaryAccount = $cart->getTemporaryAccount();

        $shipmentItemRepository = new ShipmentItemRepository();
        // dump($temporaryAccount);
        // exit;

        $shipment = null;
        if ($cart->getShipment()) {
            $shipment = $cart->getShipment();
        } else {
            $shipment = new Shipment();
            $shipment->setCart($cart);
            $shipment->setCode(ShipmentRepository::createCode());
            $shipment->setVisitorCode(App::getContainer()->getSession()->get('visitorCode'));
            $shipment->setUserAccount(App::getContainer()->getUser()->getUserAccount()->getId() ? App::getContainer()->getUser()->getUserAccount() : null);
            $shipment->setStatus(Shipment::SHIPMENT_STATUS_ORDER_PREPARED);
            $shipment->setTemporaryAccount($temporaryAccount);
            $shipment = $shipment->getRepository()->store($shipment);

            foreach ($cart->getCartItem() as $cartItem) {
                $shipmentItem = new ShipmentItem();
                $shipmentItem->setShipment($shipment);
                $shipmentItem->setProduct($cartItem->getProduct());
                $shipmentItem->setProductPrice($cartItem->getProductPrice());
                $shipmentItem->setQuantity($cartItem->getQuantity());
                $shipmentItem = $shipmentItemRepository->store($shipmentItem);
                // $shipment->addShipmentItem($shipmentItem);
            }
            // $cart->setShipment($shipment);
            // $cart->getRepository()->storeShipmentId($cart->getId(), $shipment->getId());
            // $shipment->setAllShipmentItems($shipmentItems);
        }


        // dump('ennyi2');exit;

        if ($shipment) {
            $cart->getRepository()->rudeRemove($cart->getId());
            // $cart->setTemporaryAccount(null);
            // // $cart->setTemporaryAccount(null);
            // $cart = $cart->getRepository()->store($cart);




            // CartRepository::removeObsolete(
            //     [['refKey' => 'c.id', 'paramKey' => 'cart_id', 'operator' => '=', 'value' => $cart->getId()]],
            //     false
            // );
        }

        return $shipment;

        // dump($shipmentItem);
        // dump($shipment);exit;
    }

    // public static function createShipment() : Shipment
    // {
    //     App::getContainer()->wireService('WebshopPackage/entity/Shipment');
    //     $shipment = new Shipment();

    //     return $shipment;
    // }

    // public static function createShipmentItem() : ShipmentItem
    // {
    //     App::getContainer()->wireService('WebshopPackage/entity/ShipmentItem');
    //     $shipmentItem = new ShipmentItem();

    //     return $shipmentItem;
    // }

    public static function assembleCartErrors(Cart $cart)
    {
        App::getContainer()->wireService('UserPackage/entity/TemporaryPerson');
        App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
        $temporaryAccountData = WebshopTemporaryAccountService::assembleTemporaryAccountData($cart->getTemporaryAccount());
        // dump($temporaryAccountData); dump($cart); exit;

        $customerType = $temporaryAccountData['temporaryPerson']['customerType'];
        // $customerTypeSelected = !empty($customerType);
        /**
         * For individuals (private person) that should always be true.
        */
        $organization = $temporaryAccountData['temporaryPerson']['organization'];
        $organizationMissingError = $customerType == TemporaryPerson::CUSTOMER_TYPE_ORGANIZATION ? empty($organization['id']) : false;
        // dump($organizationMissingError);
        // dump($customerType);
        // dump($organization);
        /**
         * If addres has country and string, than it's okay.
        */
        $addressMissingError = $temporaryAccountData['temporaryPerson']['address']['country']['alpha2'] && $temporaryAccountData['temporaryPerson']['address']['string'] ? false : true;

        $termsAndConditionsAcceptanceMissingError = $temporaryAccountData['temporaryPerson']['termsAndConditionsAccepted'] ? false : true;

        // $userType = App::getContainer()->getUser()->getType();
        // if ($userType == User::TYPE_GUEST) {
        // }

        // dump($temporaryAccountData['temporaryPerson']);exit;
        
        $errors = [
            'messages' => [
                'customerTypeSelected' => !$customerType ? trans('please.select.customer.type') : null,
                'organizationSelected' => $organizationMissingError ? trans('please.select.organization') : null,
                'addressSelected' => $addressMissingError ? trans('please.select.delivery.address') : null,
                'termsAndConditionsAccepted' => $termsAndConditionsAcceptanceMissingError ? trans('please.accept.terms.and.conditions') : null,
                'recipientNameFilled' => !$temporaryAccountData['temporaryPerson']['displayedName'] ? trans('missing.recipient.name') : null,
                'emailValid' => empty($temporaryAccountData['temporaryPerson']['email']) ? trans('missing.contact.email') : null,
                // 'emailValid' => null,
                'mobileFilled' => !$temporaryAccountData['temporaryPerson']['mobile'] ? trans('missing.contact.mobile') : null,
            ],
            'summary' => [
                'checkoutPermitted' => null
            ]
        ];

        if (!empty($temporaryAccountData['temporaryPerson']['email'])) {
            App::getContainer()->wireService('UserPackage/form/UserRegistrationCustomValidator');
            $userRegistrationCustomValidator = new UserRegistrationCustomValidator();
            $emailValidation = $userRegistrationCustomValidator->validateEmail($temporaryAccountData['temporaryPerson']['email'], false, null);
            if (!$emailValidation['result']) {
                $errors['messages']['emailValid'] = $emailValidation['message'];
            }
            // dump($emailIsValid);exit;
        }

        $countErrors = 0;
        foreach ($errors['messages'] as $key => $value) {
            if ($value !== null) {
                $countErrors++;
            }
        }

        $errors['summary']['checkoutPermitted'] = $countErrors == 0 ? true : false;
        // dump($errors);exit;

        return $errors;
    }
}