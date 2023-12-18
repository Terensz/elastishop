<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;
use framework\packages\UserPackage\entity\Address;

class Person extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `person` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_account_id` int(11) DEFAULT NULL,
        `full_name` varchar(250) DEFAULT NULL,
        `username` varchar(250) DEFAULT NULL,
        `password` varchar(250) DEFAULT NULL,
        `email` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `mobile` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1500 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        // 'repositoryPath' => 'framework/packages/UserPackage/repository/PersonRepository',
        // 'relations' => [
        //     'UserAccount' => [
        //         'targetClass' => UserAccount::class,
        //         'association' => 'oneToOne',
        //         'relationBinderTable' => false,
        //         'targetIdField' => 'id',
        //         'referencedIdField' => 'user_account_id'
        //     ],
        //     'Address' => [
        //         'targetClass' => Address::class,
        //         'association' => 'oneToMany'
        //     ]
        // ],
        // 'active' => true
        'encryptedProperties' => array('fullName', 'username', 'password', 'email', 'mobile'),
        'technicalProperties' => array('displayedPassword', 'retypedPassword')
        // 'onStoreFunctionCalls' => array('password' => 'onStorePassword')
    ];
    private $id;
    private $userAccount;
    private $address = array();
    // private $address;
    private $fullName;
    private $username;
    private $password;
    private $displayedPassword;
    private $retypedPassword;
    private $email;
    private $mobile;

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

    public function getUserAccount() : ? UserAccount
    {
        return $this->userAccount;
    }

    public function addAddress(Address $address)
    {
        $this->address[] = $address;
    }

    // public function setAddress(Address $address)
    // {
    //     $this->address[] = $address;
    // }

    public function getAddress() : array
    {
        return $this->address;
    }

    public function setFullName($fullName)
    {
        $this->fullName = $this->decrypt($fullName);
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function setUsername($username)
    {
        $this->username = $this->decrypt($username);
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $this->decrypt($password);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setDisplayedPassword($displayedPassword)
    {
        if ($displayedPassword && $displayedPassword != '') {
            $this->password = md5($displayedPassword);
        }
    }

    public function getDisplayedPassword()
    {
        return '';
    }

    public function setRetypedPassword($retypedPassword)
    {
        $this->retypedPassword = $retypedPassword;
    }

    public function getRetypedPassword()
    {
        return $this->retypedPassword;
    }

    public function setEmail($email)
    {
        $this->email = $this->decrypt($email);
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

    public function getDefaultAddress()
    {
        if ($this->address && !empty($this->address)) {
            if (count($this->address) == 1) {
                return $this->address[0];
            } else {
                /**
                 * @todo
                */
                return $this->address[0];
            }
        } else {
            return null;
        }
    }
}
