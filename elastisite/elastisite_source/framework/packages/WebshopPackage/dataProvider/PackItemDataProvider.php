<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;

class PackItemDataProvider extends Service
{
    public static function getRawDataPattern()
    {
        return [
            'id' => null,
            'product' => [],
        ];
    }
}