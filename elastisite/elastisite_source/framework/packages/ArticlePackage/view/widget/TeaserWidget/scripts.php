<script>
var TeaserWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/article/teaser/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('TeaserWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = TeaserWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#TeaserWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = TeaserWidget.getParameters();
                $(params.responseSelector).html(response.view);
                // $(params.responseSelector).fadeOut(function(){
                //     console.log('fade');
                //     $(params.responseSelector).html(response.view);
                //     $(params.responseSelector).fadeIn();
                // });
            },
            'error': function(request) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    submit: function() {
        TeaserWidget.call(true);
    }
};

$(document).ready(function() {
    <?php if ($container->isAjax()) { ?>
    // TeaserWidget.call(false);
    // console.log('TeaserWidget docready');
    Structure.loadWidget('TeaserWidget');
    <?php } ?>
});
</script>
