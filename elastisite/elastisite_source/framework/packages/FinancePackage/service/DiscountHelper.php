<?php
namespace framework\packages\FinancePackage\service;

use App;
use framework\component\parent\Service;

class DiscountHelper extends Service
{
    public static function calculateDiscount($listProductPrice, $activeProductPrice, $debug = null)
    {
        // Check that both arrays are filled
        if (empty($listProductPrice) || empty($activeProductPrice)) {
            throw new \Exception('Missing data');
        }

        $discountAmount = $listProductPrice['priceData']['grossUnitPriceAccurate'] - $activeProductPrice['priceData']['grossUnitPriceAccurate'];
        if ($discountAmount && $discountAmount > 0 && !empty($listProductPrice['priceData']['grossUnitPriceAccurate']) && $listProductPrice['priceData']['grossUnitPriceAccurate'] > 0) {
        
            // Check that currencies are the same
            if ($listProductPrice['currencyCode'] !== $activeProductPrice['currencyCode']) {
                // return "A két árlistának azonos valutának kell lennie!";
                throw new \Exception('Different currencies cannot be compared');
            }
        
            // Számítsa ki a kedvezményt összegben és százalékban
            // dump($listProductPrice);
            // dump($activeProductPrice);
            if ($listProductPrice['priceData']['grossUnitPriceAccurate'] == 0) {
                dump($listProductPrice);
                dump($activeProductPrice);
                dump($debug);
            }

            $discountPercent = ($discountAmount / $listProductPrice['priceData']['grossUnitPriceAccurate']) * 100;
        
            // Ha nincs kedvezmény, akkor 0% kedvezmény
            $hasDiscount = false;
            if ($discountAmount > 0) {
                $hasDiscount = true;
            }
        
            return [
                'hasDiscount' => $hasDiscount,
                'discountAmount' => $discountAmount,
                'discountPercentAccurate' => $discountPercent,
                'discountPercentRounded2' => round($discountPercent, 2)
            ];
        } else {
            return [
                'hasDiscount' => false,
                'discountAmount' => 0,
                'discountPercentAccurate' => 0,
                'discountPercentRounded2' => 0
            ];
        }
    }
}