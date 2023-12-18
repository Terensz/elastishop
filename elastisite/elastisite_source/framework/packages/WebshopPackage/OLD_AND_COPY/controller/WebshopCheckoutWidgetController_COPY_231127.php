<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\kernel\utility\FileHandler;
use framework\packages\BasicPackage\entity\Country;
use framework\packages\BusinessPackage\entity\Organization;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\LegalPackage\controller\CookieConsentService;
use framework\packages\LegalPackage\entity\VisitorConsentAcceptance;
use framework\packages\UserPackage\entity\Address;
use framework\packages\ToolPackage\service\Mailer;
use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\ToolPackage\service\TextAssembler;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\service\WebshopPriceService;
use framework\packages\WebshopPackage\service\WebshopService;

class WebshopCheckoutWidgetController extends WidgetController
{
    public function __construct()
    {
        $this->getContainer()->setService('WebshopPackage/service/WebshopService');
        $this->getContainer()->setService('WebshopPackage/service/WebshopPriceService');
    }

    /**
    * Route: [name: webshop_checkoutSideWidget, paramChain: /webshop/checkoutSideWidget]
    */
    public function webshopCheckoutSideWidgetAction()
    {
        $this->getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutSideWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('WebshopCheckoutSideWidget', $viewPath, [
                'cart' => WebshopCartService::getCart()
                // 'container' => $this->getContainer()
                // 'webshopSettings' => $webshopSettings
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response); 
    }

    /**
    * Route: [name: webshop_checkoutWidget, paramChain: /webshop/checkoutWidget]
    */
    public function webshopCheckoutWidgetAction()
    {
        $this->wireService('LegalPackage/service/CookieConsentService');
        $this->wireService('LegalPackage/entity/VisitorConsentAcceptance');
        $thirdPartyCookiesAcceptance = CookieConsentService::findThirdPartyCookiesAcceptances(false, 'Barion');
        // dump($thirdPartyCookiesAcceptance);
        if ($thirdPartyCookiesAcceptance && $thirdPartyCookiesAcceptance->getAcceptance() == VisitorConsentAcceptance::ACCEPTANCE_REFUSED) {
            return $this->displayCookieWasRefusedContent();
        } elseif (!$thirdPartyCookiesAcceptance) {
            App::redirect('/webshop');
        }
        
        return $this->displayCheckoutContent('widget');
    }

    public function displayCookieWasRefusedContent()
    {
        $this->wireService('ToolPackage/service/TextAssembler');
        $textAssembler = new TextAssembler();
        // dump($this->getContentTextService($subscriber));
        // $textAssembler->setContentTextService($this->getContentTextService());
        $textAssembler->setDocumentType('entry');
        $textAssembler->setPackage('WebshopPackage');
        $textAssembler->setReferenceKey('BarionCookieWasRefused');
        $textAssembler->setPlaceholdersAndValues([
            'httpDomain' => $this->getUrl()->getHttpDomain()
        ]);
        $textAssembler->create();
        $textView = $textAssembler->getView();

        // dump($textView);exit;

        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/cookieWasRefused.php';
        $response = [
            'view' => $this->renderWidget('WebshopCheckoutWidget', $viewPath, [
                'textView' => $textView,
                'isWebshopTestMode' => WebshopService::isWebshopTestMode(),
                'httpDomain' => App::getContainer()->getUrl()->getHttpDomain()
            ]),
            'data' => [
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_checkoutFlexibleContent, paramChain: /webshop/checkoutFlexibleContent]
    */
    public function webshopCheckoutFlexibleContentAction()
    {
        return $this->displayCheckoutContent('widgetFlexibleContent');
    }

    public function displayCheckoutContent($viewFile)
    {
        // $webshopService = $this->getContainer()->getService('WebshopService');
        App::getContainer()->wireService('WebshopService');
        App::getContainer()->wireService('WebshopCartService');
        $this->getContainer()->setService('WebshopPackage/repository/ShipmentRepository');

        $user = $this->getContainer()->getUser();

        if (!$user->getUsername() && WebshopService::getSetting('WebshopPackage_onlyRegistratedUsersCanCheckout')) {
            $this->getContainer()->setService('WebshopPackage/controller/WebshopRegistrationWidgetController');
            $webshopRegistrationWidgetController = $this->getContainer()->getService('WebshopRegistrationWidgetController');

            return $webshopRegistrationWidgetController->webshopCheckoutRegistration();
        }

        if (WebshopService::hasUnconfirmedOrder()) {
            return $this->returnFinalizeOrder();
        }

        if ($this->getContainer()->getUser()->getType() == $this->getContainer()->getUser()::TYPE_ADMINISTRATOR) {
            $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/widget.php';

            $response = [
                'view' => $this->renderWidget('WebshopCheckoutWidget', $viewPath, [
                    'isWebshopTestMode' => WebshopService::isWebshopTestMode(),
                    // 'checkoutIsUnavailableWithThisUser' => true,
                    'outputView' => 'checkoutIsUnavailableWithThisUser.php',
                    'advanceForm' => false,
                    'validateForm' => false,
                    'cart' => null,
                    'defaultCurrency' => App::getContainer()->getConfig()->getProjectData('defaultCurrency'),
                    'httpDomain' => App::getContainer()->getUrl()->getHttpDomain()
                ]),
                'data' => [
                ]
            ];

            return $this->widgetResponse($response);
        }

        $addresses = [];
        if ($user->getUserAccount() && $user->getUserAccount()->getPerson()) {
            $addresses = $user->getUserAccount()->getPerson()->getAddress();
        }

        // dump($user);exit;
        
        $cart = WebshopCartService::getCart();
        $this->getContainer()->setService('framework/packages/UserPackage/repository/UserAccountRepository');
        $this->getContainer()->setService('framework/packages/UserPackage/repository/AddressRepository');
        $this->wireService('FormPackage/service/FormBuilder');

        $advanceForm = $this->getContainer()->getRequest()->get('advanceForm');
        $advanceForm = $advanceForm == 'true' ? true : false;

        $validateForm = $this->getContainer()->getRequest()->get('validateForm');
        $validateForm = $validateForm == 'true' ? true : false;

        // dump(WebshopService::getCart());exit;

        $submitted = $this->getContainer()->getRequest()->get('submitted');
        // $recipient = $this->getContainer()->getRequest()->get('WebshopPackage_checkout_recipient');
        $selectedAddress = $this->getContainer()->getRequest()->get('WebshopPackage_checkout_address');
        $errors = 0;
        $userAccount = $this->getContainer()->getService('UserAccountRepository')->find($this->getContainer()->getUser()->getId());
        $userAccount = $userAccount ? $userAccount : null;

        $email = null;
        $mobile = null;
        $address = null;
        if ($this->getSession()->userLoggedIn()) {
            $email = $userAccount->getPerson()->getEmail();
            $mobile = $userAccount->getPerson()->getMobile();
            $address = $userAccount->getPerson()->getDefaultAddress();
            // $recipient = $recipient ? $recipient : $userAccount->getPerson()->getFullName();
        }

        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('WebshopPackage');
        $formBuilder->setSubject('checkout');
        $formBuilder->addExternalPost('advanceForm');
        $formBuilder->addExternalPost('submitted');
        $formBuilder->setSaveRequested(false);
        $formBuilder->addDefaultValue('email', $email);
        $formBuilder->addDefaultValue('mobile', $mobile);
        $form = $formBuilder->createForm();

        $recipient = $form->getValueCollector()->getDisplayed('recipient');

        if ($this->getSession()->userLoggedIn()) {
            $recipient = $recipient ? $recipient : $userAccount->getPerson()->getFullName();
        }

        $tempAcc = WebshopService::findOrCreateTemporaryAccount();

        // dump($tempAcc);exit;

        $fillingAddressIsRequired = true;
        $corporateShipmentEnabled = true;
        // Fixen false
        $registered = false;
        $pickUpPointsView = null;
        // dump(App::$projectPathBase);
        // App::$projectPathBase = App::getContainer()->getPathBase('projects').'/projects/'.App::getWebProject();
        // dump(App::$projectPathBase);exit;
        $pathToMiddlewareCheckoutModifications = App::$projectPathBase.'/middleware/checkoutModifications/CheckoutModifications.php';
        // dump($pathToMiddlewareCheckoutModifications);
        // dump(FileHandler::fileExists($pathToMiddlewareCheckoutModifications));exit;
        if (FileHandler::fileExists($pathToMiddlewareCheckoutModifications)) {
            App::getContainer()->setService('projects/'.App::getWebProject().'/middleware/checkoutModifications/CheckoutModifications', 'CheckoutModifications');
            $checkoutModifications = App::getContainer()->getService('CheckoutModifications')->get($tempAcc, $validateForm);
            $fillingAddressIsRequired = isset($checkoutModifications['fillingAddressIsRequired']) ? $checkoutModifications['fillingAddressIsRequired'] : true;
            $corporateShipmentEnabled = isset($checkoutModifications['corporateShipmentEnabled']) ? $checkoutModifications['corporateShipmentEnabled'] : true;
            $pickUpPointsView = isset($checkoutModifications['pickUpPointsView']) ? $checkoutModifications['pickUpPointsView'] : null;
            // dump($service);exit;
            $address = isset($checkoutModifications['address']) ? $checkoutModifications['address'] : null;
        }

        if (!$address) {
            $address = $tempAcc ? $tempAcc->getTemporaryPerson()->getAddress() : null;
        }
        
        /**
         * Creating temp address
        */
        if ($address && $tempAcc->getTemporaryPerson() && !$tempAcc->getTemporaryPerson()->getAddress()) {
            $tempAddress = clone $address;
            $tempAddress->setId(null);
            $tempAddress->setPerson(null);
            $tempAddress->getRepository()->store($tempAddress);
            $tempAcc->getTemporaryPerson()->setAddress($tempAddress);
            $tempAcc->getRepository()->store($tempAcc);
            // dump($tempAddress);
        }

        // dump($address);
        // dump($tempAcc);exit;

        // $organization = null;
        $organizationData = [];
        // dump($form->getValueCollector());exit;
        // dump(new Country(348));exit;

        $addressMessage = null;
        if ($submitted && $advanceForm) {
            $notice = $form->getValueCollector()->getDisplayed('notice');

            if (!$this->getSession()->userLoggedIn()) {
                $email = $form->getValueCollector()->getDisplayed('email');
                $mobile = $form->getValueCollector()->getDisplayed('mobile');
                
                if (!$address) {
                    $addressMessage = trans('missing.delivery.address');
                    $errors++;
                }
            }
            if ($this->getSession()->userLoggedIn()) {
                if (!$address) {
                    $addressMessage = trans('invalid.delivery.address');
                    $errors++;
                }
            }

            if ($errors == 0 && $form->isValid()) {
                if ($form->getValueCollector()->getDisplayed('organizationName')) {
                    $organizationData['name'] = $form->getValueCollector()->getDisplayed('organizationName');
                    $organizationData['taxId'] = $form->getValueCollector()->getDisplayed('taxId');
                    $organizationData['orgCountry'] = $form->getValueCollector()->getDisplayed('orgCountry');
                    $organizationData['orgZipCode'] = $form->getValueCollector()->getDisplayed('orgZipCode');
                    $organizationData['orgCity'] = $form->getValueCollector()->getDisplayed('orgCity');
                    $organizationData['orgStreet'] = $form->getValueCollector()->getDisplayed('orgStreet');
                    $organizationData['orgStreetSuffix'] = $form->getValueCollector()->getDisplayed('orgStreetSuffix');
                    $organizationData['orgHouseNumber'] = $form->getValueCollector()->getDisplayed('orgHouseNumber');
                    // dump($organization);exit;
                }

                return $this->createShipment(
                    $userAccount, 
                    $organizationData,
                    $tempAcc, 
                    $address, 
                    $email,
                    $mobile,
                    $recipient, 
                    $notice
                );
            }
            // else {
            //     // dump($form->isValid());dump($form);exit;
            // }
        }

        // dump($tempAcc);exit;
        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/'.$viewFile.'.php';
// dump($advanceForm);exit;
        $response = [
            'view' => $this->renderWidget('WebshopCheckoutWidget', $viewPath, [
                // 'checkoutIsUnavailableWithThisUser' => false,
                'unfilteredProductListLink' => App::getContainer()->getRoutingHelper()->getLink('webshop_productList_noFilter'),
                'outputView' => $cart ? 'form.php' : 'cartLost.php',
                'isWebshopTestMode' => WebshopService::isWebshopTestMode(),
                'recipient' => $recipient,
                'cart' => $cart,
                'userAccount' => $userAccount,
                'advanceForm' => $advanceForm,
                'validateForm' => $validateForm,
                'selectedAddress' => $selectedAddress,
                'registered' => $registered, // Fixen false
                'fillingAddressIsRequired' => $fillingAddressIsRequired,
                'corporateShipmentEnabled' => $corporateShipmentEnabled,
                'pickUpPointsView' => $pickUpPointsView,
                'address' => $address,
                'addresses' => $addresses,
                'addressMessage' => $addressMessage,
                'allowRemoveLastCartItem' => true,
                // 'cartLost' => $cartLost,
                'email' => $email,
                'mobile' => $mobile,
                'form' => $form,
                'defaultCurrency' => App::getContainer()->getConfig()->getProjectData('defaultCurrency'),
                'httpDomain' => App::getContainer()->getUrl()->getHttpDomain()
            ]),
            'data' => [
                'agreementMessage' => $form->getValueCollector()->getValue('agreement', 'displayed'),
                'recipientMessage' => $form->getValueCollector()->getValue('recipient', 'displayed'),
                'focusOnField' => !empty($this->getContainer()->getRequest()->get('focusOnField')) ? $this->getContainer()->getRequest()->get('focusOnField') : null,
                'advanceForm' => $advanceForm,
                'validateForm' => $validateForm
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_checkout_addToCart, paramChain: /webshop/checkout/addToCart]
    */
    public function webshopCheckoutAddToCartAction()
    {
        $productPriceActiveId = (int)$this->getContainer()->getRequest()->get('offerId');
        $webshopService = $this->getContainer()->getService('WebshopService');
        $newQuantity = $this->getContainer()->getRequest()->get('newQuantity');
        if ($newQuantity !== null && !is_numeric($newQuantity)) {
            $newQuantity = 0;
        }
        $addedQuantity = $newQuantity === null ? 1 : null;
        $cartItem = $webshopService->addToCart($productPriceActiveId, $addedQuantity, $newQuantity);
        $cart = WebshopService::getCart();

        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/cartContent.php';
        $response = [
            'view' => $this->renderWidget('webshop_checkout_addToCart', $viewPath, [
                'container' => $this->getContainer(),
                'allowRemoveLastCartItem' => true,
                'cart' => $cart,
                'defaultCurrency' => App::getContainer()->getConfig()->getProjectData('defaultCurrency')
            ]),
            'data' => [
                'cartItemId' => isset($cartItem) ? $cartItem->getId() : null,
                'toastTitle' => trans('product.added.to.cart'),
                'cartExists' => $cart ? true : false,
                'toastBody' => isset($cartItem) ? ($this->getSession()->getLocale() == 'en' 
                    ? $cartItem->getProduct()->getNameEn() 
                    : $cartItem->getProduct()->getName()) : null
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_checkout_removeFromCart, paramChain: /webshop/checkout/removeFromCart]
    */
    public function webshopCheckoutRemoveFromCartAction()
    {
        $productPriceActiveId = (int)$this->getContainer()->getRequest()->get('offerId');
        $webshopService = $this->getContainer()->getService('WebshopService');
        $cartItem = $webshopService->removeFromCart($productPriceActiveId, 1);
        $cart = WebshopService::getCart();

        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/cartContent.php';
        $response = [
            'view' => $this->renderWidget('webshop_checkout_removeFromCart', $viewPath, [
                'container' => $this->getContainer(),
                'allowRemoveLastCartItem' => true,
                'cart' => $cart,
                'defaultCurrency' => App::getContainer()->getConfig()->getProjectData('defaultCurrency')
            ]),
            'data' => [
                'cartItemId' => isset($cartItem) ? $cartItem->getId() : null,
                'toastTitle' => trans('product.removed.from.cart'),
                'cartExists' => $cart ? true : false,
                'toastBody' => isset($cartItem) ? ($this->getSession()->getLocale() == 'en' 
                    ? $cartItem->getProduct()->getNameEn() 
                    : $cartItem->getProduct()->getName()) : null
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function createShipment($userAccount, $organizationData, $tempAcc, $address, $email, $mobile, $recipient, $notice)
    {
        // dump($userAccount); exit;
        $this->wireService('WebshopPackage/entity/Shipment');
        $this->wireService('WebshopPackage/service/WebshopService');
        $webshopFinanceService = $this->getContainer()->getService('WebshopPriceService');
        $this->setService('UserPackage/repository/TemporaryPersonRepository');
        $this->setService('WebshopPackage/repository/ShipmentRepository');
        $this->setService('WebshopPackage/repository/ShipmentItemRepository');
        $shipmentRepo = $this->getService('ShipmentRepository');
        $shipmentItemRepo = $this->getService('ShipmentItemRepository');
        $temporaryPersonRepo = $this->getService('TemporaryPersonRepository');

        // $tempAddress = clone $address;
        // $addressRepo = $tempAddress->getRepository();
        // $tempAddress->setId(null);
        // $tempAddress->setPerson(null);
        // $tempAddress = $addressRepo->store($tempAddress);
        // $temporaryPerson = $temporaryPersonRepo->createNewEntity();
        // $temporaryPerson->setAddress($tempAddress);
        // $temporaryPerson->setName($recipient);
        // $temporaryPerson->setEmail($email);
        // $temporaryPerson->setMobile($mobile);
        // $temporaryPerson->setStatus(1);
        // $temporaryPerson->setCreatedAt($this->getCurrentTimestamp());
        // $temporaryPerson = $temporaryPersonRepo->store($temporaryPerson);
        
        $tempAcc->getTemporaryPerson()->setName($recipient);
        $tempAcc->getTemporaryPerson()->setEmail($email);
        $tempAcc->getTemporaryPerson()->setMobile($mobile);
        $tempAcc->setStatus($tempAcc::STATUS_CLOSED);
        $temporaryPersonRepo->store($tempAcc->getTemporaryPerson());

        $tempAddress = $tempAcc->getTemporaryPerson()->getAddress();
        $shipment = new Shipment();
        $shipment->setCode($shipmentRepo->createCode());
        $shipment->setVisitorCode($this->getSession()->get('visitorCode'));
        $shipment->setUserAccount($userAccount);

        if (!empty($organizationData)) {
            $this->wireService('BusinessPackage/entity/Organization');
            $organization = new Organization();
            $organization->setName($organizationData['name']);
            $organization->setTaxId($organizationData['taxId']);

            $this->wireService('BasicPackage/entity/Country');
            $this->wireService('UserPackage/entity/Address');
            $orgAddress = new Address();

            $orgAddress->setCountry(new Country($organizationData['orgCountry']));
            $orgAddress->setZipCode($organizationData['orgZipCode']);
            $orgAddress->setCity($organizationData['orgCity']);
            $orgAddress->setStreet($organizationData['orgStreet']);
            $orgAddress->setStreetSuffix($organizationData['orgStreetSuffix']);
            $orgAddress->setHouseNumber($organizationData['orgHouseNumber']);
            $orgAddress = $orgAddress->getRepository()->store($orgAddress);
            // $organization->setName($form->getValueCollector()->getDisplayed('orgCountry'));
            $organization->setAddress($orgAddress);
            $organization = $organization->getRepository()->store($organization);
            $shipment->setOrganization($organization);
        }

        if (!$tempAddress) {
            dump($address);
            dump($tempAcc);
            dump($tempAddress);exit;
        }

        $shipment->setTemporaryAccount($tempAcc);
        $shipment->setCountry($tempAddress->getCountry());
        $shipment->setZipCode($tempAddress->getZipCode());
        $shipment->setCity($tempAddress->getCity());
        // $shipment->setTemporaryPerson($temporaryPerson);
        $shipment->setCustomerNote($notice);
        // JAVITVA
        $shipment->setStatus(Shipment::SHIPMENT_STATUS_REQUIRED);
        $shipment->setCreatedAt($this->getCurrentTimestamp());
        $shipment = $shipmentRepo->store($shipment);

        $cart = WebshopService::getCart();
        if ($cart) {
            $cart->setShipment($shipment);
            $cart->getRepository()->store($cart);
        }

        $orderedProducts = [];
        $currency = WebshopPriceService::getActiveCurrency();

        // if (!$shipment || !$storedShipment) {
        //     dump($storedShipment);
        //     dump($shipment);exit;
        // }
        $totalPayable = 0;
        foreach ($cart->getCartItem() as $cartItem) {
            $shipmentItem = $shipmentItemRepo->createNewEntity();
            $shipmentItem->setShipment($shipment);
            $shipmentItem->setProduct($cartItem->getProduct());
            $shipmentItem->setProductPrice($cartItem->getProductPrice());
            $shipmentItem->setQuantity($cartItem->getQuantity());
            $shipmentItem = $shipmentItemRepo->store($shipmentItem);
            $itemGross = WebshopPriceService::format($cartItem->getQuantity() * WebshopPriceService::getAnalyzedPriceData($cartItem->getProductPrice()->getId())['gross_price'], 'price', false, '.');
            // if (!is_numeric($itemGross)) {
            //     dump($itemGross);
            // }
            $totalPayable += $itemGross;
            $orderedProducts[] = [
                'productName' => $cartItem->getProduct()->getName(),
                'quantity' => $cartItem->getQuantity(),
                'itemGross' => $itemGross,
                'currency' => $currency
            ];
        }

        $mailer = new Mailer();
        $mailer->setSubject($this->getContainer()->getCompanyData('brand').' - '.trans('information.about.initaion.of.a.webshop.order'));
        $mailer->textAssembler->setPackage('WebshopPackage');
        $mailer->textAssembler->setReferenceKey('orderSummary');
        $mailer->textAssembler->setEmbeddedViewKeys(['orderedProducts']);
        $mailer->textAssembler->setPlaceholdersAndValues([
            'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain(),
            'name' => $recipient,
            'mobile' => $mobile,
            'cancelOrderLink' => $this->getContainer()->getUrl()->getHttpDomain().'/webshop/cancelOrder/'.$shipment->getCode(),
            'orderedProducts' => $orderedProducts,
            'currency' => $currency,
            'totalPayable' => $totalPayable
        ]);
        $mailer->textAssembler->create();
        $mailer->setBody($mailer->textAssembler->getView());
        // dump($mailer);exit;
        // $email = $userAccount->getPerson()->getEmail();
        $mailer->addRecipient($email, $recipient);
        $mailer->send();

        if (!$mailer->success) {
            dump($mailer);exit;
            $shipmentRepo->remove($shipment->getId());
            $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/emailSendingError.php';
            $response = [
                'view' => $this->renderWidget('emailSendError', $viewPath, [
                    'email' => $email
                ]),
                'data' => []
            ];
    
            return $this->widgetResponse($response);
        } else {
            return $this->returnFinalizeOrder();



            // $webshopService->removeOldCart();
            // $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/finalize.php';
            // $response = [
            //     'view' => $this->renderWidget('webshopCheckoutSuccess', $viewPath, [
            //         'cart' => WebshopService::getCart()
            //     ]),
            //     'data' => []
            // ];
            // return $this->widgetResponse($response);
        }
    }

    /**
    * Route: [name: webshop_paymentResultWidget, paramChain: /webshop/paymentResultWidget]
    * @example: http://elastisite/payment/redirectFromGatewayProvider/Barion/cdsq9hgzqz3rnsy69ztw9mt6?paymentId=3bd4d1c98180ed118bea001dd8b71cc4
    */
    public function webshopPaymentResultWidgetAction()
    {
        $result = 'failed';
        $paymentResult = WebshopService::getPaymentResult(false);
        if ($paymentResult['paymentSuccessful']) {
            $result = 'successful';
        } elseif ($paymentResult['successPage']) {
            $result = 'error';
        }

        // http://elastisite/payment/redirectFromGatewayProvider/Barion/3bd4d1c98180ed118bea001dd8b71cc4
        // dump($_SERVER);exit;
        // else {

        // }

        // $shipmentCode = 'alma';
        // dump($paymentResult);exit;

        $this->wireService('ToolPackage/service/TextAssembler');
        $textAssembler = new TextAssembler();
        $textAssembler->setPackage('WebshopPackage');
        $textAssembler->setDocumentType('entry');
        $textAssembler->setReferenceKey('payment' . ucfirst($result));
        $textAssembler->setPlaceholdersAndValues([
            'shipmentCode' => $paymentResult['payment'] ? $paymentResult['payment']->getShipment()->getCode() : null,
            'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain()
        ]);
        $textAssembler->create();
        $textView = $textAssembler->getView();

        // dump($textAssembler);
        // dump('payment' . ucfirst($result));
        // dump($textView);exit;

        // dump($textAssembler);exit;

        // dump($this->getContainer()->getRouting());exit;

        // dump($paymentResult);exit;

        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopPaymentResultWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('WebshopPaymentResultWidget', $viewPath, [
                // 'container' => $this->getContainer(),
                'isWebshopTestMode' => WebshopService::isWebshopTestMode(),
                'result' => $result,
                'textView' => $textView
                // 'successPage' => $paymentResult['successPage'],
                // 'paymentSuccessful' => $paymentResult['paymentSuccessful'],
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_orderSuccessfulWidget, paramChain: /webshop/orderSuccessfulWidget]
    */
    public function webshopOrderSuccessfulWidgetAction()
    {
        $this->wireService('WebshopPackage/service/WebshopService');
        // dump(App::getContainer()->getUrl()->getDetails());exit;
        $urlDetails = App::getContainer()->getUrl()->getDetails();
        $shipmentCode = isset($urlDetails[0]) ? $urlDetails[0] : null;
        $shipment = WebshopService::getShipmentByCode($shipmentCode);

        $shipmentCode = null;
        $result = 'failed';
        if ($shipment) {
            $shipmentCode = $shipment->getCode();
            $result = 'successful';
        }

        // dump($result);exit;

        $this->wireService('ToolPackage/service/TextAssembler');
        $textAssembler = new TextAssembler();
        $textAssembler->setPackage('WebshopPackage');
        $textAssembler->setDocumentType('entry');
        $textAssembler->setReferenceKey($result == 'successful' ? 'paymentSuccessful' : 'orderFailed');
        $textAssembler->setPlaceholdersAndValues([
            'shipmentCode' => $shipmentCode,
            // 'shipmentCode' => $paymentResult['payment'] ? $paymentResult['payment']->getShipment()->getCode() : null,
            'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain()
        ]);
        $textAssembler->create();
        $textView = $textAssembler->getView();

        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopOrderSuccessfulWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('WebshopOrderSuccessfulWidget', $viewPath, [
                'container' => $this->getContainer(),
                'isWebshopTestMode' => WebshopService::isWebshopTestMode(),
                'result' => $result,
                'textView' => $textView
                // 'successPage' => $paymentResult['successPage'],
                // 'paymentSuccessful' => $paymentResult['paymentSuccessful'],
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_finalizeOrderWidget, paramChain: /webshop/finalizeOrderWidget]
    */
    public function webshopFinalizeOrderWidgetAction()
    {
        return $this->returnFinalizeOrder(true);
    }

    public function returnFinalizeOrder($submitted = false)
    {
        $this->wireService('PaymentPackage/service/OnlinePaymentService');
        $webshopService = $this->getContainer()->getService('WebshopService');
        // $webshopFinanceService = $this->getContainer()->getService('WebshopPriceService');
        $shipment = $webshopService->getUnfinishedOrder();
        $currency = WebshopPriceService::getActiveCurrency();
        $orderedProducts = [];
        $closeAllowed = false;

        $paymentMethod = $this->getContainer()->getRequest()->get('WebshopPackage_orderFinalize_paymentMethod');
        $paymentMethod = $paymentMethod == '*null*' ? null : $paymentMethod;

        $closeRequest = $this->getContainer()->getRequest()->get('closeRequest');
        if ($closeRequest === 'true') {
            $closeRequest = true;
        } elseif ($closeRequest === 'false') {
            $closeRequest = false;
        } else {
            $closeRequest = false;
        }

        $totalPayable = 0;
        foreach ($shipment->getShipmentItem() as $shipmentItem) {
            $itemGross = WebshopPriceService::format($shipmentItem->getQuantity() * WebshopPriceService::getAnalyzedPriceData($shipmentItem->getProductPrice()->getId())['gross_price'], 'price', false, '.');
            // if (!is_numeric($itemGross)) {
            //     dump($itemGross);
            // }
            // dump($itemGross);exit;
            $totalPayable += $itemGross;
            $orderedProducts[] = [
                'productName' => $shipmentItem->getProduct()->getName(),
                'quantity' => $shipmentItem->getQuantity(),
                'itemGross' => $itemGross,
                'currency' => $currency
            ];
        }

        $this->wireService('PaymentPackage/service/OnlinePaymentService');
        $prepared = false;
        $payment = OnlinePaymentService::getPayment($shipment);
        $this->wireService('ToolPackage/service/TextAssembler');
        $textAssembler = new TextAssembler();
        $textAssembler->setPackage('WebshopPackage');
        $textAssembler->setDocumentType('entry');

        if ($payment) {
            $paymentParams = OnlinePaymentService::getPaymentParams($shipment, null);
            $paymentMethod = $paymentParams['gatewayProvider'];

            if (in_array($paymentParams['status'], [Payment::PAYMENT_STATUS_EXPIRED, Payment::PAYMENT_STATUS_CANCELLED])) {
                $textAssembler->setReferenceKey('paymentPreparationExpired');
                
                $payment->setClosedAt(new \DateTime());
                $payment->getRepository()->store($payment);

                $payment = null;
                $paymentMethod = null;
            } elseif (in_array($paymentParams['status'], [Payment::PAYMENT_STATUS_PREPARED])) {
                $prepared = true;
            } elseif (in_array($paymentParams['status'], [Payment::PAYMENT_STATUS_CREATED, Payment::PAYMENT_STATUS_AUTHORIZED, Payment::PAYMENT_STATUS_WAITING])) {

            }
        }

        $paymentMethodMessage = null;

        /**
         * If submitted AND paymentMethod exists, than closeAllowed => true
        */
        if ($submitted) {
            if (!$paymentMethod) {
                $paymentMethodMessage = trans('required.field');
            } else {
                $closeAllowed = true;
                if ($closeRequest) {
                    if (!$paymentMethod) {
                        dump('!$paymentMethod');
                    }
                    $paymentService = new OnlinePaymentService($paymentMethod, $shipment);
                    $paymentService->preparePayment();
                }
            }
        }














        /**
         * Itt dobott egy hibát.
         * ===============================
         * - Biztos, hogy ez nem működik?
         * 
        */


        if ($paymentMethod && (($prepared) || (!$payment && $closeAllowed && $closeRequest))) {

            $payment = OnlinePaymentService::getPayment($shipment);
            if (!$paymentMethod) {
                dump('!$paymentMethod');
            }
            $paymentService = new OnlinePaymentService($paymentMethod, $shipment);
            $paymentService->preparePayment();
            $textAssembler->setReferenceKey('orderSuccessful');
            $textAssembler->setEmbeddedViewKeys(['orderedProducts']);
            $textAssembler->setPlaceholdersAndValues([
                'paymentMethod' => $paymentMethod,
                'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain(),
                'shipmentCode' => $shipment->getCode(),
                'orderedProducts' => $orderedProducts,
                'currency' => $currency,
                'totalPayable' => $totalPayable
            ]);


            if (!$payment) {
                $payment = $paymentService->gatewayOperator->findPayment($shipment);
                dump($payment);exit;
                $payment->setShipment($shipment);
                $payment = $payment->getRepository()->store($payment);
                // dump($payment);
                // dump($closeAllowed);
                // dump($closeRequest);
                // dump($paymentService);
                // exit;
            }
            
            if ($payment->getRedirectedAt()) {
                $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopFinalizeOrderWidget/paymentIsPrepared.php';
            } else {
                $payment->setRedirectedAt(new \DateTime());
                $payment->getRepository()->store($payment);
                $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopFinalizeOrderWidget/redirectToPaymentGatewayOperator.php';
            }
        } else {
            $textAssembler->setReferenceKey('finalizeOrder');
            $textAssembler->setEmbeddedViewKeys(['orderedProducts']);
            $textAssembler->setPlaceholdersAndValues([
                'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain(),
                'shipmentCode' => $shipment->getCode(),
                'orderedProducts' => $orderedProducts,
                'currency' => $currency,
                'totalPayable' => $totalPayable
            ]);
            $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopFinalizeOrderWidget/widget.php';
        }

        $textAssembler->create();

        if ($paymentMethod) {
            $closeAllowed = true;
        }

        $refreshedPaymentParams = OnlinePaymentService::getPaymentParams($shipment, null);

        $response = [
            'view' => $this->renderWidget('WebshopFinalizeOrderWidget', $viewPath, [
                'paymentParams' => $refreshedPaymentParams,
                'shipment' => $shipment,
                'isWebshopTestMode' => WebshopService::isWebshopTestMode(),
                'closeRequest' => $closeRequest,
                'closeAllowed' => $closeAllowed,
                'cart' => WebshopService::getCart(),
                'text' => $textAssembler->getView(),
                'selectedPaymentMethod' => $paymentMethod,
                'paymentMethodMessage' => $paymentMethodMessage,
                'paymentMethods' => OnlinePaymentService::getAvailableGatewayProviders(),
                'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain()
            ]),
            'data' => [
                'paymentMethodMessage' => $paymentMethodMessage,
                'closeRequest' => $closeRequest,
                'closeAllowed' => $closeAllowed,
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function sendOrderConfirmationEmail()
    {

    }

    public function sendOrderSuccessEmail()
    {

    }

    /**
    * Route: [name: webshop_addAddress, paramChain: /webshop/addAddress]
    */
    public function webshopAddAddressAction()
    {
        $this->getContainer()->setService('framework/packages/BasicPackage/repository/CountryRepository');
        $this->getContainer()->setService('framework/packages/UserPackage/repository/UserAccountRepository');
        $this->wireService('FormPackage/service/FormBuilder');
        $this->wireService('UserPackage/entity/Address');

        $countryRepo = $this->getContainer()->getService('CountryRepository');
        $userAccountRepo = $this->getContainer()->getService('UserAccountRepository');
        $userAccount = $userAccountRepo->find($this->getContainer()->getUser()->getId());
        $addressId = null;

        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('WebshopPackage');
        $formBuilder->setSubject('addAddress');
        $formBuilder->addExternalPost('submitted');
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();
        // dump($form);exit;
        if ($form->isValid()) {
            $address = $form->getEntity();

            if ($this->getSession()->userLoggedIn()) {
                $addressRepo = $address->getRepository();
                $address->setPerson($userAccount->getPerson());
                $address = $addressRepo->store($address);
            }

            $address = $this->getService('WebshopService')->setTemporaryAddress($address);
            // dump($address);exit;
            $addressId = $address->getId();
        }

        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/addAddress.php';
        $response = [
            'view' => $this->renderWidget('addAddress', $viewPath, [
                'container' => $this->getContainer(),
                'countries' => $countryRepo->findAllAvailable(),
                'streetSuffixes' => Address::CHOOSABLE_STREET_SUFFIXES,
                'form' => $form,
                'change' => false
            ]),
            'data' => ['addressId' => $addressId]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_changeAddress, paramChain: /webshop/changeAddress]
    */
    public function webshopChangeAddressAction()
    {
        $this->getContainer()->setService('framework/packages/BasicPackage/repository/CountryRepository');
        $this->wireService('FormPackage/service/FormBuilder');
        $this->wireService('UserPackage/entity/Address');
        $this->setService('UserPackage/repository/TemporaryAccountRepository');
        $this->setService('WebshopPackage/service/WebshopService');
        $countryRepo = $this->getService('CountryRepository');
        $tempAccRepo = $this->getService('TemporaryAccountRepository');
        $webshopService = $this->getService('WebshopService');

        $tempAcc = $webshopService->findOrCreateTemporaryAccount();
        $addressId = $tempAcc->getTemporaryPerson()->getAddress()->getId();

        // $existingTempAcc = $tempAccRepo->findOneBy(['conditions' => [
        //     ['key' => 'visitor_code', 'value' => $this->getSession()->get('visitorCode')]
        // ]]);
        // if (!$existingTempAcc) {
        //     return false;
        // }

        // $addressId = $existingTempAcc->getTemporaryPerson()->getAddress()->getId();

        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('WebshopPackage');
        $formBuilder->setSubject('addAddress');
        $formBuilder->addExternalPost('submitted');
        $formBuilder->setPrimaryKeyValue($tempAcc->getTemporaryPerson()->getAddress()->getId());
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();

        // dump($form);exit;

        if ($form->isValid()) {
            $tempAcc->getTemporaryPerson()->setAddress($form->getEntity());
            $tempAccRepo->store($tempAcc);
        }

        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/addAddress.php';
        $response = [
            'view' => $this->renderWidget('addAddress', $viewPath, [
                'container' => $this->getContainer(),
                'countries' => $countryRepo->findAllAvailable(),
                'form' => $form,
                'change' => true,
                'streetSuffixes' => Address::CHOOSABLE_STREET_SUFFIXES
            ]),
            'data' => ['addressId' => $addressId]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_registerAndCheckoutWidget, paramChain: /webshop/registerAndCheckoutWidget]
    */
    public function webshopRegisterAndCheckoutWidgetAction()
    {
        // dump($this->getSession()->getAll());
        
        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopRegisterAndCheckoutWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('WebshopRegisterAndCheckoutWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_cancelOrderWidget, paramChain: /webshop/cancelOrderWidget]
    */
    public function webshopCancelOrderWidgetAction()
    {
        $cancelAllowed = false;
        $webshopService = $this->getContainer()->getService('WebshopService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        // $webshopCartService = new WebshopCartService();
        if (WebshopService::hasUnconfirmedOrder()) {
            $codeRequest = $this->getContainer()->getUrl()->getDetails()[0];
            $this->getContainer()->setService('WebshopPackage/repository/ShipmentRepository');
            $shipmentRepo = $this->getContainer()->getService('ShipmentRepository');
            $shipment = $shipmentRepo->findOneBy(['conditions' => [
                    ['key' => 'visitor_code', 'value' => $this->getContainer()->getSession()->get('visitorCode')],
                    ['key' => 'code', 'value' => $codeRequest]
                ]
            ]);
            $shipmentRepo->remove($shipment->getId());
            WebshopCartService::removeOldCart();

            // dump($shipment);exit;
            if ($shipment) {
                $cancelAllowed = true;
            }
        }

        $this->wireService('ToolPackage/service/TextAssembler');
        $textAssembler = new TextAssembler();
        $textAssembler->setPackage('WebshopPackage');
        // dump($textAssembler);exit;

        if ($cancelAllowed) {
            $textAssembler->setReferenceKey('cancelOrderSuccessful');
            $textAssembler->setPlaceholdersAndValues([
                'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain(),
                'shipmentCode' => $shipment->getCode()
            ]);
        } else {
            $textAssembler->setReferenceKey('cancelOrderFailed');
            $textAssembler->setPlaceholdersAndValues([
                'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain()
                // 'shipmentCode' => $shipment->getCode()
            ]);
        }

        $textAssembler->create();
        // else {
        //     $cancelAllowed = false;
        // }

        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCancelOrderWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('WebshopCancelOrderWidget', $viewPath, [
                'isWebshopTestMode' => WebshopService::isWebshopTestMode(),
                'container' => $this->getContainer(),
                'cancelAllowed' => $cancelAllowed,
                'text' => $textAssembler->getView()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }
}
