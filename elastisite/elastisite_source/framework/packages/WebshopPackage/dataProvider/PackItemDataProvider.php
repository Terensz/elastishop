<?php
namespace framework\packages\WebshopPackage\dataProvider;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\WebshopPackage\dataProvider\interfaces\PackItemInterface;
use framework\packages\WebshopPackage\entity\CartItem;
use framework\packages\WebshopPackage\entity\ShipmentItem;

class PackItemDataProvider extends Service
{
    public static function getRawDataPattern()
    {
        return [
            'id' => null,
            'quantity' => null,
            'product' => [],
        ];
    }

    public static function assembleDataSet(PackItemInterface $object) : array
    {
        // dump($object->getQuantity());
        App::getContainer()->wireService('WebshopPackage/entity/CartItem');
        App::getContainer()->wireService('WebshopPackage/entity/ShipmentItem');
        App::getContainer()->wireService('WebshopPackage/dataProvider/ProductDataProvider');
        $dataSet = self::getRawDataPattern();
        $dataSet['id'] = $object->getId();
        if ($object instanceof CartItem) {
            $productPrice = $object->getProductPrice();
        } elseif ($object instanceof ShipmentItem) {
            $productPrice = $object->getProductPrice();
        }
        
        $dataSet['quantity'] = $object->getQuantity();
        $dataSet['product'] = ProductDataProvider::assembleDataSet($object->getProduct(), $productPrice, $object->getQuantity());

        return $dataSet;
    }
}