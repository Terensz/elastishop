<?php
namespace framework\packages\FinancePackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\packages\WebshopPackage\entity\Shipment;

class InvoiceHeader extends DbEntity
{
    const COMM_STATUS_OK = 'OK';

    const PERSON_TYPE_PRIVATE_PERSON = 'private_person';
    
    const PERSON_TYPE_ORGANIZATION = 'organization';
    
    const INVOICE_TYPES = [
        'proforma',
        'regular',
        'correction'
    ];

    const INVOICE_TYPE_PROFORMA = 'proforma';
    const INVOICE_TYPE_REGULAR = 'regular';
    const INVOICE_TYPE_CORRECTION = 'correction';

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `invoice_header` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `shipment_id` int(11) DEFAULT NULL,
        `order_number` varchar(100) DEFAULT NULL,
        `invoice_number` varchar(100) DEFAULT NULL,
        `corrected_invoice_number` varchar(100) DEFAULT NULL,
        `invoice_type` varchar(20) DEFAULT NULL,
        `currency` varchar(10) DEFAULT NULL,
        `payment_method` varchar(20) DEFAULT NULL,
        `year_of_issue` int(4) DEFAULT NULL,
        `sequence_number` int(10) DEFAULT NULL,
        `date_of_issue` datetime DEFAULT NULL, -- Kiallitas datuma
        -- issuer
        `issuer_name` varchar(200) DEFAULT NULL,
        `issuer_bank_account_number` varchar(50) DEFAULT NULL,
        `issuer_country` varchar(150) DEFAULT NULL,
        `issuer_zip_code` varchar(10) DEFAULT NULL,
        `issuer_city` varchar(100) DEFAULT NULL,
        `issuer_street` varchar(100) DEFAULT NULL,
        `issuer_street_suffix` varchar(30) DEFAULT NULL,
        `issuer_house_number` varchar(20) DEFAULT NULL,
        `issuer_staircase` varchar(20) DEFAULT NULL,
        `issuer_floor` varchar(20) DEFAULT NULL,
        `issuer_door` varchar(20) DEFAULT NULL,
        -- `issuer_utr` varchar(50) DEFAULT NULL, -- Company's tax number (Unique tax reference)
        `buyer_person_type` varchar(30) DEFAULT NULL,
        `buyer_name` varchar(200) DEFAULT NULL,
        `buyer_tax_id` varchar(200) DEFAULT NULL,
        `buyer_country` varchar(150) DEFAULT NULL,
        `buyer_zip_code` varchar(10) DEFAULT NULL,
        `buyer_city` varchar(100) DEFAULT NULL,
        `buyer_street` varchar(100) DEFAULT NULL,
        `buyer_street_suffix` varchar(30) DEFAULT NULL,
        `buyer_house_number` varchar(20) DEFAULT NULL,
        `buyer_staircase` varchar(20) DEFAULT NULL,
        `buyer_floor` varchar(20) DEFAULT NULL,
        `buyer_door` varchar(20) DEFAULT NULL,
        -- `buyer_utr` varchar(50) DEFAULT NULL, -- In Hungary this is required for inland buyer, just for companies
        `total_vat` int(11) DEFAULT NULL,
        `total_net` int(11) DEFAULT NULL,
        `payment_deadline` datetime DEFAULT NULL,
        `payment_date` datetime DEFAULT NULL,
        `delivery_date` datetime DEFAULT NULL,
        `tax_office_short_name` varchar(50) DEFAULT NULL,
        -- `tax_office_id` varchar(100) DEFAULT NULL,
        `tax_office_transaction_id` varchar(100) DEFAULT NULL,
        -- `tax_office_invoice_xml` text DEFAULT NULL,
        -- `tax_office_reply_xml` text DEFAULT NULL,
        `tax_office_comm_status` varchar(50) DEFAULT NULL,
        `tax_office_error_code` varchar(250) DEFAULT NULL,
        `tax_office_error_message` text DEFAULT NULL,
        `reported_at` datetime DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=70000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $website;
    protected $invoiceItem = array();
    protected $shipment;
    protected $orderNumber;
    protected $invoiceNumber;
    protected $correctedInvoiceNumber;
    protected $invoiceType;
    protected $currency;
    protected $paymentMethod;
    protected $yearOfIssue;
    protected $sequenceNumber;
    protected $dateOfIssue;

