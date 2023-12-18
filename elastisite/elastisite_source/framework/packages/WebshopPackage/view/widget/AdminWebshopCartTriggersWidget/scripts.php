<script>
var AdminWebshopCartTriggersWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/AdminWebshopCartTriggersWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('AdminWebshopCartTriggersWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = AdminWebshopCartTriggersWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#AdminWebshopCartTriggersWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = AdminWebshopCartTriggersWidget.getParameters();
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
    Structure.loadWidget('AdminWebshopCartTriggersWidget');
    <?php } ?>
});
</script>
