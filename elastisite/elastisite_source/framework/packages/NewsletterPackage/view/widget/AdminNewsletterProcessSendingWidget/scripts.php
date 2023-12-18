<script>
var AdminNewsletterProcessSendingWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/newsletter/processSending/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('AdminNewsletterProcessSendingWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = AdminNewsletterProcessSendingWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#AdminNewsletterProcessSendingWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = AdminNewsletterProcessSendingWidget.getParameters();
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
    Structure.loadWidget('AdminNewsletterProcessSendingWidget');
    <?php } ?>
});
</script>
