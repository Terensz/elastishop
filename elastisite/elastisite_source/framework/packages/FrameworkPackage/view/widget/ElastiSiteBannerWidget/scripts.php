<script>
var ElastiSiteBannerWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/ElastiSiteBannerWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('ElastiSiteBannerWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = ElastiSiteBannerWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#ElastiSiteBannerWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = ElastiSiteBannerWidget.getParameters();
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
    Structure.loadWidget('ElastiSiteBannerWidget');
    <?php } ?>
});
</script>
