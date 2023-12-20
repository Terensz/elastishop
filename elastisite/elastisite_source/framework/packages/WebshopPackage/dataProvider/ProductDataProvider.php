<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\FinancePackage\service\DiscountHelper;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\entity\ProductImage;
use framework\packages\WebshopPackage\entity\ProductPrice;
use framework\packages\WebshopPackage\repository\ProductPriceRepository;
use framework\packages\WebshopPackage\dataProvider\PriceDataProvider;

class ProductDataProvider extends Service
{
    public static function getRawDataPattern()
    {
        return [
            'id' => null,
            'name' => null,
            'specialPurpose' => null,
            'productCategory' => null,
            'condition' => null,
            'shortInfo' => null,
            'description' => null,
            'slug' => null,
            'status' => null,
            'statusText' => null,
            'SKU' => null,
            'listPrice' => null,
            'actualPrice' => null,
            'activePrice' => null,
            'discount' => null,
            'infoLink' => null,
            'mainProductImageLink' => null,
            'productImages' => null,
        ];
    }

    public static function assembleDataSet(Product $object, ProductPrice $actualProductPrice, int $quantity) : array
    {
        App::getContainer()->wireService('WebshopPackage/entity/Product');
        App::getContainer()->wireService('WebshopPackage/entity/ProductImage');
        App::getContainer()->wireService('WebshopPackage/entity/ProductPrice');
        App::getContainer()->wireService('WebshopPackage/repository/ProductPriceRepository');
        App::getContainer()->wireService('WebshopPackage/dataProvider/PriceDataProvider');
        App::getContainer()->wireService('WebshopPackage/dataProvider/ProductCategoryDataProvider');
        App::getContainer()->wireService('FinancePackage/service/DiscountHelper');
        
        $dataSet = self::getRawDataPattern();
        $dataSet['id'] = $object->getId();
        $dataSet['name'] = App::getContainer()->getSession()->getLocale() == 'en' ? $object->getNameEn() : $object->getName();
        $dataSet['specialPurpose'] = $object->getSpecialPurpose();
        $dataSet['productCategory'] = ProductCategoryDataProvider::assembleDataSet($object->getProductCategory());
        // $dataSet['condition'] = $object->getCon();
        $dataSet['shortInfo'] = $object->getShortInfo();
        $dataSet['description'] = $object->getDescription();
        $dataSet['slug'] = App::getContainer()->getSession()->getLocale() == 'en' ? $object->getSlugEn() : $object->getSlug();
        $dataSet['status'] = $object->getStatus();
        $dataSet['statusText'] = $object::getStatusText($object->getStatus());
        $dataSet['SKU'] = $object->getCode() ? : $object->getId();

        // $activeProductPriceObject = $object->getProductPriceActive()->getProductPrice();
        $productPriceRepository = new ProductPriceRepository();
        $listProductPrice = $productPriceRepository->findOneBy(['conditions' => [
            ['key' => 'product_id', 'value' => $object->getId()],
            ['key' => 'price_type', 'value' => ProductPrice::PRICE_TYPE_LIST],
        ]]);
        $dataSet['listPrice'] = array_merge([
            'id' => $listProductPrice->getId(),
            'offerId' => null,
            'currencyCode' => $listProductPrice->getCurrency()->getCode(),
        ], PriceDataProvider::assembleDataSet([
            'grossUnitPrice' => $listProductPrice->getGrossPrice(),
            'vatPercent' => $listProductPrice->getVat(),
            'quantity' => $quantity
        ]));
        $dataSet['actualPrice'] = array_merge([
            'id' => $actualProductPrice->getId(),
            'offerId' => null,
            'currencyCode' => $actualProductPrice->getCurrency()->getCode(),
        ], PriceDataProvider::assembleDataSet([
            'grossUnitPrice' => $actualProductPrice->getGrossPrice(),
            'vatPercent' => $actualProductPrice->getVat(),
            'quantity' => $quantity
        ]));
        // dump($actualProductPrice->getProduct()->getProductPriceActive());exit;
        $activeProductPrice = $actualProductPrice->getProduct()->getProductPriceActive()->getProductPrice();
        $dataSet['activePrice'] = array_merge([
            'id' => $activeProductPrice->getId(),
            'offerId' => $actualProductPrice->getProduct()->getProductPriceActive()->getId(),
            'currencyCode' => $activeProductPrice->getCurrency()->getCode(),
        ], PriceDataProvider::assembleDataSet([
            'grossUnitPrice' => $activeProductPrice->getGrossPrice(),
            'vatPercent' => $activeProductPrice->getVat(),
            'quantity' => $quantity
        ]));

        $discountData = DiscountHelper::calculateDiscount($dataSet['listPrice'], $dataSet['actualPrice']);
        // dump($discountData);exit;
        $dataSet['discountData'] = $discountData;

        $productImages = [];
        $productImageObjects = $object->getProductImage();
        foreach ($productImageObjects as $productImageObject) {
            // dump($productImageObject->getMain());
            $main = (int)$productImageObject->getMain() == 1 ? true : false;
            $link = ProductImage::createProductImageLink($productImageObject->getSlug());
            if (!$dataSet['mainProductImageLink']) {
                $dataSet['mainProductImageLink'] = $link;
            }
            if ($main) {
                $dataSet['mainProductImageLink'] = $link;
            }
            $productImages[] = [
                'slug' => $productImageObject->getSlug(),
                'link' => $link,
                'isMain' => $main,
            ];
        }
        $dataSet['productImages'] = $productImages;

        // dump($dataSet);exit;

        return $dataSet;
    }
}