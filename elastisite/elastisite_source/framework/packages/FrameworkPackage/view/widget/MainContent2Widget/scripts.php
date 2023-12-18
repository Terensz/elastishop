<script>
var MainContent2Widget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/MainContent2Widget',
            'responseSelector': '#widgetContainer-mainContent2'
        };
    },
    call: function(isSubmitted) {
        var params = MainContent2Widget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#MainContent2Widget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = MainContent2Widget.getParameters();
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
    Structure.loadWidget('MainContent2Widget');
    <?php } ?>
});
</script>
