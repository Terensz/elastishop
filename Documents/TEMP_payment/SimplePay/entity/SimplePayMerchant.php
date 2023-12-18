<?php
namespace framework\packages\WebshopPackage\service\payment\SimplePay\entity;

use framework\component\parent\Service;

class SimplePayMerchant extends Service
{
    protected $merchantId;
    protected $name;
    protected $secretKey;
    protected $currency;

    public function __construct()
    {

    }

    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

}