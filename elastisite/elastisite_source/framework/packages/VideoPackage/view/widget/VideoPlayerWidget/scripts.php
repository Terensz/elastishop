<script>
var VideoPlayerWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/videoPlayer/VideoPlayerWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('VideoPlayerWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = VideoPlayerWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#VideoPlayerWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = VideoPlayerWidget.getParameters();
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
    Structure.loadWidget('VideoPlayerWidget');
    <?php } ?>
});
</script>
