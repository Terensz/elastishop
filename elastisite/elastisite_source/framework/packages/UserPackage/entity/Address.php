<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\Person;
use framework\packages\BasicPackage\entity\Country;
// use framework\packages\UserPackage\entity\UserAccount;

class Address extends DbEntity
{
    public const CHOOSABLE_STREET_SUFFIXES = [
        'road' => 'road',
        'street' => 'street',
        'boulevard' => 'boulevard',
        'avenue' => 'avenue',
        'promenade' => 'promenade',
        'park' => 'park',
        'steps' => 'steps',
        'mead' => 'mead', // = field
        'ranch' => 'ranch',
        'terrace' => 'terrace',
        'vale' => 'vale',
        'rise' => 'rise', // Rezsu
        'rise2' => 'rise2', // Emelkedo
        'rise3' => 'rise3', // Lejto
        'grove' => 'grove'
    ];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `address` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `person_id` int(11) DEFAULT NULL,
        `country_id` int(11) NOT NULL,
        `zip_code` varchar(10) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `city` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `street` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `street_suffix` varchar(30) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `house_number` varchar(20) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `staircase` varchar(20) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `floor` varchar(20) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `door` varchar(20) COLLATE utf8_hungarian_ci DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1300 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    // const ENTITY_ATTRIBUTES = [
    //     'repositoryPath' => 'framework/packages/BusinessPackage/repository/AddressRepository',
    //     'relations' => [],
    //     'active' => true
    // ];

    /**
    * primary key
    */
    protected $id;

    /**
    * @var Person
    */
    private $person;

    /**
    * @var Country
    */
    protected $country;

    /**
    * @var $zipCode // e.g 2040
    */
    protected $zipCode;

    /**
    * @var City
    */
    protected $city;

    /**
    * var $street
    */
    protected $street;

    /**
    * var $streetSuffix
    */
    protected $streetSuffix;

    /**
    * var $houseNumber
    */
    protected $houseNumber;
    
    /**
    * var $staircase
    */
    protected $staircase;

    /**
    * var $floor
    */
    protected $floor;

    /**
    * var $door
    */
    protected $door;

    public function __construct()
    {
        $this->wireService('BasicPackage/entity/Country');
    }

    public function __toString()
    {
        // dump('TOSTRING!!!');exit;
        if (!$this->getCountry()) {
            return '';
        }

        return trans($this->getCountry()->getTranslationReference().'.country').', '.$this->getZipCode().' '.$this->getCity().', '.$this->getStreet().' '.$this->getStreetSuffix().' '.$this->getHouseNumber().' '.$this->getStaircase().' '.$this->getFloor().' '.$this->getDoor();
    }

    public function getCountryName()
    {
        if ($this->country) {
            return trans($this->country->getTranslationReference().'.country');
        }
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPerson(Person $person = null)
    {
        $this->person = $person;
    }

    public function getPerson()
    {
        return $this->person;
    }

    public function setCountry(Country $country)
    {
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    public function getZipCode()
    {
        return $this->zipCode;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setStreet($street)
    {
        $this->street = $street;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreetSuffix($streetSuffix)
    {
        $this->streetSuffix = $streetSuffix;
    }

    public function getStreetSuffix()
    {
        return $this->streetSuffix;
    }

    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;
    }

    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    public function setStaircase($staircase)
    {
        $this->staircase = $staircase;
    }

    public function getStaircase()
    {
        return $this->staircase;
    }

    public function setFloor($floor)
    {
        $this->floor = $floor;
    }

    public function getFloor()
    {
        return $this->floor;
    }

    public function setDoor($door)
    {
        $this->door = $door;
    }

    public function getDoor()
    {
        return $this->door;
    }
}
