<script>
var AscScaleListerWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/asc/scaleLister/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('AscScaleListerWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = AscScaleListerWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#AscScaleListerWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = AscScaleListerWidget.getParameters();
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
    Structure.loadWidget('AscScaleListerWidget');
    <?php } ?>
});
</script>
