<script>
var WebshopFinalizeOrderWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/webshop/finalizeOrderWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('WebshopFinalizeOrderWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = WebshopFinalizeOrderWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#WebshopFinalizeOrderWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = WebshopFinalizeOrderWidget.getParameters();
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
    Structure.loadWidget('WebshopFinalizeOrderWidget');
    <?php } ?>
});
</script>
