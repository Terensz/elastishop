<?php
namespace framework\packages\WebshopPackage\entity;

use App;
use framework\component\parent\DbEntity;

class ProductVisitHistory extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `product_visit_history` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `visitor_code` varchar(100) DEFAULT NULL,
        `number_of_visits` int(11) DEFAULT NULL,
        `product_id` int(11) COLLATE utf8_hungarian_ci NOT NULL,
        `created_at` datetime DEFAULT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=343000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        'active' => true
    ];
    protected $id;
    protected $website;
    protected $visitorCode;
    protected $numberOfVisits;
    protected $product;
    protected $createdAt;
    protected $updatedAt;

    public function __construct()
    {
        $this->website = App::getWebsite();
        $this->createdAt = $this->getCurrentTimestamp();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setVisitorCode($visitorCode)
    {
        $this->visitorCode = $visitorCode;
    }

    public function getVisitorCode()
    {
        return $this->visitorCode;
    }

    public function setNumberOfVisits($numberOfVisits)
    {
        $this->numberOfVisits = $numberOfVisits;
    }

    public function getNumberOfVisits()
    {
        return $this->numberOfVisits;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
