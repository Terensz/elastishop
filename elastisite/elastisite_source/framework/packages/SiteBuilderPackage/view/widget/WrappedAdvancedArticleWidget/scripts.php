<script>
var WrappedAdvancedArticleWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/WrappedAdvancedArticleWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('WrappedAdvancedArticleWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = WrappedAdvancedArticleWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#WrappedAdvancedArticleWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = WrappedAdvancedArticleWidget.getParameters();
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
    Structure.loadWidget('WrappedAdvancedArticleWidget');
    <?php } ?>
});
</script>
