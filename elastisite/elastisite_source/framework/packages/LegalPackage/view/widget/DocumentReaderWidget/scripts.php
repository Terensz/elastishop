<script>
var DocumentReaderWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/documents/documentReader/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('DocumentReaderWidget'); ?>'
        };
    },
    save: function() {
        DocumentReaderWidget.call(true);
    },
    call: function(isSubmitted) {
        var params = DocumentReaderWidget.getParameters();
        var ajaxData = {};
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = DocumentReaderWidget.getParameters();
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
    Structure.loadWidget('DocumentReaderWidget');
    // UsersDocumentsWidget.call(false);
    <?php } ?>
});
</script>
