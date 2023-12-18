<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates;

use DOMDocument;
use DOMElement;

/**
 * The Abstract Factory interface declares creation methods for WayBill
 */
interface WayBillTemplate
{
    /**
     * @param  array<string>  $waybill
     */
    public static function create(array $waybill, DOMDocument $xml): DOMElement;
}
