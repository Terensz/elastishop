<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\FinancePackage\entity\InvoiceItem;
use framework\packages\WebshopPackage\dataProvider\PriceDataProvider;

class WebshopInvoiceService extends Service
{
    public static function getRawAddressPattern()
    {
        return [
            'country' => [
                'alpha2Code' => null,
                'translatedName' => null
            ],
            'zipCode' => null,
            'city' => null,
            'street' => null,
            'streetSuffix' => null,
            'houseNumber' => null,
            'staircase' => null,
            'floor' => null,
            'door' => null
        ];
    }

    public static function getRawInvoiceHeaderPattern()
    {
        $pattern = [
            'id' => null,
            'shipmentId' => null,
            'orderNumber' => null,
            'invoiceNumber' => null,
            'correctedInvoiceNumber' => null,
            'invoiceType' => null,
            'currencyCode' => null,
            'paymentMethod' => null,
            'yearOfIssue' => null,
            'sequenceNumber' => null,
            'dateOfIssue' => null,
            'issuer' => [
                'name' => null,
                'bankAccountNumber' => null,
                'address' => self::getRawAddressPattern()
            ],
            'buyer' => [
                'name' => null,
                'personType' => null,
                'taxId' => null,
                'address' => self::getRawAddressPattern()
            ],
            'value' => [
                'totalVat' => null,
                'totalNet' => null,
            ],
            'paymentDeadline' => null,
            'paymentDate' => null,
            'deliveryDate' => null,
            'taxOffice' => [
                'shortName' => null,
                'transactionId' => null,
                'commStatus' => null,
                'errorCode' => null,
                'errorMessage' => null,
            ],
            'reportedAt' => null,
            'createdAt' => null,
            'invoiceItems' => [],
            'invoiceItemsSummary' => [
                'grossAmountAccurate' => null,
                'grossAmountRounded0' => null,
                'grossAmountRounded2' => null,
                'grossAmountFormatted' => null,
            ],
            'validity' => [
                'isValid' => true,
                'errorMessages' => []
            ]
        ];

        return $pattern;
    }

    public static function getRawInvoiceItemPattern()
    {
        $pattern = [
            'invoiceItem' => [
                'id' => null,
                'lineIndex' => null,
                'referencedLineIndex' => null,
                'product' => [
                    'name' => null,
                ],
                'value' => [],
                // 'value' => [
                //     'unitNet' => null,
                //     'itemNet' => null,
                //     'vatPercent' => null,
                //     'itemVat' => null,
                // ],
                'quantity' => null,
                'unitOfMeasure' => null,
                'gatewayProvider' => null,
                'paymentMethod' => null,
                'totalGrossValue' => null,
                'currencyCode' => null,
                'createdAt' => null,
                'redirectedAt' => null,
                'closedAt' => null,
                'status' => null
            ]
        ];

        return $pattern;
    }

    public static function convertCartDataToInvoiceData($packDataSet)
    {
        // dump($packDataSet);//exit;
        $cartData = $packDataSet['pack'];
        App::getContainer()->wireService('FinancePackage/entity/InvoiceItem');
        App::getContainer()->wireService('FinancePackage/entity/InvoiceItem');

        $invoiceItemsData = [];

        $currencyCode = null;

        $invoiceHeaderData = self::getRawInvoiceHeaderPattern();

        if (isset($cartData['packItems']) && is_array($cartData['packItems'])) {
            $summaryGrossAmountAccurate = 0;
            foreach ($cartData['packItems'] as $cartItemData) {
                $invoiceItemData = self::getRawInvoiceItemPattern();

                $itemCurrencyCode = $cartItemData['product']['actualPrice']['currencyCode'];
                if (!$currencyCode) {
                    $currencyCode = $itemCurrencyCode;
                } else {
                    if ($itemCurrencyCode != $currencyCode) {
                        $invoiceHeaderData['validity']['isValid'] = false;
                        $invoiceHeaderData['validity']['errorMessages'][] = trans('items.have.mixed.currencies');
                    }
                }
                // dump($cartItemData['product']['productData']);
                $priceData = PriceDataProvider::assembleDataSet([
                    'quantity' => $cartItemData['quantity'],
                    'grossUnitPrice' => $cartItemData['product']['actualPrice']['grossUnitPriceAccurate'],
                    'vatPercent' => $cartItemData['product']['actualPrice']['vatPercent']
                ]);
                
                // [
                //     'productName' => $cartItemData['product']['productData']['productName'],
                //     'currencyCode' => $itemCurrencyCode,
                //     'quantity' => $cartItemData['quantity'],
                //     'unitOfMeasure' => InvoiceItem::UNIT_OF_MEASURE_PIECE,
                //      => $priceData
                // ];

                $invoiceItemData['invoiceItem']['product']['name'] = $cartItemData['product']['name'];
                $invoiceItemData['invoiceItem']['currencyCode'] = $itemCurrencyCode;
                $invoiceItemData['invoiceItem']['quantity'] = $cartItemData['quantity'];
                $invoiceItemData['invoiceItem']['unitOfMeasure'] = InvoiceItem::UNIT_OF_MEASURE_PIECE;
                $invoiceItemData['invoiceItem']['value'] = $priceData;
                $invoiceItemsData[] = $invoiceItemData;
                $summaryGrossAmountAccurate += $priceData['grossItemPriceAccurate'];
            }

            $invoiceHeaderData['invoiceItems'] = $invoiceItemsData;
            $invoiceHeaderData['invoiceItemsSummary']['grossAmountAccurate'] = $summaryGrossAmountAccurate;
            $invoiceHeaderData['invoiceItemsSummary']['grossAmountRounded0'] = round($summaryGrossAmountAccurate, 0);
            $invoiceHeaderData['invoiceItemsSummary']['grossAmountRounded2'] = round($summaryGrossAmountAccurate, 2);
            $invoiceHeaderData['invoiceItemsSummary']['grossAmountFormatted'] = StringHelper::formatNumber($summaryGrossAmountAccurate, 2, ',', '.');
            $invoiceHeaderData['currencyCode'] = $currencyCode;
        } else {
            $invoiceHeaderData['validity']['isValid'] = false;
            $invoiceHeaderData['validity']['errorMessages'][] = trans('no.invoice.items');
        }

        // dump($invoiceHeaderData);exit;

        return $invoiceHeaderData;
    }

