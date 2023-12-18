<script>
var InviteUserRegistrationWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/asc/InviteUserRegistrationWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('InviteUserRegistrationWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = InviteUserRegistrationWidget.getParameters();
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
                var params = InviteUserRegistrationWidget.getParameters();
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
    Structure.loadWidget('InviteUserRegistrationWidget');
    <?php } ?>
});
</script>
