<script>
var ESDocumentationSubmenuWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/ESDocumentationSubmenuWidget',
            'responseSelector': '#widgetContainer-left1'
        };
    },
    call: function(isSubmitted) {
        var params = ESDocumentationSubmenuWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#ESDocumentationSubmenuWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = ESDocumentationSubmenuWidget.getParameters();
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
    Structure.loadWidget('ESDocumentationSubmenuWidget');
    <?php } ?>
});
</script>
