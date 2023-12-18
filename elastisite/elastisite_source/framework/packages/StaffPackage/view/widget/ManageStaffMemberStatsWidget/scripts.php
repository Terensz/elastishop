<script>
var ManageStaffMemberStatsWidget = {
    getParameters: function() {
        return {
            'responseMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/ManageStaffMemberStatsWidget',
            'responseSelector': '#widgetContainer-<?php echo $container->getRouting()->getActualRoute()->getWidgetPosition('ManageStaffMemberStatsWidget'); ?>'
        };
    },
    call: function(isSubmitted) {
        var params = ManageStaffMemberStatsWidget.getParameters();
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#ManageStaffMemberStatsWidget_form');
            ajaxData = form.serialize();
        }
        $.ajax({
            'type' : 'POST',
            'url' : params.responseMethodPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                var params = ManageStaffMemberStatsWidget.getParameters();
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
    Structure.loadWidget('ManageStaffMemberStatsWidget');
    <?php } ?>
});
</script>
