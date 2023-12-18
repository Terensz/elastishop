<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents;

use DOMDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Trianity\SzamlazzHu\DataTransferObjects\InvoicePdfData;

class ParseInvoicePdfQuery
{
    public static function handle(InvoicePdfBuilder $builder): InvoicePdfData
    {
        $dom = new DOMDocument();
        if (! $builder->apiAnswer()) {
            return self::handleHardFailer($builder);
        }
        $dom->loadXML($builder->apiAnswer());
        $success = $dom->getElementsByTagName('sikeres');
        if ($success->item(0)->nodeValue === 'true') {
            return self::handleSuccess($dom, $builder);
        }

        return self::handleFailer($dom);
    }

    /**
     * Expected XML Object nodes:
     *   [sikeres] => true
     *   [szamlaszam] => D-TRNTY-11
     *   [szamlanetto] => 100000
     *   [szamlabrutto] => 127000
     *   [kintlevoseg] => 127000
     *   [vevoifiokurl] => url string CDATA
     *   [pdf] => base64encoded data of the PDF document
     */
    public static function handleSuccess(DOMDocument $dom, InvoicePdfBuilder $builder): InvoicePdfData
    {
        $pdfFilename = (string) $dom->getElementsByTagName('szamlaszam')->item(0)->nodeValue.'.pdf';
        if (! Storage::disk('local')->put('szamla/'.$pdfFilename, base64_decode($dom->getElementsByTagName('pdf')->item(0)->nodeValue))) {
            Log::error("[trianity/szamlazzhu] Couldn't store Proforma PDF {$pdfFilename}");
            $pdfFilename = '';
        } else {
            if (file_exists($builder->xmlPath())) {
                unlink($builder->xmlPath());
            }
        }

        return InvoicePdfData::from([
            'sikeres' => true,
            'szamlaszam' => strval($dom->getElementsByTagName('szamlaszam')->item(0)->nodeValue),
            'szamlanetto' => floatval($dom->getElementsByTagName('szamlanetto')->item(0)->nodeValue),
            'szamlabrutto' => floatval($dom->getElementsByTagName('szamlabrutto')->item(0)->nodeValue),
            'kintlevoseg' => floatval($dom->getElementsByTagName('kintlevoseg')->item(0)->nodeValue),
            'vevoifiokurl' => strval($dom->getElementsByTagName('vevoifiokurl')->item(0)->nodeValue),
            'pdf_name' => $pdfFilename,
        ]);
    }

    /**
     * XML Object nodes with error
     *   [sikeres] => false
     *   [hibakod] => XML Object()
     *   [hibauzenet] => XML Object()
     */
    public static function handleFailer(DOMDocument $dom): InvoicePdfData
    {
        return InvoicePdfData::from([
            'sikeres' => false,
            'hibakod' => $dom->getElementsByTagName('hibakod')->item(0)->nodeValue,
            'hibauzenet' => $dom->getElementsByTagName('hibauzenet')->item(0)->nodeValue,
        ]);
    }

    public static function handleHardFailer(InvoicePdfBuilder $builder): InvoicePdfData
    {
        //A Szamlazz.hu online rendszere nem elérhető valamilyen hiba van
        if (file_exists($builder->xmlPath())) {
            unlink($builder->xmlPath());
        }

        return InvoicePdfData::from([
            'sikeres' => false,
            'hibakod' => '0',
            'hibauzenet' => 'Nincs számlázz.hu kapcsolat!',
        ]);
    }
}
