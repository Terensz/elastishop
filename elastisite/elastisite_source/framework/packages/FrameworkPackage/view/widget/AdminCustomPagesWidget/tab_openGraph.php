<div id="customPageEdit_openGraph_formContainer">
<?php
// $pageTool
// dump($form);
include('tab_openGraph_form.php');
?>
</div>
<script>
    var CustomPageOpenGraph = {
        submitForm: function() {
            CustomPageOpenGraph.loadForm(true, true);
        },
        loadForm: function(submitted, closeModalIfValid) {
            console.log('id: ', $('#customPageId').html());
            var form = $('#FrameworkPackage_customPageOpenGraph_form');
            var formData = form.serialize();
            var additionalData = {
                'customPageId': $('#customPageId').html(),
                // 'code': $('#openGraphEdit_code').html(),
                // 'extension': $('#openGraphEdit_extension').html(),
                'submitted': submitted
            };
            ajaxData = formData + '&' + $.param(additionalData);
            // console.log(ajaxData);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/openGraph/editForm',
                'data': ajaxData,
                'async': false,
                'success': function(response) {
                    LoadingHandler.stop();
                    // console.log(response.data);
                    
                    $('#customPageId').html(response.data.customPageId);
                    // $('#FrameworkPackage_openGraphEdit_imageHeaderId').val(response.data.openGraphImage);
                    $('#customPageEdit_openGraph_formContainer').html(response.view);
                    // console.log('ajax response',response);
                    if (submitted) {
                        AdminCustomPagesGrid.list(true);
                    }
                }
            });
        },
        selectCustomPageOpenGraph: function(openGraphId) {
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/openGraph/setCustomPageOpenGraph',
                'data': {
                    'customPageId': $('#customPageId').html(),
                    'openGraphId': openGraphId
                },
                'async': false,
                'success': function(response) {
                    LoadingHandler.stop();
                    CustomPageOpenGraph.loadForm();
                }
            });
        },
        removeCustomPageOpenGraph: function(e) {
            e.preventDefault();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/openGraph/removeCustomPageOpenGraph',
                'data': {
                    'customPageId': $('#customPageId').html()
                },
                'async': false,
                'success': function(response) {
                    LoadingHandler.stop();
                    CustomPageOpenGraph.loadForm();
                }
            });
        }
    };
    
    $('document').ready(function() {
        $('body').off('click', '.customPageOpenGraph_input');
        $('body').on('click', '.customPageOpenGraph_input', function() {
        // $('.customPageOpenGraph_input').on('click', function() {
            var openGraph = $(this);
            var openGraphId = openGraph.attr('data-opengraphid');
            // console.log(openGraph.attr('data-opengraphid'));
            CustomPageOpenGraph.selectCustomPageOpenGraph(openGraphId);
        });
        LoadingHandler.stop();
    });
</script>