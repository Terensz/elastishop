<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates\Invoice;

use DOMDocument;
use DOMElement;
use Trianity\SzamlazzHu\Documents\Templates\ItemsTemplate;

class InvoiceItemsTemplate implements ItemsTemplate
{
    /**
     * @param  array<int, array<string>>  $items
     */
    public static function create(array $items, DOMDocument $xml): DOMElement
    {
        $base = $xml->createElement('tetelek');
        if (count($items) < 1) {
            return $base;
        }
        foreach ($items as $item) {
            $baseItem = $xml->createElement('tetel');
            $name = $xml->createElement('megnevezes');
            $cdata_name = $xml->createCDATASection($item['name']);
            $name->appendChild($cdata_name);
            $baseItem->appendChild($name);
            $baseItem->appendChild($xml->createElement('mennyiseg', $item['quantity']));
            $baseItem->appendChild($xml->createElement('mennyisegiEgyseg', $item['quantity_unit']));
            $baseItem->appendChild($xml->createElement('nettoEgysegar', $item['unite_price']));
            $baseItem->appendChild($xml->createElement('afakulcs', $item['vat_rate']));
            $baseItem->appendChild($xml->createElement('nettoErtek', $item['net_price']));
            $baseItem->appendChild($xml->createElement('afaErtek', $item['vat_amount']));
            $baseItem->appendChild($xml->createElement('bruttoErtek', $item['gross_amount']));
            $icomment = $xml->createElement('megjegyzes');
            $cdata_icomment = $xml->createCDATASection($item['icomment'] ?? '');
            $icomment->appendChild($cdata_icomment);
            $baseItem->appendChild($icomment);
            $base->appendChild($baseItem);
        }

        return $base;
    }
}
