<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Trianity\SzamlazzHu\DataTransferObjects\TaxpayerData;
use Trianity\SzamlazzHu\Documents\TaxpayerBuilder;
use Trianity\SzamlazzHu\Exceptions\InvalidTaxpayerHeader;
use Trianity\SzamlazzHu\Exceptions\InvalidTaxpayerSettings;
use Trianity\SzamlazzHu\Utility\Rules;

class Taxpayer
{
    /**
     * @var array<string>
     */
    protected array $settings;

    /**
     * @var array<string>
     */
    protected array $header;

    protected bool $validated;

    protected bool $debug;

    /**
     * @param  array<string>  $settings
     * @param  array<string>  $header
     */
    public function __construct(
        array $header,
        array $settings = [],
        bool $debug = false
    ) {
        $this->header = $header;
        $this->settings = $settings;
        $this->debug = $debug;

        $this->validated = $this->validateData();
    }

    public function validateData(): bool
    {
        if (strlen($this->header['taxpayer_id_base']) > 8) {
            $this->header['taxpayer_id_base'] = substr($this->header['taxpayer_id_base'], 0, 8);
        }
        $this->validateSettings();
        $this->validateHeader();

        return true;
    }

    /**
     * Create and send Proforma XML to SzamlaAgent
     */
    public function agentApi(TaxpayerBuilder $taxpayerBuilder): TaxpayerData
    {
        return $taxpayerBuilder
            ->settings($this->settings, $this->debug)
            ->header($this->header)
            ->generateXml()
            ->saveXml()
            ->createRequest()
            ->parseRequest();
    }

    public function validateSettings(): bool
    {
        $test = config('szamlazzhu.test', false);
        $config = config('szamlazzhu.client.credentials.live');
        if ($test) {
            $config = config('szamlazzhu.client.credentials.test');
        }
        $this->settings = [...$config, ...$this->settings];
        $validator = Validator::make($this->settings, Rules::invoiceSettings());
        if ($validator->fails()) {
            $failedRules = $validator->failed();
            Log::error('Failed Rules: '.print_r($failedRules, true));
            throw new InvalidTaxpayerSettings($validator);
        }

        return true;
    }

    public function validateHeader(): bool
    {
        $validator = Validator::make($this->header, Rules::taxpayerHeaderSettings());
        if ($validator->fails()) {
            $failedRules = $validator->failed();
            Log::error('Failed Rules: '.print_r($failedRules, true));
            throw new InvalidTaxpayerHeader($validator);
        }

        return true;
    }
}
