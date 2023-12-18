<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates;

use DOMDocument;
use DOMElement;

/**
 * The Abstract Factory interface declares creation methods for Seller Data
 */
interface SellerTemplate
{
    /**
     * @param  array<string>  $seller
     */
    public static function create(array $seller, DOMDocument $xml): DOMElement;
}
