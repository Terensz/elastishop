<div id="cookieBox-detailedList-container">
    <div style="background-color: #eaeaea; border-bottom: 1px solid #c0c0c0; padding: 10px;">
        <div style="width: 260px; float: left;"><?php echo trans('remove.decision'); ?></div>
        <div style="text-align: center;"><a href="" onclick="CookieBoxWidget.closeDetailedList(event);">X</a></div>
        <?php  ?>
    </div>

    <div class="cookieBox-detailedList-detailsContainer" style="color: #b1b1b1;"><?php echo trans('remove.decision.info'); ?></div>

    <div class="cookieBox-detailedList-detailsContainer">
<?php 
$counter = 0;
?>
    <?php foreach ($thirdPartyCookiesAcceptances as $thirdPartyCookiesAcceptance): ?>
        <?php if ($counter > 0): ?>
        <div class="rowSeparator"></div>
        <?php endif; ?>
        <div>
            <div class="row" style="height: 30px;">
                <div class="col-4 DefaultFontBold">
                    <?php echo $thirdPartyCookiesAcceptance->getRequestSubscriber(); ?>
                </div>
                <div class="col-4">
                    <?php echo trans($thirdPartyCookiesAcceptance->getAcceptance()); ?>
                </div>
                <div class="col-4">
                    <a href="" onclick="CookieInterface.removeRefusedConsent(event, '<?php echo $thirdPartyCookiesAcceptance->getRequestSubscriber(); ?>');"><?php echo trans('delete'); ?></a>
                </div>
            </div>
        </div>
    <?php 
    $counter++;
    ?>
    <?php endforeach; ?>
    </div>
</div>

<style>
#cookieBox-detailedList-container {
    position: fixed;
    bottom: 24px;
    left: 10px;
    width: 300px; 
    /* height: 100px;  */
    /* cursor: pointer; */
    background-color: #fff;
    color: #000;
    /* background-image: url('/image/baking-tray.png'); */
    z-index: 10000001;
    box-shadow: rgba(0, 0, 0, 0.25) 0px 14px 28px, rgba(0, 0, 0, 0.22) 0px 10px 10px;
    /* text-align: center; */
    /* margin-top: auto;
    margin-bottom: auto; */
}
.cookieBox-detailedList-detailsContainer {
    padding: 10px;
}
/* .cookieBox-summarizer-title {
    font-size: 10px;
}
.cookieBox-summarizer-counter {
    color: #8c160d;
    font-size: 24px;
    -webkit-text-stroke: 1px #282828;
    text-shadow: 0px 4px 4px #282828;
} */
</style>