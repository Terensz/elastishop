<script>
var AdminFooterWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/AdminFooterWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('AdminFooterWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = AdminFooterWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#AdminFooterWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = AdminFooterWidget.getParameters();
                // console.log($(params.responseSelector));

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
    Structure.loadWidget('AdminFooterWidget');
    <?php } ?>
});
</script>
