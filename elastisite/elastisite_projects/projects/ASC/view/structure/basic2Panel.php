<link href="/public_folder/plugin/select2/select2.min.css" rel="stylesheet" />
<script src="/public_folder/plugin/select2/select2.min.js"></script>
<?php 
// <!-- <link href="/public_folder/plugin/select2/select2.min.css" rel="stylesheet" />
// <script src="/public_folder/plugin/select2/select2.min.js"></script>
// <script type="text/javascript" src="/public_folder/plugin/Moment/moment.min.js"></script>
// <script type="text/javascript" src="/public_folder/plugin/DateRangePicker/daterangepicker.min.js"></script>
// <link rel="stylesheet" type="text/css" href="/public_folder/plugin/DateRangePicker/daterangepicker.css" />
// <script src="/public_folder/asset/TextareaEditor/TextareaEditor.js"></script>
// <script src="/public_folder/plugin/nicEdit/nicEdit.js"></script> -->
// dump($widgetChanges);

$leftPanelIsInactive = true;
foreach ($widgetChanges as $widgetChangeKey => $widgetChangeValue) {
    // dump($widgetChangeKey);
    $pos = strpos($widgetChangeKey, 'left');
    // dump($pos === 0);
    if ($pos === 0) {
        $leftPanelIsInactive = false;
    }
}
?>

        <div id="sheetContainer" class="sheetWidth">
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-BannerWidget">{{ BannerWidget }}</div>
                    </div>
                </div>
            </div>

            <!-- <div id="stickyMenuStart"></div>
            <div style="position: relative; z-index: 200;">
                <div class="stickyMenuDiv row sheetLevel">
                    <div class="col-sm-12 widgetRail widgetRail-noPadding">
                        <div class="widgetWrapper-off">
                            <div class="widgetContainer" id="widgetContainer-MenuWidget">{{ MenuWidget }}</div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- <div>
                <div class="row sheetLevel">
                    <div class="col-sm-12 widgetRail widgetRail-noPadding">
                        <div class="widgetWrapper-off">
                            <div class="widgetContainer" id="widgetContainer-MenuWidget">{{ MenuWidget }}</div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- <div class="row sheetLevel" style="padding-top: 20px; padding-left: 20px; padding-right: 20px;"> -->

            <div class="row sheetLevel">
                <div id="leftPanel-container" class="col-xl-3 col-lg-4 col-md-12 col-sm-12 widgetRail widgetRail-first" <?php if ($leftPanelIsInactive) { echo ' style="display: none;"'; } ?>>
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer softWidgetChangeTransition" id="widgetContainer-left1">{{ left1 }}</div>
                    </div>
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer softWidgetChangeTransition" id="widgetContainer-left2">{{ left2 }}</div>
                    </div>
                </div>
                <div id="contentPanel-container" class="<?php if ($leftPanelIsInactive) { echo 'col-xl-12 col-lg-12'; } else { echo 'col-xl-9 col-lg-8'; } ?> col-md-12 col-sm-12 widgetRail widgetRail-<?php if ($leftPanelIsInactive) { echo 'noPadding'; } else { echo 'last'; } ?>">
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
        include('framework/packages/VideoPackage/view/widget/VideoBoxWidget/widget.php');
        ?>

