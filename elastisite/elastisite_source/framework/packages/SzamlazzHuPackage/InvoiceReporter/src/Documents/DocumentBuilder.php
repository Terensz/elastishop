<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents;

use Trianity\SzamlazzHu\DataTransferObjects\InvoiceData;
use Trianity\SzamlazzHu\DataTransferObjects\InvoicePdfData;
use Trianity\SzamlazzHu\DataTransferObjects\InvoiceXmlData;
use Trianity\SzamlazzHu\DataTransferObjects\TaxpayerData;

/**
 * The Abstract Factory interface declares creation methods for each distinct
 * document element type.
 */
interface DocumentBuilder
{
    /**
     * @param  array<string>  $settings
     */
    public function settings(array $settings, bool $debug = false): DocumentBuilder;

    /**
     * @param  array<string>  $header
     */
    public function header(array $header): DocumentBuilder;

    /**
     * @param  array<string>  $seller
     */
    public function seller(array $seller): DocumentBuilder;

    /**
     * @param  array<string>  $buyer
     */
    public function buyer(array $buyer): DocumentBuilder;

    /**
     * @param  array<string>  $waybill
     */
    public function waybill(array $waybill): DocumentBuilder;

    /**
     * @param  array<int, array<string>>  $items
     */
    public function items(array $items): DocumentBuilder;

    public function generateXml(): DocumentBuilder;

    public function saveXml(): DocumentBuilder;

    public function createRequest(): DocumentBuilder;

    public function parseRequest(): InvoiceData|InvoicePdfData|InvoiceXmlData|TaxpayerData;

    public function getDocumentType(): string;
}
