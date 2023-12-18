<script>
var WebshopPaymentWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/webshop/paymentWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('WebshopPaymentWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = WebshopPaymentWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#WebshopPaymentWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = WebshopPaymentWidget.getParameters();
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
    Structure.loadWidget('WebshopPaymentWidget');
    <?php } ?>
});
</script>
