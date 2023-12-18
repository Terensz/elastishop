<?php
namespace framework\packages\FinancePackage\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\WebshopPackage\repository\ShipmentRepository;

class InvoiceService extends Service
{
    public static function createAndReportInvoice($shipmentId)
    {
        App::getContainer()->wireService('WebshopPackage/repository/ShipmentRepository');
        $shipmentRepo = new ShipmentRepository();

        $shipment = $shipmentRepo->find($shipmentId);
        if (!$shipment) {
            return false;
        }

        App::getContainer()->wireService('FinancePackage/service/InvoiceCreator');
        $invoiceCreator = new InvoiceCreator();
        $invoiceCreator = $invoiceCreator->createFromShipment($shipment);
        $invoiceCreator = self::reportInvoice($invoiceCreator);

        return $invoiceCreator;
    }

    public static function createAndReportCreditNote($invoiceHeaderId)
    {
        App::getContainer()->wireService('FinancePackage/service/InvoiceCreator');
        $invoiceCreator = new InvoiceCreator();
        $invoiceCreator = $invoiceCreator->createCreditNote($invoiceHeaderId);
        $invoiceCreator = self::reportInvoice($invoiceCreator);

        return $invoiceCreator;
    }

    public static function reportInvoice(InvoiceCreator $invoiceCreator)
    {
        APP::getContainer()->wireService('FinancePackage/service/VATProfileHandler');
        $vatProfileHandler = new VATProfileHandler(App::getContainer()->getConfig()->getProjectData('VATProfile'));
        $invoiceCreator = $vatProfileHandler->process($invoiceCreator);

        return $invoiceCreator;
    }

    public static function getInvoiceHeader($invoiceHeaderId)
    {
        App::getContainer()->setService('FinancePackage/repository/InvoiceHeaderRepository');
        $invoiceHeaderRepo = App::getContainer()->getService('InvoiceHeaderRepository');
        $invoiceHeader = $invoiceHeaderRepo->findOneBy(['conditions' => [
            ['key' => 'id', 'value' => $invoiceHeaderId],
            ['key' => 'website', 'value' => App::getWebsite()],
        ]]);

        if (!$invoiceHeader) {
            throw new \Exception('Not existing invoice');
        }

        return $invoiceHeader ? : null;
    }

    public static function getVatDeclarationHelper()
    {
        APP::getContainer()->wireService('FinancePackage/service/VATProfileHandler');
        $vatProfileHandler = new VATProfileHandler(App::getContainer()->getConfig()->getProjectData('VATProfile'));

        return $vatProfileHandler->taxOffice->vatDeclarationHelper;
    }

    public static function getVatDeclarationData($invoiceHeaderId)
    {
        $invoiceHeader = self::getInvoiceHeader($invoiceHeaderId);
        $vatDeclarationHelper = self::getVatDeclarationHelper();

        return $vatDeclarationHelper->queryInvoice($invoiceHeader->getInvoiceNumber());
        // dump($alma1);
        // dump($alma2);
        // dump($vatDeclarationHelper);exit;
    }

    public static function createInvoiceView($invoiceHeaderId)
    {
        App::getContainer()->wireService('FinancePackage/service/InvoiceCreator');
        $taxOfficeConfig = InvoiceCreator::getTaxOfficeConfig();

        // dump($taxOfficeConfig);exit;
        // App::getContainer()->setService('FinancePackage/repository/InvoiceHeaderRepository');
        // $invoiceHeaderRepo = App::getContainer()->getService('InvoiceHeaderRepository');
        // $invoiceHeader = $invoiceHeaderRepo->findOneBy(['conditions' => [['key' => 'invoice_number', 'value' => $invoiceNumber]]]);
        $invoiceHeader = self::getInvoiceHeader($invoiceHeaderId);
        $invoiceTaxData = self::getInvoiceTaxData($invoiceHeader->getInvoiceItem(), 0, ',', ' ');
        // dump($invoiceTaxData);exit;

        $viewPath = 'framework/packages/FinancePackage/view/invoice/body.php';
        $response = App::renderView($viewPath, [
            'viewSpaceHeight' => '20px;',
            'httpDomain' => App::getContainer()->getUrl()->getHttpDomain(),
            'issuerName' => App::getContainer()->getCompanyData('name'),
            'invoiceId' => $invoiceHeaderId,
            'invoiceHeader' => $invoiceHeader,
            'taxOfficeConfig' => $taxOfficeConfig,
            'issuerConcatenatedAddress' => InvoiceCreator::getConcatenatedAddress(),
            'invoiceTaxData' => $invoiceTaxData,
            'currency' => $invoiceHeader->getCurrency()
            // 'form' => $form,
            // 'contentText' => $contentText,
            // 'container' => $this->getContainer(),
            // 'uniqueId' => $uniqueId
        ]);

        // echo '<pre>';
        // echo $response;exit;

        return $response;
    }

