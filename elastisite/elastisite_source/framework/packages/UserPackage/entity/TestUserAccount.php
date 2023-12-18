<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\TestPerson;
use framework\packages\UserPackage\entity\Address;

class TestUserAccount extends DbEntity
{
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
        'active' => false
    ];
    private $id;
    private $code;
    private $testPerson;
    private $registeredAt;
    private $permissionGroups = array();
    private $status;

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

    public function setTestPerson(TestPerson $testPerson = null)
    {
        $this->testPerson = $testPerson;
    }

    public function getTestPerson()
    {
        return $this->testPerson;
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
