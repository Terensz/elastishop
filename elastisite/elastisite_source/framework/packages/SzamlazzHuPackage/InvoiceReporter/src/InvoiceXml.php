<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Trianity\SzamlazzHu\DataTransferObjects\InvoiceXmlData;
use Trianity\SzamlazzHu\Documents\InvoiceXmlBuilder;
use Trianity\SzamlazzHu\Exceptions\InvalidInvoiceXmlSettings;
use Trianity\SzamlazzHu\Utility\Rules;

class InvoiceXml
{
    /**
     * @var array<string>
     */
    protected array $settings;

    protected bool $validated;

    protected bool $debug;

    /**
     * @param  array<string>  $settings
     */
    public function __construct(
        array $settings,
        bool $debug = false
    ) {
        $this->settings = $settings;
        $this->debug = $debug;
        $this->validated = $this->validateData();
    }

    /**
     * Create and send XML to SzamlaAgent to query PDF Invoice
     */
    public function agentApi(InvoiceXmlBuilder $invoiceXmlBuilder): InvoiceXmlData
    {
        return $invoiceXmlBuilder
            ->settings($this->settings, $this->debug)
            ->generateXml()
            ->saveXml()
            ->createRequest()
            ->parseRequest();
    }

    protected function validateData(): bool
    {
        $this->validateSettings();

        return true;
    }

    protected function validateSettings(): bool
    {
        $test = config('szamlazzhu.test', false);
        $config = config('szamlazzhu.client.credentials.live');
        if ($test) {
            $config = config('szamlazzhu.client.credentials.test');
        }
        $this->settings = [...$config, ...$this->settings];
        $validator = Validator::make($this->settings, Rules::invoiceXmlSettings());
        if ($validator->fails()) {
            $failedRules = $validator->failed();
            Log::error('Failed Rules: '.print_r($failedRules, true));
            throw new InvalidInvoiceXmlSettings($validator);
        }

        return true;
    }
}
