<?php  
    // $productsData[] = [
    //     'productId' => $rawProductsDataRow['product_id'],
    //     'categoryId' => $rawProductsDataRow['category_id'],
    //     'productCondition' => $rawProductsDataRow['product_condition'],
    //     'productCategoryName' => $rawProductsDataRow['category_name'],
    //     'productName' => $rawProductsDataRow['product_name'],
    //     'productDescription' => $rawProductsDataRow['product_description'],
    //     'productSlug' => $rawProductsDataRow['product_slug'],
    //     'productStatus' => $rawProductsDataRow['product_status'],
    //     'productStatusText' => Product::getStatusText($rawProductsDataRow['product_status']),
    //     'listProductPrice' => [
    //         'currencyCode' => $rawProductsDataRow['ppl_currency_code'],
    //         'priceType' => $rawProductsDataRow['ppl_price_type'],
    //         'netPrice' => $rawProductsDataRow['ppl_net'],
    //         'vatPercent' => $rawProductsDataRow['ppl_vat'],
    //     ],
    //     'activeProductPrice' => [
    //         'binderId' => $rawProductsDataRow['ppa_binder_id'],
    //         'currencyCode' => $rawProductsDataRow['ppa_currency_code'],
    //         'priceType' => $rawProductsDataRow['ppa_price_type'],
    //         'netPrice' => $rawProductsDataRow['ppa_net'],
    //         'vatPercent' => $rawProductsDataRow['ppa_vat'],
    //     ],
    //     'productInfoLink' => $rawProductsDataRow['product_info_link'],
    //     'mainProductImageLink' => $mainProductImageLink,
    //     'productImages' => $productImages,
    // ];
    // dump($productsData);
    // dump($cartDataSet);
    // dump($productsData);
?>

<!-- <div class="pc-container">
    <div class="pcoded-content card-container"> -->
        <?php if (!empty($productsData)): ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-3 g-4">
        <!-- <div class="row row-cols-1 row-cols-sm-3 row-cols-md-4 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-4" style="width: 100%;"> -->
            <?php foreach ($productsData as $productData): ?>
                <?php 
                // dump($cartData);
                $cartItemData = null;
                if (isset($cartDataSet['cart']['cartItems']['productId-'.$productData['productId']]['cartItem'])) {
                    $cartItemData = $cartDataSet['cart']['cartItems']['productId-'.$productData['productId']]['cartItem'];
                }
                include('ProductCard.php');
                ?>
            <?php endforeach; ?>
        </div> <!-- /row -->
        <?php else: ?>
        <div class="row">
            <div class="col-md-12 card-pack-header">
                <h4><?php echo trans('search.has.no.results'); ?></h4>
            </div>
        </div>
        <?php endif; ?>

    <!-- </div>
</div> -->
