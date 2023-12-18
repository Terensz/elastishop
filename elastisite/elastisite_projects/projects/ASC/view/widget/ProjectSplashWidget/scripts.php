<script>
var ProjectSplashWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/ProjectSplashWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('ProjectSplashWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = ProjectSplashWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#ProjectSplashWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = ProjectSplashWidget.getParameters();
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
    Structure.loadWidget('ProjectSplashWidget');
    <?php } ?>
});
</script>
