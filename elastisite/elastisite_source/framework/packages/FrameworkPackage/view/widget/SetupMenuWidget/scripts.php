<script>
var SetupMenuWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/SetupMenuWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('SetupMenuWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = SetupMenuWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#SetupMenuWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = SetupMenuWidget.getParameters();
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
    // MenuWidget.call(false);
    // console.log('MenuWidget docready');
    Structure.loadWidget('SetupMenuWidget');
    <?php } ?>
});
</script>
