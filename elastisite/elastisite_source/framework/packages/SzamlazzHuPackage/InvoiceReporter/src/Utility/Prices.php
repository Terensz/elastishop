<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Utility;

use Trianity\SzamlazzHu\Proforma;

class Prices
{
    /**
     * @param  array<string, string>  $item
     * @return array<string, string>
     */
    public static function hufCorrection(array $item): array
    {
        $corrected_item = $item;
        //Brutto Based Correction
        if (isset($item['brutto_price'])) {
            $gross_amount = round((int) $item['quantity'] * (int) $item['brutto_price']);
            $vat_amount = round($gross_amount / (100 + (int) $item['vat_rate']) * (int) $item['vat_rate']);
            $net_price = $gross_amount - $vat_amount;
            $net_unite_price = round($net_price / (int) $item['quantity'], 2);

            $corrected_item['unite_price'] = (string) $net_unite_price;
            $corrected_item['net_price'] = (string) $net_price;
            $corrected_item['gross_amount'] = (string) $gross_amount;
            $corrected_item['vat_amount'] = (string) $vat_amount;

            return $corrected_item;
        }
        //Netto Based Correction
        $net_price = round((int) $item['quantity'] * (int) $item['unite_price']);
        $gross_amount = round($net_price * (1 + (int) $item['vat_rate'] / 100));
        $vat_amount = $gross_amount - $net_price;

        $corrected_item['unite_price'] = (string) $item['unite_price'];
        $corrected_item['net_price'] = (string) $net_price;
        $corrected_item['gross_amount'] = (string) $gross_amount;
        $corrected_item['vat_amount'] = (string) $vat_amount;

        return $corrected_item;
    }

    /**
     * Base correction if the VAT_RATE is not numeric (like AAM, etc.)
     *
     * @param  array<string, string>  $item
     * @return array<string, string>
     */
    public static function baseCorrection(array $item): array
    {
        $corrected_item = $item;
        //Brutto Based Correction
        if (isset($item['brutto_price'])) {
            $gross_amount = round((int) $item['quantity'] * (int) $item['brutto_price']);
            $vat_amount = '0';
            $net_price = $gross_amount;
            $net_unite_price = round($net_price / (int) $item['quantity'], 2);

            $corrected_item['unite_price'] = (string) $net_unite_price;
            $corrected_item['net_price'] = (string) $net_price;
            $corrected_item['gross_amount'] = (string) $gross_amount;
            $corrected_item['vat_amount'] = (string) $vat_amount;

            return $corrected_item;
        }
        //Netto Based Correction
        $net_price = round((int) $item['quantity'] * (int) $item['unite_price']);
        $gross_amount = $net_price;
        $vat_amount = '0';

        $corrected_item['unite_price'] = (string) $item['unite_price'];
        $corrected_item['net_price'] = (string) $net_price;
        $corrected_item['gross_amount'] = (string) $gross_amount;
        $corrected_item['vat_amount'] = (string) $vat_amount;

        return $corrected_item;
    }

    public static function correctItemPrices(Proforma $proforma): void
    {
        $items = $proforma->readItems();
        $corrected_items = [];
        foreach ($items as $item) {
            //Correct Missing prices by Brutto or Netto base
            if (is_numeric($item['vat_rate']) && ($item['currency'] === 'HUF' || $item['currency'] === 'Ft')) {
                $corrected_items[] = self::hufCorrection($item);
            }

            if (! is_numeric($item['vat_rate']) && ($item['currency'] === 'HUF' || $item['currency'] === 'Ft')) {
                $corrected_items[] = self::baseCorrection($item);
            }
        }
        $proforma->writeItems($corrected_items);
    }
}
