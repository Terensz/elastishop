<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\FileHandler;
use framework\packages\PaymentPackage\entity\Payment;
use framework\packages\PaymentPackage\repository\PaymentRepository;
use framework\packages\PaymentPackage\service\OnlinePaymentService;
use framework\packages\ToolPackage\service\Mailer;
use framework\packages\WebshopPackage\repository\ProductPriceActiveRepository;
use framework\packages\WebshopPackage\entity\ProductPriceActive;
use framework\packages\WebshopPackage\repository\CartRepository;
use framework\packages\WebshopPackage\repository\CartItemRepository;
use framework\packages\WebshopPackage\repository\ShipmentRepository;
use framework\packages\UserPackage\repository\UserAccountRepository;
use framework\packages\UserPackage\entity\TemporaryAccount;
use framework\packages\WebshopPackage\entity\Cart;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\repository\ProductCategoryRepository;

class WebshopService_COPY_231108 extends Service
{
    const WEBSHOP_IS_ACTIVE = false;
    const ALLOW_CART_QUANTITY = true;
    const PRODUCT_LIST_MAX_COLS = 3;
    const PRODUCT_LIST_MAX_PRODUCTS_ON_PAGE = 10;
    const ALL_PRODUCTS = 'all_products';
    const NON_LISTABLE_PRODUCTS = 'non_listable_products';
    const DISCOUNTED_PRODUCTS = 'discounted_products';
    const MOST_POPULAR_PRODUCTS = 'most_popular_products';
    const RECOMMENDED_PRODUCTS = 'recommended_products';
    const SHOW_NON_PRICED_PRODUCTS = true;

    // const WEBSHOP_SLUG_KEY_IN_LOCAL_LANG = 'webaruhaz';
    // const SHOW_PRODUCT_SLUG_KEY_IN_LOCAL_LANG = 'termekInfo';
    // const CATEGORY_SLUG_KEY_IN_LOCAL_LANG = 'kategoria';
    // const SEARCH_SLUG_KEY_IN_LOCAL_LANG = 'kereses';
    // const ALL_PRODUCTS_SLUG_KEY_IN_LOCAL_LANG = 'minden_termek';
    // const DISCOUNTED_PRODUCTS_SLUG_KEY_IN_LOCAL_LANG = 'akcios_termekek';
    const PAGE_SLUG_KEY_IN_LOCAL_LANG = 'lap';

    const SLUGS = [
        'webshop' => [
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
        'category' => [
            'slugTranslations' => [
                'hu' => 'kategoria',
                'en' => 'category'
            ],
            'transRef' => 'category',
            'isSpecialCategory' => false
        ],
        'search' => [
            'slugTranslations' => [
                'hu' => 'kereses',
                'en' => 'search'
            ],
            'transRef' => 'search',
            'isSpecialCategory' => false
        ],
        'show_product' => [
            'slugTranslations' => [
                'hu' => 'termek_info',
                'en' => 'show_product'
            ],
            'transRef' => 'show.product',
            'isSpecialCategory' => false
        ],
        'page' => [
            'slugTranslations' => [
                'hu' => 'lap',
                'en' => 'page'
            ],
            'transRef' => 'page',
            'isSpecialCategory' => false
        ],
        'all_products' => [
            'slugTranslations' => [
                'hu' => 'minden_termek',
                'en' => 'all_products'
            ],
            'transRef' => 'all.products',
            'isSpecialCategory' => true
        ],
        'discounted_products' => [
            'slugTranslations' => [
                'hu' => 'akcios_termekek',
                'en' => 'discounted_products'
            ],
            'transRef' => 'discounted.products',
            'isSpecialCategory' => true
        ],
        'most_popular_products' => [
            'slugTranslations' => [
                'hu' => 'legnepszerubb_termekek',
                'en' => 'most_popular_products'
            ],
            'transRef' => 'most.popular.products',
            'isSpecialCategory' => true
        ],
        'recommended_products' => [
            'slugTranslations' => [
                'hu' => 'ajanlott_termekek',
                'en' => 'recommended_products'
            ],
            'transRef' => 'recommended.products',
            'isSpecialCategory' => true
        ],
        'non_listable_products' => [
            'slugTranslations' => [
                'en' => 'non_listable_products',
                'hu' => 'non_listable_products'
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
        // dump(App::getContainer()->getUser());exit;
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
        'WebshopPackage_webshopIsActive' => self::WEBSHOP_IS_ACTIVE,
        'WebshopPackage_allowCartMultipleQuantity' => self::ALLOW_CART_QUANTITY,
        'WebshopPackage_priceDecimalSeparator' => ',',
        'WebshopPackage_priceDecimals' => 2,
        'WebshopPackage_discountPercentDecimals' => 1,
        'WebshopPackage_removeCartOnLogin' => false,
        'WebshopPackage_homepageListType' => self::ALL_PRODUCTS,
        'WebshopPackage_removeTemporaryPersonOnCloseShipment' => true,
        'WebshopPackage_onlyRegistratedUsersCanCheckout' => false,
        'WebshopPackage_closedShipmentIsEditable' => false,
        'WebshopPackage_reopenShipmentIsAllowed' => true,
        'WebshopPackage_displayedRunningOrders' => 20,
        'WebshopPackage_maxProductsOnPage' => self::PRODUCT_LIST_MAX_PRODUCTS_ON_PAGE,
        'WebshopPackage_productListMaxCols' => self::PRODUCT_LIST_MAX_COLS,
        'WebshopPackage_showNonPricedProducts' => self::SHOW_NON_PRICED_PRODUCTS
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

    public function getShipmentStatusConversionArray($requesterType = 'admin')
    {
        $container = App::getContainer();
        $container->wireService('WebshopPackage/entity/Shipment');
        $result = [];
        foreach (Shipment::$statuses as $key => $titles) {
            $result[$key] = $titles[$requesterType.'Title'];
        }

        return $result;
    }

    public static function hasRequiredOrder()
    {
        $container = App::getContainer();
        $container->setService('WebshopPackage/repository/ShipmentRepository');
        $shipmentRepo = $container->getService('ShipmentRepository');

        return $shipmentRepo->hasRequiredOrder();
    }

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
                    OnlinePaymentService::refreshPaymentStatus($payment->getShipment());
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

    public function getShipmentCount($status = null)
    {
        $this->getContainer()->setService('WebshopPackage/repository/ShipmentRepository');
        $shipmentRepo = $this->getContainer()->getService('ShipmentRepository');

        return $shipmentRepo->getShipmentCount($status = null);
    }

    public static function getShipmentByCode($shipmentCode)
    {
        App::getContainer()->setService('WebshopPackage/repository/ShipmentRepository');
        $shipmentRepo = App::getContainer()->getService('ShipmentRepository');
        $shipment = $shipmentRepo->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'code', 'value' => $shipmentCode],
        ]]);

        return $shipment;
    }

