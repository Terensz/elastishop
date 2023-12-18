<script>
var ElastiSitePromo1Widget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/ElastiSitePromo1Widget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('ElastiSitePromo1Widget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = ElastiSitePromo1Widget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#ElastiSitePromo1Widget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = ElastiSitePromo1Widget.getParameters();
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
    Structure.loadWidget('ElastiSitePromo1Widget');
    <?php } ?>
});
</script>
