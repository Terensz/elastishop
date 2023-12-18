<div id="cookieBoxFrame">
    <?php include('widgetFlexibleContent.php'); ?>
</div>
<script>
    var CookieBoxWidget = {
        loadDetailedList: function(detailedListRequest) {
            LoadingHandler.start();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/widget/CookieBoxWidget',
                'data': {
                    'detailedListRequest': detailedListRequest,
                    // 'acceptance': acceptance
                },
                'async': true,
                'success': function(response) {
                    // ElastiTools.checkResponse(response);
                    if (response.view) {
                        $('#cookieBoxFrame').html(response.view);
                    }
                    LoadingHandler.stop();
                    // if (response.data.removeCookieNotice == true) {
                    //     location.reload();
                    // } else {
                    //     LoadingHandler.stop();
                    // }
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        closeDetailedList: function(e) {
            e.preventDefault();
            CookieInterface.loadCookieBox();
        }
    };

    $('document').ready(function() {
        $('body').off('click', '#cookieBox-summarizer-container');
        $('body').on('click', '#cookieBox-summarizer-container', function() {
            CookieBoxWidget.loadDetailedList(true);
            console.log('KLIK');
        });
    });
</script>