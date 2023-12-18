<script>
var Left2Widget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/Left2Widget',
            'responseSelector': '#widgetContainer-left2'
        };
    },
    call: function(isSubmitted) {
        var params = Left2Widget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#Left2Widget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = Left2Widget.getParameters();
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
    Structure.loadWidget('Left2Widget');
    <?php } ?>
});
</script>
