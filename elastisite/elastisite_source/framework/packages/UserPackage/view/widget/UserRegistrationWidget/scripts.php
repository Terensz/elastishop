<script>
var UserRegistrationWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/user/registration/widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('UserRegistrationWidget'); ?>'
        };
    },
    save: function() {
        UserRegistrationWidget.call(true);
    },
    call: function(isSubmitted) {
        var params = UserRegistrationWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#UserPackage_userRegistration_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = UserRegistrationWidget.getParameters();
                $(params.responseSelector).html(response.view);
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    }
};

$(document).ready(function() {
    <?php if ($container->isAjax()) { ?>
    Structure.loadWidget('UserRegistrationWidget');
    <?php } ?>
});
</script>
