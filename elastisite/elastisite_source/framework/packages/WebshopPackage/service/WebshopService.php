<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\FileHandler;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\PaymentPackage\repository\PaymentRepository;
use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\ToolPackage\service\Mailer;
use framework\packages\WebshopPackage\repository\ShipmentRepository;
use framework\packages\UserPackage\entity\TemporaryAccount;
use framework\packages\WebshopPackage\entity\Shipment;

class WebshopService extends Service
{
    const TAG_WEBSHOP = 'Webshop';
    const TAG_PAGE = 'Page';
    const TAG_CATEGORY = 'Category';
    const TAG_SEARCH = 'Search';
    const TAG_PRODUCT_SLUG = 'ProductSlug';
    const TAG_ALL_PRODUCTS = 'AllProducts';
    const TAG_DISCOUNTED_PRODUCTS = 'DiscountedProducts';
    const TAG_SHOW_PRODUCT = 'ShowProduct';
    const TAG_ANOMALOUS_PRODUCTS = 'AnomalousProducts';
    const TAG_MOST_POPULAR_PRODUCTS = 'MostPopularProducts';
    const TAG_RECOMMENDED_PRODUCTS = 'RecommendedProducts';

    // const LINK_BASES = [
    //     [self::TAG_SHOW_PRODUCT] => [
    //         'en' => 'webshop/show_product/{ProductSlug}',
    //         'hu' => 'webaruhaz/termek_info/{ProductSlug}'
    //     ]
    // ];

    // const PRODUCT_INFO_TAGS = [];
    const DEFAULT_SETTING_WEBSHOP_IS_ACTIVE = false;
    const DEFAULT_SETTING_ALLOW_CART_QUANTITY = true;
    const DEFAULT_SETTING_PRODUCT_LIST_MAX_COLS = 3;
    const DEFAULT_SETTING_PRODUCT_LIST_MAX_PRODUCTS_ON_PAGE = 10;
    // const ALL_PRODUCTS = 'all_products';
    // const NON_LISTABLE_PRODUCTS = 'non_listable_products';
    // const DISCOUNTED_PRODUCTS = 'discounted_products';
    // const MOST_POPULAR_PRODUCTS = 'most_popular_products';
    // const RECOMMENDED_PRODUCTS = 'recommended_products';
    const DEFAULT_SETTING_SHOW_NON_PRICED_PRODUCTS = true;

    // const WEBSHOP_SLUG_KEY_IN_LOCAL_LANG = 'webaruhaz';
    // const SHOW_PRODUCT_SLUG_KEY_IN_LOCAL_LANG = 'termekInfo';
    // const CATEGORY_SLUG_KEY_IN_LOCAL_LANG = 'kategoria';
    // const SEARCH_SLUG_KEY_IN_LOCAL_LANG = 'kereses';
    // const ALL_PRODUCTS_SLUG_KEY_IN_LOCAL_LANG = 'minden_termek';
    // const DISCOUNTED_PRODUCTS_SLUG_KEY_IN_LOCAL_LANG = 'akcios_termekek';
    // const PAGE_SLUG_KEY_IN_LOCAL_LANG = 'lap';

