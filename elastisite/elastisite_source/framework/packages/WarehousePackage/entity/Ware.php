<?php
namespace framework\packages\WarehousePackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\WebshopPackage\entity\Product;

class Ware extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `ware` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `product_id` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=121000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        'active' => false
    ];

    private $id;
    private $product;
    // private $name;

    public function __construct()
    {
        // $this->product = $product;
        // $this->name = $name;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    public function getProduct()
    {
        return $this->product;
    }

    // public function getName()
    // {
    //     return $this->name;
    // }
}