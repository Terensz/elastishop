<script>
var CalendarWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/calendar/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('CalendarWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = CalendarWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#CalendarWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                var params = CalendarWidget.getParameters();
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
    // CalendarWidget.call(false);
    // console.log('CalendarWidget docready');
    Structure.loadWidget('CalendarWidget');
    <?php } ?>
});
</script>
