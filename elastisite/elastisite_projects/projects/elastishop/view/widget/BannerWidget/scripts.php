<script>
var BannerWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/BannerWidget',
            'responseSelector': '#widgetContainer-BannerWidget'
        };
    },
    call: function(isSubmitted) {
        var params = BannerWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#BannerWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = BannerWidget.getParameters();
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
    Structure.loadWidget('BannerWidget');
    <?php } ?>
});
</script>
