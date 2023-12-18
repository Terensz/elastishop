<script>
var AdminBgBindingsWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/background/bindings/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('AdminBgBindingsWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = AdminBgBindingsWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#AdminBgBindingsWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = AdminBgBindingsWidget.getParameters();
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
    Structure.loadWidget('AdminBgBindingsWidget');
    <?php } ?>
});
</script>
