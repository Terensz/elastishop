<script>
var CustomUserRegistrationWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/user/CustomUserRegistrationWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('CustomUserRegistrationWidget'); ?>'
        };
    },
    save: function() {
        CustomUserRegistrationWidget.call(true);
    },
    call: function(isSubmitted) {
        var params = CustomUserRegistrationWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#UserPackage_UserRegistration_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = CustomUserRegistrationWidget.getParameters();
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
    Structure.loadWidget('CustomUserRegistrationWidget');
    <?php } ?>
});
</script>