    public function getImagePath($relative = false)
    {
        // $filePath = 'projects/'.App::getWebProject().'/webshop/productImage';
        $filePath = 'projects/'.App::getWebProject().'/webshop/productImage';

        return $relative ? $filePath : FileHandler::completePath($filePath, 'dynamic');
    }

    public static function fillProductRows($products, $cols)
    {
        // dump($cols);
        $productRows = array();
        $counter = 0;
        $rowCounter = 0;
        $colCounter = 0;
        foreach ($products as $product) {
            if ($colCounter + 1 > $cols) {
                $rowCounter++;
                $colCounter = 0;
            }
            $productRows[$rowCounter][$colCounter] = $product;
            if ($counter == count($products) - 1 && $colCounter < $cols - 1) {
                # Missing cols in this row will be filled with NULL. Because we don't want view to do this, instead the controller.
                for ($i = ($colCounter + 1); $i < $cols; $i++) {
                    $productRows[$rowCounter][$i] = null;
                }
            }
            $colCounter++;
            $counter++;
        }

        return $productRows;
    }

    public static function examineAndSortProductList($products)
    {
        // $processedProducts = array();
        $listableProducts = array();
        $nonListableProducts = array();
        $errors = array();
        foreach ($products as $product) {
            if ($product->getProductPriceActive() && $product->getProductPriceActive()->getProductPrice()) {
                $listableProducts[] = $product;
            } else { 
                if (App::getContainer()->isGranted('viewProjectAdminContent')) {
                    $listableProducts[] = $product;
                    $nonListableProducts[] = $product;
                    $errors[$product->getId()] = array();
                    if (!$product->getProductPriceActive()) {
                        $errors[$product->getId()][] = trans('missing.active.product.price');
                    }
                    if ($product->getProductPriceActive() && !$product->getProductPriceActive()->getProductPrice()) {
                        $errors[$product->getId()][] = trans('missing.or.corrupted.product.price');
                    }
                }
            }
        }
        // dump($errors);exit;
        
        return array(
            // 'processedProducts' => $processedProducts,
            'listableProducts' => $listableProducts,
            'nonListableProducts' => $nonListableProducts,
            'errors' => $errors
        );
    }

    public static function getSlugConfig($searchedSlugTranslation)
    {
        foreach (WebshopService::SLUGS as $slugKey => $slugConfig) {
            foreach ($slugConfig['slugTranslations'] as $locale => $slugTranslation) {
                if ($slugTranslation == $searchedSlugTranslation) {
                    return $slugConfig;
                }
            }
        }

        return null;
    }

    public static function getSlugLocale($searchedSlugTranslation)
    {
        foreach (WebshopService::SLUGS as $slugKey => $slugConfig) {
            foreach ($slugConfig['slugTranslations'] as $locale => $slugTranslation) {
                if ($slugTranslation == $searchedSlugTranslation) {
                    return $locale;
                }
            }
        }

        return null;
    }

    public static function getSlugTransRef($searchedSlugKey, $searchedLocale)
    {
        foreach (WebshopService::SLUGS as $slugKey => $slugConfig) {
            foreach ($slugConfig['slugTranslations'] as $locale => $slugTranslation) {
                if ($slugKey == $searchedSlugKey && $locale == $searchedLocale) {
                    return $slugTranslation;
                }
            }
        }

        return null;
    }

