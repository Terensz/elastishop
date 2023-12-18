<script>
var MenuWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/MenuWidget',
            'responseSelector': '#widgetContainer-MenuWidget'
        };
    },
    call: function(isSubmitted) {
        var params = MenuWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#MenuWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = MenuWidget.getParameters();
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
    // MenuWidget.call(false);
    // console.log('MenuWidget docready');
    Structure.loadWidget('MenuWidget');
    <?php } ?>
});
</script>
