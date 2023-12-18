<?php
namespace framework\packages\FinancePackage\taxOffices\noVATDeclarationHU;
use framework\component\parent\Service;
use framework\packages\FinancePackage\service\InvoiceCreator;
use framework\packages\FinancePackage\taxOffices\noVATDeclarationHU\VatDeclarationHelper;

/*
Docs:
-----
https://onlineszamla.nav.gov.hu/dokumentaciok

completenessIndicator: Hivatkozott szamla (STORNO vagy ) XML-jeben: false, szamlabekuldes
*/
class TaxOffice extends Service
{
    const INVOICE_CREATION = false;

    public $vatProfileHandler;

    public $vatDeclarationHelper;

    public function __construct()
    {
        $this->getContainer()->wireService('FinancePackage/taxOffices/noVATDeclarationHU/VatDeclarationHelper');
    }

    public function init() : void
    {
        $this->vatProfileHandler->invoiceCreation = self::INVOICE_CREATION;
        $this->vatDeclarationHelper = new VatDeclarationHelper();
    }

    public function setAPIUrl() : void
    {
    }

    public function process(InvoiceCreator $invoiceCreator)
    {
        return $invoiceCreator;
    }

}
