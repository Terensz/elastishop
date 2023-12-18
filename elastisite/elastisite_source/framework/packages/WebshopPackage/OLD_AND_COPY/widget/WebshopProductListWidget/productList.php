<?php 
use framework\packages\WebshopPackage\service\WebshopPriceService;
?>

<!-- productList! -->
<style>
.discountedProduct {
    background-color: #1cac37;
    color: #fff;
}
.webshop-productList-discountBar {
    text-decoration: none !important;
}
.webshop-productList-discountBar:hover {
    text-decoration: none !important;
}
</style>
<div class="row">
<?php
// dump($productListMaxCols);
// ===========================================================================
// controllerMethod: webshopProductListWidgetAction + renderWebshopProductList
// ===========================================================================
// dump($productRows);

if (!isset($errors)) {
    $errors = [];
}
$bootstrapColUnits = 4;

if ($productListMaxCols == 6) {
    $bootstrapColUnits = 2;
}
if ($productListMaxCols == 4) {
    $bootstrapColUnits = 3;
}
if ($productListMaxCols == 2) {
    $bootstrapColUnits = 6;
}
$productErrorKeys = array_keys($errors);
foreach ($productRows as $productRow) {
    foreach ($productRow as $colCounter => $product) {
        // $slabPos = $colCounter == 0 ? 'first' : ($colCounter == count($productRow) - 1 ? 'last' : 'mid');
        $slabPos = 'mosaic';

        /**
         * $priceData will come with the following details:
         *   [discount_gross_price] => 2000
         *   [discount_net_price] => 1600
         *   [discount_percent] => 20
         *   [discount_vat] => 25
         *   [id] => 25010
         *   [list_gross_price] => 2500
         *   [list_net_price] => 2000
         *   [list_vat] => 25
        */
        if ($product && !in_array($product->getId(), $productErrorKeys)) {
            $priceData = WebshopPriceService::getActivePriceData($product->getId());
            // dump($priceData);
            // dump($priceData['list_gross_price']);
            
            $discount = $product->getProductPriceActive()->getProductPrice()->getPriceType() == 'discount' ? true : false;
            if ($discount) {
                $discountPercent = WebshopPriceService::format($priceData['discount_percent'], 'discountPercent');
                if ($discountPercent <= 0) {
                    $discount = false;
                }
            }

            $currencyString = ' '.$defaultCurrency;
            if ($discount) {
                $priceString = '<span class="webshop-old-price">'.$priceData['list_gross_price'].$currencyString.'</span> <span class="webshop-new-price">'.$priceData['discount_gross_price'].$currencyString.'</span>';
            } else {
                $priceString = $priceData['list_gross_price'].$currencyString;
            }

            $mainImage = $product ? $product->getRepository()->getMainImage($product->getId()) : null;
            include('productCard.php');
?>

<?php
        /**
         * This else-branch means that there is an error with this product, and an administrator have to mend it
         * in order to let customers list it.
         * As an administrator, you can list those products, also you wil get all the information, what's wrong with them.
        */
        } elseif ($product && App::getContainer()->isGranted('viewProjectAdminContent')) {
            $mainImage = $product ? $product->getRepository()->getMainImage($product->getId()) : null;
            include('productCard_inactive.php');
        }
    }
}
?>
</div>
