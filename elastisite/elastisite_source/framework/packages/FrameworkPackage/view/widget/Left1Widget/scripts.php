<script>
var Left1Widget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/Left1Widget',
            'responseSelector': '#widgetContainer-left1'
        };
    },
    call: function(isSubmitted) {
        var params = Left1Widget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#Left1Widget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = Left1Widget.getParameters();
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
    Structure.loadWidget('Left1Widget');
    <?php } ?>
});
</script>
