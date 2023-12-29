<style>

</style>

<?php 
// dump($customPageId);
?>
<script>
    console.log('modal.php loaded. customPageId: <?php echo $customPageId; ?>');
</script>


<!-- <div class="card-header" style="padding-bottom: 0px !important;">
    <ul class="nav nav-tabs" id="myTabs" role="tablist" style="border-bottom: 0px !important;">
        <li class="nav-item">
            <a class="navLink-priorized nav-link active" id="tab1-tab" data-toggle="tab" href="" onclick="EventActualityList.switchTab(event, 'priorized');" role="tab" aria-controls="tab1" aria-selected="true">Aktuális teendők</a>
        </li>
        <li class="nav-item">
            <a class="navLink-closed nav-link" id="tab2-tab" data-toggle="tab" href="" onclick="EventActualityList.switchTab(event, 'closed');" role="tab" aria-controls="tab2" aria-selected="false">Lezárt teendők</a>
        </li>
    </ul>
</div> -->


<div id="customPageId" style="display: none;"><?php echo $customPageId; ?></div>
<div id="routeName" style="display: none;"><?php echo $routeName; ?></div>
<div class="row tabs">
<div class="card-header" style="padding-bottom: 0px !important;">
    <ul class="nav nav-tabs" id="myTabs" role="tablist" style="border-bottom: 0px !important;">
        <li class="nav-item">
            <a class="navLink-priorized nav-link active customPageTab customPageTab-basic doNotTriggerHref" 
                id="tab1-tab" data-toggle="tab" href="" onclick="CustomPageEdit.switchTab(event, 'basic');" role="tab" aria-controls="tab1" aria-selected="true">
                <?php echo trans('basic.settings'); ?>
            </a>
        </li>
    <!-- <div href="" onclick="CustomPageEdit.switchTab(event, 'basic');" class="col-lg-3 customPageTab customPageTab-basic tab-active doNotTriggerHref">
        <a class="doNotTriggerHref" href><?php echo trans('basic.settings'); ?></a>
    </div> -->
