<script>
var LoginGuideWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/LoginGuideWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('LoginGuideWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = LoginGuideWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#LoginGuideWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = LoginGuideWidget.getParameters();
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
    Structure.loadWidget('LoginGuideWidget');
    <?php } ?>
});
</script>
