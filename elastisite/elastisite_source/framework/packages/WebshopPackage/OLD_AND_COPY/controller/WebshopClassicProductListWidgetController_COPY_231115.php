<?php
namespace framework\packages\WebshopPackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\kernel\utility\BasicUtils;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\repository\ProductCategoryRepository;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\WebshopPackage\service\WebshopPriceService;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler_Categories;
use framework\packages\WebshopPackage\responseAssembler\WebshopResponseAssembler_ProductList;

class WebshopClassicProductListWidgetController_COPY_231115 extends WidgetController
{
    public function __construct()
    {
        $this->getContainer()->setService('WebshopPackage/service/WebshopService');
        $this->getContainer()->setService('WebshopPackage/service/WebshopRequestService');
        $this->getContainer()->setService('WebshopPackage/service/WebshopPriceService');
    }
    
    /**
    * Route: [name: webshop_productListWidget, paramChain: /webshop/productListWidget]
    */
    public function webshopProductListWidgetAction()
    {
        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_ProductList');
        WebshopResponseAssembler_ProductList::assembleProductList();exit;
        // $this->getContainer()->setService('WebshopPackage/repository/CartRepository');
        // $cartRepo = $this->getContainer()->getService('CartRepository');
        // $cart = $cartRepo->findBy(['conditions' => [['key' => 'visitor_code', 'value' => $this->getSession()->get('visitorCode')]]]);
        // $webshopService = $this->getContainer()->getService('WebshopService');

        // dump(WebshopService::getCart());exit;

        // dump($this->getSession()->get('visitorCode'));
        // dump(WebshopService::hasUnconfirmedOrder());exit;
        if (WebshopService::hasUnconfirmedOrder()) {
            App::getContainer()->wireService('WebshopPackage/controller/WebshopCheckoutWidgetController');
            $controller = new WebshopCheckoutWidgetController();

            return $controller->returnFinalizeOrder();
        }

        App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_Categories');
        // echo WebshopResponseAssembler_Categories::renderCategories();exit;

        // $this->setService('WebshopPackage/repository/ProductCategoryRepository');
        // $productCategoryRepo = $this->getContainer()->getService('ProductCategoryRepository');
        $listParams = WebshopRequestService::getProcessedRequestData();

        $productCategory = $listParams['categoryObject'] ? : null;
        $english = $listParams['localeRequest'] == 'en' ? true : false;

        $this->wireService('WebshopPackage/repository/ProductRepository');
        $productRepo = new ProductRepository();
        // dump($listParams);

        if ($listParams['specialCategorySlugKey'] == WebshopService::ALL_PRODUCTS) {
            // dump('inaccurateSearchAll');
            $products = $productRepo->searchAll($listParams['searchString'], $listParams['currentPage']);
        } elseif ($listParams['specialCategorySlugKey'] == WebshopService::DISCOUNTED_PRODUCTS) {
            $products = $productRepo->searchDiscounted($listParams['searchString'], $listParams['currentPage']);
        } else {
            $products = $productRepo->searchCategory($listParams['searchString'], $listParams['categorySlugRequest'], $english, $listParams['currentPage']);
        }

        // dump($products);exit;

        if ($listParams['categoryObject'] && !empty($listParams['searchString'])) {
            $this->getSession()->set('webshop_categoryFilter', 'category');
        }

        if ($listParams['specialCategorySlugKey'] == WebshopService::ALL_PRODUCTS && !empty($listParams['searchString'])) {
            $this->getSession()->set('webshop_categoryFilter', 'all');
        }

        $listedProducts = $products ? $this->getListedProducts($products, $productCategory, $listParams) : null;
        $totalProducts = $listedProducts ? count($listedProducts['products']) : 0;
        $limit = WebshopService::getSetting('WebshopPackage_maxProductsOnPage');
        $totalPages = ceil($totalProducts / $limit);
        $listAllLink = WebshopRequestService::getListAllLink();

        $viewParams = [
            'currentPage' => $listParams['currentPage'],
            'totalPages' => $totalPages,
            'isWebshopTestMode' => WebshopService::isWebshopTestMode(),
            // 'container' => $this->getContainer(),
            'renderedWebshopProductList' => $this->renderWebshopProductList($listedProducts, $listParams['currentPage'], $limit),
            // 'productCategory' => $productCategory,
            'searchString' => $listParams['searchString'],
            'specialCategorySlugKey' => $listParams['specialCategorySlugKey'],
            'specialCategoryTransRef' => $listParams['specialCategoryTransRef'],
            'categorySlug' => $listParams['categorySlug'],
            'categoryName' => $listParams['categoryName'],
            // 'listAll' => $listParams['listAll'],
            'linkWithoutCategory' => WebshopRequestService::createProductListLink($listParams['searchString'], null, $english),
            'linkWithoutSearch' => WebshopRequestService::createProductListLink(null, $listParams['categorySlugRequest'], $english),
            'webshopBaseLink' => WebshopRequestService::getBaseLink(),
            'listAllLink' => $listAllLink,
            'listAllCategorySlug' => BasicUtils::explodeAndGetElement($listAllLink, '/', 'last'),
            // 'searchLinkBase' => WebshopService::getBaseLink().'/'.$this->getSearchStringKey($listParams['localeRequest']),
            'searchLinkBase' => rtrim(WebshopRequestService::assembleLink(['categorySlug' => '{category}', 'searchString' => '{searchString}']), '/'),
            'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain(),
            'localeRequest' => $listParams['localeRequest'],
            'categoryFilter' => $this->getSession()->get('webshop_categoryFilter'),
            'grantedViewProjectAdminContent' => $this->getContainer()->isGranted('viewProjectAdminContent')
            // 'webshopService' => $webshopService,
        ];

        // dump($viewParams);exit;

        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopProductListWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('WebshopProductListWidget', $viewPath, $viewParams),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    public function getSearchStringKey($locale)
    {
        $this->getContainer()->wireService('WebshopPackage/service/WebshopRequestService');

        return WebshopRequestService::getSlugTransRef('search', $locale);
    }

    public function getListedProducts($products, $productCategory, $listParams)
    {
        $webshopService = $this->getContainer()->getService('WebshopService');
        $webshopFinanceService = $this->getContainer()->getService('WebshopPriceService');
        $examinedProducts = WebshopService::examineAndSortProductList($products);
        
        if ($listParams['specialCategorySlugKey'] == WebshopService::NON_LISTABLE_PRODUCTS) {
            $products = $examinedProducts['nonListableProducts'];
        } else {
            $products = $examinedProducts['listableProducts'];
        }
        
        return [
            'products' => $products,
            'cartOfferIds' => WebshopService::getCartActiveProductPriceIds(),
            'productCategory' => $productCategory,
            // 'productRows' => $productRows,
            // 'container' => $this->getContainer(),
            'errors' => $examinedProducts['errors'],
            'webshopService' => $webshopService,
            'webshopFinanceService' => $webshopFinanceService
        ];
    }

    public function renderWebshopProductList($listedProducts, $currentPage, $limit)
    {
        $this->wireService('WebshopPackage/service/WebshopService');
        $startingElement = (($currentPage - 1) * $limit);
        $endingElement = ($currentPage * $limit) - 1;
        $displayedProducts = array();
        if (isset($listedProducts['products']) && is_array($listedProducts['products'])) {
            $allFilteredProducts = $listedProducts['products'];

            for ($i = 0; $i < count($allFilteredProducts); $i++) {
                if ($i >= $startingElement && $i <= $endingElement) {
                    // dump($i);
                    $displayedProducts[] = $allFilteredProducts[$i];
                }
            }

            $listedProducts['products'] = $displayedProducts;
        }

        // dump($this->getContainer()->getUrl());exit;


        // dump($this->getContainer()->getUrl()->getMainRoute());exit;
        $viewParams = $listedProducts ? : [];
        $urlDetails = WebshopService::getWebshopUrlDetails();
        $viewParams = array_merge($viewParams, $urlDetails);
        // dump($listedProducts);exit;

        $productRows = WebshopService::fillProductRows($displayedProducts, WebshopService::getSetting('WebshopPackage_productListMaxCols'));
        $viewParams['productRows'] = $productRows;
        $viewParams['defaultCurrency'] = App::getContainer()->getConfig()->getProjectData('defaultCurrency');
        $viewParams['productListMaxCols'] = WebshopService::getSetting('WebshopPackage_productListMaxCols');
        // $listedProducts['']
        // dump($viewParams);//exit;
        
        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopProductListWidget/productList.php';

        return $this->renderWidget('webshopProductList', $viewPath, $viewParams);
    }

    /**
    * Route: [name: webshop_productWidget, paramChain: /webshop/productWidget]
    */
    public function webshopProductWidgetAction()
    {
        $this->getContainer()->wireService('WebshopPackage/service/WebshopService');
        $this->getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
        $this->getContainer()->wireService('WebshopPackage/service/WebshopPriceService');
        $this->getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        $locale = $this->getContainer()->getSession()->getLocale();

        $productSlug = $this->getContainer()->getUrl()->getParamChain();
        $productSlug = str_replace('webshop/show_product/', '', $productSlug);
        $productSlug = str_replace(WebshopRequestService::getSlugTransRef('webshop', $locale).'/'.WebshopRequestService::getSlugTransRef('show_product', $locale).'/', '', $productSlug);
        // dump($productSlug);exit;
        $productImages = [];
        $repo = new ProductRepository();

        $product = $repo->findOneBy(['conditions' => [['key' => 'slug', 'value' => $productSlug]]]);
        if (!$product) {
            $product = $repo->findOneBy(['conditions' => [['key' => 'slug_en', 'value' => $productSlug]]]);
        }
        if ($product) {
            $productImages = $product->getProductImage();
        }
        if (!$product) {
            $product = $repo->findOneBy(['conditions' => [['key' => 'slug', 'value' => $productSlug]]]);
        }

        // dump($this->getRouting()->getActualRoute()->getName());exit;
        // dump($productSlug);exit;
        // dump($product);exit;
        // dump($product->getProductImage());exit;
        // $productImages = $repo->findOneBy(['conditions' => [['key' => 'slug', 'value' => $slug]]]);
        $listAllLink = WebshopRequestService::getListAllLink();

        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopProductWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('WebshopProductWidget', $viewPath, [
                // 'container' => $this->getContainer(),
                'showProductPage' => $this->getRouting()->getActualRoute()->getName() == 'webshop_showProduct' ? true : false,
                'searchLinkBase' => ($this->getSession()->getLocale() == 'en' ? 'webshop/' : WebshopRequestService::getSlugTransRef('webshop', $locale).'/').$this->getSearchStringKey($locale == 'en'),
                'cartOfferIds' => WebshopService::getCartActiveProductPriceIds(),
                // 'webshopService' => $this->getContainer()->getService('WebshopService'),
                'priceData' => WebshopPriceService::getActivePriceData($product->getId()),
                'product' => $product,
                'productImages' => $productImages,
                'defaultCurrency' => App::getContainer()->getConfig()->getProjectData('defaultCurrency'),
                'httpDomain' => App::getContainer()->getUrl()->getHttpDomain(),
                'listAllLink' => $listAllLink,
                'listAllCategorySlug' => BasicUtils::explodeAndGetElement($listAllLink, '/', 'last'),
                'categoryFilter' => $this->getSession()->get('webshop_categoryFilter'),
                'grantedViewProjectAdminContent' => $this->getContainer()->isGranted('viewProjectAdminContent')
            ]),
            'data' => [
                'title' => $product ? $product->getName() : trans('product.not.found')
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: webshop_productInfo_widget, paramChain: /webshop/productInfo/widget]
    */
    public function webshopProductInfoWidgetAction()
    {
        $productId = (int)$this->getContainer()->getRequest()->get('productId');
        $selectedImageId = (int)$this->getContainer()->getRequest()->get('selectedImageId');
        $this->getContainer()->setService('WebshopPackage/service/WebshopService');
        $this->getContainer()->setService('WebshopPackage/service/WebshopPriceService');
        $this->getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        $repo = new ProductRepository();

        $product = $repo->find($productId);

        $isIndependent = $product->getProductCategory()->getIsIndependent() == 1 ? true : false;
        // dump($isIndependent);exit;

        $productImages = $product->getProductImage();
        // dump($product);exit;
        // dump($product->getProductImage());exit;
        // $productImages = $repo->findOneBy(['conditions' => [['key' => 'slug', 'value' => $slug]]]);

        // $priceData = array();
        // foreach ($form->getEntity()->getShipmentItem() as $shipmentItem) {
        //     $priceData[$shipmentItem->getProduct()->getId()] = WebshopPriceService::getAnalyzedPriceData($shipmentItem->getProductPrice()->getId());
        // }

        $viewPath = 'framework/packages/WebshopPackage/view/widget/WebshopProductInfoWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('WebshopProductInfoWidget', $viewPath, [
                // 'container' => $this->getContainer(),
                'selectedImageId' => $selectedImageId,
                'httpDomain' => App::getContainer()->getUrl()->getHttpDomain(),
                'isIndependent' => $isIndependent,
                'defaultCurrency' => $isIndependent ? null : App::getContainer()->getConfig()->getProjectData('defaultCurrency'),
                'cartOfferIds' => $isIndependent ? null : $this->getService('WebshopService')->getCartActiveProductPriceIds(),
                'priceData' => $isIndependent ? null : WebshopPriceService::getActivePriceData($product->getId()),
                // 'webshopService' => $this->getContainer()->getService('WebshopService'),
                // 'webshopFinanceService' => $this->getContainer()->getService('WebshopPriceService'),
                'product' => $product,
                'productImages' => $productImages
            ]),
            'data' => [
                'title' => $product->getName()
            ]
        ];

        return $this->widgetResponse($response);
    }
}