    public static function findSpecialCategorySlugKey($categorySlug)
    {
        foreach (WebshopService::SLUGS as $slugKey => $slugConfig) {
            // $slugTranslations = $slugConfig['slugTranslations'];
            foreach ($slugConfig['slugTranslations'] as $locale => $slugTranslation) {
                if ($slugTranslation == $categorySlug && $slugConfig['isSpecialCategory'] == true) {
                    return $slugKey;
                }
            }
        }

        return null;
    }
    
    public static function getProductListUrlParams()
    {
        $container = App::getContainer();
        // $defaultLocale = $container->getDefaultLocale();
        $currentPage = 1;
        $categorySlugRequest = null;
        $specialCategorySlugKey = null;
        $specialCategoryTransRef = null;
        $categoryObject = null;
        $categorySlug = null;
        $categoryName = null;
        $searchString = null;
        $isHomepage = false;
        $paramChain = $container->getUrl()->getParamChain();
        $paramChainParts = explode('/', $paramChain);
        $route = $container->getRouting()->getPageRoute();
        $localeRequest = self::getSlugLocale($paramChainParts[0]);

        if (in_array($route->getName(), array('webshop_productList_noFilter', 'webshop_productList_noFilter_page'))) {
            $isHomepage = true;
            if (self::getSetting('WebshopPackage_homepageListType') == self::DISCOUNTED_PRODUCTS) {
                $specialCategorySlugKey = self::DISCOUNTED_PRODUCTS;
                $specialCategoryTransRef = self::getSlugConfig($specialCategorySlugKey)['transRef'];
                // $discountedList = true;
            } elseif (self::getSetting('WebshopPackage_homepageListType') == self::MOST_POPULAR_PRODUCTS) {
                $specialCategorySlugKey = self::MOST_POPULAR_PRODUCTS;
                $specialCategoryTransRef = self::getSlugConfig($specialCategorySlugKey)['transRef'];
            } elseif (self::getSetting('WebshopPackage_homepageListType') == self::ALL_PRODUCTS) {
                $specialCategorySlugKey = self::ALL_PRODUCTS;
                $specialCategoryTransRef = self::getSlugConfig($specialCategorySlugKey)['transRef'];
            }
        }

        if (in_array($route->getName(), array('webshop_productList_all'))) {
            // $listAll = true;
            $specialCategorySlugKey = self::ALL_PRODUCTS;
        }

        // dump($route->getName());exit;
        if ($route->getName() == 'webshop_productList_anomalousProducts') {
            // $currentPage = $paramChainParts[2];
            $specialCategorySlugKey = self::NON_LISTABLE_PRODUCTS;
        }
        if ($route->getName() == 'webshop_productList_all_page') {
            $specialCategorySlugKey = self::ALL_PRODUCTS;
            $currentPage = $paramChainParts[3];
        }
        if ($route->getName() == 'webshop_productList_noFilter_page') {
            $specialCategorySlugKey = self::ALL_PRODUCTS;
            $currentPage = $paramChainParts[2];
        }
        // if ($route->getName() == 'webshop_productList_discountedProducts_page') {
        //     $currentPage = $paramChainParts[3];
        // }
        if ($route->getName() == 'webshop_productList_category_page') {
            $currentPage = $paramChainParts[4];
        }
        if ($route->getName() == 'webshop_productList_searchString_page') {
            $currentPage = $paramChainParts[4];
        }
        if ($route->getName() == 'webshop_productList_categoryAndSearchString_page') {
            $currentPage = $paramChainParts[6];
        }

        // dump($route->getName());
        if (in_array($route->getName(), ['webshop_productList_category', 'webshop_productList_categoryAndSearchString', 'webshop_productList_category_page'])) {
            $categorySlugRequest = $paramChainParts[2];
            $categorySlugKey = WebshopService::findSpecialCategorySlugKey($categorySlugRequest);
            if ($categorySlugKey) {
                $specialCategorySlugKey = $categorySlugKey;
                $specialCategoryTransRef = self::getSlugConfig($specialCategorySlugKey)['transRef'];
                $categoryName = trans($specialCategoryTransRef);
                // $categorySlug = $categorySlugRequest;
                $categorySlug = $specialCategorySlugKey;
            }
        }
        if (in_array($route->getName(), ['webshop_productList_searchString', 'webshop_productList_searchString_page'])) {
            $searchString = $paramChainParts[2];
            // $englishRequest = $paramChainParts[1] == 'search' ? true : false;
        }
        if (in_array($route->getName(), ['webshop_productList_categoryAndSearchString', 'webshop_productList_categoryAndSearchString_page'])) {
            $categorySlugRequest = $paramChainParts[2];
            $searchString = $paramChainParts[4];
            // $englishRequest = $paramChainParts[1] == 'category' ? true : false;
        }

        if ($categorySlugRequest && !$specialCategorySlugKey) {
            $container->wireService('WebshopPackage/repository/ProductCategoryRepository');
            $productCategoryRepo = new ProductCategoryRepository();
            $category = $productCategoryRepo->findOneBy(['conditions' => [['key' => 'slug'.($localeRequest == 'en' ? '_en' : ''), 'value' => $categorySlugRequest]]]);
            if ($category) {
                $categoryObject = $category;
                $nameGetter = $localeRequest == 'en' ? 'getNameEn' : 'getName';
                $slugGetter = $localeRequest == 'en' ? 'getSlugEn' : 'getSlug';
                $categoryName = $category->$nameGetter();
                $categorySlug = $category->$slugGetter();
            }
        }

        return array(
            'categorySlugRequest' => $categorySlugRequest,
            'specialCategorySlugKey' => $specialCategorySlugKey,
            'specialCategoryTransRef' => $specialCategoryTransRef,
            'categoryObject' => $categoryObject,
            'categorySlug' => $categorySlug,
            'categoryName' => $categoryName,
            'searchString' => $searchString ? urldecode($searchString) : $searchString,
            'localeRequest' => $localeRequest,
            // 'english' => $container->getSession()->getLocale() == 'en' ? true : false,
            // 'specialCategory' => $specialCategory,
            // 'english' => $english,
            // 'discountedList' => $discountedList,
            // 'mostPopularList' => $mostPopularList,
            // 'listAll' => $listAll,
            'isHomepage' => $isHomepage,
            // 'specialTag' => $specialTag,
            'currentPage' => $currentPage
        );
    }

