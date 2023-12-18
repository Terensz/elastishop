<!-- <a class="ajaxCallerLink" href="/logout"><?php echo trans('logout'); ?></a>-->

<?php 
    $currentPath = __DIR__;
    $parentPath = dirname($currentPath);
    $pathToStaffMemberStatsDir = $parentPath . '/../StaffMemberStats/';
    // include($pathToStaffMemberStatsDir.'alma.php');
    // dump($pathToStaffMemberStatsDir);
?>
<link href="/public_folder/plugin/ApexCharts_3-41/apexcharts.css" rel="stylesheet">
<script src="/public_folder/plugin/ApexCharts_3-41/apexcharts.min.js"></script>
<!-- <script src="/public_folder/plugin/ChartJs/4-3-0/chart.js"></script> -->

<div style="padding-top: 20px; padding-left: 20px; padding-right: 20px;">

    <div class="widgetWrapper">
        <a class="" href="" onclick="ManageStaffMemberStats.initLogout(event);"><?php echo trans('logout'); ?></a>
    </div>

    <div id="ManageStaffMemberStats_ChartView_container" class="widgetWrapper"><?php echo isset($views['ChartView']) ? $views['ChartView'] : ''; ?></div>

    <div id="ManageStaffMemberStats_StatListView_container" class="widgetWrapper"><?php echo isset($views['StatListView']) ? $views['StatListView'] : ''; ?></div>
</div>

<script>
    var ManageStaffMemberStats = {
        activeInput: null,
        processResponse: function(response, calledBy, onSuccessCallback) {
            if (typeof this[onSuccessCallback] === 'function') {
                this[onSuccessCallback](response);
            }

            if (response.views && typeof(response.views.ChartView) == 'string' && response.views.ChartView != 'null' && response.views.ChartView != '') {
                console.log(response.views.ChartView);
                $('#ManageStaffMemberStats_ChartView_container').html(response.views.ChartView);
            }
            if (response.views && typeof(response.views.StatListView) == 'string' && response.views.StatListView != 'null' && response.views.StatListView != '') {
                $('#ManageStaffMemberStats_StatListView_container').html(response.views.StatListView);
                StaffMemberStatChart_1.draw();
            }
        },
        callAjax: function(calledBy, ajaxUrl, additionalData, onSuccessCallback) {
            let baseData = {};
            let ajaxData = $.extend({}, baseData, additionalData);
            // LoadingHandler.start();
            $.ajax({
                'type' : 'POST',
                'url' : '/' + ajaxUrl,
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    // LoadingHandler.stop();
                    ElastiTools.checkResponse(response);
                    // console.log(response);
                    ManageStaffMemberStats.processResponse(response, calledBy, onSuccessCallback);
                    LoadingHandler.stop();
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        initLogout: function(e) {
            e.preventDefault();
            ManageStaffMemberStats.callAjax('initLogout', 'ajax/logout', {}, 'logoutCallback');
        },
        logoutCallback: function() {
            console.log('logoutCallback!!!');
            Structure.call('');
        },
        initSaveStat: function(year, weekNumber) {
            let points = $('#statCard_input_' + year + '-' + weekNumber).val();
            if (points == null || points == '') {
                return false;
            }
            console.log(points);
            // e.preventDefault();
            ManageStaffMemberStats.callAjax('initSaveStat', 'staffMemberStats/saveStat', {
                'year': year,
                'weekNumber': weekNumber,
                'points': points
                // 'staffMemberStatId': staffMemberStatId
            }, 'saveStatCallback');
        },
        // initSaveStat: function(year, weekNumber) {
        //     var inputElement = $('#statCard_input_' + year + '-' + weekNumber);
        //     var lastFocusedInput = null;
        //     inputElement.on('blur', function() {
        //         lastFocusedInput = this;
        //         var points = $(this).val();
        //         console.log(points);
        //         ManageStaffMemberStats.callAjax('initSaveStat', 'staffMemberStats/saveStat', {
        //             'year': year,
        //             'weekNumber': weekNumber,
        //             'points': points
        //         }, 'saveStatCallback');
        //     });

        //     inputElement.on('keydown', function(e) {
        //     // Ellenőrizzük, hogy a lenyomott gomb a tab
        //     if (e.keyCode === 9) {
        //         lastFocusedInput = this;
        //     }
        //     });
        // },
        saveStatCallback: function(response) {
            console.log('saveStatCallback!!!');
            console.log(response);
            if (response.data.result == 'success') {
                Structure.throwToast('<?php echo trans('success'); ?>', response.data.message);
            } else {
                Structure.throwErrorToast('<?php echo trans('error'); ?>', response.data.message);
            }
            // Structure.call('');
        },
        // saveStatCallback: function(response) {
        //     if (response.data.result == 'success') {
        //         Structure.throwToast('<?php echo trans('success'); ?>', response.data.message);
        //     }
        //     if (response.data.result == 'error') {
        //         Structure.throwErrorToast('<?php echo trans('error'); ?>', response.data.message);
        //     }
        //     if (lastFocusedInput) {
        //         lastFocusedInput.focus();
        //     }
        // }
        
    }

    $('document').ready(function() {
        StaffMemberStatChart_1.draw();
    });
</script>