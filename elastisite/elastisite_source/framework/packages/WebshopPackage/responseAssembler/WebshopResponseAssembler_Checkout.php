<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;
use framework\packages\BusinessPackage\repository\OrganizationRepository;
use framework\packages\UserPackage\entity\User;
use framework\packages\UserPackage\repository\AddressRepository;
use framework\packages\WebshopPackage\dataProvider\PackDataProvider;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\service\WebshopFinishCheckoutService;
use framework\packages\WebshopPackage\service\WebshopInvoiceService;
use framework\packages\WebshopPackage\dataProvider\ProductListDataProvider;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;
use framework\packages\WebshopPackage\service\WebshopTemporaryAccountService;

class WebshopResponseAssembler_Checkout extends Service
{
    public static function assembleResponse($processedRequestData = null)
    {
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler');
        App::getContainer()->wireService('WebshopPackage/entity/Cart');
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopInvoiceService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        App::getContainer()->wireService('WebshopPackage/dataProvider/PackDataProvider');
        App::getContainer()->wireService('WebshopPackage/dataProvider/ProductListDataProvider');
        App::getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        App::getContainer()->wireService('WebshopPackage/service/WebshopFinishCheckoutService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');

        // WebshopCartService::checkAndExecuteTriggers();

        // dump($processedRequestData);exit;
        $locale = App::getContainer()->getSession()->getLocale();
        $temporaryAccount = WebshopTemporaryAccountService::getTemporaryAccount();
        $temporaryAccountData = WebshopTemporaryAccountService::assembleTemporaryAccountData($temporaryAccount);
        // dump($temporaryAccountData);exit;
        // dump(WebshopService::hasUnconfirmedOrder());
        /**
         * 
        */

        // if (WebshopService::hasUnstartedOrder()) {
        //     App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_FinalizeOrder');
        //     return WebshopResponseAssembler_FinalizeOrder::assembleResponse($processedRequestData);
        //     // dump('hasUnconfirmedOrder!!!');exit;
        //     // App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_Checkout');
        //     // return WebshopResponseAssembler_Checkout::assembleResponse();
        // }

        /**
         * Handling user session
        */
        $user = App::getContainer()->getUser();
        $onlyRegistratedUsersCanCheckout = WebshopService::getSetting('WebshopPackage_onlyRegistratedUsersCanCheckout');
        $removeCartOnLogin = WebshopService::getSetting('WebshopPackage_removeCartOnLogin');

        $userType = App::getContainer()->getUser()->getType();
        if ($userType == User::TYPE_USER) {
            if (empty($temporaryAccountData['temporaryPerson']['name'])) {
                WebshopTemporaryAccountService::setTemporaryPersonData('recipientName', App::getContainer()->getUser()->getUserAccount()->getPerson()->getFullName());
            }
            // $recipientName = $temporaryAccount->getTemporaryPerson();
        }

        // dump($removeCartOnLogin);
        // dump($user);exit;
        if (($onlyRegistratedUsersCanCheckout && !$user->getUserAccount()->getId()) || $user->getType() == User::TYPE_ADMINISTRATOR) {
            if ($user->getType() == User::TYPE_ADMINISTRATOR) {
                $viewPath = 'framework/packages/WebshopPackage/view/Sections/Checkout/Error/AdminSession.php';
                return WebshopResponseAssembler::returnAlternativeView('WebshopPackage_Checkout', $viewPath, []);
            }
            if ($user->getType() == User::TYPE_GUEST) {
                $viewPath = 'framework/packages/WebshopPackage/view/Sections/Checkout/Error/GuestSession.php';
                return WebshopResponseAssembler::returnAlternativeView('WebshopPackage_Checkout', $viewPath, []);
            }
            // $viewPath = 'framework/packages/WebshopPackage/view/Sections/Checkout/EmptyCart.php';
            // $view = ViewRenderer::renderWidget('WebshopPackage_Checkout', $viewPath, [
            //     'webshopBaseLink' => '/'.WebshopRequestService::getSlugTransRef(WebshopService::TAG_WEBSHOP, $locale)
            // ]);
    
            // return [
            //     'view' => $view,
            //     'data' => [
            //     ]
            // ];
        }

        /**
         * The most important data for this section.
         * We use it in the invoice, extracting used ids to get the products data.
        */
        $packDataSet = PackDataProvider::assembleDataSet(WebshopCartService::getCart());
        // dump($packDataSet);exit;
        /**
         * Checking if cart is empty, and if yes, than we offer a fancy link to get back to the webshop.
        */
        if (empty($packDataSet['pack']['packItems'])) {
            // dsadasd
            $viewPath = 'framework/packages/WebshopPackage/view/Sections/Checkout/Error/EmptyCart.php';
            return WebshopResponseAssembler::returnAlternativeView('WebshopPackage_Checkout', $viewPath, []);
            // $view = ViewRenderer::renderWidget('WebshopPackage_Checkout', $viewPath, [
            //     'webshopBaseLink' => '/'.WebshopRequestService::getSlugTransRef(WebshopService::TAG_WEBSHOP, $locale)
            // ]);
    
            // return [
            //     'view' => $view,
            //     'data' => [
            //     ]
            // ];
        }

        /**
         * The proper format for the Invoice view.
        */
        $invoiceData = WebshopInvoiceService::convertCartDataToInvoiceData($packDataSet);

        /**
         * We are now extracting product ids from the cart, to get the properly formatted product data.
        */
        $cartItemProductIds = [];
        if (isset($packDataSet['pack']['packItems']) && !empty($packDataSet['pack']['packItems'])) {
            foreach ($packDataSet['pack']['packItems'] as $cartItemData) {
                $cartItemProductIds[] = $cartItemData['product']['id'];
            }
        }

        // dump($cartItemProductIds);exit;

        /**
         * For getting the proper products data: this is the way.
         * First we get the raw data
        */
        $productRepo = new ProductRepository();
        $processedRequestData = $processedRequestData ? : WebshopRequestService::getProcessedRequestData();
        $rawProductsData = $productRepo->getProductsData(App::getContainer()->getSession()->getLocale(), [
            'productIds' => $cartItemProductIds
        ], []);
        /**
         * Than we arrange our data.
        */
        $productListDataSet = ProductListDataProvider::arrangeProductsData($rawProductsData['productData']);

        /**
         * Assembling customer data
        */
        $customerData = [];

        /**
         * Assembling delivery data
        */
        // $addresses = [];
        // if ($user->getUserAccount() && $user->getUserAccount()->getPerson()) {
        //     $addresses = $user->getUserAccount()->getPerson()->getAddress();
        // }
        // dump($addresses);exit;

        // dump(self::collectAddressesData()); exit;
        // dump(self::assembleErrors(WebshopCartService::getCart())); exit;
        $cart = WebshopCartService::getCart();
        $temporaryAccountData = WebshopTemporaryAccountService::assembleTemporaryAccountData($cart->getTemporaryAccount());

        // dump(self::collectOrganizationsData());
        // dump(WebshopFinishCheckoutService::assembleCartErrors($cart));exit;
        // dump($packDataSet);exit;

        $viewParams = [
            // 'customerType' => $cart->getCustomerType(),
            'organizationsData' => self::collectOrganizationsData(),
            'addressesData' => self::collectAddressesData(),
            'packDataSet' => $packDataSet,
            'productListDataSet' => $productListDataSet,
            'temporaryAccountData' => $temporaryAccountData,
            // 'customerData' => $customerData,
            'invoiceData' => $invoiceData,
            'localizedProductInfoLinkBase' => WebshopRequestService::getSlugTransRef(WebshopService::TAG_WEBSHOP, $locale).'/'.WebshopRequestService::getSlugTransRef(WebshopService::TAG_SHOW_PRODUCT, $locale).'/',
            'userType' => App::getContainer()->getUser()->getType(),
            'errors' => WebshopFinishCheckoutService::assembleCartErrors($cart)
        ];

        $viewPath = 'framework/packages/WebshopPackage/view/Sections/Checkout/Checkout.php';
        $view = ViewRenderer::renderWidget('WebshopPackage_Checkout', $viewPath, $viewParams);

        return [
            'view' => $view,
            'data' => [
            ]
        ];

        // $response = [
        //     'view' => $view,
        //     'data' => [
        //         // 'closeModal' => $form->isValid() ? true : false
        //     ]
        // ];

        // return WidgetResponse::create($response);
    }

