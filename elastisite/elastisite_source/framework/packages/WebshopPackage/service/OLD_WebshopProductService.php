<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\FinancePackage\service\DiscountHelper;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\entity\ProductImage;

class ProductListDataProvider extends Service
{
    public static function arrangeProductsData($rawProductsData)
    {
        App::getContainer()->wireService('WebshopPackage/entity/Product');
        App::getContainer()->wireService('WebshopPackage/entity/ProductImage');
        App::getContainer()->wireService('FinancePackage/service/DiscountHelper');
        App::getContainer()->wireService('WebshopPackage/service/PriceDataService');

        // dump($rawProductsData);//exit;

        $productsData = [];
        $index = 0;
        foreach ($rawProductsData as $rawProductsDataRow) {
            $productImages = [];
            $productImageSlugs = [];
            if (!empty($rawProductsDataRow['product_image_slugs'])) {
                $productImageSlugs = explode('[separator]', $rawProductsDataRow['product_image_slugs']);
            }
            $mainProductImageLink = null;
            $firstProductImageLink = null;
            $imageCounter = 0;
            foreach ($productImageSlugs as $productImageSlug) {
                $imageSlugAndMain = explode('[main]', $productImageSlug);
                $isMain = (int)$imageSlugAndMain[1] === 1 ? true : false;
                $productImageLink = ProductImage::createProductImageLink($imageSlugAndMain[0]);
                if ($imageCounter == 0) {
                    $firstProductImageLink = $productImageLink;
                }
                if ($isMain) {
                    $mainProductImageLink = $productImageLink;
                }
                $productImages[] = [
                    'slug' => $imageSlugAndMain[0],
                    'link' => $productImageLink,
                    'isMain' => $isMain,
                ];
                $imageCounter++;
            }

            $listProductPriceData = array_merge([
                'id' => null, // not important data 
                'offerId' => null,
                'currencyCode' => $rawProductsDataRow['ppl_currency_code'],
                // 'priceType' => $rawProductsDataRow['ppl_price_type'],
            ], PriceDataService::assembleProductPriceData([
                'quantity' => !empty($rawProductsDataRow['quantity']) ? $rawProductsDataRow['quantity'] : null,
                'grossUnitPrice' => $rawProductsDataRow['ppl_gross'],
                'vatPercent' => $rawProductsDataRow['ppl_vat'],
            ]));

            $activeProductPriceData = array_merge([
                'id' => $rawProductsDataRow['ppa_binder_product_price_id'],
                'offerId' => $rawProductsDataRow['ppa_binder_id'],
                'currencyCode' => $rawProductsDataRow['ppa_currency_code'],
                // 'priceType' => $rawProductsDataRow['ppa_price_type'],
            ], PriceDataService::assembleProductPriceData([
                'quantity' => !empty($rawProductsDataRow['quantity']) ? $rawProductsDataRow['quantity'] : null,
                'grossUnitPrice' => $rawProductsDataRow['ppa_gross'],
                'vatPercent' => $rawProductsDataRow['ppa_vat'],
            ]));

            $actualProductPriceData = $activeProductPriceData;
            /**
             * Only activeProductPriceData has offerId.
            */
            $actualProductPriceData['offerId'] = null;

            $uniqueKey = $rawProductsDataRow['unique_key'] ? : $index;
            $productsData[$uniqueKey] = [
                'id' => $rawProductsDataRow['product_id'],
                'name' => $rawProductsDataRow['product_name'],
                'specialPurpose' => $rawProductsDataRow['product_special_purpose'],
                'productCategory' => [
                    'id' => $rawProductsDataRow['category_id'],
                    'name' => $rawProductsDataRow['category_name'],
                    'slug' => null,
                    'productCategory' => null
                ],
                'condition' => $rawProductsDataRow['product_condition'],
                'shortInfo' => $rawProductsDataRow['product_short_info'],
                'description' => $rawProductsDataRow['product_description'],
                'slug' => $rawProductsDataRow['product_slug'],
                'status' => $rawProductsDataRow['product_status'],
                'statusText' => Product::getStatusText($rawProductsDataRow['product_status']),
                'SKU' => $rawProductsDataRow['product_sku'],
                'listPrice' => $listProductPriceData,
                'actualPrice' => $actualProductPriceData,
                'activePrice' => $activeProductPriceData,
                'discount' => DiscountHelper::calculateDiscount($listProductPriceData, $activeProductPriceData, $rawProductsData),
                'infoLink' => $rawProductsDataRow['product_info_link'],
                'mainProductImageLink' => $mainProductImageLink ? : $firstProductImageLink,
                'productImages' => $productImages,
            ];
            $index++;
        }

        // dump($productsData);exit;

        return $productsData;
    }

