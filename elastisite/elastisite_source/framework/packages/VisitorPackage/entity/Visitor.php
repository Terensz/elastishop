<?php
namespace framework\packages\VisitorPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class Visitor extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `visitor` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        -- `user_account_id` int(11) DEFAULT NULL,
        `code` varchar(200) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `ip_address` varchar(30) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `country_code` varchar(4) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `first_visit` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1800 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    // ALTER TABLE visitor ADD country_code varchar(4) COLLATE utf8_hungarian_ci DEFAULT NULL AFTER code;

    // const ENTITY_ATTRIBUTES = [
    //     'repositoryPath' => 'framework/packages/UserPackage/repository/VisitorRepository',
    //     'relations' => [
    //         'UserAccount' => [
    //             'targetClass' => UserAccount::class,
    //             'association' => 'oneToMany',
    //             'relationBinderTable' => false,
    //             'referencedIdField' => 'user_account_id'
    //         ]
    //     ],
    //     'active' => true
    // ];

    protected $id;
    // protected $userAccount;
    protected $code;
    protected $ipAddress;
    protected $countryCode;
    protected $firstVisit;

    public function __construct()
    {
        $this->getContainer()->wireService('framework/packages/UserPackage/entity/UserAccount');
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    // public function setUserAccount(UserAccount $userAccount)
    // {
    //     $this->userAccount = $userAccount;
    // }

    // public function getUserAccount()
    // {
    //     return $this->userAccount;
    // }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setFirstVisit($firstVisit)
    {
        $this->firstVisit = $firstVisit;
    }

    public function getFirstVisit()
    {
        return $this->firstVisit;
    }
}
