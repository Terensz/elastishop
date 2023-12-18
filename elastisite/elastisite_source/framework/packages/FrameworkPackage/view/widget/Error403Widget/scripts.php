<script>
var Error403Widget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/Error403Widget',
            'responseSelector': '#widgetContainer-ErrorWidget'
        };
    },
    call: function(isSubmitted) {
        var params = ErrorWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#ErrorWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                if (response.data['title'] !== undefined && response.data['title'] !== null && response.data['title'] != '') {
                    document.title = response.data['title'];
                }
                var params = ErrorWidget.getParameters();
                $(params.responseSelector).html(response.view);
                // $(params.responseSelector).fadeOut(function(){
                //     console.log('fade');
                //     $(params.responseSelector).html(response.view);
                //     $(params.responseSelector).fadeIn();
                // });
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    submit: function() {
        ErrorWidget.call(true);
    }
};

$(document).ready(function() {
    <?php if ($container->isAjax()) { ?>
    ErrorWidget.call(false);
    <?php } ?>
});
</script>