    public static function collectOrganizationsData()
    {
        $userAccount = App::getContainer()->getUser()->getUserAccount();
        // if (!$userAccount->getId()) {
        //     return [];
        // }

        App::getContainer()->wireService('BusinessPackage/entity/Organization');
        App::getContainer()->wireService('BusinessPackage/repository/OrganizationRepository');
        $repo = new OrganizationRepository();
        $organizationsData = [];

        if (App::getContainer()->getUser()->getType() == User::TYPE_USER) {
            /**
             * Getting the object is the only way, since addresses data are encrypted.
            */
            $organizations = $repo->findBy([
                'conditions' => [
                    ['key' => 'user_account_id', 'value' => $userAccount->getId()]
                ],
                'orderBy' => [['field' => 'created_at', 'direction' => 'DESC']]
            ]);

            foreach ($organizations as $organization) {
                $address = $organization->getAddress();
                $addressString = null;
                if ($address) {
                    $addressString = (string)$address;
                }
                $organizationsData[] = [
                    'organizationId' => $organization->getId(),
                    'organizationName' => $organization->getName(),
                    'organizationTaxId' => $organization->getTaxId(),
                    'organizationCeo' => $organization->getCeo(),
                    'addressString' => $addressString
                ];
            }
        } elseif (App::getContainer()->getUser()->getType() == User::TYPE_GUEST) {
            App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
            $temporaryAccount = WebshopTemporaryAccountService::getTemporaryAccount();
            $organization = $temporaryAccount->getTemporaryPerson()->getOrganization();
            // $addressesData = !empty($temporaryAddress) ? $temporaryAddress : [];
            if (!empty($organization)) {
                $organizationsData[] = [
                    'organizationId' => $organization->getId(),
                    'organizationName' => $organization->getName(),
                    'organizationTaxId' => $organization->getTaxId(),
                    'organizationCeo' => $organization->getCeo(),
                    'addressString' => $organization->getAddress() ? (string)$organization->getAddress() : null
                ];
            }
        }

        return $organizationsData;
    }

