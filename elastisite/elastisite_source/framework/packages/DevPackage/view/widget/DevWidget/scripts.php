<script>
var DevWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/dev/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('DevWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = DevWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#DevWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = DevWidget.getParameters();
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
    console.log('alma...fdsf');
    <?php if ($container->isAjax()) { ?>
    // console.log('DevWidget docready');
    // DevWidget.call(false);
    Structure.loadWidget('DevWidget');
    <?php } ?>
});
</script>
