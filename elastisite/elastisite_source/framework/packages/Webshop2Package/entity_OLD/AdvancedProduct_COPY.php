<?php
namespace framework\packages\WebshopPackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\kernel\utility\BasicUtils;
use framework\packages\WebshopPackage\entity\ProductCategory;
use framework\packages\WebshopPackage\entity\ProductPriceActive;
use framework\packages\WebshopPackage\entity\ProductImage;

class AdvancedProduct extends DbEntity
{
    const LANGUAGE_DEFAULT = 'Default';

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `product2_` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `default_language_code` varchar(5) COLLATE utf8_hungarian_ci NOT NULL,
        `name_def` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
        -- `name_en` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `slug_def` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        -- `slug_en` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `description_def` text COLLATE utf8_hungarian_ci NOT NULL,
        -- `description_en` text COLLATE utf8_hungarian_ci DEFAULT NULL,
        `code` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `product_category_id` int(11) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` int(2) NOT NULL DEFAULT '1',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=25000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    /*
    CREATE TABLE `product` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
    `name_en` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
    `slug` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
    `slug_en` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
    `description` text COLLATE utf8_hungarian_ci NOT NULL,
    `description_en` text COLLATE utf8_hungarian_ci NOT NULL,
    `code` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
    `product_category_id` int(11) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `status` int(11) NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci
    */
    const ENTITY_ATTRIBUTES = [
        'repositoryPath' => 'framework/packages/WebshopPackage/repository/ProductRepository',
        'relations' => [
            // 'ProductCategory' => [
            //     'targetClass' => ProductCategory::class,
            //     // 'association' => 'manyToOne',
            //     // 'relationBinderTable' => false,
            //     'referencedIdField' => 'product_category_id',
            //     'allowNewChild' => false
            // ],
            // 'ProductPrice' => [
            //     'targetClass' => ProductPrice::class,
            //     // 'association' => 'manyToOne',
            //     // 'relationBinderTable' => false,
            //     'reverseReferencedIdField' => 'product_id',
            //     'allowNewChild' => false
            // ],
            // 'ProductImage' => [
            //     'targetClass' => ProductImage::class,
            //     // 'association' => 'manyToOne',
            //     // 'relationBinderTable' => false,
            //     'targetIdField' => 'id',
            //     'reverseReferencedIdField' => 'product_id',
            //     'allowNewChild' => true
            // ],
        ],
        'active' => true
    ];
    protected $id;
    protected $website;
    protected $nameDef;
    // protected $nameEn;
    protected $slugDef;
    // protected $slugEn;
    protected $descriptionDef;
    // protected $descriptionEn;
    protected $code;
    protected $productCategory;
    protected $productPriceActive;
    protected $productImage = array();
    protected $createdAt;
    protected $status;

    public function __construct()
    {
        $this->website = App::getWebsite();
    }

    // public static function find($id)
    // {

    //     dump(new self);exit;
    // }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function checkCorrectWebsite() 
    {
        return App::getWebsite() == $this->website ? true : false;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setNameDef($nameDef)
    {
        $this->slugDef = BasicUtils::slugify($nameDef);
        $this->nameDef = $nameDef;
    }

    public function getNameDef($language = self::LANGUAGE_DEFAULT)
    {
        return $this->nameDef;
        // return $language == 'en' ? $this->nameEn : $this->nameDef;
    }

    // public function setNameEn($nameEn)
    // {
    //     $this->slugEn = BasicUtils::slugify($nameEn);
    //     $this->nameEn = $nameEn;
    // }

    // public function getNameEn()
    // {
    //     return $this->nameEn;
    // }

    public function setSlugDef($slugDef)
    {
        $this->slugDef = $slugDef;
    }

    public function getSlugDef()
    {
        return $this->slugDef;
    }

    // public function setSlugEn($slugEn)
    // {
    //     $this->slugEn = $slugEn;
    // }

    // public function getSlugEn()
    // {
    //     return $this->slugEn;
    // }

    public function setDescriptionDef($descriptionDef)
    {
        $this->descriptionDef = $descriptionDef;
    }

    public function getDescriptionDef()
    {
        return $this->descriptionDef;
    }

    // public function setDescriptionEn($descriptionEn)
    // {
    //     $this->descriptionEn = $descriptionEn;
    // }

    // public function getDescriptionEn()
    // {
    //     return $this->descriptionEn;
    // }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setProductCategory(ProductCategory $productCategory = null)
    {
        $this->productCategory = $productCategory;
    }

    public function getProductCategory()
    {
        return $this->productCategory;
    }

    // public function setActiveProductPrice2($activeProductPrice = null)
    // {
    //     $this->getContainer()->wireService('WebshopPackage/repository/ProductPriceRepository');
    //     $productPriceRepo = new ProductPriceRepository();
    //     // dump($productPriceRepo->findOneBy(['id' => $this->id, 'active' => 1]));exit;
    //     $this->activeProductPrice = $productPriceRepo->findOneBy(['id' => $this->id, 'active' => 1]);
    //     // dump($this->activeProductPrice);exit;
    //     // $this->activeProductPrice = $activeProductPrice;
    // }

    public function setProductPriceActive(ProductPriceActive $productPriceActive)
    {
        $this->productPriceActive = $productPriceActive;
    }

    public function getProductPriceActive()
    {
        return $this->productPriceActive;
    }

    public function addProductImage(ProductImage $productImage)
    {
        $this->productImage[] = $productImage;
    }

    public function getProductImage()
    {
        return $this->productImage;
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

    public function getSKU()
    {
        return $this->code ? : $this->id;
    }
}
