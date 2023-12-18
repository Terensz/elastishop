<?php
namespace framework\packages\PaymentPackage\entity;

use framework\component\parent\TechnicalEntity;

class OLD_MerchantAccount extends TechnicalEntity
{
    private $id;

    private $username;

    private $name;

    private $email;

    private $phone;

    private $locale;

    /**
     * An associative array which describes the property names at the gateway provider.
    */
    public $propertyToKeyConversionMap = [];

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    // public function setId($id)
    // {
    //     $this->id = $id;
    // }

    // public function getId()
    // {
    //     return $this->id;
    // }

    // public function setId($id)
    // {
    //     $this->id = $id;
    // }

    // public function getId()
    // {
    //     return $this->id;
    // }
}