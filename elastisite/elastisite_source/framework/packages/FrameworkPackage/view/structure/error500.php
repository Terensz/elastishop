
        <div id="sheetContainer" class="sheetWidth">
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-MenuWidget">{{ MenuWidget }}</div>
                    </div>
                </div>
            </div>

            <div class="row sheetLevel" style="position: relative; z-index: 200;">
                <div id="leftPanel-container" class="col-sm-3 widgetRail widgetRail-first">
                    <div class="widgetWrapper">
                        <div class="widgetContainer-off" id="widgetContainer-EventWidget">{{ EventWidget }}</div>
                    </div>
                    <!-- <div class="widgetWrapper">
                        <div class="widgetContainer" id="widgetContainer-TeaserPanel"></div>
                    </div> -->
                </div>
                <div id="contentPanel-container" class="col-sm-9 widgetRail widgetRail-last">
                    <div class="widgetWrapper">
                        <div class="widgetContainer" id="widgetContainer-ErrorWidget">{{ ErrorWidget }}</div>
                    </div>
                </div>
            </div>

            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off widgetContainer" id="widgetContainer-FooterWidget">{{ FooterWidget }}</div>
                </div>
            </div>
        </div>
