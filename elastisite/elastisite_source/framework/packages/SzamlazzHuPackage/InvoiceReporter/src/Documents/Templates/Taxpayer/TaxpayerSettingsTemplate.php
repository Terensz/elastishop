<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates\Taxpayer;

use DOMDocument;
use DOMElement;
use Trianity\SzamlazzHu\Documents\Templates\SettingsTemplate;

/**
 * <beallitasok>
 * <!-- settings -->
 *   <felhasznalo>teszt01</felhasznalo>
 *   <!-- a Számlázz.hu user -->
 *   <jelszo>teszt01</jelszo>
 *   <!-- a Számlázz.hu’s user password -->
 *   <szamlaagentkulcs>Please fill!</szamlaagentkulcs>
 * </beallitasok>
 */
class TaxpayerSettingsTemplate implements SettingsTemplate
{
    /**
     * @param  array<string>  $settings
     */
    public static function create(array $settings, DOMDocument $xml): DOMElement
    {
        $base = $xml->createElement('beallitasok');
        if (isset($settings['user']) && strlen($settings['user']) > 0) {
            $base->appendChild($xml->createElement('felhasznalo', $settings['user']));
            $base->appendChild($xml->createElement('jelszo', $settings['password']));
        } else {
            $base->appendChild($xml->createElement('szamlaagentkulcs', $settings['api_key']));
        }

        return $base;
    }
}
