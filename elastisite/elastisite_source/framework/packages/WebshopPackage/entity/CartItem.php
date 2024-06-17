<?php
namespace framework\packages\WebshopPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\WebshopPackage\dataProvider\interfaces\PackItemInterface;
use framework\packages\WebshopPackage\entity\Cart;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\entity\ProductPrice;

class CartItem extends DbEntity implements PackItemInterface
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `cart_item` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `cart_id` int(11) DEFAULT NULL,
        `product_id` int(11) DEFAULT NULL,
        `product_price_id` int(11) DEFAULT NULL,
        `applied_by` int(11) DEFAULT NULL,
        `quantity` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=22000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    /*
    use elastisite_devel;
    drop table cart_item ;
    CREATE TABLE `cart_item` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `cart_id` int(11) DEFAULT NULL,
    `product_id` int(11) DEFAULT NULL,
    `product_price_id` int(11) DEFAULT NULL,
    `quantity` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1300 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;
    */

    protected $id;
    protected $cart;
    protected $product;
    protected $productPrice;
    protected $appliedBy; // CartTrigger ID
    protected $quantity;

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

    public function setCart(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function getCart()
    {
        return $this->cart;
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

    public function setAppliedBy($appliedBy)
    {
        $this->appliedBy = $appliedBy;
    }

    public function getAppliedBy()
    {
        return $this->appliedBy;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }
}
