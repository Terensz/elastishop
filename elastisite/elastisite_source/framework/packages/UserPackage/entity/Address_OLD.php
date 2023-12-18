<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\Person;

class Address_OLD extends DbEntity
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
    private $person;
    private $postalAddress;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPerson(Person $person)
    {
        $this->person = $person;
    }

    public function getPerson()
    {
        return $this->person;
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
