<script>
var AscScaleBuilderWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/asc/scaleBuilder/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('AscScaleBuilderWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = AscScaleBuilderWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#AscScaleBuilderWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = AscScaleBuilderWidget.getParameters();
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
    Structure.loadWidget('AscScaleBuilderWidget');
    <?php } ?>
});
</script>
