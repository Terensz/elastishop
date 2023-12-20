<?php 

use framework\component\helper\StringHelper;

// dump($packDataSet);
?>
<div class="card mb-3 card-noBorderRadius">
    <div class="card-footer">
        <div class="col-md-12 sidebar-text-container d-flex align-items-center">
            <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-light/cart.svg" class="me-2 mb-3" alt="Cart Icon">
            <h5 class="mb-2"><?php echo trans('cart'); ?></h5>
        </div>
    </div>
<?php  
// $cartData = [];
$cartItemCounter = 0;
?>
<?php if (empty($packDataSet['pack']['packItems'])): ?>
    <div class="row">
        <div class="col-md-12 sidebar-text-container d-flex align-items-center">
            <h6 class="mb-2"><?php echo trans('cart.is.empty'); ?></h6>
        </div>
    </div>
<?php else: ?>
    <?php
    $cartItemCounter = 0;
    ?>
    <?php foreach ($packDataSet['pack']['packItems'] as $packItem): ?>
    <?php 
    // $cartItemData = $cartDataSetRow['cartItem'];
    // dump($cartItemData);
    // dump('hello');
    // dump($cartItemData['product']['productData']['activeProductPrice']);
    // if (!isset($cartItemData['product']['productData']['mainProductImageLink'])) {
    //     dump($cartItemData['product']);
    // }
    $mainProductImageLink = $packItem['product']['mainProductImageLink'];
    $productName = $packItem['product']['name'];
    $grossItemPriceFormatted = $packItem['product']['actualPrice']['grossUnitPriceFormatted'];
    $quantity = $packItem['quantity'];
    $grossUnitPriceFormatted = $packItem['quantity'] * $packItem['product']['actualPrice']['grossUnitPriceRounded2'];
    $grossUnitPriceFormatted = StringHelper::formatNumber($grossUnitPriceFormatted, 2, ',', '.');
    $currencyCode = $packItem['product']['actualPrice']['currencyCode'];
    $specialPurpose = $packItem['product']['specialPurpose'];
    /**
     * Notice that we use actual price wherever we can. But we only store offerId at the activePrice set.
    */
    $editIconOnclick = $specialPurpose ? null : "Webshop.setCartItemQuantityInit(event, '".$packItem['product']['activePrice']['offerId']."');";
    include('framework/packages/WebshopPackage/view/Common/ProductTinyCardFooter/ProductTinyCardFooter.php');
    $cartItemCounter++;
    ?>
    <?php endforeach; ?>
    <div class="card-footer">
        <div class="col-md-12 sidebar-text-container d-flex align-items-center">
            <?php if ($cartItemCounter == 0): ?>
            
            <?php else: ?>
            <a href="<?php echo $checkoutLink; ?>" class="ajaxCallerLink pc-link" style="display: flex; gap: 4px; text-decoration: none;">
                <span class="">
                    <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-blue/credit-card.svg">
                </span>
                <span class="m-1">
                    <h6 class="linkText"><?php echo trans('checkout'); ?></h6>
                </span>
            </a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

</div>