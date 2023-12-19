<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\UserPackage\entity\Address;

class AddressDataProvider extends Service
{
    public static function getRawDataPattern()
    {
        return [
            'country' => [
                'alpha2Code' => null,
                'translatedName' => null
            ],
            'zipCode' => null,
            'city' => null,
            'street' => null,
            'streetSuffix' => null,
            'houseNumber' => null,
            'staircase' => null,
            'floor' => null,
            'door' => null
        ];
    }

    public static function assembleDataSet(Address $object = null)
    {
        App::getContainer()->wireService('UserPackage/entity/Address');
        
        $dataSet = self::getRawDataPattern();
        if (!$object) {
            return $dataSet;
        }
        if ($object->getCountry()) {
            $dataSet['country']['alpha2Code'] = $object->getCountry()->getAlphaTwo();
            $dataSet['country']['translatedName'] = trans($object->getCountry()->getTranslationReference());
        }
        $dataSet['zipCode'] = $object->getZipCode();
        $dataSet['city'] = $object->getCity();
        $dataSet['street'] = $object->getStreet();
        $dataSet['streetSuffix'] = $object->getStreetSuffix();
        $dataSet['houseNumber'] = $object->getHouseNumber();
        $dataSet['staircase'] = $object->getStaircase();
        $dataSet['floor'] = $object->getFloor();
        $dataSet['door'] = $object->getDoor();

        return $dataSet;
    }
}