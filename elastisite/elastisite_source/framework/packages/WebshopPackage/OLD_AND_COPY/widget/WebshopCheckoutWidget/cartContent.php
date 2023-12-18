<?php
use framework\packages\WebshopPackage\service\WebshopService;

$counter = 0;
$totalPrice = 0;
?>
<?php if ($cart && count($cart->getCartItem()) > 0): ?>
    <?php foreach ($cart->getCartItem() as $cartItem): ?>
<?php
        $netPrice = $cartItem->getProductPrice()->getNetPrice();
        $vat = $cartItem->getProductPrice()->getVat();
        $grossPiecePrice = ceil($netPrice * (1 + $vat / 100));
        $grossPiecePriceString = number_format($grossPiecePrice, 0, null, ' ');
        $stackGrossPrice = $grossPiecePrice * $cartItem->getQuantity();
        $stackGrossPriceString = number_format($stackGrossPrice, 0, null, ' ');
        $totalPrice += $stackGrossPrice;
?>
        <div>
            <div class="row">
                <div class="col-md-4 checkout-cartItemCell">
                    <div class="row">
                        <div class="col-md-4">
                            <?php if(WebshopService::getSetting('WebshopPackage_allowCartMultipleQuantity')): ?>
                            <!-- <button style="width: 36px;" onclick="WebshopCheckout.addToCart(event, <?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>);" class="btn btn-primary">+1</button> -->
                            <a href="" onclick="WebshopCheckout.addToCart(event, <?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>);">+1</a>
                            <?php else: ?>
                                &nbsp;
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <?php if(WebshopService::getSetting('WebshopPackage_allowCartMultipleQuantity')): ?>
                            <!-- <button style="width: 36px;" onclick="WebshopCheckout.addToCart(event, <?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>);" class="btn btn-primary">+1</button> -->
                            <a href="" onclick="WebshopCheckout.showOrHideBulkAddContainer(event, <?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>);">+/-</a>
                            <?php else: ?>
                                &nbsp;
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <?php if ($allowRemoveLastCartItem || $cart->getRepository()->countItems($cart) > 1): ?>
                            <a href="" onclick="WebshopCheckout.removeFromCart(event, <?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>);">-1</a>
                            <!-- <button style="width: 36px;" onclick="WebshopCheckout.removeFromCart(event, <?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>);" class="btn btn-danger">-1</button> -->
                            <?php else: ?>
                                &nbsp;
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if(WebshopService::getSetting('WebshopPackage_allowCartMultipleQuantity')): ?>
                        <!-- display: none; -->
                    <div id="WebshopCheckoutBulkAddToCart_container_<?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>" class="row" style="display: none;">
                        <div class="col-md-6 col-sm-6 col-6">
                            <div class="form-outline">
                                <input value="<?php echo $cartItem->getQuantity(); ?>" type="number" id="WebshopCheckoutBulkAddToCart_newQuantity_<?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-6">
                            <button class="btn btn-primary" type="submit" onclick="WebshopCheckout.bulkAddToCart(event, <?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>);" 
                                style="margin-left: 0px; margin-right: 0px; height: 38px; padding-top: 6px !important;"><?php echo trans('save'); ?></button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-3 checkout-cartItemCell">
                    <?php echo $cartItem->getQuantity().' '.trans('pcs.of'); ?>&nbsp; <b><?php echo $cartItem->getProduct()->getName(); ?></b>
                </div>
                <div class="col-md-2 checkout-cartItemCell" style="text-align: right;">
                    <b><?php echo $stackGrossPriceString; ?></b> <?php echo $defaultCurrency; ?>
                </div>
                <div class="col-md-3 checkout-cartItemCell">
                    <span style="font-size: 14px;">(<?php echo trans('vat2'); ?>: <?php echo $vat; ?>%, <?php echo trans('gross.piece.price'); ?>: <?php echo $grossPiecePrice; ?> <?php echo $defaultCurrency; ?>)</span>
                </div>
            </div>
        </div>
        <?php if ($counter < count($cart->getCartItem()) - 1) { ?><div class="rowSeparator"></div><?php } ?>
<?php 
        $counter++;
?>
        <?php endforeach; ?>
<?php else: ?>
<script>
$(document).ready(function() {

});
</script>
<?php endif; ?>
<?php

?>
        <div class="rowSeparator"></div>
        <div class="">
            <div class="row">
                <div class="col-md-4 checkout-cartItemCell">
                </div>
                <div class="col-md-3 checkout-cartItemCell">
                    <?php echo trans('total'); ?> (<?php echo trans('gross'); ?>)
                </div>
                <div class="col-md-2 checkout-cartItemCell superHighlight" style="text-align: right;">
                    <b><?php echo number_format($totalPrice, 0, null, ' '); ?></b> <?php echo $defaultCurrency; ?>
                </div>
                <div class="col-md-3">
                </div>
            </div>
        </div>