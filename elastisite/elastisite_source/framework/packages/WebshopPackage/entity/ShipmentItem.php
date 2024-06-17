<?php
namespace framework\packages\WebshopPackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\packages\FinancePackage\entity\InvoiceItem;
use framework\packages\WebshopPackage\dataProvider\interfaces\PackItemInterface;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\entity\ProductPrice;

class ShipmentItem extends DbEntity implements PackItemInterface
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `shipment_item` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `shipment_id` int(11) DEFAULT NULL,
        `product_id` int(11) DEFAULT NULL,
        `product_price_id` int(11) DEFAULT NULL,
        `quantity` int(6) DEFAULT NULL,
        `unit_of_measure` varchar(20) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=29500 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";
    /*
    use elastisite_devel;
    drop table shipment_item ;
    CREATE TABLE `shipment_item` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `shipment_id` int(11) DEFAULT NULL,
    `product_id` int(11) DEFAULT NULL,
    `product_price_id` int(11) DEFAULT NULL,
    `quantity` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1300 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;
    */

    protected $id;
    protected $shipment;
    protected $product;
    protected $productPrice;
    protected $quantity;
    protected $unitOfMeasure;

    public function __construct()
    {
        App::getContainer()->wireService('FinancePackage/entity/InvoiceItem');
        $this->unitOfMeasure = InvoiceItem::UNIT_OF_MEASURE_PIECE;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setShipment(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    public function getShipment()
    {
        return $this->shipment;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProductPrice(ProductPrice $productPrice)
    {
        $this->productPrice = $productPrice;
    }

    public function getProductPrice()
    {
        return $this->productPrice;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setUnitOfMeasure($unitOfMeasure)
    {
        $this->unitOfMeasure = $unitOfMeasure;
    }

    public function getUnitOfMeasure()
    {
        return $this->unitOfMeasure;
    }

    public function getGrossUnitPrice($rounded = false)
    {
        $grossUnitPrice =  $this->productPrice->getNetPrice() * (1 + ($this->productPrice->getVat() / 100));

        return $rounded ? round($grossUnitPrice, 0) : $grossUnitPrice;
    }

    public function getGrossTotalPrice($rounded = false)
    {
        $grossTotalPrice = $this->quantity * ($this->productPrice->getNetPrice() * (1 + ($this->productPrice->getVat() / 100)));

        return $rounded ? round($grossTotalPrice, 0) : $grossTotalPrice;
    }
}
