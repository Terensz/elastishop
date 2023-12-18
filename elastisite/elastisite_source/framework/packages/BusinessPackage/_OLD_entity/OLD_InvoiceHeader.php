<?php
namespace framework\packages\BusinessPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\Address;
use framework\packages\UserPackage\entity\UserAccount;

class OLD_InvoiceHeader extends DbEntity
{
    const INVOICE_TYPES = [
        1 => 'proforma',
        2 => 'regular'
    ];

    const INVOICE_TYPE_PROFORMA = 1;
    const INVOICE_TYPE_REGULAR = 2;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `invoice_header` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `invoice_number` varchar(100) DEFAULT NULL,
        `currency_id` int(11) DEFAULT NULL,
        `payment_type` int(5) DEFAULT NULL,
        `date_of_issue` datetime DEFAULT NULL, -- Kiallitas datuma
        `issuer_name` varchar(200) DEFAULT NULL,
        `issuer_address` varchar(250) DEFAULT NULL,
        `issuer_utr` varchar(50) DEFAULT NULL, -- Company's tax number (Unique tax reference)
        `buyer_name` varchar(200) DEFAULT NULL,
        `buyer_address` varchar(250) DEFAULT NULL,
        `buyer_utr` varchar(50) DEFAULT NULL, -- In Hungary this is required for inland buyer, just for companies
        `invoice_net_value` int(11) DEFAULT NULL,
        `payment_deadline` datetime DEFAULT NULL,
        `payment_date` datetime DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=38000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $invoiceNumber;
    protected $currencyId;
    protected $paymentType;
    protected $dateOfIssue;
    protected $issuerName;
    protected $issuerAddress;
    protected $issuerUtr;
    protected $buyerName;
    protected $buyerAddress;
    protected $buyerUtr;
    protected $invoiceNetValue;
    protected $paymentDeadline;
    protected $paymentDate;
    protected $createdAt;
    protected $status;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    public function setCurrencyId($currencyId)
    {
        $this->currencyId = $currencyId;
    }

    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    public function getCurrency()
    {
        return new Currency($this->currencyId);
    }

    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
    }

    public function getPaymentType()
    {
        return $this->paymentType;
    }

    public function setDateOfIssue($dateOfIssue)
    {
        $this->dateOfIssue = $dateOfIssue;
    }

    public function getDateOfIssue()
    {
        return $this->dateOfIssue;
    }

    public function setIssuerName($issuerName)
    {
        $this->issuerName = $issuerName;
    }

    public function getIssuerName()
    {
        return $this->issuerName;
    }

    public function setIssuerAddress($issuerAddress)
    {
        $this->issuerAddress = $issuerAddress;
    }

    public function getIssuerAddress()
    {
        return $this->issuerAddress;
    }

    public function setIssuerUtr($issuerUtr)
    {
        $this->issuerUtr = $issuerUtr;
    }

    public function getIssuerUtr()
    {
        return $this->issuerUtr;
    }

    public function setBuyerName($buyerName)
    {
        $this->buyerName = $buyerName;
    }

    public function getBuyerName()
    {
        return $this->buyerName;
    }

    public function setBuyerAddress($buyerAddress)
    {
        $this->buyerAddress = $buyerAddress;
    }

    public function getBuyerAddress()
    {
        return $this->buyerAddress;
    }

    public function setBuyerUtr($buyerUtr)
    {
        $this->buyerUtr = $buyerUtr;
    }

    public function getBuyerUtr()
    {
        return $this->buyerUtr;
    }

    public function setInvoiceNetValue($invoiceNetValue)
    {
        $this->invoiceNetValue = $invoiceNetValue;
    }

    public function getInvoiceNetValue()
    {
        return $this->invoiceNetValue;
    }

    public function setPaymentDeadline($paymentDeadline)
    {
        $this->paymentDeadline = $paymentDeadline;
    }

    public function getPaymentDeadline()
    {
        return $this->paymentDeadline;
    }

    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;
    }

    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
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
