<script>
var ChangePasswordWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/ajax/ChangePasswordWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('ChangePasswordWidget'); ?>'
        };
    },
    save: function() {
        ChangePasswordWidget.call(true);
    },
    call: function(isSubmitted) {
        var params = ChangePasswordWidget.getParameters();
        var ajaxData = {};
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = ChangePasswordWidget.getParameters();
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
    Structure.loadWidget('ChangePasswordWidget');
    <?php } ?>
});
</script>
