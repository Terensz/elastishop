<?php 
include('framework/packages/WebshopPackage/view/Parts/TestWebshopWarning.php');
?>

<div class="widgetWrapper">
    <div class="article-head">
        <div class="article-title"><?php echo trans('cart.lost.error'); ?></div>
    </div>
    <br>
    <a class="ajaxCallerLink" href="<?php echo $unfilteredProductListLink; ?>/"><?php echo trans('return.to.webshop'); ?></a>
</div>