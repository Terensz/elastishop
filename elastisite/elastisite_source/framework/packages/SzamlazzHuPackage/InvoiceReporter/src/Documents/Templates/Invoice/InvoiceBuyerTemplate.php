<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents\Templates\Invoice;

use DOMDocument;
use DOMElement;
use Trianity\SzamlazzHu\Documents\Templates\BuyerTemplate;

class InvoiceBuyerTemplate implements BuyerTemplate
{
    /**
     * @param  array<string>  $buyer
     */
    public static function create(array $buyer, DOMDocument $xml): DOMElement
    {
        $base = $xml->createElement('vevo');
        $name = $xml->createElement('nev');
        $cdata_name = $xml->createCDATASection($buyer['name']);
        $name->appendChild($cdata_name);
        $base->appendChild($name);
        $base->appendChild($xml->createElement('orszag', ($buyer['country'] ?? 'Magyarország')));
        $base->appendChild($xml->createElement('irsz', $buyer['postal_code']));
        $city = $xml->createElement('telepules');
        $cdata_city = $xml->createCDATASection($buyer['city']);
        $city->appendChild($cdata_city);
        $base->appendChild($city);
        $adress = $xml->createElement('cim');
        $cdata_address = $xml->createCDATASection($buyer['address']);
        $adress->appendChild($cdata_address);
        $base->appendChild($adress);
        $base->appendChild($xml->createElement('email', $buyer['email']));
        $base->appendChild($xml->createElement('sendEmail', ($buyer['send_email'] ?? 'true')));
        $base->appendChild($xml->createElement('adoalany', ($buyer['is_taxpayer'] ?? '0')));
        $base->appendChild($xml->createElement('adoszam', ($buyer['taxpayer_id'] ?? '')));
        $base->appendChild($xml->createElement('adoszamEU', ($buyer['eutaxid'] ?? '')));
        if (isset($buyer['postal_name']) && strlen($buyer['postal_name']) > 0) {
            $base = self::postalXmlData($base, $xml, $buyer);
        }
        $base->appendChild($xml->createElement('azonosito', ($buyer['buyer_id'] ?? '')));
        $base->appendChild($xml->createElement('telefonszam', ($buyer['phone'] ?? '')));
        $bcomment = $xml->createElement('megjegyzes');
        $cdata_bcomment = $xml->createCDATASection($buyer['buyer_comment'] ?? '');
        $bcomment->appendChild($cdata_bcomment);
        $base->appendChild($bcomment);

        return $base;
    }

    /**
     * @param  array<string>  $buyer
     */
    public static function postalXmlData(DOMElement $base, DOMDocument $xml, array $buyer): DOMElement
    {
        $postalName = $xml->createElement('postazasiNev');
        $cdata_postalName = $xml->createCDATASection($buyer['postal_name']);
        $postalName->appendChild($cdata_postalName);
        $base->appendChild($postalName);
        $base->appendChild($xml->createElement('postazasiIrsz', ($buyer['postal_zipcode'] ?? '')));
        $postalCity = $xml->createElement('postazasiTelepules');
        $cdata_postalCity = $xml->createCDATASection($buyer['postal_city'] ?? '');
        $postalCity->appendChild($cdata_postalCity);
        $base->appendChild($postalCity);
        $postalAddress = $xml->createElement('postazasiNev');
        $cdata_postalAddress = $xml->createCDATASection($buyer['postal_address'] ?? '');
        $postalAddress->appendChild($cdata_postalAddress);
        $base->appendChild($postalAddress);

        return $base;
    }
}