    const SLUGS = [
        // self::TAG_ALL_PRODUCTS => [
        //     'slugTranslations' => [
        //         'hu' => 'webaruhaz',
        //         'en' => 'webshop'
        //     ],
        //     'transRef' => 'webshop',
        //     'isSpecialCategory' => false
        // ],
        self::TAG_WEBSHOP => [
            'slugTranslations' => [
                'hu' => 'webaruhaz',
                'en' => 'webshop'
            ],
            'transRef' => 'webshop',
            'isSpecialCategory' => false
        ],
        // 'product' => [
        //     'slugTranslations' => [
        //         'hu' => 'termek',
        //         'en' => 'product'
        //     ],
        //     'transRef' => 'product',
        //     'isSpecialCategory' => false
        // ],
        self::TAG_CATEGORY => [
            'slugTranslations' => [
                'hu' => 'kategoria',
                'en' => 'category'
            ],
            'transRef' => 'category',
            'isSpecialCategory' => false
        ],
        self::TAG_SEARCH => [
            'slugTranslations' => [
                'hu' => 'kereses',
                'en' => 'search'
            ],
            'transRef' => 'search',
            'isSpecialCategory' => false
        ],
        self::TAG_SHOW_PRODUCT => [
            'slugTranslations' => [
                'hu' => 'termek_info',
                'en' => 'show_product'
            ],
            'transRef' => 'show.product',
            'isSpecialCategory' => false
        ],
        self::TAG_PAGE => [
            'slugTranslations' => [
                'hu' => 'lap',
                'en' => 'page'
            ],
            'transRef' => 'page',
            'isSpecialCategory' => false
        ],
        self::TAG_ALL_PRODUCTS => [
            'slugTranslations' => [
                'hu' => 'minden_termek',
                'en' => 'all_products'
            ],
            'transRef' => 'all.products',
            'isSpecialCategory' => true
        ],
        self::TAG_DISCOUNTED_PRODUCTS => [
            'slugTranslations' => [
                'hu' => 'akcios_termekek',
                'en' => 'discounted_products'
            ],
            'transRef' => 'discounted.products',
            'isSpecialCategory' => true
        ],
        self::TAG_MOST_POPULAR_PRODUCTS => [
            'slugTranslations' => [
                'hu' => 'legnepszerubb_termekek',
                'en' => 'most_popular_products'
            ],
            'transRef' => 'most.popular.products',
            'isSpecialCategory' => true
        ],
        self::TAG_RECOMMENDED_PRODUCTS => [
            'slugTranslations' => [
                'hu' => 'ajanlott_termekek',
                'en' => 'recommended_products'
            ],
            'transRef' => 'recommended.products',
            'isSpecialCategory' => true
        ],
        self::TAG_ANOMALOUS_PRODUCTS => [
            'slugTranslations' => [
                'en' => 'anomalous_products',
                'hu' => 'anomalous_products'
            ],
            'transRef' => 'non.listable.products',
            'isSpecialCategory' => true
        ],
    ];

    public static function isWebshopTestMode($debug = null)
    {
        // if (Permission::check(Permission::WEBSHOP_TESTER_PERMISSION_GROUP)) {
        //     return true;
        // }
        // dump(App::getContainer()->getUser()->getUserAccount()->getIsTester() === 1);exit;
        if (App::getContainer()->getUser() && App::getContainer()->getUser()->getUserAccount() && App::getContainer()->getUser()->getUserAccount()->getIsTester() === 1) {
            return true;
        }
        if (!App::getContainer()->getProjectData('webshopEnabled')) {
            return true;
        }
        if (App::getContainer()->isGranted('viewWebshopTesterContent')) {
            return true;
        }

        return false;
    }

    // const SPECIAL_CATEGORIES = [
    //     'all_products',
    //     'discounted_products',
    //     'recommended_products'
    // ];

    // // const SHIPMENT_STATUS_INACTIVE = 0;
    // const SHIPMENT_STATUS_CANCELLED = 10;
    // const SHIPMENT_STATUS_ORDERED = 2;
    // const SHIPMENT_STATUS_REQUIRED = 1;
    // const SHIPMENT_STATUS_CONFIRMED = 11;
    // const SHIPMENT_STATUS_WAITING_FOR_PRODUCT = 21;
    // const SHIPMENT_STATUS_PREPARED_FOR_DELIVERY = 31;
    // const SHIPMENT_STATUS_POSTED = 39;
    // const SHIPMENT_STATUS_DELIVERED = 90;
    // // const SHIPMENT_STATUS_CLOSED = 'SHIPMENT_STATUS_DELIVERED';
    // const SHIPMENT_STATUS_CLOSED = self::SHIPMENT_STATUS_DELIVERED;
    // const MAXIMUM_PRODUCT_CATEGORY_DEPTH = 1;