    public function removeObsoleteCarts()
    {
        $this->wireService('WebshopPackage/repository/CartRepository');

        $cartRepo = new CartRepository();
        $cartRepo->removeObsolete();
    }

    public function removeUnboundCartItems()
    {
        $this->wireService('WebshopPackage/repository/CartItemRepository');

        $cartItemRepo = new CartItemRepository();
        $cartItemRepo->removeUnbound();
    }

    public function addToCart($productPriceActiveId, $addedQuantity = null, $newQuantity = null)
    {
        $this->wireService('WebshopPackage/repository/CartRepository');
        $this->wireService('WebshopPackage/repository/CartItemRepository');
        $this->wireService('WebshopPackage/entity/ProductPriceActive');
        $this->wireService('WebshopPackage/repository/ProductPriceActiveRepository');
        $this->wireService('UserPackage/repository/UserAccountRepository');

        $accRepo = new UserAccountRepository();

        $productPriceActiveRepo = new ProductPriceActiveRepository();
        $productPriceActive = $productPriceActiveRepo->find($productPriceActiveId);

        if ($productPriceActive instanceof ProductPriceActive) {
            $cartRepo = new CartRepository();
            // dump($this->getSession()->get('webshop_cartId'));exit;
            $cartId = null;
            if ($this->getSession()->get('webshop_cartId')) {
                $cartId = $this->getSession()->get('webshop_cartId');
            } 
            
            $cart = !$cartId ? null : $cartRepo->find($cartId);

            if (!$cart) {
                $cart = $cartRepo->createNewEntity();
                $userAccount = $accRepo->find($this->getContainer()->getUser()->getId());
                if ($userAccount) {
                    $cart->setUserAccount($userAccount);
                }
                $cart->setVisitorCode($this->getSession()->get('visitorCode'));
                $cart->setCreatedAt($this->getCurrentTimestamp());
                $cart = $cartRepo->store($cart);
                $cartId = $cart->getId();
                $this->getSession()->set('webshop_cartId', $cartId);
            }

            $cartItemRepo = new CartItemRepository();
            // if (!$productPriceActive->getProductPrice()) {
            //     dump($productPriceActiveId);
            //     dump($productPriceActive);exit;
            // }
            $cartItem = $cartItemRepo->findOneBy(['conditions' => [
                ['key' => 'cart_id', 'value' => $cartId], 
                ['key' => 'product_id', 'value' => $productPriceActive->getProduct()->getId()],
                ['key' => 'product_price_id', 'value' => $productPriceActive->getProductPrice()->getId()]
            ]]);

            if (!$cartItem) {
                $originalQuantity = 0;
                $cartItem = $cartItemRepo->createNewEntity();
                $cartItem->setQuantity($originalQuantity);
                $cartItem->setCart($cart);
            } else {
                $originalQuantity = $cartItem->getQuantity();
            }

            if (is_numeric($addedQuantity) && $newQuantity === null) {
                $quantity = $originalQuantity + $addedQuantity;
            } elseif ($addedQuantity === null && is_numeric($newQuantity)) {
                $quantity = $newQuantity;
            } else {
                throw new \Exception('$addedQuantity and $newQuantity cannot be null at once.');
            }

            if ($originalQuantity > $quantity) {
                return $this->removeFromCart($productPriceActiveId, ($originalQuantity - $quantity));
            }

            $cartItem->setQuantity($quantity);

            // dump($addedQuantity);
            // dump($newQuantity);
            // dump($cartItem);exit;

            $cartItem->setProduct($productPriceActive->getProduct());
            $cartItem->setProductPrice($productPriceActive->getProductPrice());
            $cartItem = $cartItemRepo->store($cartItem);
            // $cart->addCartItem($cartItem);
            return $cartItem;
        } else {
            return false;
        }
    }

