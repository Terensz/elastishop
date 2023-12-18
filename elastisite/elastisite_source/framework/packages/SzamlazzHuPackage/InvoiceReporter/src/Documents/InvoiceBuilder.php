<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents;

use Carbon\Carbon;
use CURLStringFile;
use DOMDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Trianity\SzamlazzHu\Client\CURLRequest;
use Trianity\SzamlazzHu\DataTransferObjects\InvoiceData;
use Trianity\SzamlazzHu\Documents\Templates\Invoice\InvoiceBuyerTemplate;
use Trianity\SzamlazzHu\Documents\Templates\Invoice\InvoiceHeaderTemplate;
use Trianity\SzamlazzHu\Documents\Templates\Invoice\InvoiceItemsTemplate;
use Trianity\SzamlazzHu\Documents\Templates\Invoice\InvoiceSellerTemplate;
use Trianity\SzamlazzHu\Documents\Templates\Invoice\InvoiceSettingsTemplate;
use Trianity\SzamlazzHu\Documents\Templates\Invoice\InvoiceWayBillTemplate;

class InvoiceBuilder implements DocumentBuilder
{
    protected \stdClass $invoice;

    protected DOMDocument $xml;

    protected bool $debug;

    /**
     * @param  array<string>  $settings
     */
    public function settings(array $settings, bool $debug = false): DocumentBuilder
    {
        $this->reset();
        $this->debug = $debug;
        $this->invoice->settings = InvoiceSettingsTemplate::create($settings, $this->xml);

        return $this;
    }

    /**
     * @param  array<string>  $header
     */
    public function header(array $header): DocumentBuilder
    {
        $this->invoice->type = $header['is_proforma_invoice'] === 'true' ? 'proforma' : 'invoice';
        $this->invoice->header = InvoiceHeaderTemplate::create($header, $this->xml);

        return $this;
    }

    /**
     * @param  array<string>  $seller
     */
    public function seller(array $seller): DocumentBuilder
    {
        $this->invoice->seller = InvoiceSellerTemplate::create($seller, $this->xml);

        return $this;
    }

    /**
     * @param  array<string>  $buyer
     */
    public function buyer(array $buyer): DocumentBuilder
    {
        $this->invoice->buyer = InvoiceBuyerTemplate::create($buyer, $this->xml);

        return $this;
    }

    /**
     * @param  array<string>  $waybill
     */
    public function waybill(array $waybill): DocumentBuilder
    {
        $this->invoice->waybill = InvoiceWayBillTemplate::create($waybill, $this->xml);

        return $this;
    }

    /**
     * @param  array<int, array<string>>  $items
     */
    public function items(array $items): DocumentBuilder
    {
        $this->invoice->items = InvoiceItemsTemplate::create($items, $this->xml);

        return $this;
    }

    public function generateXml(): DocumentBuilder
    {
        $data = XmlHeaders::ACTIONS['UPLOAD_COMMON_INVOICE']['schema'];
        $xml_prof = $this->xml->createElement($data[0]);
        $xml_prof->setAttribute('xmlns', $data[1]);
        $xml_prof->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml_prof->setAttribute('xsi:schemaLocation', $data[2]);
        $xml_prof->appendChild($this->invoice->settings);
        $xml_prof->appendChild($this->invoice->header);
        $xml_prof->appendChild($this->invoice->seller);
        $xml_prof->appendChild($this->invoice->buyer);
        $xml_prof->appendChild($this->invoice->waybill);
        $xml_prof->appendChild($this->invoice->items);
        $this->xml->appendChild($xml_prof);
        $this->invoice->generatedXml = $this->xml->saveXML();

        return $this;
    }

    public function getDocumentType(): string
    {
        return $this->invoice->type;
    }

    public function saveXml(): DocumentBuilder
    {
        $dt = Carbon::now('Europe/Budapest');
        $time_string = $dt->format('YmdHis');
        $this->invoice->xmlFileName = "action-{$time_string}.xml";
        if (! Storage::disk('local')->put(
            'szamla/'.$this->invoice->xmlFileName,
            $this->invoice->generatedXml
        )
        ) {
            Log::error("[trianity/szamlazzhu] Couldn't store Proforma temporary XML");
            $this->invoice->xmlPath = '';

            return $this;
        }
        $this->invoice->xmlPath = Storage::path('szamla/'.$this->invoice->xmlFileName);

        return $this;
    }

    public function createRequest(): DocumentBuilder
    {
        if (Storage::disk('local')->exists('szamla/'.$this->invoice->xmlFileName)) {
            $xml_file = new CURLStringFile(
                Storage::get('szamla/'.$this->invoice->xmlFileName),
                'action-xmlagentxmlfile'
            );
            $param = [
                'action-xmlagentxmlfile' => $xml_file,
                'generate' => 'Issue invoice',
            ];

            $request = new CURLRequest($this->debug);
            $this->invoice->apiAnswer = $request->curlResult([
                CURLOPT_URL => config('szamlazzhu.client.base_uri').'szamla/',
                CURLOPT_POSTFIELDS => $param,
            ]);

            return $this;
        }
        Log::error("[trianity/szamlazzhu] Couldn't create Proforma SzamlaAgent request, xml: {$this->invoice->xmlPath}.");
        $this->invoice->apiAnswer = '';

        return $this;
    }

    public function parseRequest(): InvoiceData
    {
        return ParseProformaCreate::handle($this);
    }

    public function apiAnswer(): ?string
    {
        return $this->invoice->apiAnswer;
    }

    public function xmlPath(): string
    {
        return $this->invoice->xmlPath;
    }

    protected function reset(): void
    {
        $this->invoice = new \stdClass();
        $this->xml = new DOMDocument('1.0', 'UTF-8');
        $this->xml->preserveWhiteSpace = true;
    }
}