    // public static $statuses = array(
    //     '10' => array(
    //         'publicTitle' => 'cancelled',
    //         'adminTitle' => 'cancelled'
    //     ),
    //     '2' => array(
    //         'publicTitle' => 'required',
    //         'adminTitle' => 'required'
    //     ),
    //     '11' => array(
    //         'publicTitle' => 'confirmed',
    //         'adminTitle' => 'confirmed'
    //     ),
    //     '1' => array(
    //         'publicTitle' => 'ordered',
    //         'adminTitle' => 'ordered'
    //     ),
    //     '21' => array(
    //         'publicTitle' => 'ordered',
    //         'adminTitle' => 'waiting.for.product'
    //     ),
    //     '22' => array(
    //         'publicTitle' => 'customer.is.unreachable',
    //         'adminTitle' => 'customer.is.unreachable'
    //     ),
    //     '31' => array(
    //         'publicTitle' => 'ordered',
    //         'adminTitle' => 'prepared.for.delivery'
    //     ),
    //     '39' => array(
    //         'publicTitle' => 'posted',
    //         'adminTitle' => 'posted'
    //     ),
    //     '90' => array(
    //         'publicTitle' => 'delivered',
    //         'adminTitle' => 'delivered'
    //     ),
    // );

    public static $settings = array(
        'WebshopPackage_webshopIsActive' => self::DEFAULT_SETTING_WEBSHOP_IS_ACTIVE,
        'WebshopPackage_allowCartMultipleQuantity' => self::DEFAULT_SETTING_ALLOW_CART_QUANTITY,
        'WebshopPackage_priceDecimalSeparator' => ',',
        'WebshopPackage_priceDecimals' => 2,
        'WebshopPackage_discountPercentDecimals' => 1,
        'WebshopPackage_removeCartOnLogin' => false,
        'WebshopPackage_homepageListType' => self::TAG_ALL_PRODUCTS,
        'WebshopPackage_removeTemporaryPersonOnCloseShipment' => false,
        'WebshopPackage_onlyRegistratedUsersCanCheckout' => true,
        'WebshopPackage_closedShipmentIsEditable' => false,
        'WebshopPackage_reopenShipmentIsAllowed' => true,
        'WebshopPackage_displayedRunningOrders' => 20,
        'WebshopPackage_maxProductsOnPage' => self::DEFAULT_SETTING_PRODUCT_LIST_MAX_PRODUCTS_ON_PAGE,
        'WebshopPackage_productListMaxCols' => self::DEFAULT_SETTING_PRODUCT_LIST_MAX_COLS,
        'WebshopPackage_showNonPricedProducts' => self::DEFAULT_SETTING_SHOW_NON_PRICED_PRODUCTS
    );

    public static function getSetting($key, $convertNonTextValues = true)
    {
        $container = App::getContainer();
        $container->setService('FrameworkPackage/service/SettingsService');
        $settingsService = $container->getService('SettingsService');
        $value = $settingsService->get($key);

        if (!$value) {
            // dump($key);
            // if (isset(self::$settings[$key])) {
            //     dump('megvan!');
            // } else {
            //     dump('nincs');
            // }
            $value = isset(self::$settings[$key]) ? self::$settings[$key] : null;
        }
        
        if ($convertNonTextValues) {
            $value = $settingsService->convertValueFromText($value);
        }

        // dump(self::$settings);
        // dump($value);
        // dump($settings);

        return $value;
    }

    public static function getDisplayedSetting($key)
    {
        $value = self::getSetting($key, false);

        $container = App::getContainer();
        $container->setService('FrameworkPackage/service/SettingsService');
        $settingsService = $container->getService('SettingsService');
        $value = $settingsService->convertValueToText($value);

        return trans($value);
    }

    // /settings

    // public static function hasUnstartedOrder()
    // {
    //     $container = App::getContainer();
    //     $container->setService('WebshopPackage/repository/ShipmentRepository');
    //     // $shipmentRepo = $container->getService('ShipmentRepository');

    //     $result = ShipmentRepository::hasShipmentWithSpecificStatuses([Shipment::SHIPMENT_STATUS_ORDER_PREPARED, Shipment::SHIPMENT_STATUS_PAYMENT_METHOD_SELECTED]);

    //     // dump($result);exit;

    //     return $result;
    // }

    public static function getPaymentByCode($paymentCode)
    {
        App::getContainer()->wireService('PaymentPackage/repository/PaymentRepository');
        $paymentRepo = new PaymentRepository();
        $payment = $paymentRepo->findOneBy(['conditions' => [
            ['key' => 'payment_code', 'value' => $paymentCode]
        ]]);

        return $payment;
    }

