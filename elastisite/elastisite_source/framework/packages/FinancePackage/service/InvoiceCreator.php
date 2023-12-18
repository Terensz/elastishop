<?php
namespace framework\packages\FinancePackage\service;

use App;
use framework\component\helper\DateUtils;
use framework\component\parent\Service;
use framework\packages\FinancePackage\entity\InvoiceHeader;
use framework\packages\FinancePackage\entity\InvoiceItem;
use framework\packages\FinancePackage\repository\InvoiceHeaderRepository;
use framework\packages\PaymentPackage\service\GeneralPaymentService;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\repository\ProductRepository;

class InvoiceCreator extends Service
{
    const FORCE_MODIFY_EXISTING_INVOICE = true;

    public $taxOffice;

    public $invoiceXml;

    public ? InvoiceHeader $invoiceHeader = null;

    public ? InvoiceHeaderRepository $invoiceHeaderRepository = null;

    private static $taxOfficeConfigCache;

    public static function getTaxOfficeConfig()
    {
        if (self::$taxOfficeConfigCache) {
            return self::$taxOfficeConfigCache;
        }
        App::getContainer()->wireService('FinancePackage/service/VATProfileHandler');
        // dump(App::getContainer()->getConfig()->getProjectData('VATProfile'));
        $config = VATProfileHandler::getConfig(App::getContainer()->getConfig()->getProjectData('VATProfile'));
        self::$taxOfficeConfigCache = $config;

        return $config;
    }

/*
(11)[issuer.country] => HU
(12)[issuer.zipCode] => 1123
(13)[issuer.city] => Budapest
(14)[issuer.street] => Váci
(15)[issuer.streetSuffix] => körút
(16)[issuer.houseNumber] => 23.
(17)[issuer.staircase] => 2.
(18)[issuer.floor] => 4.
(19)[issuer.door] => 417.
*/
    public static function getConcatenatedAddress()
    {
        $config = self::getTaxOfficeConfig();

        return $config['issuer.zipCode'].' '.$config['issuer.city'].', '.$config['issuer.street'].' '.$config['issuer.streetSuffix'].' '.$config['issuer.houseNumber'].' '.$config['issuer.staircase'].' '.$config['issuer.floor'].' '.$config['issuer.door'];
    }

    public function __construct()
    {
        App::getContainer()->wireService('FinancePackage/entity/InvoiceHeader');
        App::getContainer()->setService('FinancePackage/repository/InvoiceHeaderRepository');
        $this->invoiceHeaderRepository = App::getContainer()->getService('InvoiceHeaderRepository');
    }

    /**
     * CREDIT NOTE. This is a full correction invoice.
    */
    public function createCreditNote(int $oldInvoiceHeaderId, $properties = [])
    {
        $oldInvoiceHeader = $this->invoiceHeaderRepository->find($oldInvoiceHeaderId);
        if (!$oldInvoiceHeader) {
            return false;
        }

        $alreadyCorrected = $this->invoiceHeaderRepository->findOneBy(['conditions' => [['key' => 'corrected_invoice_number', 'value' => $oldInvoiceHeader->getInvoiceNumber()]]]);

        if ($alreadyCorrected) {
            $this->invoiceHeader = $alreadyCorrected;
            return $this;
        }

        $newPropertyValues = [
            'correctedInvoiceNumber' => $oldInvoiceHeader->getInvoiceNumber()
        ];

        return $this->cloneInvoiceHeader($oldInvoiceHeader, 'creditNote', $newPropertyValues);
    }

    /**
     * Partial correction invoice.
     * @todo
    */
    public function createPartialCorrectionInvoice(int $oldInvoiceHeaderId, $properties = [])
    {
        $oldInvoiceHeader = $this->invoiceHeaderRepository->find($oldInvoiceHeaderId);
        if (!$oldInvoiceHeader) {
            return false;
        }

        $newPropertyValues = [
            'correctedInvoiceNumber' => $oldInvoiceHeader->getInvoiceNumber()
        ];

        return $this->cloneInvoiceHeader($oldInvoiceHeader, 'partialCorrection', $newPropertyValues);
    }

