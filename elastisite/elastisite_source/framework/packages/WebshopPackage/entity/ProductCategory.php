<?php
namespace framework\packages\WebshopPackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\kernel\utility\BasicUtils;
// use framework\packages\WebshopPackage\entity\ProductCategory;
class ProductCategory extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `product_category` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `name` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
        `name_en` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `slug` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `slug_en` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `code` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `product_category_id` int(11) DEFAULT NULL,
        `is_independent` smallint(1) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` int(2) NOT NULL DEFAULT '1',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=26000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        'repositoryPath' => 'framework/packages/WebshopPackage/repository/ProductCategoryRepository',
        'relations' => [
            'ProductCategory' => [
                'targetClass' => self::class,
                'association' => 'oneToOne',
                'relationBinderTable' => false,
                'targetIdField' => 'id',
                'referencedIdField' => 'product_category_id'
            ]
        ],
        'active' => true
    ];
    protected $id;
    protected $website;
    protected $name;
    protected $nameEn;
    protected $slug;
    protected $slugEn;
    protected $code;
    // protected $website;
    protected $productCategory;
    protected $isIndependent;
    protected $createdAt;
    protected $status;

    public function __construct()
    {
        $this->website = App::getWebsite();
    }

    public function __toString()
    {
        return (string)$this->name;
    }

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

    public function setName($name)
    {
        $this->slug = BasicUtils::slugify($name);
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setNameEn($nameEn)
    {
        $this->slugEn = BasicUtils::slugify($nameEn);
        $this->nameEn = $nameEn;
    }

    public function getNameEn()
    {
        return $this->nameEn;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlugEn($slugEn)
    {
        $this->slugEn = $slugEn;
    }

    public function getSlugEn()
    {
        return $this->slugEn;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setProductCategory(ProductCategory $productCategory)
    {
        $this->productCategory = $productCategory;
    }

    public function getProductCategory()
    {
        return $this->productCategory;
    }

    public function setIsIndependent($isIndependent)
    {
        $this->isIndependent = $isIndependent;
    }

    public function getIsIndependent()
    {
        return $this->isIndependent;
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
        return $this->status === null ? 0 : (int)$this->status;
    }
}
