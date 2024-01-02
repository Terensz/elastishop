<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\WebshopPackage\repository\ProductCategoryRepository;

class WebshopRequestService extends Service
{
    // const TAG_WEBSHOP = 'Webshop';
    // const TAG_PRODUCT_SLUG = 'ProductSlug';
    // const TAG_ALL_PRODUCTS = 'AllProducts';
    // const TAG_SHOW_PRODUCT = 'ShowProduct';
    // const TAG_ANOMALOUS_PRODUCTS = 'AnomalousProducts';
    // const TAG_MOST_POPULAR_PRODUCTS = 'MostPopularProducts';
    // const TAG_RECOMMENDED_PRODUCTS = 'RecommendedProducts';

    // const LINK_BASES = [
    //     [self::TAG_SHOW_PRODUCT] => [
    //         'en' => 'webshop/show_product/{ProductSlug}',
    //         'hu' => 'webaruhaz/termek_info/{ProductSlug}'
    //     ]
    // ];

    public static function getShowProductLinkBase($locale)
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        // '/webshop/show_product/';
        return '/'.self::getSlugTransRef(WebshopService::TAG_WEBSHOP, $locale).'/'.self::getSlugTransRef(WebshopService::TAG_SHOW_PRODUCT, $locale).'/';
    }

    /**
     * @todo (?)
    */
    // public static function getSlugKey($locale, $tag, $errorIfUnavailable = false)
    // {
    //     App::getContainer()->wireService('WebshopPackage/service/WebshopService');
    //     // if (isset(WebshopService::SLUGS[$tag])) {
    //     // }
    //     if (!isset(WebshopService::SLUGS[$tag]['slugTranslations'][$locale])) {
    //         throw new \Exception('Missing webshop slug translation for tag: '.$tag);
    //     }
    //     return WebshopService::SLUGS[$tag]['slugTranslations'][$locale];
    // }

    public static function getProcessedRequestData()
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        $container = App::getContainer();
        // $defaultLocale = $container->getDefaultLocale();
        $currentPage = 1;
        $categorySlugRequest = null;
        $specialCategorySlugKey = null;
        $specialCategoryTransRef = null;
        $categoryObject = null;
        $categorySlug = null;
        $categoryName = null;
        $searchTerm = null;
        $isHomepage = false;
        $paramChain = $container->getUrl()->getParamChain();
        $paramChainParts = explode('/', $paramChain);
        $route = $container->getRouting()->getPageRoute();
        $localeRequest = self::getSlugLocale($paramChainParts[0]);

        if (in_array($route->getName(), array('webshop_productList_noFilter', 'webshop_productList_noFilter_page'))) {
            $isHomepage = true;
            if (WebshopService::getSetting('WebshopPackage_homepageListType') == WebshopService::TAG_DISCOUNTED_PRODUCTS) {
                $specialCategorySlugKey = WebshopService::TAG_DISCOUNTED_PRODUCTS;
                $specialCategoryTransRef = self::getSlugConfig($specialCategorySlugKey)['transRef'];
                // $discountedList = true;
            } elseif (WebshopService::getSetting('WebshopPackage_homepageListType') == WebshopService::TAG_RECOMMENDED_PRODUCTS) {
                $specialCategorySlugKey = WebshopService::TAG_RECOMMENDED_PRODUCTS;
                $specialCategoryTransRef = self::getSlugConfig($specialCategorySlugKey)['transRef'];
            } elseif (WebshopService::getSetting('WebshopPackage_homepageListType') == WebshopService::TAG_MOST_POPULAR_PRODUCTS) {
                $specialCategorySlugKey = WebshopService::TAG_MOST_POPULAR_PRODUCTS;
                $specialCategoryTransRef = self::getSlugConfig($specialCategorySlugKey)['transRef'];
            } elseif (WebshopService::getSetting('WebshopPackage_homepageListType') == WebshopService::TAG_ALL_PRODUCTS) {
                $specialCategorySlugKey = WebshopService::TAG_ALL_PRODUCTS;
                $specialCategoryTransRef = self::getSlugConfig($specialCategorySlugKey)['transRef'];
                // dump($specialCategoryTransRef);exit;
            }
        }

        if (in_array($route->getName(), array('webshop_productList_all'))) {
            $specialCategorySlugKey = WebshopService::TAG_ALL_PRODUCTS;
        }

        // dump($route->getName());exit;
        if ($route->getName() == 'webshop_productList_anomalousProducts') {
            // $currentPage = $paramChainParts[2];
            $specialCategorySlugKey = WebshopService::TAG_ANOMALOUS_PRODUCTS;
        }
        if ($route->getName() == 'webshop_productList_all_page') {
            $specialCategorySlugKey = WebshopService::TAG_ALL_PRODUCTS;
            $currentPage = $paramChainParts[3];
        }
        if ($route->getName() == 'webshop_productList_noFilter_page') {
            $specialCategorySlugKey = WebshopService::TAG_ALL_PRODUCTS;
            $currentPage = $paramChainParts[2];
        }
        // if ($route->getName() == 'webshop_productList_discountedProducts_page') {
        //     $currentPage = $paramChainParts[3];
        // }
        if ($route->getName() == 'webshop_productList_category_page') {
            $currentPage = $paramChainParts[4];
        }
        if ($route->getName() == 'webshop_productList_searchTerm_page') {
            $currentPage = $paramChainParts[4];
        }
        if ($route->getName() == 'webshop_productList_categoryAndSearchTerm_page') {
            $currentPage = $paramChainParts[6];
        }

        // dump($route->getName());
        if (in_array($route->getName(), ['webshop_productList_category', 'webshop_productList_categoryAndSearchTerm', 'webshop_productList_category_page'])) {
            $categorySlugRequest = $paramChainParts[2];
            $categorySlugKey = self::findSpecialCategorySlugKey($categorySlugRequest);
            if ($categorySlugKey) {
                $specialCategorySlugKey = $categorySlugKey;
                $specialCategoryTransRef = self::getSlugConfig($specialCategorySlugKey)['transRef'];
                $categoryName = trans($specialCategoryTransRef);
                // $categorySlug = $categorySlugRequest;
                $categorySlug = $specialCategorySlugKey;
            }
        }
        if (in_array($route->getName(), ['webshop_productList_searchTerm', 'webshop_productList_searchTerm_page'])) {
            $searchTerm = $paramChainParts[2];
            // $englishRequest = $paramChainParts[1] == 'search' ? true : false;
        }
        if (in_array($route->getName(), ['webshop_productList_categoryAndSearchTerm', 'webshop_productList_categoryAndSearchTerm_page'])) {
            $categorySlugRequest = $paramChainParts[2];
            $searchTerm = $paramChainParts[4];
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

        $return = array(
            'categorySlugRequest' => $categorySlugRequest,
            'specialCategorySlugKey' => $specialCategorySlugKey,
            'specialCategoryTransRef' => $specialCategoryTransRef,
            'categoryObject' => $categoryObject,
            'categoryId' => $categoryObject ? $categoryObject->getId() : null,
            'categorySlug' => $categorySlug,
            'categoryName' => $categoryName,
            'searchTerm' => $searchTerm ? urldecode($searchTerm) : $searchTerm,
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

        // dump($return);exit;

        return $return;
    }

    public static function getSlugConfig($searchedSlugKey)
    {
        return isset(WebshopService::SLUGS[$searchedSlugKey]) ? WebshopService::SLUGS[$searchedSlugKey] : null;
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
        // dump($categorySlug);
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

    public static function getBaseLink()
    {
        return self::assembleLink(['baseOnly' => true]);
    }

    public static function getListAllLink()
    {
        return self::assembleLink(['forceListAll' => true]);
    }

    public static function createProductInfoLink($searchTerm, $categorySlug, $english)
    {

    }

    public static function createProductListLink($searchTerm, $categorySlug, $english)
    {
        if ($searchTerm) {
            $params['searchTerm'] = $searchTerm;
        }

        if ($categorySlug) {
            $params['categorySlug'] = $categorySlug;
        }

        if (!$searchTerm && !$categorySlug) {
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
        //     'searchTerm' => $searchTerm ? urldecode($searchTerm) : $searchTerm,
        //     'localeRequest' => $localeRequest,
        //     'isHomepage' => $isHomepage,
        //     'currentPage' => $currentPage
        // );

        $locale = isset($params['localeRequest']) ? $params['localeRequest'] : App::getContainer()->getSession()->getLocale();
        $linkString = self::getSlugTransRef(WebshopService::TAG_WEBSHOP, $locale);

        // dump($locale);
        // dump($linkString);exit;

        $params['baseOnly'] = isset($params['baseOnly']) && $params['baseOnly'] ? $params['baseOnly'] : null;
        if ($params['baseOnly']) {
            return $linkString;
        }

        $linkString .= '/';

        $params['searchTerm'] = isset($params['searchTerm']) && $params['searchTerm'] ? $params['searchTerm'] : null;
        $params['forceListAll'] = isset($params['forceListAll']) && $params['forceListAll'] ? $params['forceListAll'] : null;
        $params['listNonListables'] = isset($params['listNonListables']) && $params['listNonListables'] ? $params['listNonListables'] : null;

        if ($params['forceListAll']) {
            $linkString .= self::getSlugTransRef(WebshopService::TAG_CATEGORY, $locale).'/';
            $linkString .= self::getSlugTransRef(WebshopService::TAG_ALL_PRODUCTS, $locale);

            return $linkString;
        }

        if ($params['listNonListables']) {
            return self::getSlugTransRef(WebshopService::TAG_WEBSHOP, $locale).'/'.self::getSlugTransRef(WebshopService::TAG_ANOMALOUS_PRODUCTS, $locale);
        }

        $params['currentPage'] = isset($params['currentPage']) && $params['currentPage'] ? $params['currentPage'] : 1;

        $params['discountedList'] = isset($params['discountedList']) && $params['discountedList'] ? $params['discountedList'] : null;
        if ($params['discountedList']) {
            $linkString .= self::getSlugTransRef(WebshopService::TAG_CATEGORY, $locale).'/';
            $linkString .= self::getSlugTransRef(WebshopService::TAG_DISCOUNTED_PRODUCTS, $locale).'/';
        }

        $params['categorySlug'] = isset($params['categorySlug']) && $params['categorySlug'] ? $params['categorySlug'] : null;
        $categorySlug = '';
        if ($params['categorySlug']) {
            $categorySlug = isset($params['categorySlugRequest']) && !empty($params['categorySlugRequest']) ? $params['categorySlugRequest'] : $params['categorySlug'];
        }
        if ($params['categorySlug'] && $params['searchTerm']) {
            $linkString .= (self::getSlugTransRef(WebshopService::TAG_CATEGORY, $locale).'/').$categorySlug.'/';
            $linkString .= (self::getSlugTransRef(WebshopService::TAG_SEARCH, $locale).'/').$params['searchTerm'].'/';
        } else {
            if ($params['categorySlug']) {
                $linkString .= (self::getSlugTransRef(WebshopService::TAG_CATEGORY, $locale).'/').$categorySlug.'/';
            }
            if ($params['searchTerm']) {
                $linkString .= (self::getSlugTransRef(WebshopService::TAG_SEARCH, $locale).'/').$params['searchTerm'].'/';
            }
        }

        if ((isset($params['pagerRequest']) && $params['pagerRequest']) || ($params['currentPage'] && $params['currentPage'] > 1)) {
            $linkString .= (self::getSlugTransRef(WebshopService::TAG_PAGE, $locale).'/').$params['currentPage'].'/';
        }

        // dump($linkString);

        return $linkString;
    }


}
