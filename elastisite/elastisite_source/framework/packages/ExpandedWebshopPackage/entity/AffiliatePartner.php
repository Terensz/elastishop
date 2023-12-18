<?php
namespace framework\packages\ExpandedWebshopPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;
/* 
$('.vjs-big-play-button').remove();
$('.vjs-inplayer-container').remove();
dfsfsfsf
*/

class AffiliatePartner extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `affiliate_partner` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `product_link_pattern` varchar(250) DEFAULT NULL,
        `partner_identifier` varchar(100) DEFAULT NULL,
        -- `contact_name` varchar(100) DEFAULT NULL,
        -- `contact_email` varchar(100) DEFAULT NULL,
        -- `contact_phone` varchar(20) DEFAULT NULL,
        -- `commission_structure` text DEFAULT NULL,
        -- `payment_information` text DEFAULT NULL,
        -- `promotional_materials` text DEFAULT NULL,
        -- `performance_statistics` text DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=400000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $name;
    protected $productLinkPattern;
    protected $partnerIdentifier;
    // protected $contactName;
    // protected $contactEmail;
    // protected $contactPhone;
    // protected $commissionStructure;
    // protected $paymentInformation;
    // protected $promotionalMaterials;
    // protected $performanceStatistics;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setProductLinkPattern($productLinkPattern)
    {
        $this->productLinkPattern = $productLinkPattern;
    }

    public function getProductLinkPattern()
    {
        return $this->productLinkPattern;
    }

    public function setPartnerIdentifier($partnerIdentifier)
    {
        $this->partnerIdentifier = $partnerIdentifier;
    }

    public function getPartnerIdentifier()
    {
        return $this->partnerIdentifier;
    }

    // public function setContactName($contactName)
    // {
    //     $this->contactName = $contactName;
    // }

    // public function getContactName()
    // {
    //     return $this->contactName;
    // }

    // public function setContactEmail($contactEmail)
    // {
    //     $this->contactEmail = $contactEmail;
    // }

    // public function getContactEmail()
    // {
    //     return $this->contactEmail;
    // }

    // public function setContactPhone($contactPhone)
    // {
    //     $this->contactPhone = $contactPhone;
    // }

    // public function getContactPhone()
    // {
    //     return $this->contactPhone;
    // }

    // public function setCommissionStructure($commissionStructure)
    // {
    //     $this->commissionStructure = $commissionStructure;
    // }

    // public function getCommissionStructure()
    // {
    //     return $this->commissionStructure;
    // }

    // public function setPaymentInformation($paymentInformation)
    // {
    //     $this->paymentInformation = $paymentInformation;
    // }

    // public function getPaymentInformation()
    // {
    //     return $this->paymentInformation;
    // }

    // public function setPromotionalMaterials($promotionalMaterials)
    // {
    //     $this->promotionalMaterials = $promotionalMaterials;
    // }

    // public function getPromotionalMaterials()
    // {
    //     return $this->promotionalMaterials;
    // }

    // public function setPerformanceStatistics($performanceStatistics)
    // {
    //     $this->performanceStatistics = $performanceStatistics;
    // }

    // public function getPerformanceStatistics()
    // {
    //     return $this->performanceStatistics;
    // }
}