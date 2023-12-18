<script>
var MyPersonalDataWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/user/MyPersonalDataWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('MyPersonalDataWidget'); ?>'
        };
    },
    save: function() {
        MyPersonalDataWidget.call(true);
    },
    call: function(isSubmitted) {
        var params = MyPersonalDataWidget.getParameters();
        var ajaxData = {};
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = MyPersonalDataWidget.getParameters();
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
    // console.log('MyPersonalDataWidget docready');
    Structure.loadWidget('MyPersonalDataWidget');
    // MyPersonalDataWidget.call(false);
    <?php } ?>
});
</script>
