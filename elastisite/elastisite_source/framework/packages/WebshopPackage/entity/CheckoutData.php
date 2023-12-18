<?php
namespace framework\packages\WebshopPackage\entity;

use framework\component\parent\DbEntity;
use framework\component\parent\TechnicalEntity;

class CheckoutData extends TechnicalEntity
{
    protected $id;
    protected $paymentMethod;
    protected $recipient;
    protected $email;
    protected $mobile;
    protected $customerNote;
    protected $agreement;

    // public function getRepository()
    // {
    //     $this->setService('WebshopPackage/repository/CheckoutDataRepository');
    //     return $this->getService('CheckoutDataRepository');
    // }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    public function getRecipient()
    {
        return $this->recipient;
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

    public function setCustomerNote($customerNote)
    {
        $this->customerNote = $customerNote;
    }

    public function getCustomerNote()
    {
        return $this->customerNote;
    }

    public function setAgreement($agreement)
    {
        $this->agreement = $agreement;
    }

    public function getAgreement()
    {
        return $this->agreement;
    }
}
