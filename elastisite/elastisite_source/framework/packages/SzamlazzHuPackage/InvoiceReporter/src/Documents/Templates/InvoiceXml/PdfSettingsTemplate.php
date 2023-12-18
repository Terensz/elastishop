<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates\InvoiceXml;

use DOMDocument;
use DOMElement;
use Trianity\SzamlazzHu\Documents\Templates\SettingsTemplate;

/**
 * <?xml version="1.0" encoding="UTF-8"?>
 * <xmlszamlaxml xmlns="http://www.szamlazz.hu/xmlszamlaxml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.szamlazz.hu/xmlszamlaxml https://www.szamlazz.hu/szamla/docs/xsds/agentxml/xmlszamlaxml.xsd">
 *   <felhasznalo>Please fill out!</felhasznalo>
 *   <jelszo>Please fill out!</jelszo>
 *   <!-- Agent key can be used, instead of username/password -->
 *   <szamlaagentkulcs>Please fill out!</szamlaagentkulcs>
 *   <szamlaszam>E-TST-2011-1</szamlaszam>
 *   <!--
 *     <rendelesSzam></rendelesSzam> <Order number can be used in the query. In this case the last receipt with this order number will be returned
 *   -->
 *   <!--
 *     <pdf>true</pdf>  Only needed if the pdf must be downloaded
 *   -->
 *  </xmlszamlaxml>
 */
class PdfSettingsTemplate implements SettingsTemplate
{
    /**
     * @param  array<string>  $settings
     * @return array<int, DOMElement>
     */
    public static function create(array $settings, DOMDocument $xml): array
    {
        return [
            '0' => $xml->createElement('szamlaagentkulcs', $settings['api_key']),
            '1' => $xml->createElement('szamlaszam', ($settings['invoice_number'] ?? '')),
            '2' => $xml->createElement('rendelesSzam', ($settings['order_number'] ?? '')),
            '3' => $xml->createElement('pdf', 'true'),
        ];
    }
}
