<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\TestPerson;

class TestAddress extends DbEntity
{
    const ENTITY_ATTRIBUTES = [
        // 'repositoryPath' => 'framework/packages/UserPackage/repository/AddressRepository',
        // 'relations' => [
        //     'Person' => [
        //         'targetClass' => Person::class,
        //         'association' => 'manyToOne',
        //         'relationBinderTable' => false,
        //         'targetIdField' => 'id',
        //         'referencedIdField' => 'person_id'
        //     ]
        // ],
        'active' => false
    ];

    private $id;
    // private $personId;
    private $testPerson;
    private $postalAddress;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTestPerson(TestPerson $testPerson)
    {
        $this->testPerson = $testPerson;
    }

    public function getTestPerson()
    {
        return $this->testPerson;
    }

    // public function setPersonId($personId)
    // {
    //     $this->personId = $personId;
    // }

    // public function getPersonId()
    // {
    //     return $this->personId;
    // }

    public function setPostalAddress($postalAddress)
    {
        $this->postalAddress = $postalAddress;
    }

    public function getPostalAddress()
    {
        return $this->postalAddress;
    }
}
