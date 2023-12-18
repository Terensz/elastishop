<script>
var LoginNoRegLinkWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/LoginNoRegLinkWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('LoginNoRegLinkWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = LoginNoRegLinkWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#LoginWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = LoginNoRegLinkWidget.getParameters();
                $(params.responseSelector).html(response.view);
                if (response.data.freshLogin == true) {
                    Structure.call(window.location.href, true);
                }
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    }
};

$(document).ready(function() {
    <?php if ($container->isAjax()) { ?>
    Structure.loadWidget('LoginNoRegLinkWidget');
    <?php } ?>
});
</script>