    public function removeFromCart($productPriceActiveId, $quantity)
    {
        $this->wireService('WebshopPackage/repository/CartRepository');
        $this->wireService('WebshopPackage/repository/CartItemRepository');
        $this->wireService('WebshopPackage/entity/ProductPriceActive');
        $this->wireService('WebshopPackage/repository/ProductPriceActiveRepository');
        $this->wireService('UserPackage/repository/UserAccountRepository');

        $accRepo = new UserAccountRepository();

        $productPriceActiveRepo = new ProductPriceActiveRepository();
        $productPriceActive = $productPriceActiveRepo->find($productPriceActiveId);

        if ($productPriceActive instanceof ProductPriceActive) {
            $cartRepo = new CartRepository();
            // dump($this->getSession()->get('webshop_cartId'));exit;
            $cartId = null;
            if ($this->getSession()->get('webshop_cartId')) {
                $cartId = $this->getSession()->get('webshop_cartId');
            }

            $cart = !$cartId ? null : $cartRepo->find($cartId);
            if (!$cart) {
                $this->getSession()->set('webshop_cartId', null);
            }

            $cartItemRepo = new CartItemRepository();
            $cartItem = $cartItemRepo->findOneBy(['conditions' => [
                ['key' => 'cart_id', 'value' => $cartId], 
                ['key' => 'product_id', 'value' => $productPriceActive->getProduct()->getId()],
                ['key' => 'product_price_id', 'value' => $productPriceActive->getProductPrice()->getId()]
            ]]);

            if ($cartItem) {
                $q = $cartItem->getQuantity() - $quantity;
                if ($q <= 0) {
                    // dump($cartItem->getId());exit;
                    $cartItemRepo->removeBy(['id' => $cartItem->getId()]);
                    // dump($cartItem->getId());exit;
                    $cartRepo->find($cartId);
                    if ($cart) {
                        $cartItems = $cartItemRepo->findBy(['conditions' => [['key' => 'cart_id', 'value' => $cart->getId()]]]);
                        if (!$cartItems || ($cartItems && count($cartItems) == 0)) {
                            // dump($cartItems);exit;
                            $cartRepo->removeBy(['id' => $cart->getId()]);
                        }
                    }
                } else {
                    $cartItem->setQuantity($q);
                    $cartItem->setProduct($productPriceActive->getProduct());
                    $cartItem->setProductPrice($productPriceActive->getProductPrice());
                    $cartItem = $cartItemRepo->store($cartItem);
                }
            }

            return $cartItem;
        } else {
            return false;
        }
    }

    public function removeOldCart()
    {
        $this->wireService('WebshopPackage/repository/CartRepository');
        $cartRepo = new CartRepository();
        $cartRepo->removeBy(['user_account_id' => $this->getSession()->get('userId')]);
        $cartRepo->removeBy(['visitor_code' => $this->getSession()->get('visitorCode')]);
    }

    public function identifyCart()
    {
        $this->wireService('UserPackage/repository/UserAccountRepository');
        $accRepo = new UserAccountRepository();

        $this->wireService('WebshopPackage/repository/CartRepository');
        $cartRepo = new CartRepository();

        $carts = $cartRepo->findBy(['conditions' => [['key' => 'visitor_code', 'value' => $this->getSession()->get('visitorCode')]]]);
        if (count($carts) == 1) {
            $userAccount = $accRepo->find($this->getSession()->get('userId'));
            // dump($this->getSession()->get('userId'));exit;
            if ($userAccount) {
                // dump($carts[0]);exit;
                $carts[0]->setUserAccount($userAccount);
                $cart = $cartRepo->store($carts[0]);
                $this->getSession()->set('webshop_cartId', $cart->getId());
            }
        } elseif (count($carts) > 1) {
            $cartRepo->removeBy(['visitor_code' => $this->getSession()->get('visitorCode')]);
        }
    }

    public static function getCart() : ? Cart
    {
        $container = App::getContainer();
        $container->wireService('WebshopPackage/repository/CartRepository');
        $cartRepo = new CartRepository();
        $foundCart = $cartRepo->find($container->getSession()->get('webshop_cartId'));
        // dump($foundCart);

        return !$foundCart ? null : $foundCart;
    }

    public function getUnfinishedOrder()
    {
        $container = App::getContainer();
        $container->wireService('WebshopPackage/repository/ShipmentRepository');
        $container->wireService('WebshopPackage/entity/Shipment');
        $shipmentRepo = new ShipmentRepository();

        return $shipmentRepo->findOneBy([
            'conditions' => [
                ['key' => 'visitor_code', 'value' => $this->getContainer()->getSession()->get('visitorCode')],
                ['key' => 'status', 'value' => Shipment::SHIPMENT_STATUS_REQUIRED],
            ],
            'orderBy' => [['field' => 'id', 'direction' => 'DESC']]
        ]);
    }

    public static function getCartActiveProductPriceIds()
    {
        $result = [];
        $cart = self::getCart();
        if ($cart) {
            foreach ($cart->getCartItem() as $cartItem) {
                $activePrice = $cartItem->getProduct()->getProductPriceActive();
                if ($activePrice) {
                    $result[] = $activePrice->getId();
                }
            }
            
        }

        return $result;
    }

    // public function getTemporaryAccount_OLD($notBelongsToOrder = false)
    // {
    //     // dump($this->getSession()->get('webshop_temporaryAccountParams'));exit;
    //     // dump($notBelongsToOrder);exit;
    //     $temporaryAccountParams = $this->getSession()->get('webshop_temporaryAccountParams');

    //     $this->setService('UserPackage/repository/TemporaryAccountRepository');
    //     $tempAccRepo = $this->getService('TemporaryAccountRepository');

