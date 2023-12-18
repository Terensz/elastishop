<script>
var DevFormWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/dev/form/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('DevFormWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = DevFormWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#DevFormWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                //console.log(response);
                var params = DevFormWidget.getParameters();
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
    Structure.loadWidget('DevFormWidget');
    <?php } ?>
});
</script>
