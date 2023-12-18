<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates;

use DOMDocument;
use DOMElement;

/**
 * The Abstract Factory interface declares creation methods for Header data
 */
interface HeaderTemplate
{
    /**
     * @param  array<string>  $header
     */
    public static function create(array $header, DOMDocument $xml): DOMElement;
}
