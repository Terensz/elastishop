        <link href="/public_folder/plugin/select2/select2.min.css" rel="stylesheet" />
        <script src="/public_folder/plugin/select2/select2.min.js"></script>
        <script type="text/javascript" src="/public_folder/plugin/Moment/moment.min.js"></script>
        <script type="text/javascript" src="/public_folder/plugin/DateRangePicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/public_folder/plugin/DateRangePicker/daterangepicker.css" />
        <script src="/public_folder/asset/TextareaEditor/TextareaEditor.js"></script>
        <script src="/public_folder/plugin/nicEdit/nicEdit.js"></script>
        
        <div id="sheetContainer" class="sheetWidth">
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-ElastiShopBannerWidget">{{ ElastiShopBannerWidget }}</div>
                    </div>
                </div>
            </div>
            <div class="row sheetLevel">
                <div id="leftPanel-container" class="col-xl-3 col-lg-4 col-md-4 col-sm-12 widgetRail widgetRail-first">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-DocumentationSubmenuWidget">{{ DocumentationSubmenuWidget }}</div>
                    </div>
                </div>
                <div id="contentPanel-container" class="col-xl-9 col-lg-8 col-md-8 col-sm-12 widgetRail widgetRail-last">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-DocumentationContentWidget">{{ DocumentationContentWidget }}</div>
                    </div>
                </div>
            </div>
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off widgetContainer" id="widgetContainer-ElastiSiteFooterWidget">{{ ElastiSiteFooterWidget }}</div>
                </div>
            </div>
        </div>
