<div id="adminNewsletterDispatchProcesses-list-container"></div>

<script>
    $('document').ready(function() {
        // console.log('docready');
        NewsletterDispatchProcess.list();
    });
    var NewsletterDispatchProcess = {
        list: function() {
            $.ajax({
                'type': 'POST',
                'url': '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/newsletter/dispatchProcesses/list',
                'data': {
                },
                'async': true,
                'success': function(response) {
                    // console.log(response);
                    $('#adminNewsletterDispatchProcesses-list-container').html(response.view);
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