<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;

class OrganizationDataProvider extends Service
{
    public static function getRawDataPattern()
    {
        return [
            'id' => null,
            'name' => null,
            'taxId' => null,
            'address' => null
        ];
    }

    public static function assembleDataSet($object)
    {
        App::getContainer()->wireService('WebshopPackage/dataProvider/AddressDataProvider');
        
        $dataSet = self::getRawDataPattern();
        $dataSet['id'] = $object->getId();
        $dataSet['name'] = $object->getId();
        $dataSet['taxId'] = $object->getId();
        $dataSet['address'] = AddressDataProvider::assembleDataSet($object->getAddress());

        return $dataSet;
    }
}