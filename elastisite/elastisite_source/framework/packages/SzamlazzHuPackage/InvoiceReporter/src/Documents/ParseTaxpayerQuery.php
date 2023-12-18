<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Documents;

use Carbon\Carbon;
use DOMDocument;
use Trianity\SzamlazzHu\DataTransferObjects\TaxpayerData;
use Trianity\SzamlazzHu\Utility\Helper;

class ParseTaxpayerQuery
{
    public static function handle(TaxpayerBuilder $builder): TaxpayerData
    {
        $dom = new DOMDocument();

        if (! $builder->apiAnswer()) {
            return self::handleHardFailer($dom, $builder);
        }
        $dom->loadXML($builder->apiAnswer());
        $funcionality = $dom->getElementsByTagName('funcCode')->item(0)->nodeValue ?? '';
        if ($funcionality !== 'OK') {
            return self::handleHardFailer($dom, $builder);
        }

        $validity = $dom->getElementsByTagName('taxpayerValidity')->item(0)->nodeValue ?? '';
        if ($validity === 'true') {
            return self::handleSuccess($dom, $builder);
        }

        return self::handleFailer($dom, $builder);
    }

    public static function handleSuccess(DOMDocument $dom, TaxpayerBuilder $builder): TaxpayerData
    {
        $name = $dom->getElementsByTagName('taxpayerShortName')->item(0)->nodeValue ?? '';
        if (strlen($name) === 0) {
            $name = $dom->getElementsByTagName('taxpayerName')->item(0)->nodeValue ?? '';
        }
        $orgType = $dom->getElementsByTagName('incorporation')->item(0)->nodeValue ?? '';
        if ($orgType === 'SELF_EMPLOYED') {
            $name = $dom->getElementsByTagName('taxpayerName')->item(0)->nodeValue.' E.V.';
        }
        if (file_exists($builder->xmlPath())) {
            unlink($builder->xmlPath());
        }

        return TaxpayerData::from([
            'validity' => true,
            'info_date' => Carbon::now('Europe/Budapest')->toDateString(),
            'response_code' => 1,
            'message' => __('szamlazzhu::messages.valid_taxid'),
            'long_name' => $dom->getElementsByTagName('taxpayerName')->item(0)->nodeValue ?? '',
            'name' => $name,
            'taxpayer_id' => $dom->getElementsByTagName('taxpayerId')->item(0)->nodeValue ?? '',
            'vat_code' => $dom->getElementsByTagName('vatCode')->item(0)->nodeValue ?? '',
            'county_code' => $dom->getElementsByTagName('countyCode')->item(0)->nodeValue ?? '',
            'org_type' => $orgType,
            'address_type' => $dom->getElementsByTagName('taxpayerAddressType')->item(0)->nodeValue ?? '',
            'country_code' => $dom->getElementsByTagName('countryCode')->item(0)->nodeValue ?? '',
            'postal_code' => $dom->getElementsByTagName('postalCode')->item(0)->nodeValue ?? '',
            'city' => Helper::spUcfirst($dom->getElementsByTagName('city')->item(0)->nodeValue ?? ''),
            'street_name' => Helper::spUcfirst($dom->getElementsByTagName('streetName')->item(0)->nodeValue ?? ''),
            'public_place_category' => mb_strtolower($dom->getElementsByTagName('publicPlaceCategory')->item(0)->nodeValue ?? '', 'utf-8'),
            'number' => $dom->getElementsByTagName('number')->item(0)->nodeValue ?? '',
            'building' => $dom->getElementsByTagName('building')->item(0)->nodeValue ?? '',
            'floor' => mb_strtolower($dom->getElementsByTagName('floor')->item(0)->nodeValue ?? '', 'utf-8'),
            'door' => $dom->getElementsByTagName('door')->item(0)->nodeValue ?? '',
        ]);
    }

    public static function handleFailer(DOMDocument $dom, TaxpayerBuilder $builder): TaxpayerData
    {
        $validity = $dom->getElementsByTagName('taxpayerValidity')->item(0)->nodeValue ?? '';
        $name = $dom->getElementsByTagName('taxpayerName')->item(0)->nodeValue ?? '';
        if ($validity === 'false' && strlen($name) > 0) {
            //Érvénytelen adószám esete (pl. felszámolták, felfügesztették)
            return self::invalidTaxpayer($dom, $builder);
        }
        if ($validity === 'false') {
            //Hibás adószám esete
            return self::wrongInput($dom, $builder);
        }

        //A NAV rendszere nem elérhető
        return self::noAccess($dom, $builder);
    }

    public static function handleHardFailer(DOMDocument $dom, TaxpayerBuilder $builder): TaxpayerData
    {
        //A NAV online rendszere nem elérhető valamilyen hiba van
        $error_code = $dom->getElementsByTagName('errorCode')->item(0)->nodeValue ?? '';
        $message = $dom->getElementsByTagName('message')->item(0)->nodeValue ?? '';
        if (file_exists($builder->xmlPath())) {
            unlink($builder->xmlPath());
        }

        return TaxpayerData::from([
            'validity' => false,
            'infoDate' => Carbon::now('Europe/Budapest')->toDateString(),
            'response_code' => 9,
            'message' => $message.' Hibakód: '.$error_code,
            'long_name' => '',
            'name' => '',
        ]);
    }

    public static function invalidTaxpayer(DOMDocument $dom, TaxpayerBuilder $builder): TaxpayerData
    {
        if (file_exists($builder->xmlPath())) {
            unlink($builder->xmlPath());
        }

        return TaxpayerData::from([
            'validity' => false,
            'infoDate' => Carbon::now('Europe/Budapest')->toDateString(),
            'response_code' => 3,
            'message' => __('szamlazzhu::messages.invalid_taxid'),
            'long_name' => $dom->getElementsByTagName('taxpayerName')->item(0)->nodeValue ?? '',
            'name' => $dom->getElementsByTagName('taxpayerShortName')->item(0)->nodeValue ?? '',
        ]);
    }

    public static function wrongInput(DOMDocument $dom, TaxpayerBuilder $builder): TaxpayerData
    {
        if (file_exists($builder->xmlPath())) {
            unlink($builder->xmlPath());
        }

        return TaxpayerData::from([
            'validity' => false,
            'info_date' => Carbon::now('Europe/Budapest')->toDateString(),
            'response_code' => 6,
            'message' => __('szamlazzhu::messages.wrong_taxid'),
            'long_name' => '',
            'name' => '',
        ]);
    }

    public static function noAccess(DOMDocument $dom, TaxpayerBuilder $builder): TaxpayerData
    {
        if (file_exists($builder->xmlPath())) {
            unlink($builder->xmlPath());
        }

        return TaxpayerData::from([
            'validity' => false,
            'info_date' => Carbon::now('Europe/Budapest')->toDateString(),
            'response_code' => 9,
            'message' => __('szamlazzhu::messages.missing_nav'),
            'long_name' => '',
            'name' => '',
        ]);
    }
}
