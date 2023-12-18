<script>
var AdminSideMenuWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/AdminSideMenuWidget',
            'responseSelector': '#widgetContainer-AdminSideMenuWidget'
        };
    },
    call: function(isSubmitted) {
        var params = AdminSideMenuWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#AdminSideMenuWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = AdminSideMenuWidget.getParameters();
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
    // AdminSideMenuWidget.call(false);
    // console.log('AdminSideMenuWidget docready');
    Structure.loadWidget('AdminSideMenuWidget');
    <?php } ?>
});
</script>
