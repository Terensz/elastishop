<?php if ($defaultCustomPage): ?>
<?php 
    // dump($defaultCustomPage); 
?>
<?php else: ?>

<?php endif; ?>
<div id="CustomPage-defaultCustomPagePanel-container">
</div>

<div id="CustomPage-dataGrid-container">
</div>
<script>
var CustomPage = {
    defaultCustomPageId: <?php if ($defaultCustomPage): echo $defaultCustomPage->getId(); else: echo 'null'; endif; ?>,
    init: function() {
        $('#CustomPage-dataGrid-container').hide();
        CustomPage.loadDataGrid();
        CustomPage.loadDefaultCustomPagePanel();
        if (CustomPage.defaultCustomPageId != null) {
            $('#CustomPage-dataGrid-container').show();
        }
    },
    loadDefaultCustomPagePanel: function() {
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPages/defaultCustomPagePanel',
            'data': {},
            'async': false,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                // console.log(response);
                $('#CustomPage-defaultCustomPagePanel-container').html(response.view);
                console.log(response);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                // LoadingHandler.stop();
            },
        });
    },
    loadDataGrid: function() {
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPages/list',
            'data': {},
            'async': false,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                // console.log(response);
                $('#CustomPage-dataGrid-container').html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                // LoadingHandler.stop();
            },
        });
    },
    createDefaultCustomPage: function() {
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPages/createDefaultCustomPage',
            'data': {},
            'async': false,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                if (isNaN(response.data.id) == false) {
                    CustomPage.defaultCustomPageId = response.data.id;
                    AdminCustomPagesGrid.edit(null, response.data.id);
                    CustomPage.init();
                }
                // console.log(response);
                // $('#CustomPage-dataGrid-container').html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                // LoadingHandler.stop();
            },
        });
    }
};
    $('document').ready(function() {
        CustomPage.init();
        console.log('init');
    });
</script>