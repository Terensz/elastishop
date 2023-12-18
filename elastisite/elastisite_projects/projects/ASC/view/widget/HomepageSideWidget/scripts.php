<script>
var HomepageSideWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/HomepageSideWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('HomepageSideWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = HomepageSideWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#HomepageSideWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = HomepageSideWidget.getParameters();
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
    Structure.loadWidget('HomepageSideWidget');
    <?php } ?>
});
</script>
