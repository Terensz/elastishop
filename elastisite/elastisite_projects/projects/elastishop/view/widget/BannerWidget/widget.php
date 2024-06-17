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

    /* .banner-container {
        color: #f5f5f5;
        background-color: #5e5e5e;
        background-image: url('/image/Lamparella_bg.png');
        height: 140px;
        display: table;
        width: 100%;
    } */

    /* .banner-text {
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        font-family: LuckiestGuy-Regular;
        color: rgba(68,178,46,1);
        text-shadow: 0px -6px 0 #212121, 0px -6px 0 #212121, 0px 6px 0 #212121, 
                    0px 6px 0 #212121, -6px 0px 0 #212121, 6px 0px 0 #212121, -6px 0px 0 #212121, 6px 0px 0 #212121, -6px -6px 0 #212121, 6px -6px 0 #212121, 
                    -6px 6px 0 #212121, 6px 6px 0 #212121, -6px 18px 0 #212121, 0px 18px 0 #212121, 6px 18px 0 #212121, 0 19px 1px rgb(0 0 0 / 10%), 0 0 6px rgb(0 0 0 / 10%), 
                    0 6px 3px rgb(0 0 0 / 30%), 0 12px 6px rgb(0 0 0 / 20%), 0 18px 18px rgb(0 0 0 / 25%), 0 24px 24px rgb(0 0 0 / 20%), 0 36px 36px rgb(0 0 0 / 15%);
    } */

.banner-inner-container {
    height: 140px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #5e5e5e;
    background-image: url('/image/Lamparella_bg.png');
}
.banner-text {
    text-align: center;
    width: 100%;
    font-family: LuckiestGuy-Regular;
    color: rgba(68, 178, 46, 1);
    text-shadow: 0px -6px 0 #212121, 0px -6px 0 #212121, 0px 6px 0 #212121, 
                    0px 6px 0 #212121, -6px 0px 0 #212121, 6px 0px 0 #212121, -6px 0px 0 #212121, 6px 0px 0 #212121, -6px -6px 0 #212121, 6px -6px 0 #212121, 
                    -6px 6px 0 #212121, 6px 6px 0 #212121, -6px 18px 0 #212121, 0px 18px 0 #212121, 6px 18px 0 #212121, 0 19px 1px rgb(0 0 0 / 10%), 0 0 6px rgb(0 0 0 / 10%), 
                    0 6px 3px rgb(0 0 0 / 30%), 0 12px 6px rgb(0 0 0 / 20%), 0 18px 18px rgb(0 0 0 / 25%), 0 24px 24px rgb(0 0 0 / 20%), 0 36px 36px rgb(0 0 0 / 15%);
}

</style>

<?php 
$sheetWidthPercent = App::getContainer()->getPageProperty('sheetWidthPercent');
$sheetMaxWidth = App::getContainer()->getPageProperty('sheetMaxWidth');
$widthStyleStr = (empty($sheetWidthPercent) ? '' : 'width: '.$sheetWidthPercent.'%;').(empty($sheetMaxWidth) ? '' : 'max-width: '.$sheetMaxWidth.'px;');
?>
<div class="banner-outer-container">
    <div class="banner-inner-container">
        <div class="banner-text">
            <?php 
            echo trans('there.could.be.your.shops.logo');
            ?>                
        </div>
    </div>
</div>

    <!-- <div class="banner-text-container" style="<?php echo $widthStyleStr; ?>">
        <div class="banner-text">
        
        </div>
    </div> -->

<!-- <div class="banner-container" style="height: 100px;">
</div> -->

<style>
    @media screen and (min-width:769px) {
        .banner-text {
            font-size: 40px;
        }
    }
    @media screen and (max-width:768px) {
        .banner-text {
            font-size: 24px;
        }
    }
    @media screen and (max-width:400px) {
        .banner-text {
            font-size: 15px;
        }
    }
</style>