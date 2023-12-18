<?php
namespace framework\packages\ExpandedWebshopPackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\kernel\utility\BasicUtils;
use framework\packages\WebshopPackage\entity\ProductCategory;
use framework\packages\WebshopPackage\entity\ProductPriceActive;
use framework\packages\WebshopPackage\entity\ProductImage;

class ExpandedProductUnitHead extends DbEntity
{
    const ROLE_PRODUCT = 'Product';
    const ROLE_CATEGORY = 'Category';

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `expanded_product_unit_head` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `role` varchar(100) DEFAULT NULL,
        `parent_id` int(11) DEFAULT NULL,
        `affiliate_partner_id` int(11) DEFAULT NULL,
        `code` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` int(2) NOT NULL DEFAULT '1',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=401000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    // const ENTITY_ATTRIBUTES = [
    //     'repositoryPath' => 'framework/packages/WebshopPackage/repository/ProductRepository',
    //     'active' => true
    // ];

    protected $id;
    protected $website;
    protected $role;
    protected $parent;
    protected $affiliatePartner;
    protected $code;
    protected $createdAt;
    protected $status;

    public function __construct()
    {
        $this->website = App::getWebsite();
        $this->createdAt = $this->getCurrentTimestamp();
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

    // public function checkCorrectWebsite() 
    // {
    //     return App::getWebsite() == $this->website ? true : false;
    // }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setParent(ExpandedProductUnitHead $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setAffiliatePartner(AffiliatePartner $affiliatePartner = null)
    {
        $this->affiliatePartner = $affiliatePartner;
    }

    public function getAffiliatePartner()
    {
        return $this->affiliatePartner;
    }


	public function setCode($code)
	{
		$this->code = $code;
	}

	public function getCode()
	{
		return $this->code;
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

    // public function getSKU()
    // {
    //     return $this->code ? : $this->id;
    // }
}
