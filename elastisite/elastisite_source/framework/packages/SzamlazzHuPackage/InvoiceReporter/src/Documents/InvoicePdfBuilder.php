<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents;

use Carbon\Carbon;
use CURLStringFile;
use DOMDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Trianity\SzamlazzHu\Client\CURLRequest;
use Trianity\SzamlazzHu\DataTransferObjects\InvoicePdfData;
use Trianity\SzamlazzHu\Documents\Templates\InvoicePdf\PdfSettingsTemplate;

class InvoicePdfBuilder implements DocumentBuilder
{
    protected \stdClass $pdf;

    protected DOMDocument $xml;

    protected bool $debug;

    /**
     * @param  array<string>  $settings
     */
    public function settings(array $settings, bool $debug = false): DocumentBuilder
    {
        $this->reset();
        $this->debug = $debug;
        $this->pdf->settings = PdfSettingsTemplate::create($settings, $this->xml);

        return $this;
    }

    /**
     * @param  array<string>  $header
     */
    public function header(array $header = []): DocumentBuilder
    {
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
        $data = XmlHeaders::ACTIONS['GET_PDF_INVOICE']['schema'];
        $xml_prof = $this->xml->createElement($data[0]);
        $xml_prof->setAttribute('xmlns', $data[1]);
        $xml_prof->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml_prof->setAttribute('xsi:schemaLocation', $data[2]);
        foreach ($this->pdf->settings as $setting) {
            $xml_prof->appendChild($setting);
        }
        $this->xml->appendChild($xml_prof);
        $this->pdf->generatedXml = $this->xml->saveXML();

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
        $this->pdf->xmlFileName = "action-{$time_string}.xml";
        if (! Storage::disk('local')->put(
            'szamla/'.$this->pdf->xmlFileName,
            $this->pdf->generatedXml
        )
        ) {
            Log::error("[trianity/szamlazzhu] Couldn't store Query PDF Invoice temporary XML");
            $this->pdf->xmlPath = '';

            return $this;
        }
        $this->pdf->xmlPath = Storage::path('szamla/'.$this->pdf->xmlFileName);

        return $this;
    }

    public function createRequest(): DocumentBuilder
    {
        if (Storage::disk('local')->exists('szamla/'.$this->pdf->xmlFileName)) {
            $xml_file = new CURLStringFile(
                Storage::get('szamla/'.$this->pdf->xmlFileName),
                'action-szamla_agent_pdf'
            );
            $param = [
                'action-szamla_agent_pdf' => $xml_file,
                'generate' => 'Query invoice pdf',
            ];

            $request = new CURLRequest($this->debug);
            $this->pdf->apiAnswer = $request->curlResult([
                CURLOPT_URL => config('szamlazzhu.client.base_uri').'szamla/',
                CURLOPT_POSTFIELDS => $param,
            ]);

            return $this;
        }
        Log::error("[trianity/szamlazzhu] Couldn't create Query PDF Invoice SzamlaAgent request, xml: {$this->pdf->xmlPath}.");
        $this->pdf->apiAnswer = '';

        return $this;
    }

    public function parseRequest(): InvoicePdfData
    {
        return ParseInvoicePdfQuery::handle($this);
    }

    public function apiAnswer(): ?string
    {
        return $this->pdf->apiAnswer;
    }

    public function xmlPath(): string
    {
        return $this->pdf->xmlPath;
    }

    protected function reset(): void
    {
        $this->pdf = new \stdClass();
        $this->xml = new DOMDocument('1.0', 'UTF-8');
        $this->xml->preserveWhiteSpace = true;
    }
}