    public function cloneInvoiceHeader(InvoiceHeader $oldInvoiceHeader, $cloningReason = 'creditNote', $newPropertyValues = [])
    {
        $currentDateTime = $this->getCurrentTimestamp();
        $currentDate = $currentDateTime->format('Y-m-d H:i:s');
        if ($oldInvoiceHeader->getCorrectedInvoiceNumber() && $cloningReason == 'creditNote') {
            throw new \Exception('Cannot make creditNote of an already corrected invoice.');
        }
        $this->invoiceHeader = clone $oldInvoiceHeader;
        $this->invoiceHeader->setId(null);
        $this->invoiceHeader->setInvoiceType(InvoiceHeader::INVOICE_TYPE_CORRECTION);
        $this->invoiceHeader->setShipment(null);
        $this->invoiceHeader->setTaxOfficeTransactionId(null);
        $this->invoiceHeader->setTaxOfficeCommStatus(null);
        $this->invoiceHeader->setTaxOfficeErrorMessage(null);
        $this->invoiceHeader->setReportedAt(null);
        // $invoiceHeader->setCorrectedInvoiceNumber($oldInvoiceHeader->getInvoiceNumber());
        $invoiceNumberParts = $this->generateInvoiceNumber();
        $this->invoiceHeader->setInvoiceNumber($invoiceNumberParts['invoiceNumber']);
        $this->invoiceHeader->setYearOfIssue($invoiceNumberParts['yearOfIssue']);
        $this->invoiceHeader->setSequenceNumber($invoiceNumberParts['sequenceNumber']);

        foreach ($newPropertyValues as $property => $value) {
            $setter = 'set'.ucfirst($property);
            $this->invoiceHeader->$setter($value);
        }

        /**
         * CREDIT NOTE. Full correction
        */
        if ($cloningReason == 'creditNote') {
            $this->invoiceHeader->setTotalVat(0 - $this->invoiceHeader->getTotalVat());
            $this->invoiceHeader->setTotalNet(0 - $this->invoiceHeader->getTotalNet());
            $this->invoiceHeader->setDateOfIssue($currentDate);
            $this->invoiceHeader->setPaymentDeadline($currentDate);
            $this->invoiceHeader->setPaymentDate($currentDate);
            $this->invoiceHeader->setDeliveryDate($currentDate);
            $oldInvoiceItems = $this->invoiceHeader->getInvoiceItem();
            $this->invoiceHeader->setAllInvoiceItems([]);

            $orderedOldInvoiceItems = [];
            foreach ($oldInvoiceItems as $invoiceItem) {
                $orderedOldInvoiceItems[$invoiceItem->getLineIndex()] = $invoiceItem;
            }

            $newLineIndex = count($oldInvoiceItems);
            foreach ($orderedOldInvoiceItems as $invoiceItem) {
                $newLineIndex++;
                $invoiceItem->setId(null);
                $invoiceItem->setQuantity(0 - $invoiceItem->getQuantity());
                $invoiceItem->setReferencedLineIndex($invoiceItem->getLineIndex());
                $invoiceItem->setLineIndex($newLineIndex);
                $invoiceItem->setItemNet(0 - $invoiceItem->getItemNet());
                $invoiceItem->setItemVat(0 - $invoiceItem->getItemVat());
                $invoiceItem->setInvoiceHeader($this->invoiceHeader);
                $this->invoiceHeader->addInvoiceItem($invoiceItem);
            }
        }

        /**
         * Partial correction
         * @todo
        */
        if ($cloningReason == 'partialCorrection') {

        }

        // dump($this->invoiceHeader);exit;

        $this->invoiceHeader = $this->invoiceHeaderRepository->store($this->invoiceHeader);

        return $this;
    }

    public function createFromShipment(Shipment $shipment, $newPropertyValues = [])
    {
        $invoiceHeader = null;

        if ($shipment->getId()) {
            $invoiceHeader = $this->invoiceHeaderRepository->findOneBy(['conditions' => [['key' => 'shipment_id', 'value' => $shipment->getId()]]]);
        }

        $yearOfIssue = null;
        $sequenceNumber = null;
        $createNewInvoice = false;
        if (!$invoiceHeader) {
            $createNewInvoice = true;
            $invoiceNumberParts = $this->generateInvoiceNumber();
            $invoiceNumber = $invoiceNumberParts['invoiceNumber'];
            $yearOfIssue = $invoiceNumberParts['yearOfIssue'];
            $sequenceNumber = $invoiceNumberParts['sequenceNumber'];
            $invoiceHeader = new InvoiceHeader();
            $invoiceHeader->setInvoiceNumber($invoiceNumber);
        } 
        // else {
        //     $invoiceHeader = $invoiceHeader;
        // }

        return $this->fillInvoiceHeaderFromShipment($invoiceHeader, $createNewInvoice, $yearOfIssue, $sequenceNumber, $shipment, $newPropertyValues);
    }

