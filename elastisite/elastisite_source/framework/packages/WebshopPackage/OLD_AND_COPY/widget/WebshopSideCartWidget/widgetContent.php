<?php 
use framework\packages\WebshopPackage\service\WebshopService;
?>

<div class="widgetWrapper-noPadding">
    <div class="widgetHeader widgetHeader-color">
        <div class="widgetHeader-titleText"><?php

 echo trans('cart.content'); ?></div>
    </div>
    <div class="widgetWrapper-textContainer widgetWrapper-textContainer-bottomMargin">
<?php 
// WebshopSideCartWidget!!!
// dump($cart);
if ($cart) {
    foreach ($cart->getCartItem() as $cartItem) {
?>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-6">
                    <?php echo $cartItem->getQuantity().' '.trans('pcs.of'); ?>&nbsp; <b><?php echo $cartItem->getProduct()->getName(); ?></b>
                </div>
                <div class="col-md-2 col-sm-2 col-2">
                <?php if(WebshopService::getSetting('WebshopPackage_allowCartMultipleQuantity')): ?>
                    <a href="" onclick="SideCart.addToCart(event, <?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>);">+1</a>
                <?php endif; ?>
                </div>
                <div class="col-md-2 col-sm-2 col-2">
                <?php if(WebshopService::getSetting('WebshopPackage_allowCartMultipleQuantity')): ?>
                    <a href="" onclick="SideCart.showOrHideBulkAddContainer(event, <?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>);">+/-</a>
                <?php endif; ?>
                </div>
                <div class="col-md-2 col-sm-2 col-2">
                    <a href="" onclick="SideCart.removeFromCart(event, <?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>);">-1</a>
                </div>
            </div>
            
            <?php if(WebshopService::getSetting('WebshopPackage_allowCartMultipleQuantity')): ?>
            <div id="bulkAddToCart_container_<?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>" class="row" style="display: none;">
                <div class="col-md-6 col-sm-6 col-6">
                    <div class="form-outline">
                        <input value="<?php echo $cartItem->getQuantity(); ?>" type="number" id="bulkAddToCart_newQuantity_<?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>" class="form-control" />
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-6">
                    <button class="btn btn-primary" type="submit" onclick="SideCart.bulkAddToCart(event, <?php echo $cartItem->getProduct()->getProductPriceActive()->getId(); ?>);" 
                        style="margin-left: 0px; margin-right: 0px; height: 38px; padding-top: 6px !important;"><?php echo trans('save'); ?></button>
                </div>
            </div>
            <?php endif; ?>

            <div class="rowSeparator"></div>
<?php
    }
?>
        <div class="row">
            <a class="ajaxCallerLink" href="<?php echo $container->getRoutingHelper()->getLink('webshop_checkout'); ?>"><?php echo trans('checkout'); ?></a>
        </div>
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
var SideCart = {
    showOrHideBulkAddContainer: function(event, offerId) {
        event.preventDefault();
        let bulkAddToCartContainerHtmlId = 'bulkAddToCart_container_' + offerId;
        if ($('#' + bulkAddToCartContainerHtmlId).is(':hidden')) {
            $('#' + bulkAddToCartContainerHtmlId).show();
        } else {
            $('#' + bulkAddToCartContainerHtmlId).hide();
        }
    },
    bulkAddToCart: function(event, offerId) {
        LoadingHandler.start();
        let newQuantity = $('#bulkAddToCart_newQuantity_' + offerId).val();
        if (isNaN(newQuantity)) {
            return false;
        }
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/webshop/addToCart',
            'data': {
                'offerId': offerId,
                'newQuantity': newQuantity
            },
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                WebshopSideCartWidget.call(false);
                Structure.throwToast(response.data.toastTitle, response.data.toastBody);
                SideCart.refreshAddtocartButtons(response.data.cartOfferIds);
                // console.log(response);
                // $(params.responseSelector).html(response.view);
                LoadingHandler.stop();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                LoadingHandler.stop();
            },
        });
    },
    addToCart: function(event, offerId) {
        event.preventDefault();
        LoadingHandler.start();
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/webshop/addToCart',
            'data': {'offerId': offerId},
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                WebshopSideCartWidget.call(false);
                Structure.throwToast(response.data.toastTitle, response.data.toastBody);
                SideCart.refreshAddtocartButtons(response.data.cartOfferIds);
                // console.log(response);
                // $(params.responseSelector).html(response.view);
                LoadingHandler.stop();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                LoadingHandler.stop();
            },
        });
    },
    removeFromCart: function(event, offerId) {
        event.preventDefault();
        LoadingHandler.start();
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/webshop/removeFromCart',
            'data': {'offerId': offerId},
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                WebshopSideCartWidget.call(false);
                Structure.throwToast(response.data.toastTitle, response.data.toastBody);
                SideCart.refreshAddtocartButtons(response.data.cartOfferIds);
                LoadingHandler.stop();
                // console.log(response);
                // $(params.responseSelector).html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                LoadingHandler.stop();
            },
        });
    },
    refreshAddtocartButtons: function(cartOfferIds) {
        if (typeof(ProductList) == "object") {
            ProductList.refreshAddtocartButtons(cartOfferIds);
        }
    }
};
</script>