<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\TestUserAccount;
// use framework\packages\UserPackage\entity\Address;

class TestPerson extends DbEntity
{
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
        'active' => false
    ];
    private $id;
    private $testUserAccount;
    private $fullName;
    private $username;
    private $password;
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

    public function setTestUserAccount(TestUserAccount $testUserAccount)
    {
        $this->testUserAccount = $testUserAccount;
    }

    public function getTestUserAccount()
    {
        return $this->testUserAccount;
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
}
