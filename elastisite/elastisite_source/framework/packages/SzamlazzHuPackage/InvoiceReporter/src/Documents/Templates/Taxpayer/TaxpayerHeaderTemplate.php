<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates\Taxpayer;

use DOMDocument;
use DOMElement;
use Trianity\SzamlazzHu\Documents\Templates\HeaderTemplate;

class TaxpayerHeaderTemplate implements HeaderTemplate
{
    /**
     * @param  array<string>  $header (validated, normalized and HTML encoded)
     */
    public static function create(array $header, DOMDocument $xml): DOMElement
    {
        return $xml->createElement('torzsszam', $header['taxpayer_id_base']);
    }
}
