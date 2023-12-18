<script>
var EventWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/event/widget',
            'responseSelector': '#widgetContainer-EventWidget'
        };
    },
    call: function(isSubmitted) {
        var params = EventWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#EventWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                var params = EventWidget.getParameters();
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
    EventWidget.call(false);
    <?php } ?>
});
</script>
