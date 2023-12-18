<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates;

use DOMDocument;
use DOMElement;

/**
 * The Abstract Factory interface declares creation methods for Items
 */
interface ItemsTemplate
{
    /**
     * @param  array<int, array<string>>  $items
     */
    public static function create(array $items, DOMDocument $xml): DOMElement;
}
