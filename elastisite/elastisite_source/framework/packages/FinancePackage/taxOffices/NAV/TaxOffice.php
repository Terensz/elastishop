<?php
namespace framework\packages\FinancePackage\taxOffices\NAV;

use framework\component\helper\DateUtils;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\kernel\utility\FileHandler;
use framework\packages\BusinessPackage\service\CurrencyConverter;
use framework\packages\FinancePackage\entity\InvoiceHeader;
use framework\packages\FinancePackage\entity\InvoiceItem;
use framework\packages\FinancePackage\service\InvoiceCreator;
use framework\packages\FinancePackage\service\InvoiceService;
use framework\packages\FinancePackage\taxOffices\NAV\VatDeclarationHelper;

/*
Docs:
-----
https://onlineszamla.nav.gov.hu/dokumentaciok

completenessIndicator: Hivatkozott szamla (STORNO vagy ) XML-jeben: false, szamlabekuldes
*/
class TaxOffice extends Service
{
    const INVOICE_CREATION = true;
    const COMM_STATUS_CONVERSIONS = [
        'OK' => 'OK'
    ];
    const PERSON_TYPE_PRIVATE_PERSON = 'PRIVATE_PERSON';
    const PERSON_TYPE_INLAND_VAT_SUBJECT = 'DOMESTIC';
    const PERSON_TYPE_OTHER_NON_VAT_SUBJECT = 'OTHER';

    public $vatProfileHandler;

    public $vatDeclarationHelper;

    public function __construct()
    {
        $this->getContainer()->wireService('FinancePackage/taxOffices/NAV/vendor/NavOnlineInvoice/RequestIdGeneratorInterface');
        $this->getContainer()->wireService('FinancePackage/taxOffices/NAV/vendor/NavOnlineInvoice/BaseRequestXml');
        $this->getContainer()->wireService('FinancePackage/taxOffices/NAV/vendor/NavOnlineInvoice/ManageInvoiceRequestXml');
        $this->getContainer()->wireServiceDir('FinancePackage/taxOffices/NAV/vendor/NavOnlineInvoice', false);
        $this->getContainer()->wireService('FinancePackage/taxOffices/NAV/VatDeclarationHelper');
    }

    public static function convertCommStatus($received)
    {
        if (isset(self::COMM_STATUS_CONVERSIONS[$received])) {
            return self::COMM_STATUS_CONVERSIONS[$received];
        }

        return $received;
    }

    // public function createReporter()
    // {
    //     // $config = $this->vatProfileHandler::getConfig($this->vatProfileHandler->vatProfileName);
    //     $navOnlineConfig = new NavOnlineConfig($this->APIUrl, $this->APIUserData, $this->APISoftwareData);
    //     $this->reporter = new NavOnlineReporter($navOnlineConfig);
    // }

    public function init() : void
    {
        $this->vatProfileHandler->invoiceCreation = self::INVOICE_CREATION;
        $this->vatDeclarationHelper = new VatDeclarationHelper();
        $this->setAPIUrl();
        $this->setAPIUserData();
        $this->setAPISoftwareData();
        // $this->vatDeclarationHelper->invoiceXml = '';
        $this->vatDeclarationHelper->createReporter();
    }

    public function setAPIUrl() : void
    {
        // $config = $this->vatProfileHandler::getConfig($this->vatProfileHandler->vatProfileName);
        $this->vatDeclarationHelper->APIUrl = $this->vatProfileHandler->serverConfig['APIUrlBase'];
    }

    public function setAPIUserData() : void
    {
        $config = $this->vatProfileHandler::getConfig($this->vatProfileHandler->vatProfileName);
        $this->vatDeclarationHelper->APIUserData = [
            "login" => $config['user.username'],
            "password" => $config['user.password'],
            "taxNumber" => $config['user.shortTaxNumber'],
            "signKey" => $config['user.signKey'],
            "exchangeKey" => $config['user.exchangeKey']
        ];
    }

