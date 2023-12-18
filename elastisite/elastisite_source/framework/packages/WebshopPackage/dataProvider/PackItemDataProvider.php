<?php
namespace framework\packages\dataProvider\service;

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