    public static function getPaymentResult($refreshPaymentStatus = true)
    {
        $successPage = false;
        $pageTitle = 'payment.failed';
        
        $paymentSuccessful = false;
        $paymentCode = null;
        $payment = null;
        if (App::getContainer()->getUrl()->getPageRoute()->getName() == 'webshop_paymentSuccessful') {
            $successPage = true;
            $paymentCode = App::getContainer()->getUrl()->getDetails()[0];

            $payment = self::getPaymentByCode($paymentCode);
            // $payment = $paymentRepo->findOneBy(['conditions' => [
            //     ['key' => 'payment_code', 'value' => $paymentCode]
            // ]]);
            // dump($payment);exit;

            if ($payment && $payment->getShipment()) {
                if ($refreshPaymentStatus) {
                    App::getContainer()->wireService('PaymentPackage/service/OnlinePaymentService');
                    App::getContainer()->wireService('PaymentPackage/repository/PaymentRepository');
                    // OnlinePaymentService::refreshPaymentStatus();
                    $paymentRepo = new PaymentRepository();
                    $payment = $paymentRepo->find($payment->getId());
                }

                if ($payment->getStatus() == Payment::PAYMENT_STATUS_SUCCEEDED) {
                    $paymentSuccessful = true;
                    $pageTitle = 'payment.successful';
                }
            }
        } else {
            $pageTitle = 'payment.error';
        }

        return [
            'pageTitle' => $pageTitle,
            'successPage' => $successPage,
            'paymentSuccessful' => $paymentSuccessful,
            'payment' => $payment
        ];
    }

    public static function getImagePath($relative = false)
    {
        // $filePath = 'projects/'.App::getWebProject().'/webshop/productImage';
        $filePath = 'projects/'.App::getWebProject().'/webshop/productImage';

        return $relative ? $filePath : FileHandler::completePath($filePath, 'dynamic');
    }

    // public static function fillProductRows($products, $cols)
    // {
    //     // dump($cols);
    //     $productRows = array();
    //     $counter = 0;
    //     $rowCounter = 0;
    //     $colCounter = 0;
    //     foreach ($products as $product) {
    //         if ($colCounter + 1 > $cols) {
    //             $rowCounter++;
    //             $colCounter = 0;
    //         }
    //         $productRows[$rowCounter][$colCounter] = $product;
    //         if ($counter == count($products) - 1 && $colCounter < $cols - 1) {
    //             # Missing cols in this row will be filled with NULL. Because we don't want view to do this, instead the controller.
    //             for ($i = ($colCounter + 1); $i < $cols; $i++) {
    //                 $productRows[$rowCounter][$i] = null;
    //             }
    //         }
    //         $colCounter++;
    //         $counter++;
    //     }

    //     return $productRows;
    // }

    // public static function examineAndSortProductList($products)
    // {
    //     // $processedProducts = array();
    //     $listableProducts = array();
    //     $nonListableProducts = array();
    //     $errors = array();
    //     foreach ($products as $product) {
    //         if ($product->getProductPriceActive() && $product->getProductPriceActive()->getProductPrice()) {
    //             $listableProducts[] = $product;
    //         } else { 
    //             if (App::getContainer()->isGranted('viewProjectAdminContent')) {
    //                 $listableProducts[] = $product;
    //                 $nonListableProducts[] = $product;
    //                 $errors[$product->getId()] = array();
    //                 if (!$product->getProductPriceActive()) {
    //                     $errors[$product->getId()][] = trans('missing.active.product.price');
    //                 }
    //                 if ($product->getProductPriceActive() && !$product->getProductPriceActive()->getProductPrice()) {
    //                     $errors[$product->getId()][] = trans('missing.or.corrupted.product.price');
    //                 }
    //             }
    //         }
    //     }
    //     // dump($errors);exit;
        
    //     return array(
    //         // 'processedProducts' => $processedProducts,
    //         'listableProducts' => $listableProducts,
    //         'nonListableProducts' => $nonListableProducts,
    //         'errors' => $errors
    //     );
    // }

    // public static function findOrCreateTemporaryAccount()
    // {
    //     App::getContainer()->setService('UserPackage/repository/TemporaryAccountRepository');
    //     App::getContainer()->wireService('UserPackage/entity/TemporaryAccount');
        
