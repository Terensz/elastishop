<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\service\WebshopProductService;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;

class WebshopResponseAssembler_ProductList extends Service
{
    public static function assembleResponse($processedRequestData = null)
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopCartService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopProductService');
        App::getContainer()->wireService('WebshopPackage/repository/ProductRepository');

        // WebshopCartService::checkAndExecuteTriggers();
        
        // dump(App::getContainer()->getEnv());
        // dump(App::get());exit;

        // if (WebshopService::hasUnconfirmedOrder()) {
        //     App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_Checkout');
        //     return WebshopResponseAssembler_Checkout::assembleResponse();
        // }

        // tPIX5Ehx
        // tPIX5Ehx
        // dump(App::getContainer()->getUser());exit;

        // dump($processedRequestData);exit;

        $productRepo = new ProductRepository();

        // App::getContainer()->wireService('WebshopPackage/responseAssembler/WebshopResponseAssembler_Categories');
        // echo WebshopResponseAssembler_Categories::renderCategories();exit;

        // $this->setService('WebshopPackage/repository/ProductCategoryRepository');
        // $productCategoryRepo = $this->getContainer()->getService('ProductCategoryRepository');
        $processedRequestData = $processedRequestData ? : WebshopRequestService::getProcessedRequestData();

        // dump($processedRequestData);exit;

        // $productCategory = $processedRequestData['categoryObject'] ? : null;
        // $english = $processedRequestData['localeRequest'] == 'en' ? true : false;
        $rawProductsData = $productRepo->getProductsData(App::getContainer()->getSession()->getLocale(), [
            'categoryId' => $processedRequestData['categoryId'],
            'searchTerm' => $processedRequestData['searchTerm']
        ], [
            'page' => $processedRequestData['currentPage']
        ]);

        // dump($rawProductsData);

        $productsData = WebshopProductService::arrangeProductsData($rawProductsData);

        /**
         * @todo ra kene jonni, hogy ezt miert csinaltam 
        */
        // if ($processedRequestData['categoryObject'] && !empty($processedRequestData['searchTerm'])) {
        //     App::getContainer()->getSession()->set('webshop_categoryFilter', 'category');
        // }
        // if ($processedRequestData['specialCategorySlugKey'] == WebshopService::ALL_PRODUCTS && !empty($listParams['searchTerm'])) {
        //     App::getContainer()->getSession()->set('webshop_categoryFilter', 'all');
        // }

        // $listedProducts = $products ? $this->getListedProducts($products, $productCategory, $listParams) : null;
        $totalListedProductsCount = count($productsData);
        $maxProductsOnPage = WebshopService::getSetting('WebshopPackage_maxProductsOnPage');
        $totalPages = ceil($totalListedProductsCount / $maxProductsOnPage);
        $listAllLink = WebshopRequestService::getListAllLink();
        // $locale = App::getContainer()->getSession()->getLocale();
        $locale = App::getContainer()->getSession()->getLocale();

        /**
         * Putting together the search links.
         * One for search all, one for search in actual category.
        */
        // $searchLinkBase = '/'.WebshopRequestService::getSlugTransRef(WebshopService::TAG_WEBSHOP, $locale);
        // $searchSlug = WebshopRequestService::getSlugTransRef(WebshopService::TAG_SEARCH, $locale);
        // $searchLinkBaseAll = $searchLinkBase.'/'.$searchSlug;
        // $searchLinkBaseCategory = null;
        // if ($processedRequestData['categorySlug']) {
        //     $searchLinkBaseCategory = $searchLinkBase.'/'.WebshopRequestService::getSlugTransRef(WebshopService::TAG_CATEGORY, $locale).'/'.$processedRequestData['categorySlug'].'/'.$searchSlug;
        // }

        $cartDataSet = WebshopCartService::assembleCartDataSet();

        // dump(App::getContainer()->getSession()->get('webshop_cartId'));
        // dump($cartData);exit;

        $viewParams = [
            'listAllLink' => $listAllLink,
            'productsData' => $productsData,
            'pagerData' => [
                'currentPage' => $processedRequestData['currentPage'],
                'maxItemsOnPage' => $maxProductsOnPage,
                'totalPages' => $totalPages,
                'totalListedItemsCount' => $totalListedProductsCount
            ],
            // 'searchLinkData' => [
            //     'searchLinkBase' => $searchLinkBase,
            //     'searchLinkBaseAll' => $searchLinkBaseAll,
            //     // 'searchLinkBaseCategory' => $searchLinkBaseCategory
            // ],
            'cartDataSet' => $cartDataSet,
            'localizedProductInfoLinkBase' => WebshopRequestService::getSlugTransRef(WebshopService::TAG_WEBSHOP, $locale).'/'.WebshopRequestService::getSlugTransRef(WebshopService::TAG_SHOW_PRODUCT, $locale).'/'
        ];

        // dump($viewParams);exit;

        $viewPath = 'framework/packages/WebshopPackage/view/Sections/ProductList/ProductList.php';
        $view = ViewRenderer::renderWidget('WebshopPackage_ProductList', $viewPath, $viewParams);

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
}