    // public static function arrangeProductsData_OLD($rawProductsData)
    // {
    //     App::getContainer()->wireService('WebshopPackage/entity/Product');
    //     App::getContainer()->wireService('WebshopPackage/entity/ProductImage');
    //     App::getContainer()->wireService('FinancePackage/service/DiscountHelper');

    //     $productsData = [];
    //     $productImages = [];
    //     foreach ($rawProductsData as $rawProductsDataRow) {
    //         $productImageSlugs = [];
    //         if (!empty($rawProductsDataRow['product_image_slugs'])) {
    //             $productImageSlugs = explode('[separator]', $rawProductsDataRow['product_image_slugs']);
    //         }
    //         $mainProductImageLink = null;
    //         $firstProductImageLink = null;
    //         $imageCounter = 0;
    //         foreach ($productImageSlugs as $productImageSlug) {
    //             $imageSlugAndMain = explode('[main]', $productImageSlug);
    //             $isMain = (int)$imageSlugAndMain[1] === 1 ? true : false;
    //             $productImageLink = ProductImage::createProductImageLink($imageSlugAndMain[0]);
    //             if ($imageCounter == 0) {
    //                 $firstProductImageLink = $productImageLink;
    //             }
    //             if ($isMain) {
    //                 $mainProductImageLink = $productImageLink;
    //             }
    //             $productImages[] = [
    //                 'slug' => $imageSlugAndMain[0],
    //                 'link' => $productImageLink,
    //                 'isMain' => $isMain,
    //             ];
    //             $imageCounter++;
    //         }
    //         $listNetPrice = $rawProductsDataRow['ppl_net'];
    //         $listVat = $rawProductsDataRow['ppl_vat'];
    //         $listGrossMaker = (100 + (int)$listVat) / 100;
    //         $listGrossPriceAccurate = $listNetPrice * $listGrossMaker;
            
    //         $listProductPriceData = [
    //             'currencyCode' => $rawProductsDataRow['ppl_currency_code'],
    //             'priceType' => $rawProductsDataRow['ppl_price_type'],
    //             'netPrice' => $rawProductsDataRow['ppl_net'],
    //             'vatPercent' => $rawProductsDataRow['ppl_vat'],
    //             'grossPriceAccurate' => $listGrossPriceAccurate,
    //             'grossPriceRounded0' => round($listGrossPriceAccurate, 0),
    //             'grossPriceRounded2' => round($listGrossPriceAccurate, 2)
    //         ];
    //         $activeNetPrice = $rawProductsDataRow['ppa_net'];
    //         $activeVat = $rawProductsDataRow['ppa_vat'];
    //         $activeGrossMaker = (100 + (int)$activeVat) / 100;
    //         $activeGrossPriceAccurate = $activeNetPrice * $activeGrossMaker;
    //         $activeProductPriceData = [
    //             'offerId' => $rawProductsDataRow['ppa_binder_id'],
    //             'productPriceId' => $rawProductsDataRow['ppa_binder_product_price_id'],
    //             'currencyCode' => $rawProductsDataRow['ppa_currency_code'],
    //             'priceType' => $rawProductsDataRow['ppa_price_type'],
    //             'netPrice' => $rawProductsDataRow['ppa_net'],
    //             'vatPercent' => $rawProductsDataRow['ppa_vat'],
    //             'grossPriceAccurate' => $activeGrossPriceAccurate,
    //             'grossPriceRounded0' => round($activeGrossPriceAccurate, 0),
    //             'grossPriceRounded2' => round($activeGrossPriceAccurate, 2)
    //         ];
    //         $productsData[] = [
    //             'productId' => $rawProductsDataRow['product_id'],
    //             'categoryId' => $rawProductsDataRow['category_id'],
    //             'productCondition' => $rawProductsDataRow['product_condition'],
    //             'productCategoryName' => $rawProductsDataRow['category_name'],
    //             'productName' => $rawProductsDataRow['product_name'],
    //             'productDescription' => $rawProductsDataRow['product_description'],
    //             'productSlug' => $rawProductsDataRow['product_slug'],
    //             'productStatus' => $rawProductsDataRow['product_status'],
    //             'productStatusText' => Product::getStatusText($rawProductsDataRow['product_status']),
    //             'listProductPrice' => $listProductPriceData,
    //             'activeProductPrice' => $activeProductPriceData,
    //             'discount' => DiscountHelper::calculateDiscount($listProductPriceData, $activeProductPriceData, $rawProductsData),
    //             'productInfoLink' => $rawProductsDataRow['product_info_link'],
    //             'mainProductImageLink' => $mainProductImageLink ? : $firstProductImageLink,
    //             'productImages' => $productImages,
    //         ];
    //     }

    //     return $productsData;
    // }
}