    public function setAPISoftwareData() : void
    {
        $config = $this->vatProfileHandler::getConfig($this->vatProfileHandler->vatProfileName);
        $this->vatDeclarationHelper->APISoftwareData = [
            'softwareId' => $config['software.id'],
            'softwareName' => $config['software.name'],
            'softwareOperation' => $config['software.operation'],
            'softwareMainVersion' => $config['software.mainVersion'],
            'softwareDevName' => $config['software.devName'],
            'softwareDevContact' => $config['software.devContact'],
            'softwareDevCountryCode' => $config['software.devCountryCode'],
            'softwareDevTaxNumber' => $config['software.devTaxNumber']
        ];
    }

    public function process(InvoiceCreator $invoiceCreator)
    {
        $invoiceNumber = $invoiceCreator->invoiceHeader->getInvoiceNumber();
        $invoiceCreator->taxOffice = $this;
        // $invoiceReportState = $this->getInvoiceReportState($invoiceCreator);
        // if (!$invoiceReportState) {
        //     $this->vatDeclarationHelper->invoiceXml = $this->createInvoiceXMLObject($invoiceCreator);
        // }
        $invoiceCreator = $this->createInvoiceXMLObject($invoiceCreator);
        // $xml = $invoiceCreator->invoiceXml;
        // $this->vatDeclarationHelper->invoiceXml = $xml;
        $invoiceCreator = $this->vatDeclarationHelper->handleInvoice($invoiceCreator);
        // dump($invoiceCreator);exit;

        // dump($this->vatDeclarationHelper->invoiceXml);
        // $created = $this->vatDeclarationHelper->createInvoice();
        
        // dump($created);
        // dump($this->vatDeclarationHelper);exit;
        // dump($this->vatDeclarationHelper);exit;
        return $invoiceCreator;
    }

    // public function getInvoiceReportState(InvoiceCreator $invoiceCreator) : ? string
    // {
    //     $taxOfficeCommStatus = $invoiceCreator->invoiceHeader->getTaxOfficeCommStatus();

    //     return $taxOfficeCommStatus;
    // }

