<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents;

use DOMDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Trianity\SzamlazzHu\DataTransferObjects\InvoiceXmlData;

class ParseInvoiceXmlQuery
{
    public static function handle(InvoiceXmlBuilder $builder): InvoiceXmlData
    {
        $dom = new DOMDocument();
        //dd($builder->apiAnswer());
        if (! $builder->apiAnswer()) {
            return self::handleHardFailer($builder);
        }
        $dom->loadXML($builder->apiAnswer());
        $success = $dom->getElementsByTagName('sikeres');
        if ($success->length === 0) {
            return self::handleSuccess($dom, $builder);
        } elseif ($success->item(0)->nodeValue === 'false') {
            return self::handleFailer($dom);
        }

        return self::handleHardFailer($builder);
    }

    public static function handleSuccess(DOMDocument $dom, InvoiceXmlBuilder $builder): InvoiceXmlData
    {
        $pdfFilename = (string) $dom->getElementsByTagName('szamlaszam')->item(0)->nodeValue.'.pdf';
        if (! Storage::disk('local')->put('szamla/'.$pdfFilename, base64_decode($dom->getElementsByTagName('pdf')->item(0)->nodeValue))) {
            Log::error("[trianity/szamlazzhu] Couldn't store Proforma or Incoice PDF {$pdfFilename}");
            $pdfFilename = '';
        } else {
            if (file_exists($builder->xmlPath())) {
                unlink($builder->xmlPath());
            }
        }
        $summ = [];
        $i = 0;
        while (is_object($osszegek = $dom->getElementsByTagName('osszegek')->item($i))) {
            foreach ($osszegek->childNodes as $nodename) {
                if ($nodename->nodeName == 'totalossz') {
                    foreach ($nodename->childNodes as $subNodes) {
                        if ($subNodes->nodeName !== '#text') {
                            $summ[$subNodes->nodeName] = $subNodes->nodeValue;
                        }
                    }
                }
            }
            $i++;
        }
        $kif = [];
        $i = 0;
        while (is_object($kifizetesek = $dom->getElementsByTagName('kifizetesek')->item($i))) {
            foreach ($kifizetesek->childNodes as $nodename) {
                if ($nodename->nodeName == 'kifizetes') {
                    foreach ($nodename->childNodes as $subNodes) {
                        if ($subNodes->nodeName !== '#text') {
                            $kif[$subNodes->nodeName] = $subNodes->nodeValue;
                        }
                    }
                }
            }
            $i++;
        }

        return InvoiceXmlData::from([
            'sikeres' => true,
            'szamlaszam' => $dom->getElementsByTagName('szamlaszam')->item(0)->nodeValue,
            'tipus' => $dom->getElementsByTagName('tipus')->item(0)->nodeValue,
            'hivdijbekszam' => $dom->getElementsByTagName('hivdijbekszam')->item(0)->nodeValue ?? '',
            'rendelesszam' => $dom->getElementsByTagName('rendelesszam')->item(0)->nodeValue,
            'email' => $dom->getElementsByTagName('email')->item(0)->nodeValue,
            'totalossz' => $summ,
            'kifizetes' => $kif,
            'pdf_name' => $pdfFilename,
        ]);
    }

    public static function handleFailer(DOMDocument $dom): InvoiceXmlData
    {
        return InvoiceXmlData::from([
            'sikeres' => false,
            'hibakod' => $dom->getElementsByTagName('hibakod')->item(0)->nodeValue,
            'hibauzenet' => $dom->getElementsByTagName('hibauzenet')->item(0)->nodeValue,
        ]);
    }

    public static function handleHardFailer(InvoiceXmlBuilder $builder): InvoiceXmlData
    {
        //A Szamlazz.hu online rendszere nem elérhető valamilyen hiba van
        if (file_exists($builder->xmlPath())) {
            unlink($builder->xmlPath());
        }

        return InvoiceXmlData::from([
            'sikeres' => false,
            'hibakod' => '0',
            'hibauzenet' => 'Nincs számlázz.hu kapcsolat!',
        ]);
    }
}
