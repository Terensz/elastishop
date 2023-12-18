<script>
var ProjectTeamworkWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/ProjectTeamworkWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('ProjectTeamworkWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = ProjectTeamworkWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#ProjectTeamworkWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = ProjectTeamworkWidget.getParameters();
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
    Structure.loadWidget('ProjectTeamworkWidget');
    <?php } ?>
});
</script>
