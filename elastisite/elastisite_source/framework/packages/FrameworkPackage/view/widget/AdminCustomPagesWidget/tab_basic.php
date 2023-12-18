<div id="customPageEdit_basic_formContainer">
<?php
// dump($form);
include('tab_basic_form.php');
?>
</div>
<script>
    var CustomPageTitle = {
        saveTitle: function(e) {
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/basic/titleForm',
                'data': {
                    'customPageId': $('#customPageId').html(),
                    'title': $('#FrameworkPackage_customPageTitle_title').val()
                },
                'async': true,
                'success': function(response) {
                    // ElastiTools.checkResponse(response);
                    AdminCustomPagesGrid.list(true);
                    LoadingHandler.stop();
                    if (response.data.success == true) {
                        Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('title.saved'); ?>');
                    } else {
                        Structure.throwErrorToast('<?php echo trans('system.message'); ?>', '<?php echo trans('title.save.failed'); ?>');
                    }
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    LoadingHandler.stop();
                },
            });
        },
    }
    var CustomPageBasic = {
        submitForm: function() {
            CustomPageBasic.loadForm(true, true);
        },
        loadForm: function(submitted, closeModalIfValid) {
            console.log('id: ', $('#customPageId').html());
            var form = $('#FrameworkPackage_customPageBasic_form');
            var formData = form.serialize();
            var additionalData = {
                'customPageId': $('#customPageId').html(),
                'routeName': $('#routeName').html(),
                // 'code': $('#openGraphEdit_code').html(),
                // 'extension': $('#openGraphEdit_extension').html(),
                'submitted': submitted
            };
            ajaxData = formData + '&' + $.param(additionalData);
            // console.log(ajaxData);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/basic/editForm',
                'data': ajaxData,
                'async': false,
                'success': function(response) {
                    LoadingHandler.stop();
                    // console.log(response.data);
                    
                    $('#customPageId').html(response.data.customPageId);
                    $('#customPageEdit_basic_formContainer').html(response.view);
                    // console.log('ajax response',response);
                    if (submitted) {
                        AdminCustomPagesGrid.list(true);
                    }
                    // if (response.data.submitted == true && response.data.formIsValid == true && closeModalIfValid == true) {
                    //     CustomPageBasic.saveSuccessful();
                    // }
                    if (response.data.freshlySaved && response.data.freshlySaved != 'false') {
                        // console.log('freshlySaved!!!');
                        $('#customPageId').html(response.data.customPageId);
                        // $('#editorModal').modal('hide');
                        AdminCustomPagesGrid.edit(null, response.data.customPageId);
                    }
                }
            });
        },
        modifyRoute: function(e) {
            e.preventDefault();
            $('#routeName').html('');
            CustomPageEdit.loadTabContent('basic');
        }
    };

    $('document').ready(function() {
        LoadingHandler.stop();
        $('.PageToolView_customPageBasic_input').off('click');
        $('.PageToolView_customPageBasic_input').on('click', function() {
            let id = $(this).attr('id');
            let dataRoute = $('#' +id).attr('data-route');
            console.log(dataRoute);
            $('#routeName').html(dataRoute);
            CustomPageEdit.loadTabContent('basic');
        });
    });
</script>