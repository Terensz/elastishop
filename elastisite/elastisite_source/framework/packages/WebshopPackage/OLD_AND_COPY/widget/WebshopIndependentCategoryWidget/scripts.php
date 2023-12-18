<script>
var WebshopIndependentCategoryWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/independentCategoryWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('WebshopIndependentCategoryWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = WebshopIndependentCategoryWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#WebshopIndependentCategoryWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = WebshopIndependentCategoryWidget.getParameters();
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
    Structure.loadWidget('WebshopIndependentCategoryWidget');
    <?php } ?>
});
</script>
