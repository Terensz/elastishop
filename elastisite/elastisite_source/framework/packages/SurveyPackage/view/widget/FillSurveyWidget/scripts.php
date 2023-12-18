<script>
var FillSurveyWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/survey/FillSurveyWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('FillSurveyWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = FillSurveyWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#FillSurveyWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = FillSurveyWidget.getParameters();
                $(params.responseSelector).html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    }
};

$(document).ready(function() {
    <?php if ($container->isAjax()) { ?>
        FillSurveyWidget.call(false);
    <?php } ?>
});
</script>