    //     $tempAccRepo = App::getContainer()->getService('TemporaryAccountRepository');
    //     $temporaryAccount = $tempAccRepo->findOneBy(['conditions' => [
    //         ['key' => 'visitor_code', 'value' => App::getContainer()->getSession()->get('visitorCode')],
    //         ['key' => 'status', 'value' => TemporaryAccount::STATUS_OPEN]
    //     ]]);

    //     // if ($this->getContainer()->getUser()->getUserAccount() && $this->getContainer()->getUser()->getUserAccount()->getPerson() && $this->getContainer()->isGranted('viewOnlyUserNotAdminContent')) {
    //     //     if ($temporaryAccount && $temporaryAccount->getTemporaryPerson()) {
    //     //         $defaultAddress = $this->getContainer()->getUser()->getUserAccount()->getPerson()->getDefaultAddress();
    //     //         $temporaryAddress = $temporaryAccount->getTemporaryPerson()->getAddress();
    //     //         if ($defaultAddress && $temporaryAddress) {
    //     //             $temporaryTestAddress = $temporaryAddress->setId($defaultAddress->getId());
    //     //             if ($defaultAddress != $temporaryTestAddress) {
    //     //                 $temporaryAddress->getRepository()->remove($temporaryAddress->getId());
    //     //                 $temporaryAccount->getTemporaryPerson()->setAddress(null);
    //     //                 $temporaryAccount->getRepository()->remove($temporaryAccount->getId());
    //     //                 $temporaryAccount = null;
    //     //             }
    //     //         }
    //     //     }
    //     //     // return null;
    //     //     // dump($this->getContainer()->getUser());exit;
    //     // }

    //     if (!$temporaryAccount) {
    //         $temporaryAccount = self::createTemporaryAccount();
    //     }

    //     App::getContainer()->getSession()->set('webshop_temporaryAccountParams', [
    //         'id' => $temporaryAccount->getId(),
    //         'visitorCode' => App::getContainer()->getSession()->get('visitorCode')
    //     ]);

    //     if (!$temporaryAccount->getTemporaryPerson()) {
    //         $temporaryPerson = self::createTemporaryPerson();
    //         $temporaryPerson->setTemporaryAccount($temporaryAccount);
    //         $temporaryPerson->getRepository()->store($temporaryPerson);
    //         $temporaryAccount->setTemporaryPerson($temporaryPerson);
    //         $temporaryAccount->getRepository()->store($temporaryAccount);
    //     }
        
    //     return $temporaryAccount;
    // }

    // public static function setTemporaryAddress($address)
    // {
    //     $address = $address->getRepository()->store($address);
    //     $temporaryAccount = self::findOrCreateTemporaryAccount();
    //     $temporaryAccount->getTemporaryPerson()->setAddress($address);
    //     $temporaryAccount->getRepository()->store($temporaryAccount);
    //     // $savedAddress = $temporaryAccount->getTemporaryPerson()->getAddress();
    //     // dump($address);
    //     return $address;
    // }

    // public static function closeOrder(Shipment $shipment, bool $sendMail = true)
    // {
    //     $container = App::getContainer();
    //     $container->wireService('WebshopPackage/service/WebshopCartService');
    //     $container->wireService('WebshopPackage/entity/Shipment');
    //     $container->wireService('WebshopPackage/service/WebshopService');
    //     $webshopService = new self();
    //     $container->wireService('WebshopPackage/service/WebshopPriceService');
    //     // $webshopFinanceService = new WebshopPriceService();
    //     // $container->setService('WebshopPackage/repository/ShipmentRepository');
    //     // $shipmentRepo = $container->getService('ShipmentRepository');

    //     WebshopCartService::removeOldCart();
    //     // dump('Miafasz????');exit;
    //     $shipment->setStatus(Shipment::SHIPMENT_STATUS_ORDERED);
    //     $shipment->getRepository()->store($shipment);

    //     // $shipment = $webshopService->getUnfinishedOrder();
    //     // if ($shipment) {
    //     //     $shipment->setStatus(Shipment::SHIPMENT_STATUS_REQUIRED);
    //     //     $shipment->getRepository()->store($shipment);
    //     // }

