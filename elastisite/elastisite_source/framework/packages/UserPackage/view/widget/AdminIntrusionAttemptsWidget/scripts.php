<script>
var AdminIntrusionAttemptsWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/intrusionAttempts/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('AdminIntrusionAttemptsWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = AdminIntrusionAttemptsWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#AdminIntrusionAttemptsWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = AdminIntrusionAttemptsWidget.getParameters();
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
    // console.log('AdminIntrusionAttemptsWidget docready');
    // AdminIntrusionAttemptsWidget.call(false);
    Structure.loadWidget('AdminIntrusionAttemptsWidget');
    <?php } ?>
});
</script>
