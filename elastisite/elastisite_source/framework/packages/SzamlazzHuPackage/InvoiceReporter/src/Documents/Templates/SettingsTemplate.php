<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates;

use DOMDocument;
use DOMElement;

/**
 * The Abstract Factory interface declares creation methods for Settings
 */
interface SettingsTemplate
{
    /**
     * @param  array<string>  $settings
     * @return DOMElement|array<int, DOMElement>
     */
    public static function create(array $settings, DOMDocument $xml): DOMElement|array;
}
