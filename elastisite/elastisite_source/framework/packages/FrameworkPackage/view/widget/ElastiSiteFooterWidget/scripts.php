<script>
var ElastiSiteFooterWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/ElastiSiteFooterWidget',
            'responseSelector': '#widgetContainer-ElastiSiteFooterWidget'
        };
    },
    call: function(isSubmitted) {
        var params = ElastiSiteFooterWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#ElastiSiteFooterWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = ElastiSiteFooterWidget.getParameters();
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
    ElastiSiteFooterWidget.call(false);
    <?php } ?>
});
</script>