    //     $this->setService('WebshopPackage/repository/ShipmentRepository');
    //     $shipmentRepo = $this->getService('ShipmentRepository');

    //     if ($notBelongsToOrder && $temporaryAccountParams && isset($temporaryAccountParams['id'])) {
    //         $shipment = $shipmentRepo->findBy(['conditions' => [
    //             ['key' => 'temporary_account_id', 'value' => $temporaryAccountParams['id']]
    //         ]]);
    //         if ($shipment) {
    //             $temporaryAccountParams = null;
    //             $this->getSession()->set('webshop_temporaryAccountParams', null);
    //         }
    //     }

    //     if ($temporaryAccountParams && ($temporaryAccountParams['visitorCode'] != $this->getSession()->get('visitorCode'))) {
    //         $temporaryAccountParams = null;
    //         $this->getSession()->set('webshop_temporaryAccountParams', null);
    //     }

    //     $temporaryAccount = null;
    //     if ($temporaryAccountParams) {
    //         $temporaryAccount = $tempAccRepo->find($temporaryAccountParams['id']);
    //     }

    //     // dump($temporaryAccount);
    //     if ($temporaryAccount) {
    //         if (!$temporaryAccount->getTemporaryPerson()) {
    //             $tempPerson = $this->createTemporaryPerson();
    //             $tempPerson->setTemporaryAccount($temporaryAccount);
    //             $tempPerson->getRepository()->store($tempPerson);
    //             $temporaryAccount->setTemporaryPerson($tempPerson);
    //             $temporaryAccount->getRepository()->store($temporaryAccount);
    //         }
    //         return $temporaryAccount;
    //     } else {
    //         return $this->createTemporaryAccount();
    //     }
    // }

    public function removeTemporaryAccount()
    {
        $this->setService('UserPackage/repository/TemporaryAccountRepository');
        $this->wireService('UserPackage/entity/TemporaryAccount');

        $tempAccRepo = $this->getService('TemporaryAccountRepository');
        $temporaryAccount = $tempAccRepo->findOneBy(['conditions' => [
            ['key' => 'visitor_code', 'value' => $this->getSession()->get('visitorCode')],
            ['key' => 'status', 'value' => TemporaryAccount::STATUS_OPEN]
        ]]);
        if ($temporaryAccount) {
            $tempAccRepo->remove($temporaryAccount->getId());
        }
    }

    public function findOrCreateTemporaryAccount()
    {
        $this->setService('UserPackage/repository/TemporaryAccountRepository');
        $this->wireService('UserPackage/entity/TemporaryAccount');
        
        $tempAccRepo = $this->getService('TemporaryAccountRepository');
        $temporaryAccount = $tempAccRepo->findOneBy(['conditions' => [
            ['key' => 'visitor_code', 'value' => $this->getSession()->get('visitorCode')],
            ['key' => 'status', 'value' => TemporaryAccount::STATUS_OPEN]
        ]]);

        // if ($this->getContainer()->getUser()->getUserAccount() && $this->getContainer()->getUser()->getUserAccount()->getPerson() && $this->getContainer()->isGranted('viewOnlyUserNotAdminContent')) {
        //     if ($temporaryAccount && $temporaryAccount->getTemporaryPerson()) {
        //         $defaultAddress = $this->getContainer()->getUser()->getUserAccount()->getPerson()->getDefaultAddress();
        //         $temporaryAddress = $temporaryAccount->getTemporaryPerson()->getAddress();
        //         if ($defaultAddress && $temporaryAddress) {
        //             $temporaryTestAddress = $temporaryAddress->setId($defaultAddress->getId());
        //             if ($defaultAddress != $temporaryTestAddress) {
        //                 $temporaryAddress->getRepository()->remove($temporaryAddress->getId());
        //                 $temporaryAccount->getTemporaryPerson()->setAddress(null);
        //                 $temporaryAccount->getRepository()->remove($temporaryAccount->getId());
        //                 $temporaryAccount = null;
        //             }
        //         }
        //     }
        //     // return null;
        //     // dump($this->getContainer()->getUser());exit;
        // }

        if (!$temporaryAccount) {
            $temporaryAccount = $this->createTemporaryAccount();
        }

        $this->getSession()->set('webshop_temporaryAccountParams', [
            'id' => $temporaryAccount->getId(),
            'visitorCode' => $this->getSession()->get('visitorCode')
        ]);

        if (!$temporaryAccount->getTemporaryPerson()) {
            $temporaryPerson = $this->createTemporaryPerson();
            $temporaryPerson->setTemporaryAccount($temporaryAccount);
            $temporaryPerson->getRepository()->store($temporaryPerson);
            $temporaryAccount->setTemporaryPerson($temporaryPerson);
            $temporaryAccount->getRepository()->store($temporaryAccount);
        }
        
        return $temporaryAccount;
    }

    public function setTemporaryAddress($address)
    {
        $address = $address->getRepository()->store($address);
        $temporaryAccount = $this->findOrCreateTemporaryAccount();
        $temporaryAccount->getTemporaryPerson()->setAddress($address);
        $temporaryAccount->getRepository()->store($temporaryAccount);
        // $savedAddress = $temporaryAccount->getTemporaryPerson()->getAddress();
        // dump($address);
        return $address;
    }

