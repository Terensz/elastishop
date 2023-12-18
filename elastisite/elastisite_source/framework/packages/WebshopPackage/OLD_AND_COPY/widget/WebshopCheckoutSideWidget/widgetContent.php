<div class="widgetWrapper-noPadding">
    <div class="widgetHeader widgetHeader-color">
        <div class="widgetHeader-titleText"><?php echo trans('cart.content'); ?></div>
    </div>
    <div class="widgetWrapper-textContainer widgetWrapper-textContainer-bottomMargin">
    <!-- <div class="rowSeparator"></div> -->
<?php 
// WebshopSideCartWidget!!!
// dump($cart);
if ($cart) {
    foreach ($cart->getCartItem() as $cartItem) {
?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <?php echo $cartItem->getQuantity().' '.trans('pcs.of'); ?>&nbsp; <b><?php echo $cartItem->getProduct()->getName(); ?></b>
                </div>
            </div>
<?php
    }
?>

<?php
} else {
?>
        <div class="row">
            <?php echo trans('cart.is.empty'); ?>
        </div>
<?php
}
?>
    </div>
</div>
<script>
    $('document').ready(function() {
        // Structure.loadWidget('WebshopCheckoutSideWidget');

    });
</script>