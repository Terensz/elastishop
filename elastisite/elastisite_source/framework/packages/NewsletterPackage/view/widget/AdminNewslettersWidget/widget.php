<div id="adminNewsletters-list-container"></div>
<script src="/public_folder/plugin/CKEditor/ckeditor/ckeditor.js"></script>

<script>
    $('document').ready(function() {
        console.log('docready');
        Newsletter.list();
    });
    var Newsletter = {
        list: function() {
            $.ajax({
                'type': 'POST',
                'url': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/newsletters/list',
                'data': {
                },
                'async': true,
                'success': function(response) {
                    // console.log(response);
                    $('#adminNewsletters-list-container').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        // showForm: function() {
        //     $.ajax({
        //         'type' : 'POST',
        //         'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/customPage/background/editForm',
        //         'data': {
        //             'customPageId': $('#customPageId').html(),
        //             'backgroundColor': $('#FrameworkPackage_customPageBackground_backgroundColor').val(),
        //             'fbsBackgroundTheme': fbsBackgroundTheme,
        //         },
        //         'async': false,
        //         'success': function(response) {
        //             LoadingHandler.stop();
        //             $('#customPageEdit_background_formContainer').html(response.view);
        //         }
        //     });
        // },
    };
</script>