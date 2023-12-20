<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\parent\Service;
use framework\packages\FinancePackage\service\DiscountHelper;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\entity\ProductImage;
use framework\packages\WebshopPackage\service\PriceDataService;

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
                'discountData' => DiscountHelper::calculateDiscount($listProductPriceData, $activeProductPriceData, $rawProductsData),
                'infoLink' => $rawProductsDataRow['product_info_link'],
                'mainProductImageLink' => $mainProductImageLink ? : $firstProductImageLink,
                'productImages' => $productImages,
            ];
            $index++;
        }

        // dump($productsData);exit;

        return $productsData;
    }
}