<script>
var SideSubmenuWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/SideSubmenuWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('SideSubmenuWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = SideSubmenuWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#SideSubmenuWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = SideSubmenuWidget.getParameters();
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
    Structure.loadWidget('SideSubmenuWidget');
    <?php } ?>
});
</script>
