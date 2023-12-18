<script>
var CVPappFerencContentWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/cv/PappFerencContentWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('CVPappFerencContentWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = CVPappFerencContentWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#CVPappFerencContentWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = CVPappFerencContentWidget.getParameters();
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
    Structure.loadWidget('CVPappFerencContentWidget');
    <?php } ?>
});
</script>
