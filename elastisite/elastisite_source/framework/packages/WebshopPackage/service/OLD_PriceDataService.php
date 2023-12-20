<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\repository\ShipmentRepository;

class OLD_PriceDataService extends Service
{
    /*
    Example rawData:
    $rawData = [
        'productName' => $cartItemData['product']['productData']['productName'],
        'currencyCode' => $itemCurrencyCode,
        'quantity' => $cartItemData['quantity'],
        'unitOfMeasure' => InvoiceItem::UNIT_OF_MEASURE_PIECE,
        'priceDetails'
        'netUnitPrice' => $netUnitPrice,
        
    ];
    */
    public static function assembleProductPriceData($rawData)
    {
        if (!isset($rawData['quantity']) || !$rawData['quantity']) {
            $rawData['quantity'] = null;
        }
        
        if (!isset($rawData['grossUnitPrice'])) {
            // $rawData['netUnitPrice'] = 0;
            throw new \Exception('grossUnitPrice is required!');
        }
        $rawData['grossUnitPrice'] = (float)$rawData['grossUnitPrice'];

        if (!isset($rawData['vatPercent'])) {
            throw new \Exception('vatPercent is required!');
        }

        $grossMaker = (100 + (int)$rawData['vatPercent']) / 100;
        $netUnitPriceAccurate = $rawData['grossUnitPrice'] / $grossMaker;
        $netUnitPriceRounded0 = round($netUnitPriceAccurate, 0);
        $netUnitPriceRounded2 = round($netUnitPriceAccurate, 2);
        $netUnitPriceFormatted = StringHelper::formatNumber($netUnitPriceAccurate, 2, ',', '.');

        $grossUnitPriceAccurate = $rawData['grossUnitPrice'];
        $grossUnitPriceRounded0 = round($grossUnitPriceAccurate, 0);
        $grossUnitPriceRounded2 = round($grossUnitPriceAccurate, 2);
        $grossUnitPriceFormatted = StringHelper::formatNumber($grossUnitPriceAccurate, 2, ',', '.');

        if ($rawData['quantity']) {
            $netItemPriceAccurate = $netUnitPriceAccurate * $rawData['quantity'];
            $netItemPriceRounded0 = round($netItemPriceAccurate, 0);
            $netItemPriceRounded2 = round($netItemPriceAccurate, 2);
            $netItemPriceFormatted = StringHelper::formatNumber($netItemPriceAccurate, 2, ',', '.');

            $grossItemPriceAccurate = $grossUnitPriceAccurate * $rawData['quantity'];
            $grossItemPriceRounded0 = round($grossItemPriceAccurate, 0);
            $grossItemPriceRounded2 = round($grossItemPriceAccurate, 2);
            $grossItemPriceFormatted = StringHelper::formatNumber($grossItemPriceAccurate, 2, ',', '.');
        } else {
            $netItemPriceAccurate = null;
            $netItemPriceRounded0 = null;
            $netItemPriceRounded2 = null;
            $netItemPriceFormatted = null;

            $grossItemPriceAccurate = null;
            $grossItemPriceRounded0 = null;
            $grossItemPriceRounded2 = null;
            $grossItemPriceFormatted = null;
        }

        $data = [
            // 'netUnitPriceFormatted' => StringHelper::formatNumber($rawData['netUnitPrice'], 2, ',', '.'),
            'vatPercent' => $rawData['vatPercent'],
            'quantity' => $rawData['quantity'],
            'netUnitPriceAccurate' => $netUnitPriceAccurate,
            'netUnitPriceRounded0' => $netUnitPriceRounded0,
            'netUnitPriceRounded2' => $netUnitPriceRounded2,
            'netUnitPriceFormatted' => $netUnitPriceFormatted,
            'grossUnitPriceAccurate' => $grossUnitPriceAccurate,
            'grossUnitPriceRounded0' => $grossUnitPriceRounded0,
            'grossUnitPriceRounded2' => $grossUnitPriceRounded2,
            'grossUnitPriceFormatted' => $grossUnitPriceFormatted,
            'netItemPriceAccurate' => $netItemPriceAccurate,
            'netItemPriceRounded0' => $netItemPriceRounded0,
            'netItemPriceRounded2' => $netItemPriceRounded2,
            'netItemPriceFormatted' => $netItemPriceFormatted,
            'grossItemPriceAccurate' => $grossItemPriceAccurate,
            'grossItemPriceRounded0' => $grossItemPriceRounded0,
            'grossItemPriceRounded2' => $grossItemPriceRounded2,
            'grossItemPriceFormatted' => $grossItemPriceFormatted
        ];

        return $data;
    }
}
