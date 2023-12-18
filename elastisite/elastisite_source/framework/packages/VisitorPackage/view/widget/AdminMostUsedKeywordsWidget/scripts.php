<script>
var AdminMostUsedKeywordsWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/mostUsedKeywords/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('AdminMostUsedKeywordsWidget'); ?>'
        };
    },
    save: function() {
        AdminMostUsedKeywordsWidget.call(true);
    },
    call: function(isSubmitted) {
        var params = AdminMostUsedKeywordsWidget.getParameters();
        var ajaxData = {};
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = AdminMostUsedKeywordsWidget.getParameters();
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
    Structure.loadWidget('AdminMostUsedKeywordsWidget');
    // UsersDocumentsWidget.call(false);
    <?php } ?>
});
</script>
