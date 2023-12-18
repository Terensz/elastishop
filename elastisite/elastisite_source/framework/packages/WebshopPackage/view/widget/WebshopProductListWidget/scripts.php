<script>
var WebshopProductListWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/webshop/productListWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('WebshopProductListWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = WebshopProductListWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#WebshopProductListWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = WebshopProductListWidget.getParameters();
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
    Structure.loadWidget('WebshopProductListWidget');
    <?php } ?>
});
</script>