    public function createTemporaryPerson()
    {
        $this->setService('UserPackage/repository/TemporaryPersonRepository');
        $tempPersonRepo = $this->getService('TemporaryPersonRepository');

        $this->setService('UserPackage/repository/AddressRepository');
        $addressRepo = $this->getService('AddressRepository');

        $temporaryPerson = $tempPersonRepo->createNewEntity();
        $temporaryPerson = $tempPersonRepo->store($temporaryPerson);

        // dump($temporaryPerson);
        // dump($this->getContainer()->getUser());

        if ($this->getContainer()->getUser()->getUserAccount() && $this->getContainer()->isGranted('viewOnlyUserNotAdminContent')) {
            $address = $this->getContainer()->getUser()->getUserAccount()->getPerson()->getDefaultAddress();
            $address->setId(null);
            $address->setPerson(null);
        } else {
            $address = $addressRepo->createNewEntity();
        }

        // dump($temporaryPerson);
        // dump($address);
        // dump($this->getContainer()->getUser());exit;

        // $address = $addressRepo->store($address);
        // $temporaryPerson->setAddress($address);
        $temporaryPerson = $tempPersonRepo->store($temporaryPerson);
        return $temporaryPerson;
    }

    private function createTemporaryAccount()
    {
        $this->setService('UserPackage/repository/TemporaryAccountRepository');
        $tempAccRepo = $this->getService('TemporaryAccountRepository');

        $temporaryPerson = $this->createTemporaryPerson();
        // $tempPerson->setCreatedAt($this->getCurrentTimestamp());
        $temporaryAccount = $tempAccRepo->createNewEntity();
        $temporaryAccount->setVisitorCode($this->getSession()->get('visitorCode'));
        $temporaryAccount->setCreatedAt($this->getCurrentTimestamp());
        $temporaryPerson->setTemporaryAccount($temporaryAccount);
        $temporaryAccount->setTemporaryPerson($temporaryPerson);
        $temporaryAccount = $tempAccRepo->store($temporaryAccount);

        return $temporaryAccount;
    }

    public static function closeOrder(Shipment $shipment, bool $sendMail = true)
    {
        $container = App::getContainer();
        $container->wireService('WebshopPackage/entity/Shipment');
        $container->wireService('WebshopPackage/service/WebshopService');
        $webshopService = new self();
        $container->wireService('WebshopPackage/service/WebshopPriceService');
        // $webshopFinanceService = new WebshopPriceService();
        // $container->setService('WebshopPackage/repository/ShipmentRepository');
        // $shipmentRepo = $container->getService('ShipmentRepository');

        $webshopService->removeOldCart();
        // dump('Miafasz????');exit;
        $shipment->setStatus(Shipment::SHIPMENT_STATUS_ORDERED);
        $shipment->getRepository()->store($shipment);

        // $shipment = $webshopService->getUnfinishedOrder();
        // if ($shipment) {
        //     $shipment->setStatus(Shipment::SHIPMENT_STATUS_REQUIRED);
        //     $shipment->getRepository()->store($shipment);
        // }

        if ($container->getSession()->userLoggedIn()) {
            $email = $shipment->getUserAccount()->getPerson()->getEmail();
            $mobile = $shipment->getUserAccount()->getPerson()->getMobile();
            $recipient = $shipment->getUserAccount()->getPerson()->getFullName();
        } else {
            $email = $shipment->getTemporaryAccount()->getTemporaryPerson()->getEmail();
            $mobile = $shipment->getTemporaryAccount()->getTemporaryPerson()->getMobile();
            $recipient = $shipment->getTemporaryAccount()->getTemporaryPerson()->getName();
        }

        // $shipment->getTemporaryAccount()->setStatus($shipment->getTemporaryAccount()::STATUS_CLOSED);
        // $shipment->getTemporaryAccount()->getRepository()->store($shipment->getTemporaryAccount());

        $currency = WebshopPriceService::getDefaultCurrency();
        $totalPayable = 0;
        $orderedProducts = [];
        foreach ($shipment->getShipmentItem() as $shipmentItem) {
            $itemGross = $shipmentItem->getQuantity() * WebshopPriceService::getAnalyzedPriceData($shipmentItem->getProductPrice()->getId())['gross_price'];
            $totalPayable += $itemGross;
            $orderedProducts[] = [
                'productName' => $shipmentItem->getProduct()->getName(),
                'quantity' => $shipmentItem->getQuantity(),
                'itemGross' => $itemGross,
                'currency' => $currency
            ];
        }

        if ($sendMail) {
            $container->wireService('ToolPackage/service/Mailer');
            $mailer = new Mailer();
            $mailer->setSubject($container->getCompanyData('brand').' - '.trans('order.successful'));
            $mailer->textAssembler->setPackage('WebshopPackage');
            $mailer->textAssembler->setReferenceKey('orderSuccessful');
            $mailer->textAssembler->setEmbeddedViewKeys(['orderedProducts']);
            $mailer->textAssembler->setPlaceholdersAndValues([
                'name' => $recipient,
                'mobile' => $mobile,
                'orderedProducts' => $orderedProducts,
                'currency' => $currency,
                'totalPayable' => $totalPayable
            ]);
            $mailer->textAssembler->create();
            $mailer->setBody($mailer->textAssembler->getView());
            $mailer->addRecipient($email, $recipient);
            $mailer->send();
        }
    }

