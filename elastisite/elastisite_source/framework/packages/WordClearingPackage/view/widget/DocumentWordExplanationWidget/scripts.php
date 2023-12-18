<script>
var DocumentWordExplanationWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/document/wordExplanation/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('DocumentWordExplanationWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = DocumentWordExplanationWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#DocumentWordExplanationWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = DocumentWordExplanationWidget.getParameters();
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
    Structure.loadWidget('DocumentWordExplanationWidget');
    <?php } ?>
});
</script>
