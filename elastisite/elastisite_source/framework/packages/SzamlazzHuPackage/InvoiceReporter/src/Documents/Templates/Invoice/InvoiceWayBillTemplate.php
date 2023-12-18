<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates\Invoice;

use DOMDocument;
use DOMElement;
use Trianity\SzamlazzHu\Documents\Templates\WayBillTemplate;

/**
 * <fuvarlevel>
 *   <!-- waybill/confinement note, you do not need this: omit the entire tag -->
 *   <uticel></uticel>
 *   <futarSzolgalat></futarSzolgalat>
 * </fuvarlevel>
 */
class InvoiceWayBillTemplate implements WayBillTemplate
{
    /**
     * @param  array<string>  $waybill
     */
    public static function create(array $waybill, DOMDocument $xml): DOMElement
    {
        $base = $xml->createElement('fuvarlevel');
        $base->appendChild($xml->createElement('uticel', ($waybill['uticel'] ?? '')));
        $base->appendChild($xml->createElement('futarSzolgalat', ($waybill['futar_szolgalat'] ?? '')));

        return $base;
    }
}
