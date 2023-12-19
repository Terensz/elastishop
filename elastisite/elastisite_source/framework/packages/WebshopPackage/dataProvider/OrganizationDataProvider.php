<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\BusinessPackage\entity\Organization;

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

    public static function assembleDataSet(Organization $object = null)
    {
        $dataSet = self::getRawDataPattern();
        if (!$object) {
            return $dataSet;
        }

        App::getContainer()->wireService('WebshopPackage/dataProvider/AddressDataProvider');

        $dataSet['id'] = $object->getId();
        $dataSet['name'] = $object->getName();
        $dataSet['taxId'] = $object->getTaxId();
        $dataSet['address'] = AddressDataProvider::assembleDataSet($object->getAddress());

        return $dataSet;
    }
}