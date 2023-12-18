<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\WebshopPackage\exception\WebshopException;

class WebshopPriceService_OLD extends Service
{
    const DEFAULT_CURRENCY_TAG = 'default';

    const DATA_TYPE_TAG_DISCOUNT_PERCENT = 'discountPercent';
    const DATA_TYPE_KEY_DISCOUNT_PERCENT = 'discount_percent';
    const DATA_TYPE_TAG_PRICE = 'price';

    protected static $analyzedPriceDataCache = [];

    public static function getDefaultCurrency()
    {
        $container = App::getContainer();
        
        return $container->getConfig()->getProjectData('defaultCurrency');
    }

    public static function getActiveCurrency()
    {
        return self::getDefaultCurrency();
    }

    public static function getShipmentFinancialData($shipment, $currency = self::DEFAULT_CURRENCY_TAG) 
    {
        $totalPayable = 0;
        $totalNet = 0;
        $totalVat = 0;
        $orderedProducts = [];
        foreach ($shipment->getShipmentItem() as $shipmentItem) {

            $analyzedPriceData = self::getAnalyzedPriceData($shipmentItem->getProductPrice()->getId());
            // dump($shipmentItem->getProductPrice()->getId());
            // dump($shipmentItem);
            // dump($analyzedPriceData);
            $itemGross = $shipmentItem->getQuantity() * $analyzedPriceData['gross_price'];
            $itemNet = $shipmentItem->getQuantity() * $analyzedPriceData['net_price'];
            $totalPayable += $itemGross;
            $itemVat = $itemGross - $itemNet;
            $totalVat += $itemGross;
            $totalNet += $itemNet;
            $orderedProducts[] = [
                'productId' => $shipmentItem->getProduct()->getId(),
                'productName' => $shipmentItem->getProduct()->getName(),
                'quantity' => $shipmentItem->getQuantity(),
                'unitOfMeasure' => $shipmentItem->getUnitOfMeasure(),
                'currency' => ($currency == self::DEFAULT_CURRENCY_TAG ? self::getActiveCurrency() : $currency),
                'vatPercent' => $analyzedPriceData['vat'],
                'unitNet' => $analyzedPriceData['net_price'],
                'unitGross' => $analyzedPriceData['gross_price'],
                'itemVat' => $itemVat,
                'itemNet' => $itemNet,
                'itemGross' => $itemGross
            ];
        }

        return [
            'orderedProducts' => $orderedProducts,
            'totalPayable' => $totalPayable,
            'totalNet' => $totalNet,
            'totalVat' => $totalVat
        ];
    }

    public static function format($data, $dataType = self::DATA_TYPE_TAG_PRICE, $forcedDecimals = false, $forcedDecimalSeparator = false)
    {
        if ($forcedDecimals) {
            $decimals = $forcedDecimals;
        } else {
            $decimals = $dataType == self::DATA_TYPE_TAG_DISCOUNT_PERCENT ? WebshopService::getSetting('WebshopPackage_discountPercentDecimals') : WebshopService::getSetting('WebshopPackage_priceDecimals');
        }
        $resultData = number_format((float)$data, $decimals, ($forcedDecimalSeparator ? : WebshopService::getSetting('WebshopPackage_priceDecimalSeparator')), '');

        return $resultData;
    }

    public static function getFormattedPriceData($productPriceId, $dataKey) 
    {
        $analyzedPriceData = self::getAnalyzedPriceData($productPriceId);
        if (!isset($analyzedPriceData[$dataKey])) {
            return false;
        }

        $dataType = $dataKey == self::DATA_TYPE_KEY_DISCOUNT_PERCENT ? self::DATA_TYPE_TAG_DISCOUNT_PERCENT : self::DATA_TYPE_TAG_PRICE;

        return self::format($analyzedPriceData[$dataKey], $dataType);
    }

    public static function getAnalyzedPriceData($productPriceId) 
    {
        if (isset(self::$analyzedPriceDataCache[$productPriceId])) {
            return self::$analyzedPriceDataCache[$productPriceId];
        }

        App::getContainer()->setService('WebshopPackage/repository/ProductPriceRepository');
        App::getContainer()->wireService('WebshopPackage/exception/WebshopException');
        $productPriceRepo = App::getContainer()->getService('ProductPriceRepository');

        $productPrice = $productPriceRepo->find($productPriceId);
        if (!$productPrice) {
            throw new WebshopException(trans('not.existing.product.price'));
        }
        $priceData = $productPriceRepo->getPriceData($productPriceId);

        $analyzedPriceData = [];

        if ($priceData) {
            $priceData['requested_price_id'] = (int)$priceData['requested_price_id'];
            $priceData['active_price_id'] = (int)$priceData['active_price_id'];
            $priceData['list_price_id'] = (int)$priceData['list_price_id'];

            $priceData['requested_vat'] = (int)$priceData['requested_vat'];
            $priceData['active_vat'] = (int)$priceData['active_vat'];
            $priceData['list_vat'] = (int)$priceData['list_vat'];

            $analyzedPriceData['requested_price_id'] = $priceData['requested_price_id'];

            $listGrossPrice = $priceData['list_net_price'] * (1 + ($priceData['list_vat'] / 100));
            $analyzedPriceData['list_gross_price'] = $listGrossPrice;

            if ($priceData['requested_price_id'] == $priceData['list_price_id']) {
                /**
                 * Requested price is a LIST price
                */
                $analyzedPriceData['discount_net_price'] = null;
                $analyzedPriceData['discount_gross_price'] = null;
                $analyzedPriceData[self::DATA_TYPE_KEY_DISCOUNT_PERCENT] = 0;
            } else {
                /**
                 * Requested price is a DISCOUNT price
                */
                $discountGrossPrice = $priceData['requested_net_price'] * (1 + ($priceData['requested_vat'] / 100));
                $analyzedPriceData['discount_gross_price'] = $discountGrossPrice;
                $analyzedPriceData[self::DATA_TYPE_KEY_DISCOUNT_PERCENT] = 100 * (1 - ($discountGrossPrice / $listGrossPrice));
            }

            if ($priceData['active_price_id'] == $priceData['requested_price_id']) {
                // $analyzedPriceData['price_changed'] = false;
                $analyzedPriceData['price_changed_to'] = false;
            } else {
                $analyzedPriceData['price_changed_to'] = $priceData['active_net_price'];
            }

            // ksort($analyzedPriceData);

            $analyzedPriceData['net_price'] = $priceData['requested_net_price'];
            $analyzedPriceData['vat'] = $priceData['requested_vat'];

            if ($analyzedPriceData[self::DATA_TYPE_KEY_DISCOUNT_PERCENT] == 0) {
                $analyzedPriceData['gross_price'] = $analyzedPriceData['list_gross_price'];
            } else {
                $analyzedPriceData['gross_price'] = $analyzedPriceData['discount_gross_price'];
            }
        }

        self::$analyzedPriceDataCache[$productPriceId] = $analyzedPriceData;

        return $analyzedPriceData;
    }

    public static function getActivePriceData($productId)
    {
        App::getContainer()->setService('WebshopPackage/repository/ProductPriceRepository');
        $productPriceRepo = App::getContainer()->getService('ProductPriceRepository');
        $activeProductPriceId = $productPriceRepo->getActivePriceId($productId);

        return self::getAnalyzedPriceData($activeProductPriceId);
    }
}
