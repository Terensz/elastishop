<script>
var AdminVisitsAndPageLoadsWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/visitsAndPageLoads/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('AdminVisitsAndPageLoadsWidget'); ?>'
        };
    },
    save: function() {
        AdminVisitsAndPageLoadsWidget.call(true);
    },
    call: function(isSubmitted) {
        var params = AdminVisitsAndPageLoadsWidget.getParameters();
        var ajaxData = {};
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = AdminVisitsAndPageLoadsWidget.getParameters();
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
    Structure.loadWidget('AdminVisitsAndPageLoadsWidget');
    // UsersDocumentsWidget.call(false);
    <?php } ?>
});
</script>
