<?php 

use framework\component\helper\StringHelper;

// dump($cartDataSet);
?>
<div class="card mb-3 card-noBorderRadius">

    <!-- <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0">
                <a class="link-underlined ajaxCallerLink text-black" href="/asc/scaleBuilder/scale/213005">
                Kosár
                </a>
            </h6>
        </div>
    </div> -->

    <div class="card-footer">
        <div class="col-md-12 sidebar-text-container d-flex align-items-center">
            <img src="/public_folder/plugin/Bootstrap-icons/Dashkit-light/cart.svg" class="me-2 mb-3" alt="Cart Icon">
            <h5 class="mb-2">Kosár</h5>
        </div>
    </div>

    <!-- <section class="w-100 p-2"> -->

        <!-- <div class="row">
            <div class="col">
                <div class="card mb-0 card-noBorderRadius card-innerCard">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row align-items-center" style="flex-shrink: 0;">
                            <div>
                                <img src="/webshop/image/thumbnail/u8PXfyKTh98T" class="img-fluid rounded-3" alt="Shopping item" style="width: 65px; height:65px;">
                            </div>
                            <div class="ms-3">
                                <div class="mb-3">
                                    <div class="text-truncate" style="max-width: 200px;">Iphone 11 pro super space tank 2000 plus franko telo i+</div>
                                </div>
                                <div class="d-flex flex-row align-items-end">
                                    <div style="width: 80px;">
                                        <b><h6 class="mb-0">$900</h6></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ms-auto text-end" style="width: 50px !important;">
                            <div>
                                <a href="#!" style="color: #cecece;"><i class="fas fa-edit"></i></a>
                            </div>
                            <h5 class="fw-normal mb-0"><b>4 db</b></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
<?php  
// $cartData = [];
$cartItemCounter = 0;
?>
<?php if (empty($cartDataSet['cart']['cartItems'])): ?>
    <div class="row">
        <div class="col-md-12 sidebar-text-container d-flex align-items-center">
            <h6 class="mb-2"><?php echo trans('cart.is.empty'); ?></h6>
        </div>
    </div>
<?php else: ?>
    <?php
    $cartItemCounter = 0;
    ?>
    <?php foreach ($cartDataSet['cart']['cartItems'] as $cartDataSetRow): ?>
    <?php 
    $cartItemData = $cartDataSetRow['cartItem'];
    // dump($cartItemData);
    // dump('hello');
    // dump($cartItemData['product']['productData']['activeProductPrice']);
    // if (!isset($cartItemData['product']['productData']['mainProductImageLink'])) {
    //     dump($cartItemData['product']);
    // }
    $mainProductImageLink = $cartItemData['product']['mainProductImageLink'];
    $productName = $cartItemData['product']['productName'];
    $grossItemPriceFormatted = $cartItemData['product']['activeProductPrice']['priceData']['grossUnitPriceFormatted'];
    $quantity = $cartItemData['quantity'];
    $grossUnitPriceFormatted = $cartItemData['quantity'] * $cartItemData['product']['activeProductPrice']['priceData']['grossUnitPriceRounded2'];
    $grossUnitPriceFormatted = StringHelper::formatNumber($grossUnitPriceFormatted, 2, ',', '.');
    $currencyCode = $cartItemData['product']['activeProductPrice']['currencyCode'];
    $specialPurpose = $cartItemData['product']['specialPurpose'];
    $editIconOnclick = $specialPurpose ? null : "Webshop.setCartItemQuantityInit(event, '".$cartItemData['product']['activeProductPrice']['offerId']."');";
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