    public function fillInvoiceHeaderFromShipment(InvoiceHeader $invoiceHeader, $createNewInvoice, $yearOfIssue, $sequenceNumber, Shipment $shipment = null, $newPropertyValues = [])
    {
        $this->invoiceHeader = $invoiceHeader;

        if ($createNewInvoice || self::FORCE_MODIFY_EXISTING_INVOICE) {
            App::getContainer()->wireService('PaymentPackage/service/GeneralPaymentService');

            $taxOfficeConfig = self::getTaxOfficeConfig();

            $this->invoiceHeader->setShipment($shipment);
            $this->invoiceHeader->setOrderNumber($shipment->getCode());
            $this->invoiceHeader->setInvoiceType(InvoiceHeader::INVOICE_TYPE_REGULAR);
            $this->invoiceHeader->setCurrency(GeneralPaymentService::getActiveCurrency());
            $this->invoiceHeader->setPaymentMethod($shipment->getSuccessfulPayment()->getPaymentMethod());
            if ($createNewInvoice) {
                $this->invoiceHeader->setYearOfIssue($yearOfIssue);
                $this->invoiceHeader->setSequenceNumber($sequenceNumber);
            }
            $this->invoiceHeader->setDateOfIssue(DateUtils::getCurrentDate('Y-m-d H:i:s'));
            $this->invoiceHeader->setIssuerName($taxOfficeConfig['issuer.name']);
            $this->invoiceHeader->setIssuerBankAccountNumber($taxOfficeConfig['issuer.bankAccountNumber']);
            $this->invoiceHeader->setIssuerCountry($taxOfficeConfig['issuer.country']);
            $this->invoiceHeader->setIssuerZipCode($taxOfficeConfig['issuer.zipCode']);
            $this->invoiceHeader->setIssuerCity($taxOfficeConfig['issuer.city']);
            $this->invoiceHeader->setIssuerStreet($taxOfficeConfig['issuer.street']);
            $this->invoiceHeader->setIssuerStreetSuffix($taxOfficeConfig['issuer.streetSuffix']);
            $this->invoiceHeader->setIssuerHouseNumber($taxOfficeConfig['issuer.houseNumber']);
            $this->invoiceHeader->setIssuerStaircase($taxOfficeConfig['issuer.staircase']);
            $this->invoiceHeader->setIssuerFloor($taxOfficeConfig['issuer.floor']);
            $this->invoiceHeader->setIssuerDoor($taxOfficeConfig['issuer.door']);

            if ($shipment->getOrganization()) {
                $this->invoiceHeader->setBuyerName($shipment->getOrganization()->getName());
                $this->invoiceHeader->setBuyerTaxId($shipment->getOrganization()->getTaxId());
                $this->invoiceHeader->setBuyerPersonType(InvoiceHeader::PERSON_TYPE_ORGANIZATION);
                $buyerAddress = $shipment->getOrganization()->getAddress();
            } else {
                $this->invoiceHeader->setBuyerName($shipment->getTemporaryAccount()->getTemporaryPerson()->getName());
                $this->invoiceHeader->setBuyerPersonType(InvoiceHeader::PERSON_TYPE_PRIVATE_PERSON);
                $buyerAddress = $shipment->getTemporaryAccount()->getTemporaryPerson()->getAddress();
            }

            $this->invoiceHeader->setBuyerCountry($buyerAddress->getCountry()->getAlphaTwo());
            $this->invoiceHeader->setBuyerZipCode($buyerAddress->getZipCode());
            $this->invoiceHeader->setBuyerCity($buyerAddress->getCity());
            $this->invoiceHeader->setBuyerStreet($buyerAddress->getStreet());
            $this->invoiceHeader->setBuyerStreetSuffix($buyerAddress->getStreetSuffix());
            $this->invoiceHeader->setBuyerHouseNumber($buyerAddress->getHouseNumber());
            $this->invoiceHeader->setBuyerStaircase($buyerAddress->getStaircase());
            $this->invoiceHeader->setBuyerFloor($buyerAddress->getFloor());
            $this->invoiceHeader->setBuyerDoor($buyerAddress->getDoor());

            $shipmentFinancialData = WebshopPriceService::getShipmentFinancialData($shipment);
            $this->invoiceHeader->setTotalVat($shipmentFinancialData['totalVat']);
            $this->invoiceHeader->setTotalNet($shipmentFinancialData['totalNet']);
            
            $estimatedCompletionDate = new \DateTime();
            $estimatedCompletionDate->modify('+3 day')->format('Y-m-d H:i:s');

            $this->invoiceHeader->setPaymentDeadline($estimatedCompletionDate);
            $this->invoiceHeader->setPaymentDate(new \DateTime());
            $this->invoiceHeader->setDeliveryDate($estimatedCompletionDate);
            $this->invoiceHeader->setTaxOfficeShortName(App::getContainer()->getConfig()->getGlobal('finance.taxOffice'));

            foreach ($newPropertyValues as $property => $value) {
                $setter = 'set'.ucfirst($property);
                $this->invoiceHeader->$setter($value);
            }

            if ($this->invoiceHeader->getInvoiceItem()) {
                $newInvoiceItems = [];
                foreach ($this->invoiceHeader->getInvoiceItem() as $nvoiceLoopItem) {
                    foreach ($shipmentFinancialData['orderedProducts'] as $unitData) {
                        if ($nvoiceLoopItem->getProductId() == $unitData['productId']) {
                            $invoiceItem = $this->fillInvoiceItem($nvoiceLoopItem, $unitData);
                            $invoiceItem->setInvoiceHeader($this->invoiceHeader);
                            $newInvoiceItems[] = $invoiceItem;
                            // $this->invoiceHeader->addInvoiceItem($invoiceItem);
                        }
                    }
                }
                $this->invoiceHeader->setAllInvoiceItems($newInvoiceItems);
            } else {
                $lineIndex = 0;
                foreach ($shipmentFinancialData['orderedProducts'] as $unitData) {
                    $lineIndex++;
                    // dump($unitData);
                    $invoiceItem = new InvoiceItem();
                    $invoiceItem = $this->fillInvoiceItem($invoiceItem, $unitData);
                    $invoiceItem->setInvoiceHeader($this->invoiceHeader);
                    $invoiceItem->setLineIndex($lineIndex);
                    $this->invoiceHeader->addInvoiceItem($invoiceItem);
                }
            }

            $this->invoiceHeader = $this->invoiceHeaderRepository->store($this->invoiceHeader);
        }

        // dump($this->invoiceHeader); exit;

        return $this;
    }

