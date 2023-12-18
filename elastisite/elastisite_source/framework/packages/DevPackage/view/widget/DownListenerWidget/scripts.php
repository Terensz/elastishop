<script>
var DownListenerWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/downListener/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('DownListenerWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = DownListenerWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#DownListenerWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = DownListenerWidget.getParameters();
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
    Structure.loadWidget('DownListenerWidget');
    <?php } ?>
});
</script>
