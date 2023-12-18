<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates\Invoice;

use DOMDocument;
use DOMElement;
use Trianity\SzamlazzHu\Documents\Templates\HeaderTemplate;

class InvoiceHeaderTemplate implements HeaderTemplate
{
    /**
     * @param  array<string>  $header (validated, normalized and HTML encoded)
     */
    public static function create(array $header, DOMDocument $xml): DOMElement
    {
        $base = $xml->createElement('fejlec');
        $base->appendChild($xml->createElement('keltDatum', $header['creating_date']));
        $base->appendChild($xml->createElement('teljesitesDatum', $header['payment_date']));
        $base->appendChild($xml->createElement('fizetesiHataridoDatum', $header['due_date']));
        $base->appendChild($xml->createElement('fizmod', ($header['payment_type'] ?? 'Átutalás')));
        $base->appendChild($xml->createElement('penznem', ($header['currency'] ?? 'HUF')));
        $base->appendChild($xml->createElement('szamlaNyelve', ($header['language'] ?? 'hu')));
        $comment = $xml->createElement('megjegyzes');
        $cdata_comment = $xml->createCDATASection($header['comment'] ?? '');
        $comment->appendChild($cdata_comment);
        $base->appendChild($comment);
        $exbank = $xml->createElement('arfolyamBank');
        $cdata_exbank = $xml->createCDATASection($header['exchange_rate_bank'] ?? 'MNB');
        $exbank->appendChild($cdata_exbank);
        $base->appendChild($exbank);
        $base->appendChild($xml->createElement('arfolyam', ($header['exchange_rate'] ?? '0.0')));
        $base->appendChild($xml->createElement('rendelesSzam', ($header['order_number'] ?? '')));
        $base->appendChild($xml->createElement('dijbekeroSzamlaszam', ($header['proforma_number'] ?? '')));
        $base->appendChild($xml->createElement('elolegszamla', ($header['is_deposit_invoice'] ?? 'false')));
        $base->appendChild($xml->createElement('vegszamla', ($header['is_final_invoice'] ?? 'false')));
        $base->appendChild($xml->createElement('helyesbitoszamla', ($header['is_correction_invoice'] ?? 'false')));
        $base->appendChild($xml->createElement('helyesbitettSzamlaszam', ($header['corrected_invoice_number'] ?? '')));
        $base->appendChild($xml->createElement('dijbekero', ($header['is_proforma_invoice'] ?? 'true')));
        $base->appendChild($xml->createElement('szamlaszamElotag', ($header['invoice_prefix'] ?? '')));

        return $base;
    }
}
