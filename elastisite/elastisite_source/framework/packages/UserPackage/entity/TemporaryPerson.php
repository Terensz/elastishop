<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\BusinessPackage\entity\Organization;
// use framework\packages\UserPackage\entity\Address;
use framework\packages\UserPackage\entity\TemporaryAccount;

class TemporaryPerson extends DbEntity
{
    const CUSTOMER_TYPE_ORGANIZATION = 'Organization';
    const CUSTOMER_TYPE_PRIVATE_PERSON = 'PrivatePerson';

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `temporary_person` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `temporary_account_id` int(11) DEFAULT NULL,
        `address_id` int(11) DEFAULT NULL,
        `customer_type` varchar(20) DEFAULT NULL,
        `organization_id` int(11) DEFAULT NULL,
        `name` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `recipient_name` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `terms_and_conditions_accepted` int(1) DEFAULT NULL,
        `email` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `mobile` varchar(32) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `customer_note` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1600 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";
    /*
    use elastisite_devel;
    drop table temporary_person ;
    CREATE TABLE `temporary_person` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(200) COLLATE utf8_hungarian_ci DEFAULT NULL,
    `email` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
    `mobile` varchar(32) COLLATE utf8_hungarian_ci DEFAULT NULL,
    `address_id` int(11) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `status` int(2) DEFAULT 0,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1300 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;
    */

    /**
    * @primaryKey
    */
    protected $id;

    /**
    * @var TemporaryAccount
    */
    protected $temporaryAccount;

    /**
    * @var Address
    */
    protected $address;

    /**
    * @var string
    */
    protected $customerType;

    /**
    * @var Organization
    */
    protected $organization;

    /**
    * @var string
    */
    protected $name;

    /**
    * @var string
    */
    protected $recipientName;

    /**
    * @var int
    */
    protected $termsAndConditionsAccepted;

    /**
    * @var string
    */
    protected $email;

    /**
    * @var string
    */
    protected $mobile;

    /**
    * @var string
    */
    protected $customerNote;

    /**
    * @var \DateTime
    */
    // protected $createdAt;

    /**
    * @var int
    */
    // protected $status;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTemporaryAccount(TemporaryAccount $temporaryAccount = null)
    {
        $this->temporaryAccount = $temporaryAccount;
    }

    public function getTemporaryAccount()
    {
        return $this->temporaryAccount;
    }

    public function setAddress(Address $address = null)
    {
        $this->address = $address;
    }

    public function getAddress() : ? Address
    {
        return $this->address;
    }

    public function setCustomerType($customerType = null)
    {
        if ($customerType === null || $customerType === self::CUSTOMER_TYPE_ORGANIZATION || $customerType === self::CUSTOMER_TYPE_PRIVATE_PERSON) {
            $this->customerType = $customerType;
        } else {
            throw new \InvalidArgumentException('Invalid customer type: '.$customerType);
        }
        // $this->customerType = $customerType;
    }

    public function getCustomerType()
    {
        return $this->customerType;
    }

    public function setOrganization(Organization $organization = null)
    {
        $this->organization = $organization;
    }
    
    public function getOrganization()
    {
        return $this->organization;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;
    }

    public function getRecipientName()
    {
        return $this->recipientName;
    }

    public function setTermsAndConditionsAccepted($termsAndConditionsAccepted)
    {
        $this->termsAndConditionsAccepted = $termsAndConditionsAccepted;
    }

    public function getTermsAndConditionsAccepted()
    {
        return $this->termsAndConditionsAccepted;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $this->decrypt($mobile);
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setCustomerNote($customerNote)
    {
        $this->customerNote = $customerNote;
    }

    public function getCustomerNote()
    {
        return $this->customerNote;
    }

    // public function setCreatedAt($createdAt)
    // {
    //     $this->createdAt = $createdAt;
    // }

    // public function getCreatedAt()
    // {
    //     return $this->createdAt;
    // }

    // public function setStatus($status)
    // {
    //     $this->status = $status;
    // }

    // public function getStatus()
    // {
    //     return $this->status;
    // }
}
