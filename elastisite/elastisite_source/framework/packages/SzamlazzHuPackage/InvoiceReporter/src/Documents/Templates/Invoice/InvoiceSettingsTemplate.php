<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates\Invoice;

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
 *   <eszamla>true</eszamla>
 *   <!-- „true” in case you need to create an e-invoice -->
 *   <szamlaLetoltes>true</szamlaLetoltes>
 *   <!-- „true” in case you would like to get the PDF invoice in the response -->
 *   <valaszVerzio>1</valaszVerzio>
 *   <!-- 1: gives a simple text or PDF as answer.
 *        2: xml answer, in case you asked for the PDF as well,
 *           it will be included in the XML with base64 coding. -->
 *   <aggregator></aggregator>
 *   <!-- omit this tag -->
 *   <szamlaKulsoAzon></szamlaKulsoAzon>
 *   <!--     string  -->
 *   <!-- The invoice can be identified with this key by the third party system
 *   (system which uses the Számla Agent): later the invoice can be queried with this key -->
 * </beallitasok>
 */
class InvoiceSettingsTemplate implements SettingsTemplate
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
        $base->appendChild($xml->createElement('eszamla', 'true'));
        $base->appendChild($xml->createElement('szamlaLetoltes', 'true'));
        $base->appendChild($xml->createElement('valaszVerzio', '2'));
        $base->appendChild($xml->createElement('aggregator', ''));
        $base->appendChild($xml->createElement('szamlaKulsoAzon', ($settings['external_inv_id'] ?? '')));

        return $base;
    }
}
