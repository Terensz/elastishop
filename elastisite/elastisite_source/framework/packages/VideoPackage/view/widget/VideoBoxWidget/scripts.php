<script>
var VideoBoxWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/videoBox/VideoBoxWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('VideoBoxWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = VideoBoxWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#VideoBoxWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = VideoBoxWidget.getParameters();
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
    Structure.loadWidget('VideoBoxWidget');
    <?php } ?>
});
</script>