    // public static function convertCartDataToInvoiceData_OLD($cartData)
    // {
    //     App::getContainer()->wireService('FinancePackage/entity/InvoiceItem');

    //     $invoiceData = [
    //         'invoiceHeaderData' => [],
    //         'invoiceFinancialData' => [
    //             'currencyCode' => null
    //         ],
    //         'invoiceItemsData' => [],
    //         'invoiceItemsSummary' => [
    //             'summaryGrossAmountAccurate' => null,
    //             'summaryGrossAmountRounded0' => null,
    //             'summaryGrossAmountRounded2' => null,
    //         ],
    //         'isValid' => true,
    //         'invalidityReason' => null
    //     ];

    //     $invoiceItemsData = [];

    //     $currencyCode = null;

    //     if (isset($cartData['packItems']) && is_array($cartData['packItems'])) {
    //         $summaryGrossAmountAccurate = 0;
    //         foreach ($cartData['packItems'] as $cartItemData) {
    //             $itemCurrencyCode = $cartItemData['product']['productData']['actualPrice']['currencyCode'];
    //             if (!$currencyCode) {
    //                 $currencyCode = $itemCurrencyCode;
    //             } else {
    //                 if ($itemCurrencyCode != $currencyCode) {
    //                     $invoiceData['isValid'] = false;
    //                     $invoiceData['invalidityReason'] = trans('items.have.mixed.currencies');
    //                 }
    //             }

    //             $priceData = PriceDataService::assembleProductPriceData([
    //                 'quantity' => $cartItemData['quantity'],
    //                 'netUnitPrice' => $cartItemData['product']['productData']['actualPrice']['netUnitPrice'],
    //                 'vatPercent' => $cartItemData['product']['productData']['actualPrice']['vatPercent']
    //             ]);

    //             $invoiceItemsData[] = [
    //                 'productName' => $cartItemData['product']['productData']['productName'],
    //                 'currencyCode' => $itemCurrencyCode,
    //                 'quantity' => $cartItemData['quantity'],
    //                 'unitOfMeasure' => InvoiceItem::UNIT_OF_MEASURE_PIECE,
    //                  => $priceData
    //             ];
    //             $summaryGrossAmountAccurate += $priceData['grossItemPriceAccurate'];
    //         }

    //         $invoiceData['invoiceItemsData'] = $invoiceItemsData;
    //         $invoiceData['invoiceItemsSummary']['summaryGrossAmountAccurate'] = $summaryGrossAmountAccurate;
    //         $invoiceData['invoiceItemsSummary']['summaryGrossAmountRounded0'] = round($summaryGrossAmountAccurate, 0);
    //         $invoiceData['invoiceItemsSummary']['summaryGrossAmountRounded2'] = round($summaryGrossAmountAccurate, 2);
    //         $invoiceData['invoiceItemsSummary']['summaryGrossAmountFormatted'] = StringHelper::formatNumber($summaryGrossAmountAccurate, 2, ',', '.');
    //         $invoiceData['invoiceFinancialData']['currencyCode'] = $currencyCode;
    //     } else {
    //         $invoiceData['isValid'] = false;
    //         $invoiceData['invalidityReason'] = trans('no.invoice.items');
    //     }

    //     // dump($invoiceData);exit;

    //     return $invoiceData;
    // }
}
