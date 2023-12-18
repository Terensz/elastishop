<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\TechnicalEntity;
use framework\component\interfaces\UserInterface;
use framework\packages\UserPackage\entity\UserAccount;

class User extends TechnicalEntity implements UserInterface
{
    const TYPE_ADMINISTRATOR = 'administrator';

    const TYPE_USER = 'user';

    const TYPE_GUEST = 'guest';

    const ENTITY_ATTRIBUTES = [
        'repositoryPath' => 'framework/packages/UserPackage/repository/UserRepository',
        'relations' => null,
        'active' => true
    ];

    private $id;
    private $userAccount;
    private $name;
    private $username;
    private $password;
    private $email;
    private $mobile;
    private $permissionGroups = array();
    private $type = self::TYPE_GUEST;
    private $status;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUserAccount($userAccount)
    {
        $this->userAccount = $userAccount;
    }

    public function getUserAccount() : ? UserAccount
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

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
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
        $this->mobile = $mobile;
    }

    public function getMobile()
    {
        return $this->mobile;
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

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
