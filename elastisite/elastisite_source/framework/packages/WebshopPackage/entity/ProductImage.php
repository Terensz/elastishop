<?php
namespace framework\packages\WebshopPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\WebshopPackage\entity\Product;
// use framework\packages\WebshopPackage\entity\ProductCategory;
class ProductImage extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `product_image` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `product_id` int(11) NOT NULL,
        `title` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `slug` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `image_code` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `extension` varchar(10) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `mime` varchar(20) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `main` int(2) NOT NULL DEFAULT '1',
        `created_at` datetime DEFAULT NULL,
        `status` int(2) NOT NULL DEFAULT '1',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=27000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    // const ENTITY_ATTRIBUTES = [
    //     'repositoryPath' => 'framework/packages/WebshopPackage/repository/ProductCategoryRepository',
    //     'relations' => [
    //         'ProductCategory' => [
    //             'targetClass' => self::class,
    //             'association' => 'oneToOne',
    //             'relationBinderTable' => false,
    //             'targetIdField' => 'id',
    //             'referencedIdField' => 'product_category_id'
    //         ]
    //     ],
    //     'active' => true
    // ];
    protected $id;
    protected $product;
    protected $title;
    protected $slug;
    protected $imageCode;
    protected $extension;
    protected $mime;
    protected $main;
    protected $createdAt;
    protected $status;

    public static function createProductImageLink($slug)
    {
        return '/webshop/image/thumbnail/'.$slug;
    }

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

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setImageCode($imageCode)
    {
        $this->imageCode = $imageCode;
    }

    public function getImageCode()
    {
        return $this->imageCode;
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    public function getMime()
    {
        return $this->mime;
    }

    public function setMain($main)
    {
        $this->main = $main;
    }

    public function getMain()
    {
        return $this->main;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
