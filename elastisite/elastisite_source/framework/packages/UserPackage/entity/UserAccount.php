<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\Person;
use framework\packages\NewsletterPackage\entity\NewsletterSubscription;

class UserAccount extends DbEntity
{
    const STATUS_ACTIVE = 1;
    const STATUS_PROVEN = 2;
    const STATUS_DISABLED = 0;

    const STATUS_CODE_CONVERSIONS = [
        '0' => 'disabled',
        '1' => 'active',
        '2' => 'proven'
    ];
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `user_account` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `code` varchar(100) DEFAULT NULL,
        `registration_visitor_code` varchar(12) DEFAULT NULL,
        `is_tester` smallint(1) DEFAULT 0,
        `registered_at` datetime DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1300 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        // 'repositoryPath' => 'framework/packages/UserPackage/repository/UserAccountRepository',
        // 'relations' => [
        //     'Person' => [
        //         'targetClass' => Person::class,
        //         'association' => 'oneToOne',
        //         'relationBinderTable' => false,
        //         'referencedIdField' => null
        //     ]
        // ],
        // 'technicalProperties' => array('permissionGroups'),
        // 'active' => 'alma'
        'encryptedProperties' => array(),
        'technicalProperties' => array('permissionGroups')
    ];
    protected $id;
    protected $code;
    protected $registrationVisitorCode;
    protected $person;
    protected $newsletterSubscription;
    protected $isTester;
    // private $person = array();
    protected $registeredAt;
    protected $permissionGroups = array();
    protected $status;

    public function __construct()
    {
        $this->registrationVisitorCode = $this->getSession()->get('visitorCode');
        // $this->code = $this->getRepository()->createCode();
        $this->registeredAt = $this->getCurrentTimestamp();
        // $this->status = 1;
        // dump($this);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setRegistrationVisitorCode($registrationVisitorCode)
    {
        $this->registrationVisitorCode = $registrationVisitorCode;
    }

    public function getRegistrationVisitorCode()
    {
        return $this->registrationVisitorCode;
    }

    public function setPerson(Person $person = null)
    {
        $this->person = $person;
    }

    public function getPerson() : ? Person
    {
        return $this->person;
    }

    public function setNewsletterSubscription(NewsletterSubscription $newsletterSubscription) 
    {
        $this->newsletterSubscription = $newsletterSubscription;
    }

    public function getNewsletterSubscription() : ? NewsletterSubscription
    {
        return $this->newsletterSubscription;
    }

    public function setIsTester($isTester)
    {
        $this->isTester = $isTester;
    }

    public function getIsTester()
    {
        return $this->isTester;
    }

    public function setRegisteredAt($registeredAt)
    {
        $this->registeredAt = $registeredAt;
    }

    public function getRegisteredAt()
    {
        return $this->registeredAt;
    }

    public function getPermissionGroups()
    {
        return $this->permissionGroups;
    }

    public function setPermissionGroups($permissionGroupArray)
    {
        $this->permissionGroups = $permissionGroupArray;
    }

    public function addPermissionGroup($permissionGroup)
    {
        if (!in_array($permissionGroup, $this->permissionGroups)) {
            $this->permissionGroups[] = $permissionGroup;
        }
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
