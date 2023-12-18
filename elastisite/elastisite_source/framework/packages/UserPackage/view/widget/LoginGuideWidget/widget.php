<!-- <div class="row sheetLevel">
    <div id="contentPanel-container" class="col-md-12 col-sm-12 widgetRail col-xl-12 col-lg-12 widgetRail-noPadding">
        <div class="widgetWrapper">
            LoginGuideWidget!
        </div>
    </div>
</div> -->
<div class="pc-container">
    <div class="pcoded-content card-container">
        <div class="row">
            <div class="col-md-4 col-lg-4">
                <div class="card card-highlighted mb-4 onMouseOver-hand">
                    <div class="card-body text-center" onclick="LoginHandler.initLogin();">
                        Már regisztráltam, és tudom is a jelszavamat
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4">
                <div class="card card-highlighted mb-4 onMouseOver-hand">
                    <div class="card-body text-center" onclick="CustomRegistration.init();">
                        Még sosem regisztráltam
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4">
                <div class="card card-highlighted mb-4 onMouseOver-hand">
                    <div class="card-body text-center" onclick="LoginHandler.recoverPasswordModalOpen(null, '<?php echo trans('forgotten.password'); ?>');">
                        Elveszítettem a jelszavamat
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="LoginGuide-container" class="row sheetLevel">
    <div id="contentPanel-container" class="col-md-12 col-sm-12 widgetRail col-xl-12 col-lg-12 widgetRail-noPadding">
        <div class="widgetWrapper-off" style="padding: 10px;">
            <div class="contentViewer-fancyTextbox-container">
                <div onclick="LoginHandler.initLogin();" class="contentViewer-fancyTextbox">
                    <?php echo trans('i.have.my.credentials'); ?>
                </div>
                <div onclick="CustomRegistration.init();" class="contentViewer-fancyTextbox">
                    <?php echo trans('i.never.registered'); ?>
                </div>
                <div onclick="LoginHandler.recoverPasswordModalOpen(null, '<?php echo trans('forgotten.password'); ?>');" class="contentViewer-fancyTextbox">
                    <?php echo trans('i.lost.my.password'); ?>
                </div>
            </div>
        </div>
    </div>
</div> -->
<style>
    .contentViewer-fancyTextbox {
        background-color: #f8f8f8;
        width: auto !important;
        font-size: 24px;
        margin: 20px;
        padding: 20px;
        box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
        border-radius: 12px;
        cursor: pointer;
    }
    .contentViewer-fancyTextbox:hover {
        background-color: #6b6b6b;
        color: #ffffff;
    }
    .contentViewer-fancyTextbox-container {
        max-width: 800px; /* Válaszd a megfelelő szélességet */
        margin: 0 auto;
    }
</style>

<script>
    $('document').ready(function() {
        // $('#LoginGuide-container').off('click', '.contentViewer-fancyTextbox');
        // $('#LoginGuide-container').on('click', '.contentViewer-fancyTextbox', function() {
        //     console.log('click contentViewer-fancyTextbox!');
        // });
    });
</script>