<?php 
include('framework/packages/WebshopPackage/view/Parts/TestWebshopWarning.php');
?>

<div class="widgetWrapper">
    <div class="article-head">
        <div class="article-title"><?php echo trans('order.remove.fail'); ?></div>
    </div>
    <div class="article-content"><?php echo $text; ?></div>
    <br>
    <a class="ajaxCallerLink" href="<?php echo $container->getRoutingHelper()->getLink('webshop_productList_noFilter'); ?>/"><?php echo trans('return.to.webshop'); ?></a>
</div>