    protected $issuerName;
    protected $issuerBankAccountNumber;
    protected $issuerCountry;
    protected $issuerZipCode;
    protected $issuerCity;
    protected $issuerStreet;
    protected $issuerStreetSuffix;
    protected $issuerHouseNumber;
    protected $issuerStaircase;
    protected $issuerFloor;
    protected $issuerDoor;

    protected $buyerPersonType;
    protected $buyerName;
    protected $buyerTaxId;
    protected $buyerCountry;
    protected $buyerZipCode;
    protected $buyerCity;
    protected $buyerStreet;
    protected $buyerStreetSuffix;
    protected $buyerHouseNumber;
    protected $buyerStaircase;
    protected $buyerFloor;
    protected $buyerDoor;

    protected $totalVat;
    protected $totalNet;

    protected $paymentDeadline;
    protected $paymentDate;
    protected $deliveryDate;
    protected $taxOfficeShortName;
    // protected $taxOfficeId;
    protected $taxOfficeTransactionId;
    // protected $taxOfficeInvoiceXml;
    // protected $taxOfficeReplyXml;
    protected $taxOfficeCommStatus;
    protected $taxOfficeErrorCode;
    protected $taxOfficeErrorMessage;
    protected $reportedAt;
    protected $createdAt;
    // protected $status;

    public function __construct()
    {
        $this->website = App::getWebsite();
        $this->createdAt = $this->getCurrentTimestamp();
    }

    // public function getTotalGross() 
    // {
    //     // $totalNet = 0;
    //     $totalGross = 0;
    //     foreach ($this->invoiceItem as $invoiceItem) {
    //         $totalGross += $invoiceItem->getItemNet();
    //     }
    // }

    public function checkCorrectWebsite() 
    {
        return App::getWebsite() == $this->website ? true : false;
    }

