<?php
if ($backgrounds) {
    foreach ($backgrounds as $background):
        include('framework/packages/BackgroundPackage/view/widget/AdminBackgroundsWidget/listElement.php');
    endforeach;
}
?>
<!-- <script>
var BackgroundList = {
    getParameters: function() {
        return {
            'deleteMethodPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/background/delete'
        };
    },
    delete: function(e, id) {
        // console.log('id:' + id);
        e.preventDefault();
        var params = BackgroundList.getParameters();
        $.ajax({
            'type' : 'POST',
            'url' : params.deleteMethodPath,
            'data': {'FBSBackgroundId': id},
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                AdminBackgroundsWidget.call();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    }
};
</script> -->
