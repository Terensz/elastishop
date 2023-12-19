<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;

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

    public static function assembleDataSet($object)
    {
        $dataSet = self::getRawDataPattern();
        if ($object->getAddress()) {
            if ($object->getAddress()->getCountry()) {
                $dataSet['customer']['address']['country']['alpha2Code'] = $object->getAddress()->getCountry()->getAlphaTwo();
                $dataSet['customer']['address']['country']['translatedName'] = trans($object->getAddress()->getCountry()->getTranslationReference());
            }
            $dataSet['zipCode'] = $object->getAddress()->getZipCode();
            $dataSet['city'] = $object->getAddress()->getCity();
            $dataSet['street'] = $object->getAddress()->getStreet();
            $dataSet['streetSuffix'] = $object->getAddress()->getStreetSuffix();
            $dataSet['houseNumber'] = $object->getAddress()->getHouseNumber();
            $dataSet['staircase'] = $object->getAddress()->getStaircase();
            $dataSet['floor'] = $object->getAddress()->getFloor();
            $dataSet['door'] = $object->getAddress()->getDoor();
        }

        return $dataSet;
    }
}