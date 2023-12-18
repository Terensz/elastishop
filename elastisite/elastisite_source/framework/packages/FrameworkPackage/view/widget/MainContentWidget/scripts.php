<script>
var MainContentWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/MainContentWidget',
            'responseSelector': '#widgetContainer-mainContent'
        };
    },
    call: function(isSubmitted) {
        var params = MainContentWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#MainContentWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = MainContentWidget.getParameters();
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
    Structure.loadWidget('MainContentWidget');
    <?php } ?>
});
</script>
