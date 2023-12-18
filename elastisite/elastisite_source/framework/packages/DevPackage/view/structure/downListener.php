
        <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
        <link href="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/select2/select2.min.css" rel="stylesheet" />
        <script src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/select2/select2.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <script src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/asset/TextareaEditor/TextareaEditor.js"></script>
        <script src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/nicEdit/nicEdit.js"></script>
        
        <div id="sheetContainer" class="sheetWidth">
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-ElastiSiteBannerWidget">{{ ElastiSiteBannerWidget }}</div>
                    </div>
                </div>
            </div>
            <div id="stickyMenuStart"></div>
            <div style="position: relative; z-index: 200;">
                <div class="stickyMenuDiv row sheetLevel">
                    <div class="col-sm-12 widgetRail widgetRail-noPadding">
                        <div class="widgetWrapper-off">
                            <div class="widgetContainer" id="widgetContainer-AdminMenuWidget">{{ AdminMenuWidget }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row sheetLevel">
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 widgetRail widgetRail-first">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-LoginWidget">{{ LoginWidget }}</div>
                    </div>
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-AdminSideMenuWidget">{{ AdminSideMenuWidget }}</div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12 widgetRail widgetRail-last">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-mainContent">{{ mainContent }}</div>
                    </div>
                </div>
            </div>
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off widgetContainer" id="widgetContainer-ElastiSiteFooterWidget">{{ ElastiSiteFooterWidget }}</div>
                </div>
            </div>
        </div>
