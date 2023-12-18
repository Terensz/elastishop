<script>
var RegActivationWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/user/registration/activation/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('RegActivationWidget'); ?>'
        };
    },
    save: function() {
        RegActivationWidget.call(true);
    },
    call: function(isSubmitted) {
        var params = RegActivationWidget.getParameters();
        var ajaxData = {};
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = RegActivationWidget.getParameters();
                $(params.responseSelector).html(response.view);
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    }
};

$(document).ready(function() {
    <?php if ($container->isAjax()) { ?>
    // console.log('RegActivationWidget docready');
    Structure.loadWidget('RegActivationWidget');
    // RegActivationWidget.call(false);
    <?php } ?>
});
</script>
