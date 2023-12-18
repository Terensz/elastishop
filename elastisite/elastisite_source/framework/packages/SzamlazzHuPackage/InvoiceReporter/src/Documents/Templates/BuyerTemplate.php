<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates;

use DOMDocument;
use DOMElement;

/**
 * The Abstract Factory interface declares creation methods for Buyer data
 */
interface BuyerTemplate
{
    /**
     * @param  array<string>  $buyer
     */
    public static function create(array $buyer, DOMDocument $xml): DOMElement;
}
