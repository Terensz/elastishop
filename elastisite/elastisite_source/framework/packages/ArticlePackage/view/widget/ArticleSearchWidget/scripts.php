<script>
var ArticleSearchWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/article/search/widget',
            'responseSelector': '#widgetContainer-ArticleSearchWidget',
            'formSelector': '#ArticlePackage_articleSearch_form'
        };
    },
    call: function(isSubmitted) {
        var params = ArticleSearchWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $(params.formSelector);
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                var params = ArticleSearchWidget.getParameters();
                // console.log(response.view);
                // console.log(params.responseSelector);
                $(params.responseSelector).html(response.view);
                // $(params.responseSelector).html('response.view');
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
    submit: function() {
        ArticleSearchWidget.call(true);
        $('#ArticlePackage_articleSearch_mixed').focus();
    }
};

$(document).ready(function() {
    <?php if ($container->isAjax()) { ?>
    ArticleSearchWidget.call(false);
    <?php } ?>
});
</script>
