<?php
namespace framework\packages\WebshopPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\repository\ProductRepository;

class ProductPrice extends DbEntity
{
    const PRICE_TYPE_LIST = 'list';
    const PRICE_TYPE_DISCOUNT = 'discount';

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `product_price` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `product_id` int(11) NOT NULL,
        `currency_id` int(11) NOT NULL,
        `title` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `net_price` decimal(13,2) DEFAULT NULL,
        `gross_price` decimal(13,2) DEFAULT NULL,
        `price_type` varchar(30) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `vat` int(3) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=28000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

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
    protected $currency;
    protected $title;
    protected $netPrice;
    protected $grossPrice;
    protected $priceType;
    protected $vat;
    protected $createdAt;
    // protected $status;

    public function __construct()
    {
        // $this->status = 1;
        // $productId = (int)$this->getContainer()->getRequest()->get('productId');
        // if ($productId) {
        //     $this->getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        //     $productRepo = new ProductRepository();
        //     // $product = $productRepo->findOneBy(['productId' => $productId]);
        //     dump($productRepo);exit;
        // }
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setProduct(Product $product = null)
    {
        $this->product = $product;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setCurrency(Currency $currency = null)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setNetPrice($netPrice)
    {
        $this->netPrice = $netPrice;
    }

    public function setGrossPrice($grossPrice)
    {
        $this->grossPrice = $grossPrice;
    }

    public function getGrossPrice()
    {
        return $this->grossPrice;
        // return ceil($this->netPrice * (1 + ($this->vat / 100)));
    }

    public function getNetPrice()
    {
        return $this->netPrice;
    }

    public function setPriceType($priceType)
    {
        $this->priceType = $priceType;
    }

    public function getPriceType()
    {
        return $this->priceType;
    }

    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    public function getVat()
    {
        return $this->vat;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    // public function setStatus($status)
    // {
    //     $this->status = $status;
    // }

    // public function getStatus()
    // {
    //     return $this->status;
    // }
}
