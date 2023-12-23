        <link href="/public_folder/plugin/select2/select2.min.css" rel="stylesheet" />
        <script src="/public_folder/plugin/select2/select2.min.js"></script>
        <script type="text/javascript" src="/public_folder/plugin/Moment/moment.min.js"></script>
        <script type="text/javascript" src="/public_folder/plugin/DateRangePicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/public_folder/plugin/DateRangePicker/daterangepicker.css" />
        <script src="/public_folder/asset/TextareaEditor/TextareaEditor.js"></script>
        <script src="/public_folder/plugin/nicEdit/nicEdit.js"></script>
        <link href="/public_folder/skin/Basic/css/adminSkin.css?v=<?php echo time(); ?>" rel="stylesheet">
        <script src="/public_folder/plugin/ApexCharts/apexcharts.min.js"></script>
        
<script>
    console.log('Admin structure loaded');
</script>
<style>
/* #sheetContainer {
border: auto;
width: 100%;
background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8nwYAAmoBZ0eMiB8AAAAASUVORK5CYII=');
box-shadow: 0 6px 18px #000;
margin: 0 auto;
} */
</style>
<style>
/* #menuAndContent-flexDiv {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
}
#menuAndContent-flexDiv .sideNavbar-container {
  flex: 0 0 280px;
}
.sideNavbar-container {
    height: 100vh;
    overflow-y: hidden;
    background: linear-gradient(180deg, transparent, #fff 60%, #fff);
}
.sideNavbar-container .navbar-wrapper {
  height: 100%;
} */
<?php 
$sheetWidthPercent = App::getContainer()->getPageProperty('sheetWidthPercent');
$sheetMaxWidth = App::getContainer()->getPageProperty('sheetMaxWidth');
$widthStyleStr = (empty($sheetWidthPercent) ? '' : 'width: '.$sheetWidthPercent.'%;').(empty($sheetMaxWidth) ? '' : 'max-width: '.$sheetMaxWidth.'px;');
?>
</style>
        <div id="sheetContainer" class="sheetWidth" style="<?php echo $widthStyleStr; ?>">
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-noPadding">
                    <div class="widgetWrapper-off">
                        <div class="widgetContainer" id="widgetContainer-ElastiSiteBannerWidget">{{ ElastiSiteBannerWidget }}</div>
                    </div>
                </div>
            </div>
            <!-- <div id="stickyMenuStart"></div> -->
            <div>
                <div class="stickyMenuDiv row sheetLevel">
                    <div class="col-sm-12 widgetRail widgetRail-noPadding">
                        <div class="widgetWrapper-off">
                            <div class="widgetContainer" id="widgetContainer-AdminMenuWidget">{{ AdminMenuWidget }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="menuAndContent-flexDiv">

                <div class="sideNavbar-container admin-sideNavbar-container admin-sideNavbar-scroll">
                    <nav class="pc-sidebar collapse show" id="AscScaleBuilder_PrimarySubjectBar_container">
                        <div class="navbar-wrapper">
                            <div class="navbar-content ps">
                            
                                <div class="widgetContainer" id="widgetContainer-LoginWidget">{{ LoginWidget }}</div>
                                <div class="widgetContainer" id="widgetContainer-AdminSideMenuWidget">{{ AdminSideMenuWidget }}</div>

                            </div>
                        </div>
                    </nav>
                </div>

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