    public static function collectAddressesData()
    {
        // dump(App::getContainer()->getUser()->getType());exit;
        // if (!$userAccount->getId() || !$userAccount->getPerson()) {
        //     return [];
        // }
        App::getContainer()->wireService('UserPackage/repository/AddressRepository');
        $repo = new AddressRepository();
        $addressesData = [];

        if (App::getContainer()->getUser()->getType() == User::TYPE_USER) {
            $userAccount = App::getContainer()->getUser()->getUserAccount();
            $addresses = $repo->findBy([
                'conditions' => [
                    ['key' => 'person_id', 'value' => $userAccount->getPerson()->getId()]
                ],
                'orderBy' => [['field' => 'id', 'direction' => 'DESC']]
            ]);

            foreach ($addresses as $address) {
                $addressesData[] = [
                    'addressId' => $address->getId(),
                    'addressString' => (string)$address
                ];
            }
        } elseif (App::getContainer()->getUser()->getType() == User::TYPE_GUEST) {
            App::getContainer()->wireService('WebshopPackage/service/WebshopTemporaryAccountService');
            $temporaryAccount = WebshopTemporaryAccountService::getTemporaryAccount();
            $temporaryAddress = $temporaryAccount->getTemporaryPerson()->getAddress();
            // $addressesData = !empty($temporaryAddress) ? $temporaryAddress : [];
            if (!empty($temporaryAddress)) {
                $addressesData[] = [
                    'addressId' => $temporaryAddress->getId(),
                    'addressString' => (string)$temporaryAddress
                ];
            }
        }

        return $addressesData;
    }

    /**
     * @todo
    */
    // public static function OLD_STUFF_displayCookieWasRefusedContent()
    // {
    //     App::getContainer()->wireService('ToolPackage/service/TextAssembler');
    //     $textAssembler = new TextAssembler();
    //     // dump($this->getContentTextService($subscriber));
    //     // $textAssembler->setContentTextService($this->getContentTextService());
    //     $textAssembler->setDocumentType('entry');
    //     $textAssembler->setPackage('WebshopPackage');
    //     $textAssembler->setReferenceKey('BarionCookieWasRefused');
    //     $textAssembler->setPlaceholdersAndValues([
    //         'httpDomain' => App::getContainer()->getUrl()->getHttpDomain()
    //     ]);
    //     $textAssembler->create();
    //     $textView = $textAssembler->getView();

    //     // dump($textView);exit;

    //     $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopCheckoutWidget/cookieWasRefused.php';
    //     $response = [
    //         'view' => ViewRenderer::renderWidget('WebshopCheckoutWidget', $viewPath, [
    //             'textView' => $textView,
    //             'isWebshopTestMode' => WebshopService::isWebshopTestMode(),
    //             'httpDomain' => App::getContainer()->getUrl()->getHttpDomain()
    //         ]),
    //         'data' => [
    //         ]
    //     ];

    //     // return $this->widgetResponse($response);
    // }

    public static function oldStuff()
    {
        // $this->wireService('LegalPackage/service/CookieConsentService');
        // $this->wireService('LegalPackage/entity/VisitorConsentAcceptance');
        // $thirdPartyCookiesAcceptance = CookieConsentService::findThirdPartyCookiesAcceptances(false, 'Barion');
        // // dump($thirdPartyCookiesAcceptance);
        // if ($thirdPartyCookiesAcceptance && $thirdPartyCookiesAcceptance->getAcceptance() == VisitorConsentAcceptance::ACCEPTANCE_REFUSED) {
        //     return $this->displayCookieWasRefusedContent();
        // } elseif (!$thirdPartyCookiesAcceptance) {
        //     App::redirect('/webshop');
        // }
    }
}