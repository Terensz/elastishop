<?php 
include('framework/packages/WebshopPackage/view/Parts/TestWebshopWarning.php');
?>

<div class="widgetWrapper">
    <div class="article-container">
        <div class="article-head">
            <div class="article-title"><?php echo trans('order.successful'); ?></div>
        </div>
        <div class="article-content"></div>
        <div class="articleFooter"></div>
    </div>
</div>

<script>
    MenuWidget.call();
</script>