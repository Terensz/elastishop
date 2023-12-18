<script>
var DocumentationSubmenuWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/DocumentationSubmenuWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('DocumentationSubmenuWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = DocumentationSubmenuWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#DocumentationSubmenuWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = DocumentationSubmenuWidget.getParameters();
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
    Structure.loadWidget('DocumentationSubmenuWidget');
    <?php } ?>
});
</script>
