<script>
var RemoveMyPersonalDataWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/user/RemoveMyPersonalDataWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('RemoveMyPersonalDataWidget'); ?>'
        };
    },
    save: function() {
        RemoveMyPersonalDataWidget.call(true);
    },
    call: function(isSubmitted) {
        var params = RemoveMyPersonalDataWidget.getParameters();
        var ajaxData = {};
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = RemoveMyPersonalDataWidget.getParameters();
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
    // console.log('RemoveMyPersonalDataWidget docready');
    Structure.loadWidget('RemoveMyPersonalDataWidget');
    // RemoveMyPersonalDataWidget.call(false);
    <?php } ?>
});
</script>
