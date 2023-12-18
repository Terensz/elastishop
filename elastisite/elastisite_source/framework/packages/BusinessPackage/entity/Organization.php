<?php
namespace framework\packages\BusinessPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\Address;
use framework\packages\UserPackage\entity\UserAccount;

class Organization extends DbEntity
{
    const TYPES = ['company', 'entrepreneur', 'non-profit'];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `organization` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_account_id` int(11) DEFAULT NULL,
        `name` varchar(250) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `tax_id` varchar(200) DEFAULT NULL,
        `ceo` varchar(250) DEFAULT NULL,
        `contact_person` varchar(250) DEFAULT NULL,
        `contact_phone` varchar(250) DEFAULT NULL,
        `contact_email` varchar(250) DEFAULT NULL,
        `constitutional_document_id` varchar(250) DEFAULT NULL,
        `entrepreneur_registration_number` varchar(100) DEFAULT NULL,
        `entrepreneur_card_number` varchar(100) DEFAULT NULL,
        `address_id` int(11) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=72000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        // 'repositoryPath' => 'framework/packages/BusinessPackage/repository/CompanyRepository',
        // 'relations' => [],
        'active' => true
    ];

    protected $id;
    protected $userAccount;
    protected $name;
    protected $taxId;
    protected $ceo;
    protected $contactPerson;
    protected $contactPhone;
    protected $contactEmail;
    protected $constitutionalDocumentId;
    protected $entrepreneurRegistrationNumber;
    protected $entrepreneurCardNumber;
    /**
    * @var Address
    */
    protected $address;
    protected $createdAt;
    protected $status;

    public function __construct()
    {
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

    public function setUserAccount(UserAccount $userAccount = null)
    {
        $this->userAccount = $userAccount;
    }

    public function getUserAccount()
    {
        return $this->userAccount;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setTaxId($taxId)
    {
        $this->taxId = $taxId;
    }

    public function getTaxId()
    {
        return $this->taxId;
    }

    public function setCeo($ceo)
    {
        $this->ceo = $ceo;
    }

    public function getCeo()
    {
        return $this->ceo;
    }

    public function setContactPerson($contactPerson)
    {
        $this->contactPerson = $contactPerson;
    }

    public function getContactPerson()
    {
        return $this->contactPerson;
    }

    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;
    }

    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
    }

    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    public function setConstitutionalDocumentId($constitutionalDocumentId)
    {
        $this->constitutionalDocumentId = $constitutionalDocumentId;
    }

    public function getConstitutionalDocumentId()
    {
        return $this->constitutionalDocumentId;
    }

    public function setEntrepreneurRegistrationNumber($entrepreneurRegistrationNumber)
    {
        $this->entrepreneurRegistrationNumber = $entrepreneurRegistrationNumber;
    }

    public function getEntrepreneurRegistrationNumber()
    {
        return $this->entrepreneurRegistrationNumber;
    }

    public function setEntrepreneurCardNumber($entrepreneurCardNumber)
    {
        $this->entrepreneurCardNumber = $entrepreneurCardNumber;
    }

    public function getEntrepreneurCardNumber()
    {
        return $this->entrepreneurCardNumber;
    }

    public function setAddress(Address $address)
    {
        $this->address = $address;
    }

    public function getAddress()
    {
        return $this->address;
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
