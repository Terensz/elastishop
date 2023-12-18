<script>
var AdminWebshopStockWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/webshop/stock/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('AdminWebshopStockWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = AdminWebshopStockWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#AdminWebshopStockWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = AdminWebshopStockWidget.getParameters();
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
    Structure.loadWidget('AdminWebshopStockWidget');
    <?php } ?>
});
</script>