    //     if ($container->getSession()->userLoggedIn()) {
    //         $email = $shipment->getUserAccount()->getPerson()->getEmail();
    //         $mobile = $shipment->getUserAccount()->getPerson()->getMobile();
    //         $recipient = $shipment->getUserAccount()->getPerson()->getFullName();
    //     } else {
    //         $email = $shipment->getTemporaryAccount()->getTemporaryPerson()->getEmail();
    //         $mobile = $shipment->getTemporaryAccount()->getTemporaryPerson()->getMobile();
    //         $recipient = $shipment->getTemporaryAccount()->getTemporaryPerson()->getName();
    //     }

    //     // $shipment->getTemporaryAccount()->setStatus($shipment->getTemporaryAccount()::STATUS_CLOSED);
    //     // $shipment->getTemporaryAccount()->getRepository()->store($shipment->getTemporaryAccount());

    //     $currency = WebshopPriceService::getDefaultCurrency();
    //     $totalPayable = 0;
    //     $orderedProducts = [];
    //     foreach ($shipment->getShipmentItem() as $shipmentItem) {
    //         $itemGross = $shipmentItem->getQuantity() * WebshopPriceService::getAnalyzedPriceData($shipmentItem->getProductPrice()->getId())['gross_price'];
    //         $totalPayable += $itemGross;
    //         $orderedProducts[] = [
    //             'productName' => $shipmentItem->getProduct()->getName(),
    //             'quantity' => $shipmentItem->getQuantity(),
    //             'itemGross' => $itemGross,
    //             'currency' => $currency
    //         ];
    //     }

    //     if ($sendMail) {
    //         $container->wireService('ToolPackage/service/Mailer');
    //         $mailer = new Mailer();
    //         $mailer->setSubject($container->getCompanyData('brand').' - '.trans('order.successful'));
    //         $mailer->textAssembler->setPackage('WebshopPackage');
    //         $mailer->textAssembler->setReferenceKey('orderSuccessful');
    //         $mailer->textAssembler->setEmbeddedViewKeys(['orderedProducts']);
    //         $mailer->textAssembler->setPlaceholdersAndValues([
    //             'name' => $recipient,
    //             'mobile' => $mobile,
    //             'orderedProducts' => $orderedProducts,
    //             'currency' => $currency,
    //             'totalPayable' => $totalPayable
    //         ]);
    //         $mailer->textAssembler->create();
    //         $mailer->setBody($mailer->textAssembler->getView());
    //         $mailer->addRecipient($email, $recipient);
    //         $mailer->send();
    //     }
    // }

    // public static function getWebshopUrlDetails() 
    // {
    //     $pageWebshopSlug = App::getContainer()->getUrl()->getMainRoute();
    //     $pageLocaleCode = null;
    //     $slugs = [];
        
    //     foreach (WebshopService::SLUGS['webshop']['slugTranslations'] as $localeCode => $webshopSlugVariant) {
    //         if ($webshopSlugVariant == $pageWebshopSlug) {
    //             $pageLocaleCode = $localeCode;
    //         }
    //     }

    //     $subRoute = App::getContainer()->getUrl()->getSubRoute();
    //     if ($subRoute) {
    //         $details = array_merge([$subRoute], App::getContainer()->getUrl()->getDetails());
    
    //         $nextDetailIsValueForSlug = null;
    //         foreach ($details as $detail) {
    //             if (!$detail) {
    //                 continue;
    //             }

    //             $slugFound = false;
    //             foreach (WebshopService::SLUGS as $slugKey => $slugConfig) {
    //                 $slugTranslations = $slugConfig['slugTranslations'];
    //                 if (isset($slugTranslations[$pageLocaleCode]) && $slugTranslations[$pageLocaleCode] == $detail) {
    //                     $slugFound = true;
    //                     $nextDetailIsValueForSlug = $slugKey;
    //                 }
    //             }

    //             if ($slugFound === false && $nextDetailIsValueForSlug) {
    //                 $slugs[$nextDetailIsValueForSlug] = $detail;
    //             }
    //         }
    //     }

    //     $return = [
    //         'pageWebshopSlug' => $pageWebshopSlug,
    //         'pageLocaleCode' => $pageLocaleCode,
    //         'slugs' => $slugs
    //     ];

    //     return $return;
    // }
}
