<script>
var LoginWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/LoginWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('LoginWidget'); ?>'
        };
    },
    call: function(isSubmitted, callingObj) {
        var params = LoginWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#LoginWidget_form');
            ajaxData = form.serialize();
        }
        if (typeof(callingObj) == 'undefined') {
            callingObj = null;
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                console.log('Login ajaxData:');
                console.log(ajaxData);
                ElastiTools.checkResponse(response);
                var params = LoginWidget.getParameters();
                $(params.responseSelector).html(response.view);

                if (callingObj) {
                    // console.log('typeof(' + callingObj + '.callback(): ');
                    // console.log(typeof(window['Login.callback']));
                    // console.log(typeof(window[callingObj + '.callback']));
                    // console.log(eval("typeof " + callingObj + ".callback === 'function'"));

                    // typeof(callbackFunction);
                    if (eval("typeof(" + callingObj + ".callback) === 'function'")) {
                        eval(callingObj + ".callback(" + JSON.stringify(response) + ")");
                    }
                    // if (typeof(callingObj + '.callback()'))
                }

                if (response.data.freshLogin == true) {
                    Structure.call(window.location.href, true);
                }
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    }
};

$(document).ready(function() {
    <?php if ($container->isAjax()) { ?>
    Structure.loadWidget('LoginWidget');
    <?php } ?>
});
</script>
