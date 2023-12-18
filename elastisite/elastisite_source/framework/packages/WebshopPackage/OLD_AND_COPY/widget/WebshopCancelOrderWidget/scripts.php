<script>
var WebshopCancelOrderWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/webshop/cancelOrderWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('WebshopCancelOrderWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = WebshopCancelOrderWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#WebshopCancelOrderWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = WebshopCancelOrderWidget.getParameters();
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
    Structure.loadWidget('WebshopCancelOrderWidget');
    <?php } ?>
});
</script>
