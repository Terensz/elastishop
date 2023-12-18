<?php
namespace framework\packages\WebshopPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\entity\ProductPrice;

class ProductPriceActive extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `product_price_active` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `product_id` int(11) NOT NULL,
        `product_price_id` int(11) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=29000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    // const ENTITY_ATTRIBUTES = [
    //     'repositoryPath' => 'framework/packages/WebshopPackage/repository/ProductPriceRepository',
    //     'relations' => [
    //         'Product' => [
    //             'targetClass' => Product::class,
    //             // 'association' => 'manyToOne',
    //             // 'relationBinderTable' => false,
    //             'referencedIdField' => 'product_id',
    //             'allowNewChild' => false
    //         ]
    //     ],
    //     'active' => true
    // ];
    protected $id;
    protected $product;
    protected $productPrice;

    public function __construct()
    {
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

    public function setProductPrice(ProductPrice $productPrice)
    {
        $this->productPrice = $productPrice;
    }

    public function getProductPrice()
    {
        return $this->productPrice;
    }
}
