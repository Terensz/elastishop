<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\WebshopPackage\dataProvider\interfaces\PackItemInterface;

class PackItemDataProvider extends Service
{
    public static function getRawDataPattern()
    {
        return [
            'id' => null,
            'product' => [],
        ];
    }

    public static function assembleDataSet(PackItemInterface $object) : array
    {
        $dataSet = self::getRawDataPattern();
        $dataSet['id'] = $object->getId();

        return $dataSet;
    }
}