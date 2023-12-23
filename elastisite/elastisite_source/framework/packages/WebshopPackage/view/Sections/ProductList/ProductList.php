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
    // dump($packDataSet);
    // dump($productsData);
?>

<!-- <div class="pc-container">
    <div class="pcoded-content card-container"> -->
        <!-- <?php if (!empty($productListDataSet)): ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-3 g-4"> -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-4">
        <!-- <div class="row row-cols-1 row-cols-sm-3 row-cols-md-4 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-4" style="width: 100%;"> -->
            <?php foreach ($productListDataSet as $productData): ?>
                <?php 
                // dump($cartData);
                $cartItemData = null;
                if (isset($packDataSet['pack']['packItems']['productId-'.$productData['id']])) {
                    $cartItemData = $packDataSet['pack']['packItems']['productId-'.$productData['id']];
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

<!-- <div class="row row-cols-1 row-cols-sm-3 row-cols-md-4 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-4">
    <div class="col">
        <div class="card">
            <div class=" card-header d-flex justify-content-between align-items-center">
                <div class="card-header-textContainer ellipsis-container" style="width: 100%">
                    <h6 class="mb-0 ellipsis-text">Főcélok</h6>
                </div>
            </div>
            <div class="card-body m-0 p-0">                                                    
                <div class="columnView-cell">
                    <a class="ajaxCallerLink link-underlined" href="/asc/scaleBuilder/columnView/scale/213000/child/215000">
                        <p class="card-text">
                            Alma
                        </p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class=" card-header d-flex justify-content-between align-items-center">
                        <div class="card-header-textContainer ellipsis-container" style="width: 100%">
                            <h6 class="mb-0 ellipsis-text">
                                                                    Célok                                                            </h6>
                        </div>
                    </div>
                                        <div class="card-body m-0 p-0">
                                                                            
                        <div class="columnView-cell">
                            <a class="ajaxCallerLink link-underlined" href="/asc/scaleBuilder/columnView/scale/213000/child/215002">
                                <p class="card-text">
                                    Alma cél                                </p>
                            </a>
                        </div>
                                                                            
                        <div class="columnView-cell">
                            <a class="ajaxCallerLink link-underlined" href="/asc/scaleBuilder/columnView/scale/213000/child/215003">
                                <p class="card-text">
                                    Alma 2 alcél                                </p>
                            </a>
                        </div>
                                            </div>
                </div>
            </div>
                                                        <div class="col">
                <div class="card">
                                                            <div class=" card-header d-flex justify-content-between align-items-center">
                        <div class="card-header-textContainer ellipsis-container" style="width: 100%">
                            <h6 class="mb-0 ellipsis-text">
                                                                    Irányelvek                                                            </h6>
                        </div>
                    </div>
                                        <div class="card-body m-0 p-0">
                                                                            
                        <div class="columnView-cell">
                            <a class="ajaxCallerLink link-underlined" href="/asc/scaleBuilder/columnView/scale/213000/child/215001">
                                <p class="card-text">
                                    Alma irányelv                                </p>
                            </a>
                        </div>
                                            </div>
                </div>
            </div>
                                                                                                                                                                                                                                                            </div> -->