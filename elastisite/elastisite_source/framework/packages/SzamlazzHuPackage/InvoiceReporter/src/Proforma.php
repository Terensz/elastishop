<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu;

use Trianity\SzamlazzHu\DataTransferObjects\InvoiceData;
use Trianity\SzamlazzHu\Documents\InvoiceBuilder;
use Trianity\SzamlazzHu\Utility\ProformaValidator;

class Proforma
{
    /**
     * @var array<string, string|null>
     */
    protected array $settings;

    /**
     * @var array<string, string>
     */
    protected array $header;

    /**
     * @var array<string>
     */
    protected array $seller;

    /**
     * @var array<string, string|null>
     */
    protected array $buyer;

    /**
     * @var array<string>
     */
    protected array $waybill;

    /**
     * @var array<int, array<string, string>>
     */
    protected array $items;

    protected bool $validated;

    protected bool $debug;

    protected ProformaValidator $validator;

    /**
     * @param  array<string, string>  $header
     * @param  array<string, string|null>  $buyer
     * @param  array<int, array<string, string>>  $items
     * @param  array<string, string|null>  $settings
     * @param  array<string>  $seller
     * @param  array<string>  $waybill
     */
    public function __construct(
        array $header = [],
        array $buyer = [],
        array $items = [],
        array $settings = [],
        array $seller = [],
        array $waybill = [],
        bool $debug = false
    ) {
        $this->settings = $settings;
        $this->header = $header;
        $this->seller = $seller;
        $this->buyer = $buyer;
        $this->items = $items;
        $this->waybill = $waybill;
        $this->debug = $debug;

        $this->validator = new ProformaValidator();

        $this->validated = $this->validateData();
    }

    public function validateData(): bool
    {
        $this->validator->validateSettings($this);
        $this->validator->validateHeader($this);
        $this->validator->validateSeller($this);
        $this->validator->validateBuyer($this);
        $this->validator->validateItems($this);

        return true;
    }

    /**
     * Create and send Proforma XML to SzamlaAgent
     */
    public function agentApi(InvoiceBuilder $proformaBuilder): InvoiceData
    {
        return $proformaBuilder
            ->settings($this->settings, $this->debug)
            ->header($this->header)
            ->seller($this->seller)
            ->buyer($this->buyer)
            ->waybill($this->waybill)
            ->items($this->items)
            ->generateXml()
            ->saveXml()
            ->createRequest()
            ->parseRequest();
    }

    /**
     * @return array<string, string>
     */
    public function readSettings(): array
    {
        return $this->settings;
    }

    /**
     * @return array<string, string>
     */
    public function readHeader(): array
    {
        return $this->header;
    }

    /**
     * @return array<string, string>
     */
    public function readSeller(): array
    {
        return $this->seller;
    }

    /**
     * @return array<string, string>
     */
    public function readBuyer(): array
    {
        return $this->buyer;
    }

    /**
     * @return array<string, string>
     */
    public function readWaybill(): array
    {
        return $this->waybill;
    }

    /**
     * @return array<int, array<string>>
     */
    public function readItems(): array
    {
        return $this->items;
    }

    /**
     * @param  array<string, string>  $settings
     */
    public function writeSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * @param  array<string, string>  $header
     */
    public function writeHeader(array $header): void
    {
        $this->header = $header;
    }

    /**
     * @param  array<string, string>  $seller
     */
    public function writeSeller(array $seller): void
    {
        $this->seller = $seller;
    }

    /**
     * @param  array<string, string>  $buyer
     */
    public function writeBuyer(array $buyer): void
    {
        $this->buyer = $buyer;
    }

    /**
     * @param  array<string, string>  $waybill
     */
    public function writeWaybill(array $waybill): void
    {
        $this->waybill = $waybill;
    }

    /**
     * @param  array<int, array<string>>  $items
     */
    public function writeItems(array $items): void
    {
        $this->items = $items;
    }
}
