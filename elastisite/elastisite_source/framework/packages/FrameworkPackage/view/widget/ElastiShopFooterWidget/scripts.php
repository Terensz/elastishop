<script>
var ElastiShopFooterWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/ElastiShopFooterWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('ElastiShopFooterWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = ElastiShopFooterWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#ElastiShopFooterWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = ElastiShopFooterWidget.getParameters();
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
        ElastiShopFooterWidget.call(false);
    <?php } ?>
});
</script>
