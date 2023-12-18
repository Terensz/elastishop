<script>
var SetupMainWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/setup/MainWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('SetupMainWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = SetupMainWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#SetupMainWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = SetupMainWidget.getParameters();
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
    Structure.loadWidget('SetupMainWidget');
    <?php } ?>
});
</script>