    public function getBuyerAddress()
    {
        // return $this->buyerAddress ? $this->buyerAddress->__toString() : null;
        if (!$this->buyerCountry) {
            return '';
        }

        return $this->buyerZipCode.' '.$this->buyerCity.', '.$this->buyerStreet.' '.$this->buyerStreetSuffix.' '.$this->buyerHouseNumber;
        // .' '.$config['issuer.staircase'].' '.$config['issuer.floor'].' '.$config['issuer.door'];
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setAllInvoiceItems(array $invoiceItems)
    {
        $this->invoiceItem = $invoiceItems;
    }

    public function addInvoiceItem(InvoiceItem $invoiceItem)
    {
        $this->invoiceItem[] = $invoiceItem;
    }

    public function getInvoiceItem() : array
    {
        return $this->invoiceItem;
    }

    public function setShipment(Shipment $shipment = null)
    {
        $this->shipment = $shipment;
    }

    public function getShipment()
    {
        return $this->shipment;
    }

    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    public function setCorrectedInvoiceNumber($correctedInvoiceNumber)
    {
        $this->correctedInvoiceNumber = $correctedInvoiceNumber;
    }

    public function getCorrectedInvoiceNumber()
    {
        return $this->correctedInvoiceNumber;
    }

    public function setInvoiceType($invoiceType)
    {
        $this->invoiceType = $invoiceType;
    }

    public function getInvoiceType()
    {
        return $this->invoiceType;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setYearOfIssue($yearOfIssue)
    {
        $this->yearOfIssue = $yearOfIssue;
    }

    public function getYearOfIssue()
    {
        return $this->yearOfIssue;
    }

    public function setSequenceNumber($sequenceNumber)
    {
        $this->sequenceNumber = $sequenceNumber;
    }

    public function getSequenceNumber()
    {
        return $this->sequenceNumber;
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

    public function setIssuerBankAccountNumber($issuerBankAccountNumber)
    {
        $this->issuerBankAccountNumber = $issuerBankAccountNumber;
    }

    public function getIssuerBankAccountNumber()
    {
        return $this->issuerBankAccountNumber;
    }

    public function setIssuerCountry($issuerCountry)
    {
        $this->issuerCountry = $issuerCountry;
    }

    public function getIssuerCountry()
    {
        return $this->issuerCountry;
    }

    public function setIssuerZipCode($issuerZipCode)
    {
        $this->issuerZipCode = $issuerZipCode;
    }

    public function getIssuerZipCode()
    {
        return $this->issuerZipCode;
    }

    public function setIssuerCity($issuerCity)
    {
        $this->issuerCity = $issuerCity;
    }

    public function getIssuerCity()
    {
        return $this->issuerCity;
    }

    public function setIssuerStreet($issuerStreet)
    {
        $this->issuerStreet = $issuerStreet;
    }

    public function getIssuerStreet()
    {
        return $this->issuerStreet;
    }

    public function setIssuerStreetSuffix($issuerStreetSuffix)
    {
        $this->issuerStreetSuffix = $issuerStreetSuffix;
    }

    public function getIssuerStreetSuffix()
    {
        return $this->issuerStreetSuffix;
    }

    public function setIssuerHouseNumber($issuerHouseNumber)
    {
        $this->issuerHouseNumber = $issuerHouseNumber;
    }

    public function getIssuerHouseNumber()
    {
        return $this->issuerHouseNumber;
    }

    public function setIssuerStaircase($issuerStaircase)
    {
        $this->issuerStaircase = $issuerStaircase;
    }

    public function getIssuerStaircase()
    {
        return $this->issuerStaircase;
    }

    public function setIssuerFloor($issuerFloor)
    {
        $this->issuerFloor = $issuerFloor;
    }

    public function getIssuerFloor()
    {
        return $this->issuerFloor;
    }

    public function setIssuerDoor($issuerDoor)
    {
        $this->issuerDoor = $issuerDoor;
    }

    public function getIssuerDoor()
    {
        return $this->issuerDoor;
    }

    public function setBuyerPersonType($buyerPersonType)
    {
        $this->buyerPersonType = $buyerPersonType;
    }

    public function getBuyerPersonType()
    {
        return $this->buyerPersonType;
    }

    public function setBuyerName($buyerName)
    {
        $this->buyerName = $buyerName;
    }

    public function getBuyerName()
    {
        return $this->buyerName;
    }

    public function setBuyerTaxId($buyerTaxId)
    {
        $this->buyerTaxId = $buyerTaxId;
    }

    public function getBuyerTaxId()
    {
        return $this->buyerTaxId;
    }

    public function setBuyerCountry($buyerCountry)
    {
        $this->buyerCountry = $buyerCountry;
    }
    
    public function getBuyerCountry()
    {
        return $this->buyerCountry;
    }
    
    public function setBuyerZipCode($buyerZipCode)
    {
        $this->buyerZipCode = $buyerZipCode;
    }
    
    public function getBuyerZipCode()
    {
        return $this->buyerZipCode;
    }
    
    public function setBuyerCity($buyerCity)
    {
        $this->buyerCity = $buyerCity;
    }
    
    public function getBuyerCity()
    {
        return $this->buyerCity;
    }
    
    public function setBuyerStreet($buyerStreet)
    {
        $this->buyerStreet = $buyerStreet;
    }
    
    public function getBuyerStreet()
    {
        return $this->buyerStreet;
    }
    
    public function setBuyerStreetSuffix($buyerStreetSuffix)
    {
        $this->buyerStreetSuffix = $buyerStreetSuffix;
    }
    
    public function getBuyerStreetSuffix()
    {
        return $this->buyerStreetSuffix;
    }
    
    public function setBuyerHouseNumber($buyerHouseNumber)
    {
        $this->buyerHouseNumber = $buyerHouseNumber;
    }
    
    public function getBuyerHouseNumber()
    {
        return $this->buyerHouseNumber;
    }
    
    public function setBuyerStaircase($buyerStaircase)
    {
        $this->buyerStaircase = $buyerStaircase;
    }
    
    public function getBuyerStaircase()
    {
        return $this->buyerStaircase;
    }
    
    public function setBuyerFloor($buyerFloor)
    {
        $this->buyerFloor = $buyerFloor;
    }
    
    public function getBuyerFloor()
    {
        return $this->buyerFloor;
    }
    
    public function setBuyerDoor($buyerDoor)
    {
        $this->buyerDoor = $buyerDoor;
    }
    
    public function getBuyerDoor()
    {
        return $this->buyerDoor;
    }

    // public function setBuyerUtr($buyerUtr)
    // {
    //     $this->buyerUtr = $buyerUtr;
    // }

    // public function getBuyerUtr()
    // {
    //     return $this->buyerUtr;
    // }

    public function setTotalVat($totalVat)
    {
        $this->totalVat = $totalVat;
    }

    public function getTotalVat()
    {
        return $this->totalVat;
    }

    public function setTotalNet($totalNet)
    {
        $this->totalNet = $totalNet;
    }

    public function getTotalNet()
    {
        return $this->totalNet;
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

    public function getPaymentDate() : ? \DateTime
    {
        if (is_string($this->paymentDate)) {
            $this->paymentDate = new \DateTime($this->paymentDate);
        }

        return $this->paymentDate;
    }

    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;
    }

    public function getDeliveryDate() : ? \DateTime
    {
        if (is_string($this->deliveryDate)) {
            $this->deliveryDate = new \DateTime($this->deliveryDate);
        }

        return $this->deliveryDate;
    }

    public function setTaxOfficeShortName($taxOfficeShortName)
    {
        $this->taxOfficeShortName = $taxOfficeShortName;
    }

    public function getTaxOfficeShortName()
    {
        return $this->taxOfficeShortName;
    }

    // public function setTaxOfficeId($taxOfficeId)
    // {
    //     $this->taxOfficeId = $taxOfficeId;
    // }

    // public function getTaxOfficeId()
    // {
    //     return $this->taxOfficeId;
    // }

    public function setTaxOfficeTransactionId($taxOfficeTransactionId)
    {
        $this->taxOfficeTransactionId = $taxOfficeTransactionId;
    }

    public function getTaxOfficeTransactionId()
    {
        return $this->taxOfficeTransactionId;
    }

    // public function setTaxOfficeInvoiceXml($taxOfficeInvoiceXml)
    // {
    //     $this->taxOfficeInvoiceXml = $taxOfficeInvoiceXml;
    // }

    // public function getTaxOfficeInvoiceXml()
    // {
    //     return $this->taxOfficeInvoiceXml;
    // }

    // public function setTaxOfficeReplyXml($taxOfficeReplyXml)
    // {
    //     $this->taxOfficeReplyXml = $taxOfficeReplyXml;
    // }

    // public function getTaxOfficeReplyXml()
    // {
    //     return $this->taxOfficeReplyXml;
    // }

    public function setTaxOfficeCommStatus($taxOfficeCommStatus)
    {
        $this->taxOfficeCommStatus = $taxOfficeCommStatus;
    }

    public function getTaxOfficeCommStatus()
    {
        return $this->taxOfficeCommStatus;
    }

    public function setTaxOfficeErrorCode($taxOfficeErrorCode)
    {
        $this->taxOfficeErrorCode = $taxOfficeErrorCode;
    }

    public function getTaxOfficeErrorCode()
    {
        return $this->taxOfficeErrorCode;
    }

    public function setTaxOfficeErrorMessage($taxOfficeErrorMessage)
    {
        $this->taxOfficeErrorMessage = $taxOfficeErrorMessage;
    }

    public function getTaxOfficeErrorMessage()
    {
        return $this->taxOfficeErrorMessage;
    }

    public function setReportedAt($reportedAt)
    {
        $this->reportedAt = $reportedAt;
    }

    public function getReportedAt()
    {
        return $this->reportedAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    // public function setStatus($status)
    // {
    //     $this->status = $status;
    // }

    // public function getStatus()
    // {
    //     return $this->status;
    // }
}