    public function fillInvoiceItem($invoiceItem, $unitData)
    {
        $invoiceItem->setProductId($unitData['productId']);
        $invoiceItem->setProductName($unitData['productName']);
        $invoiceItem->setQuantity($unitData['quantity']);
        $invoiceItem->setUnitOfMeasure($unitData['unitOfMeasure']);
        $invoiceItem->setUnitNet($unitData['unitNet']);
        $invoiceItem->setItemNet($unitData['itemNet']);
        $invoiceItem->setVatPercent($unitData['vatPercent']);
        $invoiceItem->setItemVat($unitData['itemVat']);

        return $invoiceItem;
    }

    public function generateInvoiceNumber()
    {
        $config = self::getTaxOfficeConfig();

        $lastInvoiceHeader = $this->findLastInvoiceHeader();
        if ($lastInvoiceHeader) {
            $yearOfIssue = $lastInvoiceHeader->getYearOfIssue();
            $sequenceNumber = (int)$lastInvoiceHeader->getSequenceNumber() + 1;
        } else {
            $yearOfIssue = DateUtils::getCurrentYear();
            $sequenceNumber = $config['invoice.startingSequence'];
        }

        $format = $config['invoice.numberFormat'];
        $invoiceNumber = str_replace('{year}', $yearOfIssue, $format);
        $invoiceNumber = str_replace('{sequence}', $sequenceNumber, $invoiceNumber);

        return [
            'yearOfIssue' => $yearOfIssue,
            'sequenceNumber' => $sequenceNumber,
            'invoiceNumber' => $invoiceNumber
        ];
    }

    public function findLastInvoiceHeader() : ? InvoiceHeader
    {
        $lastInvoiceHeader = $this->invoiceHeaderRepository->findOneBy([
            'conditions' => [
                ['key' => 'year_of_issue', 'value' => DateUtils::getCurrentYear()],
            ], 
            'orderBy' => [['field' => 'sequence_number', 'direction' => 'DESC']]
        ]);

        return $lastInvoiceHeader;
    }
}
