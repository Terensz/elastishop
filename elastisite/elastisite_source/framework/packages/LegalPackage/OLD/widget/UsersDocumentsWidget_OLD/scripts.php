<script>
var UsersDocumentsWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/UsersDocumentsWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('UsersDocumentsWidget'); ?>'
        };
    },
    save: function() {
        UsersDocumentsWidget.call(true);
    },
    call: function(isSubmitted) {
        var params = UsersDocumentsWidget.getParameters();
        var ajaxData = {};
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = UsersDocumentsWidget.getParameters();
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
    // console.log('UsersDocumentsWidget docready');
    Structure.loadWidget('UsersDocumentsWidget');
    // UsersDocumentsWidget.call(false);
    <?php } ?>
});
</script>
