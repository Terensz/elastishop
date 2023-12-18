<script>
var CreateNewSiteWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/CreateNewSiteWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('CreateNewSiteWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = CreateNewSiteWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#CreateNewSiteWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = CreateNewSiteWidget.getParameters();
                // console.log($(params.responseSelector));

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
    Structure.loadWidget('CreateNewSiteWidget');
    <?php } ?>
});
</script>
