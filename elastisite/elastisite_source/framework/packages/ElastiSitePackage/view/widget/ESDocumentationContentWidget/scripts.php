<script>
var ESDocumentationContentWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/ESDocumentationContentWidget',
            'responseSelector': '#widgetContainer-mainContent'
        };
    },
    call: function(isSubmitted) {
        var params = ESDocumentationContentWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#ESDocumentationContentWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = ESDocumentationContentWidget.getParameters();
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
    Structure.loadWidget('ESDocumentationContentWidget');
    <?php } ?>
});
</script>
