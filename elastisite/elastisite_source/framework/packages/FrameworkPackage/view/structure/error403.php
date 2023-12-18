
        <div id="sheetContainer" class="sheetWidth">
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-BannerWidget">{{ BannerWidget }}</div>
                    </div>
                </div>
            </div>
            <div id="stickyMenuStart"></div>
            <div style="position: relative; z-index: 200;">
                <div class="stickyMenuDiv row sheetLevel">
                    <div class="col-sm-12 widgetRail widgetRail-noPadding">
                        <div class="widgetWrapper-off">
                            <div class="widgetContainer" id="widgetContainer-MenuWidget">{{ MenuWidget }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pc-container">
                <div class="pcoded-content card-container">

                    <div class="row">
                        <div class="col-md-12 card-pack-header">
                            <h4><?php echo trans('error.403.title').': '.trim($container->getRouting()->getActualRoute()->getParamChain(), '/'); ?></h4>
                            <div class="card-pack-sub-heading">
                                <h5><?php echo trans('error.403'); ?></h5>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off widgetContainer" id="widgetContainer-FooterWidget">{{ FooterWidget }}</div>
                </div>
            </div>
        </div>