    public function createInvoiceXMLObject(InvoiceCreator $invoiceCreator)
    {
        $this->wireService('BusinessPackage/service/CurrencyConverter');
        $this->wireService('FinancePackage/entity/InvoiceHeader');
        $this->wireService('FinancePackage/entity/InvoiceItem');
        $this->wireService('FinancePackage/service/InvoiceService');

        $config = $this->vatProfileHandler::getConfig($this->vatProfileHandler->vatProfileName);
        // $path = FileHandler::completePath('framework/packages/FinancePackage/taxOffices/NAV/view/XMLContent/inlandSimpleInvoice.php', 'source');
        // $xml = file_get_contents($path);
        // dump();

        $issuerTaxIdParts = explode('-', $config['issuer.taxId']);
        $issuerTaxpayerId = '';
        $issuerVatCode = '';
        $issuerCountyCode = '';
        if (count($issuerTaxIdParts) == 3) {
            $issuerTaxpayerId = $issuerTaxIdParts[0];
            $issuerVatCode = $issuerTaxIdParts[1];
            $issuerCountyCode = $issuerTaxIdParts[2];
        }

        // dump('invoiceTaxData:');
        $invoiceTaxData = InvoiceService::getInvoiceTaxData($invoiceCreator->invoiceHeader->getInvoiceItem(), 2, '.', '', [InvoiceItem::UNIT_OF_MEASURE_PIECE => 'PIECE']);
        // dump($invoiceTaxData);exit;
        // $invoiceItemsTaxData = $invoiceTaxData['invoiceItemsTaxData'];

        // dump($invoiceItems);exit;
        $vatSummary = [];
        foreach ($invoiceTaxData['invoiceItemsTaxData'] as $invoiceItem) {
            // dump($invoiceItem);
            // dump($invoiceItem['vatFraction']);
            $key = (string)$invoiceItem['formattedVatFraction'];
            if (!isset($vatSummary[$key])) {
                $vatSummary[$key]['fractionNet'] = $invoiceItem['itemNet'];
                $vatSummary[$key]['fractionVat'] = $invoiceItem['itemVat'];
                $vatSummary[$key]['fractionGross'] = $invoiceItem['itemGross'];
            } else {
                $vatSummary[$key]['fractionNet'] += $invoiceItem['itemNet'];
                $vatSummary[$key]['fractionVat'] += $invoiceItem['itemVat'];
                $vatSummary[$key]['fractionGross'] += $invoiceItem['itemGross'];
            }
        }

        $formattedVatSummary = [];
        foreach ($vatSummary as $vatSummaryKey => $vatSummaryRow) {
            // dump($vatSummaryRow);
            $formattedVatSummary[$vatSummaryKey]['vat'] = $vatSummaryKey;
            $formattedVatSummary[$vatSummaryKey]['fractionNet'] = StringHelper::formatNumber($vatSummaryRow['fractionNet']);
            $formattedVatSummary[$vatSummaryKey]['fractionVat'] = StringHelper::formatNumber($vatSummaryRow['fractionVat']);
            $formattedVatSummary[$vatSummaryKey]['fractionGross'] = StringHelper::formatNumber($vatSummaryRow['fractionGross']);
        }
        // exit;
        // dump($formattedVatSummary);//exit;

        $buyerPersonType = $invoiceCreator->invoiceHeader->getBuyerPersonType();
        $customerVatStatus = $buyerPersonType == InvoiceHeader::PERSON_TYPE_PRIVATE_PERSON 
            ? self::PERSON_TYPE_PRIVATE_PERSON 
            : ($buyerPersonType == InvoiceHeader::PERSON_TYPE_ORGANIZATION ? self::PERSON_TYPE_INLAND_VAT_SUBJECT : self::PERSON_TYPE_OTHER_NON_VAT_SUBJECT);

        $buyerTaxIdParts = explode('-', $invoiceCreator->invoiceHeader->getBuyerTaxId());
        $buyerTaxpayerId = '';
        $buyerVatCode = '';
        $buyerCountyCode = '';
        if (count($buyerTaxIdParts) == 3) {
            $buyerTaxpayerId = $buyerTaxIdParts[0];
            $buyerVatCode = $buyerTaxIdParts[1];
            $buyerCountyCode = $buyerTaxIdParts[2];
        }

        $xmlString = self::wrapXML($this->renderView('framework/packages/FinancePackage/taxOffices/NAV/view/XMLContent/inlandInvoice.php', [
            'invoiceNumber' => $invoiceCreator->invoiceHeader->getInvoiceNumber(),
            'correctedInvoiceNumber' => $invoiceCreator->invoiceHeader->getCorrectedInvoiceNumber(),
            'dateOfIssue' => self::formatDate($invoiceCreator->invoiceHeader->getDateOfIssue()),
            'completenessIndicator' => 'false',

            'issuerTaxpayerId' => $issuerTaxpayerId,
            'issuerVatCode' => $issuerVatCode,
            'issuerCountyCode' => $issuerCountyCode,

            'issuerName' => $invoiceCreator->invoiceHeader->getIssuerName(),
            'issuerBankAccountNumber' => $invoiceCreator->invoiceHeader->getIssuerBankAccountNumber(),
            'issuerCountry' => $invoiceCreator->invoiceHeader->getIssuerCountry(),
            'issuerZipCode' => $invoiceCreator->invoiceHeader->getIssuerZipCode(),
            'issuerCity' => $invoiceCreator->invoiceHeader->getIssuerCity(),
            'issuerStreet' => $invoiceCreator->invoiceHeader->getIssuerStreet(),
            'issuerStreetSuffix' => $invoiceCreator->invoiceHeader->getIssuerStreetSuffix(),
            'issuerHouseNumber' => $invoiceCreator->invoiceHeader->getIssuerHouseNumber(),

            'buyerTaxpayerId' => $buyerTaxpayerId,
            'buyerVatCode' => $buyerVatCode,
            'buyerCountyCode' => $buyerCountyCode,

            'customerVatDataRequired' => $customerVatStatus == self::PERSON_TYPE_PRIVATE_PERSON ? false : true,
            'customerVatStatus' => $customerVatStatus,
            'buyerName' => $invoiceCreator->invoiceHeader->getBuyerName(),
            'buyerCountry' => $invoiceCreator->invoiceHeader->getBuyerCountry(),
            'buyerZipCode' => $invoiceCreator->invoiceHeader->getBuyerZipCode(),
            'buyerCity' => $invoiceCreator->invoiceHeader->getBuyerCity(),
            'buyerStreet' => $invoiceCreator->invoiceHeader->getBuyerStreet(),
            'buyerStreetSuffix' => $invoiceCreator->invoiceHeader->getBuyerStreetSuffix(),
            'buyerHouseNumber' => $invoiceCreator->invoiceHeader->getBuyerHouseNumber(),

            'deliveryDate' => $invoiceCreator->invoiceHeader->getDeliveryDate()->format('Y-m-d'),
            'paymentDate' => $invoiceCreator->invoiceHeader->getPaymentDate()->format('Y-m-d'),
            'currency' => $invoiceCreator->invoiceHeader->getCurrency(),
            'currencyExchangeRate' => CurrencyConverter::getRate('HUF', $invoiceCreator->invoiceHeader->getCurrency()),
            'invoiceAppearance' => 'ELECTRONIC',
            'orderNumber' => $invoiceCreator->invoiceHeader->getOrderNumber(),

            'invoiceItemsTaxData' => $invoiceTaxData['invoiceItemsTaxData'],
            'vatSummary' => $formattedVatSummary,
            'formattedTotalNet' => $invoiceTaxData['formattedTotalNet'],
            'formattedTotalVat' => $invoiceTaxData['formattedTotalVat'],
            'formattedTotalGross' => $invoiceTaxData['formattedTotalGross'],
            // 'formattedTotalNet' => StringHelper::formatNumber($invoiceTaxData['totalNet']),
            // 'formattedTotalVat' => StringHelper::formatNumber($invoiceTaxData['totalVat']),
            // 'formattedTotalGross' => StringHelper::formatNumber($invoiceTaxData['totalGross'])
        ]));
        // echo '<pre>';
        // echo htmlentities($xmlString);exit;
        $XMLObject = simplexml_load_string($xmlString, \SimpleXMLElement::class, LIBXML_NOCDATA);
        // dump($XMLObject); exit;
        $invoiceCreator->invoiceXml = $XMLObject;

        return $invoiceCreator;
    }

    // public static function formatSumOfMoney($number)
    // {
    //     return number_format((float)$number, 2, '.', '');
    // }

    public static function formatDate($dateInput)
    {
        $date = null;
        if (is_string($dateInput)) {
            $date = new \DateTime($dateInput);
        }

        return $date->format('Y-m-d');
    }

    public static function wrapXML($xml)
    {
        $encodingPos = strpos($xml, 'encoding="');
        if ($encodingPos === false) {
            return '<?xml version="1.0" encoding="UTF-8"?>
'.$xml;
        } else {
            return $xml;
        }
    }

    // public function tokenExchange()
    // {
    //     $this->wireService('ToolPackage/service/CurlApiCaller');
    //     $curlApiCaller = new CurlApiCaller();
    //     // $curlApiCaller->data = $this->getPreparePaymentData();
    //     $curlApiCaller->call($this->vatProfileHandler->serverConfig['APIUrlBase'] . $this->vatProfileHandler->serverConfig['APIRoutes']['tokenExchange']);
    //     dump($curlApiCaller);
    // }
}
