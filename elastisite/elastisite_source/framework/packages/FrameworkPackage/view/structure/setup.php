        <!-- <link href="/public_folder/skin/Basic/css/adminSkin.css?v=<?php echo time(); ?>" rel="stylesheet"> -->
        <link href="/public_folder/plugin/select2/select2.min.css" rel="stylesheet" />
        <script src="/public_folder/plugin/select2/select2.min.js"></script>
        <script type="text/javascript" src="/public_folder/plugin/Moment/moment.min.js"></script>
        <script type="text/javascript" src="/public_folder/plugin/DateRangePicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/public_folder/plugin/DateRangePicker/daterangepicker.css" />
        <script src="/public_folder/asset/TextareaEditor/TextareaEditor.js"></script>
        <script src="/public_folder/plugin/nicEdit/nicEdit.js"></script>
        <link href="/public_folder/skin/Basic/css/skin.css?v=<?php echo time(); ?>" rel="stylesheet">
        <link href="/public_folder/skin/Basic/css/adminSkin.css?v=<?php echo time(); ?>" rel="stylesheet">

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
                            <div class="widgetContainer" id="widgetContainer-Left2Widget">{{ Left2Widget }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="AscScaleBuilder_BuilderInterface_container" style="display: flex;">

                <nav class="pc-sidebar collapse show sideNavbar-container" id="AscScaleBuilder_PrimarySubjectBar_container" 
                style="width: 280px; height: 100% !important; z-index: 0 !important; margin-bottom: 20px;">
                    <div class="navbar-wrapper" style="width: 280px; height: 100% !important;">
                        <div class="navbar-content ps">
                        
                            <div class="widgetContainer" id="widgetContainer-Login2Widget">{{ Login2Widget }}</div>
                            <div class="widgetContainer" id="widgetContainer-SetupMenuWidget">{{ SetupMenuWidget }}</div>

                        </div>
                    </div>
                </nav>

                <div class="pc-container">
                    <div class="pcoded-content">
                        <div class="widgetWrapper-off">
                            <div class="widgetContainer" id="widgetContainer-mainContent">{{ mainContent }}</div>
                        </div>
                        <div class="widgetWrapper-off">
                            <div class="widgetContainer" id="widgetContainer-mainContent2">{{ mainContent2 }}</div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off widgetContainer" id="widgetContainer-ElastiSiteFooterWidget">{{ ElastiSiteFooterWidget }}</div>
                </div>
            </div>
        </div>