    public static function getBaseLink()
    {
        return self::assembleLink(['baseOnly' => true]);
    }

    public static function getListAllLink()
    {
        return self::assembleLink(['forceListAll' => true]);
    }

    public static function createProductListLink($searchString, $categorySlug, $english)
    {
        if ($searchString) {
            $params['searchString'] = $searchString;
        }

        if ($categorySlug) {
            $params['categorySlug'] = $categorySlug;
        }

        if (!$searchString && !$categorySlug) {
            $params['forceListAll'] = true;
        }

        return self::assembleLink($params, $english);
    }

    public static function assembleLink(array $params, $locale = null)
    {
        // return array(
        //     'categorySlugRequest' => $categorySlugRequest,
        //     'specialCategorySlugKey' => $specialCategorySlugKey,
        //     'specialCategoryTransRef' => $specialCategoryTransRef,
        //     'categoryObject' => $categoryObject,
        //     'categorySlug' => $categorySlug,
        //     'searchString' => $searchString ? urldecode($searchString) : $searchString,
        //     'localeRequest' => $localeRequest,
        //     'isHomepage' => $isHomepage,
        //     'currentPage' => $currentPage
        // );

        $locale = isset($params['localeRequest']) ? $params['localeRequest'] : App::getContainer()->getSession()->getLocale();
        $linkString = self::getSlugTransRef('webshop', $locale);

        $params['baseOnly'] = isset($params['baseOnly']) && $params['baseOnly'] ? $params['baseOnly'] : null;
        if ($params['baseOnly']) {
            return $linkString;
        }

        $linkString .= '/';

        $params['searchString'] = isset($params['searchString']) && $params['searchString'] ? $params['searchString'] : null;
        $params['forceListAll'] = isset($params['forceListAll']) && $params['forceListAll'] ? $params['forceListAll'] : null;
        $params['listNonListables'] = isset($params['listNonListables']) && $params['listNonListables'] ? $params['listNonListables'] : null;

        if ($params['forceListAll']) {
            $linkString .= self::getSlugTransRef('category', $locale).'/';
            $linkString .= self::getSlugTransRef('all_products', $locale);

            return $linkString;
        }

        if ($params['listNonListables']) {
            return 'webshop/non_listable_products';
        }

        $params['currentPage'] = isset($params['currentPage']) && $params['currentPage'] ? $params['currentPage'] : 1;

        $params['discountedList'] = isset($params['discountedList']) && $params['discountedList'] ? $params['discountedList'] : null;
        if ($params['discountedList']) {
            $linkString .= self::getSlugTransRef('category', $locale).'/';
            $linkString .= self::getSlugTransRef('discounted_products', $locale).'/';
        }

        $params['categorySlug'] = isset($params['categorySlug']) && $params['categorySlug'] ? $params['categorySlug'] : null;
        if ($params['categorySlug'] && $params['searchString']) {
            $linkString .= (self::getSlugTransRef('category', $locale).'/').$params['categorySlug'].'/';
            $linkString .= (self::getSlugTransRef('search', $locale).'/').$params['searchString'].'/';
        } else {
            if ($params['categorySlug']) {
                $linkString .= (self::getSlugTransRef('category', $locale).'/').$params['categorySlug'].'/';
            }
            if ($params['searchString']) {
                $linkString .= (self::getSlugTransRef('search', $locale).'/').$params['searchString'].'/';
            }
        }

        if ($params['currentPage'] && $params['currentPage'] > 1) {
            $linkString .= (self::getSlugTransRef('page', $locale).'/').$params['currentPage'].'/';
        }

        return $linkString;
    }

    public static function getWebshopUrlDetails() 
    {
        $pageWebshopSlug = App::getContainer()->getUrl()->getMainRoute();
        $pageLocaleCode = null;
        $slugs = [];
        
        foreach (WebshopService::SLUGS['webshop']['slugTranslations'] as $localeCode => $webshopSlugVariant) {
            if ($webshopSlugVariant == $pageWebshopSlug) {
                $pageLocaleCode = $localeCode;
            }
        }

        $subRoute = App::getContainer()->getUrl()->getSubRoute();
        if ($subRoute) {
            $details = array_merge([$subRoute], App::getContainer()->getUrl()->getDetails());
    
            $nextDetailIsValueForSlug = null;
            foreach ($details as $detail) {
                if (!$detail) {
                    continue;
                }

                $slugFound = false;
                foreach (WebshopService::SLUGS as $slugKey => $slugConfig) {
                    $slugTranslations = $slugConfig['slugTranslations'];
                    if (isset($slugTranslations[$pageLocaleCode]) && $slugTranslations[$pageLocaleCode] == $detail) {
                        $slugFound = true;
                        $nextDetailIsValueForSlug = $slugKey;
                    }
                }

                if ($slugFound === false && $nextDetailIsValueForSlug) {
                    $slugs[$nextDetailIsValueForSlug] = $detail;
                }
            }
        }

        $return = [
            'pageWebshopSlug' => $pageWebshopSlug,
            'pageLocaleCode' => $pageLocaleCode,
            'slugs' => $slugs
        ];

        return $return;
    }
}