    public static function getInvoiceTaxData($invoiceItems, $decimals = 2, $decimalSeparator = '.', $thousandsSeparator = '', $unitOfMeasureConversions = [])
    {
        // if (!$thousandsSeparator) {
        //     $thousandsSeparator = '';
        // }
        // dump($thousandsSeparator);
        $invoiceItemsTaxData = [];
        $totalNet = 0;
        $totalVat = 0;
        $totalGross = 0;
        foreach ($invoiceItems as $invoiceItem) {
            // dump($invoiceItem);
            $unitNet = $invoiceItem->getUnitNet();
            $itemNet = $invoiceItem->getItemNet();
            $itemVat = $invoiceItem->getItemVat();
            $itemGross = $itemNet + $itemVat;
            $totalNet += $itemNet;
            $totalVat += $itemVat;
            $totalGross += $itemGross;
            // $vatFraction = 1 - (1 / (1 + ($invoiceItem->getVatPercent() / 100)));
            $vatFraction = $invoiceItem->getVatPercent() / 100;
            $unitOfMeasure = $invoiceItem->getUnitOfMeasure();
            foreach ($unitOfMeasureConversions as $unitOfMeasureKey => $unitOfMeasureConversionDesiredValue) {
                if ($unitOfMeasure == $unitOfMeasureKey) {
                    $unitOfMeasure = $unitOfMeasureConversionDesiredValue;
                }
            }
            $invoiceItemsTaxData[] = [
                'lineIndex' => $invoiceItem->getLineIndex(),
                'referencedLineIndex' => $invoiceItem->getReferencedLineIndex(),
                'productName' => $invoiceItem->getProductName(),
                'quantity' => $invoiceItem->getQuantity(),
                'unitOfMeasure' => $unitOfMeasure,
                'unitNet' => $unitNet,
                'formattedUnitNet' => StringHelper::formatNumber($unitNet, $decimals, $decimalSeparator, $thousandsSeparator),
                'itemNet' => $itemNet,
                'formattedItemNet' => StringHelper::formatNumber($itemNet, $decimals, $decimalSeparator, $thousandsSeparator),
                'itemGross' => $itemGross,
                'formattedItemGross' => StringHelper::formatNumber($itemGross, $decimals, $decimalSeparator, $thousandsSeparator),
                'vatPercent' => $invoiceItem->getVatPercent(),
                'vatFraction' => $vatFraction,
                'formattedVatFraction' => (string)str_replace(',', '.', $vatFraction),
                'itemVat' => $itemVat,
                'formattedItemVat' => StringHelper::formatNumber($itemVat, $decimals, $decimalSeparator, $thousandsSeparator)
            ];
        }

        return [
            'invoiceItemsTaxData' => $invoiceItemsTaxData,
            'totalNet' => $totalNet,
            'totalVat' => $totalVat,
            'totalGross' => $totalGross,
            'formattedTotalNet' => StringHelper::formatNumber($totalNet, $decimals, $decimalSeparator, $thousandsSeparator),
            'formattedTotalVat' => StringHelper::formatNumber($totalVat, $decimals, $decimalSeparator, $thousandsSeparator),
            'formattedTotalGross' => StringHelper::formatNumber($totalGross, $decimals, $decimalSeparator, $thousandsSeparator)
        ];
    }
}
