<script>
var RedeemPasswordRecoveryTokenWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/RedeemPasswordRecoveryTokenWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('RedeemPasswordRecoveryTokenWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = RedeemPasswordRecoveryTokenWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#UserPackage_redeemPasswordRecoveryToken_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = RedeemPasswordRecoveryTokenWidget.getParameters();
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
    Structure.loadWidget('RedeemPasswordRecoveryTokenWidget');
    <?php } ?>
});
</script>
