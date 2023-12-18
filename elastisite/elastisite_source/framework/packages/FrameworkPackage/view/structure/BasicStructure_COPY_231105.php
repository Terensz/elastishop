<link href="/public_folder/plugin/select2/select2.min.css" rel="stylesheet" />
<script src="/public_folder/plugin/select2/select2.min.js"></script>
<?php 
?>

        <div id="sheetContainer" class="sheetWidth">
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-BannerWidget">{{ BannerWidget }}</div>
                    </div>
                </div>
            </div>

            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-MenuWidget">{{ MenuWidget }}</div>
                    </div>
                </div>
            </div>

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
        <?php 
        // include('framework/packages/VideoPackage/view/widget/VideoBoxWidget/widget.php');
        ?>