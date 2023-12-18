<script>
var AdminDatabaseInfoWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/database/info/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('AdminDatabaseInfoWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        if (typeof(isSubmitted) == 'undefined') {
            isSubmitted = false;
        }
        var params = AdminDatabaseInfoWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            ajaxData = {
                'AdminDatabaseInfoWidget_submit': true
            };
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = AdminDatabaseInfoWidget.getParameters();
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
    AdminDatabaseInfoWidget.call(false);
    <?php } ?>
});
</script>
