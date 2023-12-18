<script>
var DevDbWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/devDb/widget',
            'responseSelector': '#widgetContainer-DevDbWidget'
        };
    },
    call: function(isSubmitted) {
        var params = LoginWidget.getParameters();
        var ajaxData = {};
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                var params = LoginWidget.getParameters();
                $(params.responseSelector).html(response.view);
                if (response.data.freshLogin == true) {
                    Structure.call(window.location.href, true);
                }
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
    DevDbWidget.call(false);
    <?php } ?>
});
</script>