<?php
if ($customPageId) {
?>
        <li class="nav-item">
            <a class="navLink-priorized nav-link customPageTab customPageTab-openGraph doNotTriggerHref" 
                id="tab1-tab" data-toggle="tab" href="" onclick="CustomPageEdit.switchTab(event, 'openGraph');" role="tab" aria-controls="tab1" aria-selected="true">
                <?php echo trans('open.graph.settings'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="navLink-priorized nav-link customPageTab customPageTab-keywords doNotTriggerHref" 
                id="tab1-tab" data-toggle="tab" href="" onclick="CustomPageEdit.switchTab(event, 'keywords');" role="tab" aria-controls="tab1" aria-selected="true">
                <?php echo trans('keywords'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="navLink-priorized nav-link customPageTab customPageTab-background doNotTriggerHref" 
                id="tab1-tab" data-toggle="tab" href="" onclick="CustomPageEdit.switchTab(event, 'background');" role="tab" aria-controls="tab1" aria-selected="true">
                <?php echo trans('background.settings'); ?>
            </a>
        </li>


        
    <!-- <div href="" onclick="CustomPageEdit.switchTab(event, 'openGraph');" class="customPageTab customPageTab-openGraph tab-inactive doNotTriggerHref">
        <a class="doNotTriggerHref" href><?php echo trans('open.graph.settings'); ?></a>
    </div>
    <div href="" onclick="CustomPageEdit.switchTab(event, 'keywords');" class="col-lg-3 customPageTab customPageTab-keywords tab-inactive doNotTriggerHref">
        <a class="doNotTriggerHref" href><?php echo trans('keywords'); ?></a>
    </div>
    <div href="" onclick="CustomPageEdit.switchTab(event, 'background');" class="col-lg-3 customPageTab customPageTab-background tab-inactive doNotTriggerHref">
        <a class="doNotTriggerHref" href><?php echo trans('background.settings'); ?></a>
    </div> -->
<?php
} else {
?> 
    <!-- <div class="col-lg-3 customPageTab-price tab-inactive doNotTriggerHref">
        <?php echo trans('open.graph.settings'); ?>
    </div>
    <div class="col-lg-3 customPageTab-price tab-inactive doNotTriggerHref">
        <?php echo trans('keywords'); ?>
    </div>
    <div class="col-lg-3 customPageTab-image tab-inactive doNotTriggerHref">
        <?php echo trans('background.settings'); ?>
    </div> -->
        <!-- <li class="nav-item">
            <button class="navLink-priorized nav-link customPageTab customPageTab-openGraph doNotTriggerHref" 
                id="tab1-tab" data-toggle="tab" href="" role="tab" aria-controls="tab1" aria-selected="true">
                <?php echo trans('open.graph.settings'); ?>
            </button>
        </li>
        <li class="nav-item">
            <button class="navLink-priorized nav-link customPageTab customPageTab-keywords doNotTriggerHref" 
                id="tab1-tab" data-toggle="tab" href="" role="tab" aria-controls="tab1" aria-selected="true">
                <?php echo trans('keywords'); ?>
            </button>
        </li>
        <li class="nav-item">
            <button class="navLink-priorized nav-link customPageTab customPageTab-background doNotTriggerHref" 
                id="tab1-tab" data-toggle="tab" href="" role="tab" aria-controls="tab1" aria-selected="true">
                <?php echo trans('background.settings'); ?>
            </button>
        </li> -->
<?php
}
?>

    </ul>
</div>

<div id="customPage-tabContent-container" style="width: 100%; padding-top: 20px;">
</div>

<!-- <div class="customPage-basic-container">
</div>  

<div class="customPage-openGraph-container">
</div> 

<div class="customPage-keywords-container">
</div> 

<div class="customPage-background-container">
</div>  -->

</div>

<script>
var CustomPageEdit = {
    loadTabContent: function(tabId) {
        let newRouteRequest = false;
        let formNames = {
            'basic': 'FrameworkPackage_customPageBasic_form',
            'openGraph': 'FrameworkPackage_customPageOpenGraph_form',
            'keywords': 'FrameworkPackage_customPageKeywords_form',
            'background': 'FrameworkPackage_customPageBackground_form'
        };
        if (tabId == 'basic' && $('#customPageId').html() != '' && $('#customPageId').html() != '0' && $('#routeName').html() == '') {
            newRouteRequest = true;
        }
        let formId = formNames[tabId];
        // console.log('formId: ' + formId);

        var ajaxData = {};
        var formName = '#' + formId;
        var form = $('#' + formId);
        var formData = form.serialize();
        var additionalData = {
            'tabId': tabId,
            'newRouteRequest': newRouteRequest,
            'customPageId': $('#customPageId').html(),
            'routeName': $('#routeName').html()
        };
        ajaxData = formData + '&' + $.param(additionalData);
        // console.log('formData');
        // console.log(formData);
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/edit/modalTabContent/' + tabId,
            'data': ajaxData,
            'async': false,
            'success': function(response) {
                // LoadingHandler.stop();
                // console.log('stop!');
                ElastiTools.checkResponse(response);
                console.log(response);
                $('#customPage-tabContent-container').html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                // LoadingHandler.stop();
            },
        });
    },
    // alma: function(backgroundId) {
    //     $.ajax({
    //         'type' : 'POST',
    //         'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/background/binding/edit',
    //         'data': {
    //             'id': backgroundId
    //         },
    //         'async': false,
    //         'success': function(response) {
    //             ElastiTools.checkResponse(response);
    //             // console.log(response);
    //             $('#ElastiSite_newsSubscription_form-container').html(response.view);
    //         },
    //         'error': function(request, error) {
    //             console.log(request);
    //             console.log(" Can't do because: " + error);
    //             // LoadingHandler.stop();
    //         },
    //     });
    // },
    switchTab: function(e, tabId) {
        e.preventDefault();
        // return;
        // console.log(tabId);
        CustomPageEdit.loadTabContent(tabId);
        if (tabId == 'basic') {
            $('.customPageTab-basic').addClass('active');
            $('.customPageTab-basic').removeClass('inactive');
            $('.customPageTab-openGraph').addClass('inactive');
            $('.customPageTab-openGraph').removeClass('active');
            $('.customPageTab-keywords').addClass('inactive');
            $('.customPageTab-keywords').removeClass('active');
            $('.customPageTab-background').addClass('inactive');
            $('.customPageTab-background').removeClass('active');
            return true;
            // $('.customPage-basic-container').show();
            // $('.customPage-openGraph-container').hide();
            // $('.customPage-keywords-container').hide();
            // $('.customPage-background-container').hide();
        }
        if (tabId == 'openGraph') {
            // console.log('opengraf');
            $('.customPageTab-basic').addClass('inactive');
            $('.customPageTab-basic').removeClass('active');
            $('.customPageTab-openGraph').addClass('active');
            $('.customPageTab-openGraph').removeClass('inactive');
            $('.customPageTab-keywords').addClass('inactive');
            $('.customPageTab-keywords').removeClass('active');
            $('.customPageTab-background').addClass('inactive');
            $('.customPageTab-background').removeClass('active');
            return true;
            // $('.customPage-basic-container').hide();
            // $('.customPage-openGraph-container').show();
            // $('.customPage-keywords-container').hide();
            // $('.customPage-background-container').hide();
        }
        if (tabId == 'keywords') {
            // return true;
            $('.customPageTab-basic').addClass('inactive');
            $('.customPageTab-basic').removeClass('active');
            $('.customPageTab-openGraph').addClass('inactive');
            $('.customPageTab-openGraph').removeClass('active');
            $('.customPageTab-keywords').addClass('active');
            $('.customPageTab-keywords').removeClass('inactive');
            $('.customPageTab-background').addClass('inactive');
            $('.customPageTab-background').removeClass('active');
            return true;
            // $('.customPage-basic-container').hide();
            // $('.customPage-openGraph-container').hide();
            // $('.customPage-keywords-container').show();
            // $('.customPage-background-container').hide();
        }
        if (tabId == 'background') {
            $('.customPageTab-basic').addClass('inactive');
            $('.customPageTab-basic').removeClass('active');
            $('.customPageTab-openGraph').addClass('inactive');
            $('.customPageTab-openGraph').removeClass('active');
            $('.customPageTab-keywords').addClass('inactive');
            $('.customPageTab-keywords').removeClass('active');
            $('.customPageTab-background').addClass('active');
            $('.customPageTab-background').removeClass('inactive');
            return true;
            // $('.customPage-basic-container').hide();
            // $('.customPage-openGraph-container').hide();
            // $('.customPage-keywords-container').hide();
            // $('.customPage-background-container').show();
        }
    }
};

$('document').ready(function() {
    $('body').off('click', '.customPageTab');
    $('body').on('click', '.customPageTab', function() {
        LoadingHandler.start();
    });
    CustomPageEdit.loadTabContent('basic');
});
</script>