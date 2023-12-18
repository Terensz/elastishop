<script>
var CVPappFerencMenuWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/cv/PappFerencMenuWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('CVPappFerencMenuWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = CVPappFerencMenuWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#CVPappFerencMenuWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = CVPappFerencMenuWidget.getParameters();
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
    Structure.loadWidget('CVPappFerencMenuWidget');
    <?php } ?>
});
</script>
