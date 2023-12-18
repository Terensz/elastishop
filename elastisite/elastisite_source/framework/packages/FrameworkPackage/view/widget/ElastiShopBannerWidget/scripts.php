<script>
var ElastiShopBannerWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/ElastiShopBannerWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('ElastiShopBannerWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = ElastiShopBannerWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#ElastiShopBannerWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = ElastiShopBannerWidget.getParameters();
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
    Structure.loadWidget('ElastiShopBannerWidget');
    <?php } ?>
});
</script>
