        <div id="sheetContainer" class="sheetWidth">
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-ElastiSiteBannerWidget">{{ ElastiSiteBannerWidget }}</div>
                    </div>
                </div>
            </div>
            <?php 
            // echo 'alma!';exit; 
            ?>
            <div id="stickyMenuStart"></div>
            <div style="position: relative; z-index: 200;">
                <div class="stickyMenuDiv row sheetLevel">
                    <div class="col-sm-12 widgetRail widgetRail-noPadding">
                        <div class="widgetWrapper-off">
                            <div class="widgetContainer" id="widgetContainer-ESMenuWidget">{{ ESMenuWidget }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row sheetLevel">
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 widgetRail widgetRail-first">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-leftTopContent">{{ leftTopContent }}</div>
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
