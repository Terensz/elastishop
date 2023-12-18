<link href="/public_folder/plugin/select2/select2.min.css" rel="stylesheet" />
<script src="/public_folder/plugin/select2/select2.min.js"></script>
<?php 
$sheetWidthPercent = App::getContainer()->getPageProperty('sheetWidthPercent');
$sheetMaxWidth = App::getContainer()->getPageProperty('sheetMaxWidth');
$widthStyleStr = (empty($sheetWidthPercent) ? '' : 'width: '.$sheetWidthPercent.'%;').(empty($sheetMaxWidth) ? '' : 'max-width: '.$sheetMaxWidth.'px;');
?>

<style>
.documentLevel {
    width: 100%;
    display: flex;
    justify-content: center;
}
</style>

        <div class="widgetContainer banner-frame" data-structurename="BasicStructure" id="widgetContainer-BannerWidget">{{ BannerWidget }}</div>

        <div class="widgetContainer" id="widgetContainer-MenuWidget">{{ MenuWidget }}</div>

        <div class="documentLevel">
            <div id="sheetContainer" style="<?php echo $widthStyleStr; ?>">
                <!-- <div class="row sheetLevel">
                    <div class="col-sm-12 widgetRail widgetRail-noPadding">
                        <div class="widgetWrapper-off">
                            <div class="widgetContainer" id="widgetContainer-MenuWidget">{{ MenuWidget }}</div>
                        </div>
                    </div>
                </div> -->

                <div class="row sheetLevel">
                    <div id="contentPanel-container" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 widgetRail widgetRail-noPadding">
                        <div class="widgetWrapper-off">
                            <div class="widgetContainer softWidgetChangeTransition" id="widgetContainer-mainContent">{{ mainContent }}</div>
                        </div>
                        <div class="widgetWrapper-off">
                            <div class="widgetContainer softWidgetChangeTransition" id="widgetContainer-mainContent2">{{ mainContent2 }}</div>
                        </div>
                    </div>
                </div>
                <div class="row sheetLevel">
                    <div class="col-sm-12 widgetRail widgetRail-noPadding">
                        <div class="widgetWrapper-off widgetContainer" id="widgetContainer-FooterWidget">{{ FooterWidget }}</div>
                    </div>
                </div>
            </div>
        </div>


        <?php 
        // include('framework/packages/VideoPackage/view/widget/VideoBoxWidget/widget.php');
        ?>