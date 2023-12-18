<?php 
// dump($isWebshopTestMode);
?>
<div style="padding: 20px;">
<?php 
include('framework/packages/WebshopPackage/view/Parts/TestWebshopWarning.php');
?>

    <div class="widgetWrapper-noPadding">
        <div class="widgetHeader widgetHeader-color">
            <div class="widgetHeader-titleText">
                <h1><?php echo trans('order.' . $result); ?></h1>
            </div>
        </div>
        <div class="widgetWrapper-textContainer">
            <div><?php echo $textView; ?></div>
        </div>
    </div>
</div>