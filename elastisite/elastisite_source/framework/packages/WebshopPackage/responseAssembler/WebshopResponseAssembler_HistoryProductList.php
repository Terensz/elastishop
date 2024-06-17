<?php
namespace framework\packages\WebshopPackage\responseAssembler;

use App;
use framework\component\parent\Service;
use framework\kernel\view\ViewRenderer;
use framework\packages\WebshopPackage\service\ProductVisitHistoryService;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;

class WebshopResponseAssembler_HistoryProductList extends Service
{
    public static $cache = [
        'assembleResponse' => null
    ];

    public static function assembleResponse($processedRequestData = null)
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
        // App::getContainer()->wireService('WebshopPackage/repository/ProductCategoryRepository');
        App::getContainer()->wireService('WebshopPackage/service/ProductVisitHistoryService');

        if (self::$cache['assembleResponse']) {
            return self::$cache['assembleResponse'];
        }

        $last10 = ProductVisitHistoryService::findLast10();

        // $productCatRepo = new ProductCategoryRepository();
        // $processedRequestData = $processedRequestData ? : WebshopRequestService::getProcessedRequestData();
        // $activeCategoryId = null;
        // if ($processedRequestData['categoryObject']) {
        //     $activeCategoryId = $processedRequestData['categoryObject']->getId();
        // }

        // $locale = App::getContainer()->getSession()->getLocale();
        // $data = $productCatRepo->getCategoriesData($locale);
        // $categoriesData = [
        //     'config' => [
        //         'active' => $activeCategoryId
        //     ],
        //     'data' => $data
        // ];

        // $openGraphData = App::getContainer()->getOpenGraphData();
        // dump($openGraphData);exit;
        $locale = App::getContainer()->getSession()->getLocale();
        $viewParams = [
            'last10' => $last10,
            'localizedProductInfoLinkBase' => WebshopRequestService::getSlugTransRef(WebshopService::TAG_WEBSHOP, $locale).'/'.WebshopRequestService::getSlugTransRef(WebshopService::TAG_SHOW_PRODUCT, $locale).'/'
            // 'categoriesData' => $categoriesData,
            // 'specialCategorySlugKey' => $processedRequestData['specialCategorySlugKey'],
            // 'allProductsSlugKey' => 'AllProducts',
            // 'recommendedProductsSlugKey' => 'RecommendedProducts',
            // 'allProductsTitle' => trans('all.products'),
            // 'recommendedProductsTitle' => trans('recommended.products'),
            // 'localizedWebshopUrlKey' => WebshopRequestService::getSlugTransRef(WebshopService::TAG_WEBSHOP, $locale),
            // 'localizedCategoryUrlKey' => WebshopRequestService::getSlugTransRef(WebshopService::TAG_CATEGORY, $locale),
            // 'localizedAllProductsSlugKey' => WebshopRequestService::getSlugTransRef(WebshopService::TAG_ALL_PRODUCTS, $locale),
            // 'localizedRecommendedProductsSlugKey' => WebshopRequestService::getSlugTransRef(WebshopService::TAG_RECOMMENDED_PRODUCTS, $locale)
        ];

        $viewPath = 'framework/packages/WebshopPackage/view/Sections/HistoryProductList/HistoryProductList.php';
        $view = ViewRenderer::renderWidget('WebshopPackage_Categories', $viewPath, $viewParams);

        $result = [
            'view' => $view,
            'data' => [
            ]
        ];

        self::$cache['assembleResponse'] = $result;

        return $result;
    }

    // Ocsmanysag, ki kell torolni.
    // public static function addPropsToProductCategories($productCategories)
    // {
    //     if (!is_array($productCategories)) {
    //         return null;
    //     }

    //     $locale = App::getContainer()->getSession()->getLocale();

    //     $returnArray = [];
    //     foreach ($productCategories as $productCategory) {
    //         $categorySlug = null;
    //         $displayedName = null;

    //         if ($locale == 'en') {
    //             $categorySlug = $productCategory['slug_en'];
    //             $displayedName = $productCategory['name_en'];
    //         } else {
    //             $categorySlug = $productCategory['slug'];
    //             $displayedName = $productCategory['name'];
    //         }

    //         if ($locale == 'en' && !$categorySlug) {
    //             $categorySlug = $productCategory['slug'];
    //             $displayedName = $productCategory['name'];
    //         }

    //         if (!$categorySlug) {
    //             $productCategory['categoryLink'] = WebshopService::assembleLink(['forceListAll' => true]);
    //         } else {
    //             $productCategory['categoryLink'] = WebshopService::assembleLink(['categorySlug' => $categorySlug]);
    //         }

    //         $productCategory['displayedName'] = $displayedName;

    //         $returnArray[] = $productCategory;
    //     }

    //     return $returnArray;
    // }
}