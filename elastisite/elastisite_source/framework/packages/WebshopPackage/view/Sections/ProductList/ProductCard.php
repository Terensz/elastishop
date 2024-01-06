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
    // dump($productData);
    // dump($cartItemData);
?>

<div class="col">
    <div class="productCard card<?php if ($cartItemData) { echo ' card-highlighted'; } ?>"<?php if (isset($maxWidthPixels)) { echo ' style="max-width: '.$maxWidthPixels.'px;"'; } ?>>
        <div class="card-header p-2 d-flex justify-content-between align-items-center">
            <div class="card-header-textContainer ellipsis-container" style="width: 100%;">
            <?php if ($cartItemData): ?>
                <h6 class="mb-0 ellipsis-text"><span class="fontColor-pale"><?php echo trans('in.cart'); ?>:</span> <?php echo $cartItemData['quantity']; ?> <?php echo trans('pcs.of'); ?></h6>
            <?php else: ?>
                <h6 class="mb-0 ellipsis-text fontColor-extraPale"><?php echo trans('not.in.cart'); ?></h6>
            <?php endif; ?>
            </div>
        </div>
        <?php if (!empty($productData['mainProductImageLink'])): ?>
        <div class="card-image-container m-0 p-0">
            <img class="card-image" src="<?php echo $productData['mainProductImageLink']; ?>">
        </div>
        <?php else: ?>
        <div class="card-image-container m-0 p-0" style="background-color: #000;">

        </div>
        <?php endif; ?>
        <div class="card-header p-2 d-flex justify-content-between align-items-center">
            <div class="card-header-textContainer ellipsis-container" style="width: 100%;">
                <h6 class="mb-0 ellipsis-text"><?php echo $productData['name']; ?></h6>
            </div>
        </div>
        <div class="card-body p-2">

            <div class="webshop-productCard-price">
                <h4 class="mb-0 ellipsis-text">
                <?php if ($productData['discountData']['hasDiscount']): ?>
                    <b><span class="fontColor-extraPale" style="text-decoration: line-through;"><?php echo $productData['listPrice']['grossUnitPriceRounded0'].' '.$productData['listPrice']['currencyCode']; ?></span></b>
                <?php endif; ?>
                    <b><?php echo $productData['actualPrice']['grossUnitPriceRounded0'].' '.$productData['actualPrice']['currencyCode']; ?></b>
                </h4>
            </div>

            <?php if (!empty($productData['productCategory']['name'])): ?>
            <div class="row">
                <div class="col-md-12">
                    <h6 class="mb-0 ellipsis-text fontColor-pale"><?php echo trans('category') . ': ' . $productData['productCategory']['name']; ?></h6>
                </div>
            </div>
            <?php endif; ?>

        </div>
        <?php if (!isset($options['skipFooter']) || $options['skipFooter'] === false): ?>
        <div class="card-footer p-2">
            <div class="d-flex">

                <a href="" onclick="Webshop.setCartItemQuantityInit(event, <?php echo $productData['activePrice']['offerId']; ?>);" class="ajaxCallerLink pc-link" style="display: flex; gap: 4px; text-decoration: none;">
                    <span class="">
                        <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-blue/cart.svg" style="width:16px; height: 16px;">
                    </span>
                    <span class="m-1">
                        <h6 class="linkText"><?php echo trans('cart.quantity'); ?> (+/-)</h6>
                    </span>
                </a>

                <a href="<?php echo '/'.$localizedProductInfoLinkBase.$productData['slug']; ?>" onclick="Webshop.showProductDetailsModalInit(event, '<?php echo $productData['id']; ?>');" class="pc-link" style="display: flex; gap: 0px; text-decoration: none; padding-left: 8px;">
                    <span class="">
                        <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-blue/info-circle.svg" style="width:16px; height: 16px;">
                    </span>
                    <span class="m-1">
                        <h6 class="linkText"><?php echo trans('more.info'); ?></h6>
                    </span>
                </a>

            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
