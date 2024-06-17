<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\WebshopPackage\entity\ProductCategory;
use framework\packages\WebshopPackage\entity\ProductPrice;
use framework\packages\WebshopPackage\repository\ProductPriceRepository;
use framework\packages\WebshopPackage\service\PriceDataProvider;

class ProductCategoryDataProvider extends Service
{
    public static function getRawDataPattern()
    {
        return [
            'id' => null,
            'name' => null,
            'slug' => null,
            'productCategory' => null
        ];
    }

    public static function assembleDataSet(ProductCategory $object = null) : array
    {
        $dataSet = self::getRawDataPattern();
        if (!$object) {
            return $dataSet;
        }

        App::getContainer()->wireService('WebshopPackage/entity/ProductCategory');

        // App::getContainer()->wireService('WebshopPackage/entity/Product');
        // App::getContainer()->wireService('WebshopPackage/entity/ProductPrice');
        // App::getContainer()->wireService('WebshopPackage/repository/ProductPriceRepository');
        // App::getContainer()->wireService('WebshopPackage/dataProvider/PriceDataProvider');

        $dataSet['id'] = $object->getId();
        $dataSet['name'] = App::getContainer()->getSession()->getLocale() == 'en' ? $object->getNameEn() : $object->getName();
        $dataSet['slug'] = App::getContainer()->getSession()->getLocale() == 'en' ? $object->getSlugEn() : $object->getSlug();
        $dataSet['productCategory'] = $object->getProductCategory() ? ProductCategoryDataProvider::assembleDataSet($object->getProductCategory()) : null;

        return $dataSet;
    }
}