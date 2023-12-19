<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;

class ProductDataProvider extends Service
{
    public static function getRawDataPattern()
    {
        return [
            'id' => null,
            'specialPurpose' => null,
            'productCategoryId' => null,
            'condition' => null,
            'productCategoryName' => null,
            'name' => null,
            'shortInfo' => null,
            'description' => null,
            'slug' => null,
            'status' => null,
            'statusText' => null,
            'SKU' => null,
            'listPrice' => null,
            'activePrice' => null,
            'discount' => null,
            'infoLink' => null,
            'imageLink' => null,
            'images' => null,
        ];
    }
}