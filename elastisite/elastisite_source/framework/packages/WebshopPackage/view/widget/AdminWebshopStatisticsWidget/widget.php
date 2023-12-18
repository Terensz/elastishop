<!-- <div style="display: none;">

</div> -->

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0"><?php echo trans('current.month.and.previous'); ?></h6>
        </div>
    </div>
    <div class="card-body" style="width: 99%;">
        <div style="width: 99%;">
    <?php 
    echo $chart1;
    ?>
        </div>
        <div style="width: 99%;">
            <a id="webshopStatistics-showHidePreviousMonth-link" href="" onclick="WebshopStatistics.showHidePreviousMonth(event);"><?php echo trans('show.or.hide.previous.month'); ?></a>
        </div>
        <div id="webshopStatistics-previousMonth-container" style="width: 99%;">
    <?php
    echo $chart2;
    ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0"><?php echo trans('earlier.month'); ?></h6>
        </div>
    </div>
    <div class="card-body">

        <div id="webshopStatistics_earlierMonth_minMonthIndex" style="display: none;"><?php echo $minMonthIndex; ?></div>

        <div id="webshopStatistics_earlierMonth_maxMonthIndex" style="display: none;"><?php echo $maxMonthIndex; ?></div>

        <form name="webshopStatistics_earlierMonth_form">
            <select name="webshopStatistics_earlierMonth_month" id="webshopStatistics_earlierMonth_month" class="inputField form-control">
                <option value="null"><?php echo trans('please.choose.month'); ?></option>
                <?php foreach ($periodMonthProperties as $periodMonthProperty): ?>
                <option value="<?php echo $periodMonthProperty['monthIndex']; ?>"><?php echo $periodMonthProperty['year'].' - '.$periodMonthProperty['monthName']; ?></option>
                <?php endforeach; ?>
            </select>
        </form>

        <div style="padding-top: 10px;">
            <nav aria-label="">
                <ul class="pagination">
                    <li id="webshopStatistics_earlierMonth_pager_previous-container" class="page-item">
                        <a id="webshopStatistics_earlierMonth_pager_previous" class="page-link triggerModal" href="" onclick="WebshopStatistics.showPreviousEarlierMonthChart(event);"><?php echo trans('previous'); ?></a>
                    </li>
                    <li id="webshopStatistics_earlierMonth_pager_next-container" class="page-item">
                        <a id="webshopStatistics_earlierMonth_pager_next" class="page-link triggerModal" href="" onclick="WebshopStatistics.showNextEarlierMonthChart(event);"><?php echo trans('next'); ?></a>
                    </li>
                </ul>
            </nav>
        </div>

        <div id="chart3" style="min-height: 365px;"></div>

    </div>
</div>

<script>
var WebshopStatistics = {
    showHidePreviousMonth: function(e) {
        // console.log('WebshopStatistics.showHidePreviousMonth');
        e.preventDefault();

        if ($('#webshopStatistics-previousMonth-container').is(':hidden')) {
            $('#webshopStatistics-previousMonth-container').show();
        } else {
            $('#webshopStatistics-previousMonth-container').hide();
        }
    },
    showEarlierMonthChart: function(monthIndex) {
        WebshopStatistics.setPagerContainerClasses();
        $.ajax({
	        'type' : 'POST',
	        'url' : '<?php echo $httpDomain; ?>/admin/webshop/statistics/earlierMonth',
	        'data': {
                'monthIndex': monthIndex
            },
	        'async': true,
			'success': function(response) {
				ElastiTools.checkResponse(response);
				$('#chart3').html(response.view);
	        },
	        'error': function(request, error) {
				ElastiTools.checkResponse(request.responseText);
	            // console.log(request);
	            console.log(" Can't do because: " + error);
	        },
        });
    },
    setPagerContainerClasses: function() {
        var monthIndex = $('#webshopStatistics_earlierMonth_month').val();
        if (monthIndex == 'null') {
            $('#webshopStatistics_earlierMonth_pager_previous-container').addClass('disabled');
            $('#webshopStatistics_earlierMonth_pager_next-container').addClass('disabled');
            return true;
        }

        // console.log('monthIndex: ' + monthIndex);
        // console.log('min: ' + $('#webshopStatistics_earlierMonth_minMonthIndex').html());
        // console.log('max: ' + $('#webshopStatistics_earlierMonth_maxMonthIndex').html());

        if (monthIndex == $('#webshopStatistics_earlierMonth_minMonthIndex').html()) {
            $('#webshopStatistics_earlierMonth_pager_previous-container').addClass('disabled');
        } else {
            $('#webshopStatistics_earlierMonth_pager_previous-container').removeClass('disabled');
        }

        if (monthIndex == $('#webshopStatistics_earlierMonth_maxMonthIndex').html()) {
            $('#webshopStatistics_earlierMonth_pager_next-container').addClass('disabled');
        } else {
            $('#webshopStatistics_earlierMonth_pager_next-container').removeClass('disabled');
        }
    },
    showPreviousEarlierMonthChart: function(e) {
        e.preventDefault();
        var currentMonthIndex = $('#webshopStatistics_earlierMonth_month').val();
        var previousMonthIndex = parseInt(currentMonthIndex) - 1;
        $('#webshopStatistics_earlierMonth_month').val(previousMonthIndex);
        WebshopStatistics.showEarlierMonthChart(previousMonthIndex);
    },
    showNextEarlierMonthChart: function(e) {
        e.preventDefault();
        var currentMonthIndex = $('#webshopStatistics_earlierMonth_month').val();
        var nextMonthIndex = parseInt(currentMonthIndex) + 1;
        $('#webshopStatistics_earlierMonth_month').val(nextMonthIndex);
        // console.log(currentMonthIndex);
        WebshopStatistics.showEarlierMonthChart(nextMonthIndex);
    }
};

$('document').ready(function() {
    WebshopStatistics.setPagerContainerClasses();

    $('#webshopStatistics_earlierMonth_month').on('change', function() {
        // console.log($('#webshopStatistics_earlierMonth_month').val());
        WebshopStatistics.showEarlierMonthChart($('#webshopStatistics_earlierMonth_month').val());
    });
});
</script>
