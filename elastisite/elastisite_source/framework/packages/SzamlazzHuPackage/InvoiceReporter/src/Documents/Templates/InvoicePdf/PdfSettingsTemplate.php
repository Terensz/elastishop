<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates\InvoicePdf;

use DOMDocument;
use DOMElement;
use Trianity\SzamlazzHu\Documents\Templates\SettingsTemplate;

/**
 * <xmlszamlapdf xmlns="http://www.szamlazz.hu/xmlszamlapdf" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.szamlazz.hu/xmlszamlapdf https://www.szamlazz.hu/szamla/docs/xsds/agentpdf/xmlszamlapdf.xsd">
 *   <felhasznalo>Test123</felhasznalo><!-- a Számlázz.hu user -->
 *   <jelszo>Test123</jelszo><!-- a Számlázz.hu password -->
 *   <szamlaagentkulcs>Please fill!</szamlaagentkulcs>
 *   <szamlaszam>E-TST-2011-1</szamlaszam><!-- invoice number -->
 *   <valaszVerzio>2</valaszVerzio><!-- response type (see details below) -->
 *   <szamlaKulsoAzon></szamlaKulsoAzon><!--     string  --><!-- The invoice can be identified with this key by the third party system (system which uses the Számla Agent): later the invoice can be queried with this key -->
 * </xmlszamlapdf>
 */
class PdfSettingsTemplate implements SettingsTemplate
{
    /**
     * @param  array<string>  $settings
     * @return array<int, DOMElement>
     */
    public static function create(array $settings, DOMDocument $xml): array
    {

        if (isset($settings['user']) && strlen($settings['user']) > 0) {
            $base = [
                '0' => $xml->createElement('felhasznalo', $settings['user']),
                '1' => $xml->createElement('jelszo', $settings['password']),
                '2' => $xml->createElement('szamlaszam', $settings['invoice_number']),
                '3' => $xml->createElement('valaszVerzio', '2'),
                '4' => $xml->createElement('szamlaKulsoAzon', ($settings['external_inv_id'] ?? '')),
            ];
        } else {
            $base = [
                '0' => $xml->createElement('szamlaagentkulcs', $settings['api_key']),
                '1' => $xml->createElement('szamlaszam', $settings['invoice_number']),
                '2' => $xml->createElement('valaszVerzio', '2'),
                '3' => $xml->createElement('szamlaKulsoAzon', ($settings['external_inv_id'] ?? '')),
            ];
        }

        return $base;
    }
}
