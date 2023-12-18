<?php
namespace framework\packages\PaymentPackage\entity;

use framework\component\parent\TechnicalEntity;

class OnlineGateway extends TechnicalEntity
{
    private $APIUrl;

    private $merchantId;

    private $merchantUsername;

    private $merchantName;

    private $merchantEmail;

    private $merchantPhone;

    private $merchantLocale;

    private $currency;

    private $transactionId;

    public $isSandbox;

    public $status;

    public $challengeSuccess;

    /**
     * An associative array which describes the property names at the gateway provider.
    */
    public $propertyToKeyConversionMap = [];

    public function setAPIUrl($APIUrl)
    {
        $this->APIUrl = $APIUrl;
    }

    public function getAPIUrl()
    {
        return $this->APIUrl;
    }

    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function setMerchantUsername($merchantUsername)
    {
        $this->merchantUsername = $merchantUsername;
    }

    public function getMerchantUsername()
    {
        return $this->merchantUsername;
    }

    public function setMerchantName($merchantName)
    {
        $this->merchantName = $merchantName;
    }

    public function getMerchantName()
    {
        return $this->merchantName;
    }

    public function setMerchantEmail($merchantEmail)
    {
        $this->merchantEmail = $merchantEmail;
    }

    public function getMerchantEmail()
    {
        return $this->merchantEmail;
    }

    public function setMerchantPhone($merchantPhone)
    {
        $this->merchantPhone = $merchantPhone;
    }

    public function getMerchantPhone()
    {
        return $this->merchantPhone;
    }

    public function setMerchantLocale($merchantLocale)
    {
        $this->merchantLocale = $merchantLocale;
    }

    public function getMerchantLocale()
    {
        return $this->merchantLocale;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }
}