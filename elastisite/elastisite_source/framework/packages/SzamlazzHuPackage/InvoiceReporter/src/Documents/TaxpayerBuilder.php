<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents;

use Carbon\Carbon;
use CURLStringFile;
use DOMDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Trianity\SzamlazzHu\Client\CURLRequest;
use Trianity\SzamlazzHu\DataTransferObjects\TaxpayerData;
use Trianity\SzamlazzHu\Documents\Templates\Taxpayer\TaxpayerHeaderTemplate;
use Trianity\SzamlazzHu\Documents\Templates\Taxpayer\TaxpayerSettingsTemplate;

class TaxpayerBuilder implements DocumentBuilder
{
    protected \stdClass $taxpayer;

    protected DOMDocument $xml;

    protected bool $debug;

    /**
     * @param  array<string>  $settings
     */
    public function settings(array $settings, bool $debug = false): DocumentBuilder
    {
        $this->reset();
        $this->debug = $debug;
        $this->taxpayer->settings = TaxpayerSettingsTemplate::create($settings, $this->xml);

        return $this;
    }

    /**
     * @param  array<string>  $header
     */
    public function header(array $header): DocumentBuilder
    {
        $this->taxpayer->header = TaxpayerHeaderTemplate::create($header, $this->xml);

        return $this;
    }

    /**
     * @param  array<string>  $seller
     */
    public function seller(array $seller = []): DocumentBuilder
    {
        return $this;
    }

    /**
     * @param  array<string>  $buyer
     */
    public function buyer(array $buyer = []): DocumentBuilder
    {
        return $this;
    }

    /**
     * @param  array<string>  $waybill
     */
    public function waybill(array $waybill = []): DocumentBuilder
    {
        return $this;
    }

    /**
     * @param  array<int, array<string>>  $items
     */
    public function items(array $items = []): DocumentBuilder
    {
        return $this;
    }

    public function generateXml(): DocumentBuilder
    {
        $data = XmlHeaders::ACTIONS['GET_TAXPAYER']['schema'];
        $xml_prof = $this->xml->createElement($data[0]);
        $xml_prof->setAttribute('xmlns', $data[1]);
        $xml_prof->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml_prof->setAttribute('xsi:schemaLocation', $data[2]);
        $xml_prof->appendChild($this->taxpayer->settings);
        $xml_prof->appendChild($this->taxpayer->header);
        $this->xml->appendChild($xml_prof);
        $this->taxpayer->generatedXml = $this->xml->saveXML();

        return $this;
    }

    public function getDocumentType(): string
    {
        return '';
    }

    public function saveXml(): DocumentBuilder
    {
        $dt = Carbon::now('Europe/Budapest');
        $time_string = $dt->format('YmdHis');
        $this->taxpayer->xmlFileName = "action-{$time_string}.xml";
        if (! Storage::disk('local')->put(
            'szamla/'.$this->taxpayer->xmlFileName,
            $this->taxpayer->generatedXml
        )
        ) {
            Log::error("[trianity/szamlazzhu] Couldn't store Query taxpayer temporary XML");
            $this->taxpayer->xmlPath = '';

            return $this;
        }
        $this->taxpayer->xmlPath = Storage::path('szamla/'.$this->taxpayer->xmlFileName);

        return $this;
    }

    public function createRequest(): DocumentBuilder
    {
        if (Storage::disk('local')->exists('szamla/'.$this->taxpayer->xmlFileName)) {
            $xml_file = new CURLStringFile(
                Storage::get('szamla/'.$this->taxpayer->xmlFileName),
                'action-szamla_agent_taxpayer'
            );
            $param = [
                'action-szamla_agent_taxpayer' => $xml_file,
                'generate' => 'Query taxpayer',
            ];

            $request = new CURLRequest($this->debug);
            $this->taxpayer->apiAnswer = $request->curlResult([
                CURLOPT_URL => config('szamlazzhu.client.base_uri').'szamla/',
                CURLOPT_POSTFIELDS => $param,
            ]);

            return $this;
        }
        Log::error("[trianity/szamlazzhu] Couldn't create Query taxpayer SzamlaAgent request, xml: {$this->taxpayer->xmlPath}.");
        $this->taxpayer->apiAnswer = '';

        return $this;
    }

    public function parseRequest(): TaxpayerData
    {
        return ParseTaxpayerQuery::handle($this);
    }

    public function apiAnswer(): ?string
    {
        return $this->taxpayer->apiAnswer;
    }

    public function xmlPath(): string
    {
        return $this->taxpayer->xmlPath;
    }

    protected function reset(): void
    {
        $this->taxpayer = new \stdClass();
        $this->xml = new DOMDocument('1.0', 'UTF-8');
        $this->xml->preserveWhiteSpace = true;
    }
}
