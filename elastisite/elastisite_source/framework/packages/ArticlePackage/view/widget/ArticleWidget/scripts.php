<script>
var ArticleWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/article/widget',
            'responseSelector': '#widgetContainer-ArticleWidget'
        };
    },
    call: function(isSubmitted) {
        var params = ArticleWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#ArticleWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                // if (response.data['title'] !== undefined && response.data['title'] !== null && response.data['title'] != '') {
                //     document.title = response.data['title'];
                // }
                var params = ArticleWidget.getParameters();
                $(params.responseSelector).html(response.view);
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    submit: function() {
        ArticleWidget.call(true);
    }
};

$(document).ready(function() {
    <?php if ($container->isAjax()) { ?>
    ArticleWidget.call(false);
    <?php } ?>
});
</script>
