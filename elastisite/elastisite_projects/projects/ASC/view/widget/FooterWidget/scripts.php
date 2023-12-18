<script>
var FooterWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/FooterWidget',
            'responseSelector': '#widgetContainer-FooterWidget'
        };
    },
    call: function(isSubmitted) {
        var params = FooterWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#FooterWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = FooterWidget.getParameters();
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
    Structure.loadWidget('FooterWidget');
    <?php } ?>
});
</script>
