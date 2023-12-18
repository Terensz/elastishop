<style>
    /* .banner-container {
        color: #f5f5f5;
        background-color: #5e5e5e;
        position: relative;
        width: 100%;
    } */
    .banner-frame {
        width: 100%;
    }
    .banner-container {
        color: #f5f5f5;
        background-color: #5e5e5e;
    }
    /* .banner-container {
        color: #f5f5f5;
        background-color: #5e5e5e;
        position: relative;
        width: 100vw;
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
    } */
    .banner-text {
        padding: 20px;
        font: 40px Kaushan-Regular;
    }

</style>

<?php 
$sheetWidthPercent = App::getContainer()->getPageProperty('sheetWidthPercent');
$sheetMaxWidth = App::getContainer()->getPageProperty('sheetMaxWidth');
$widthStyleStr = (empty($sheetWidthPercent) ? '' : 'width: '.$sheetWidthPercent.'%;').(empty($sheetMaxWidth) ? '' : 'max-width: '.$sheetMaxWidth.'px;');
?>

<div class="banner-container documentLevel" style="height: 100px;">
    <div class="banner-text-container" style="<?php echo $widthStyleStr; ?>">
        <div class="banner-text">
        Ide jön a bannered
        </div>
    </div>
</div>

<!-- <div class="banner-container" style="height: 100px;">
</div> -->