<div id="cookieNoticeFrame" class="noOpacity">
    <?php include('widgetFlexibleContent.php'); ?>
</div>
<div id="cookieNoticeVeil" class="">
</div>
<script>
    var CookieNoticeWidget = {
        moreInfoMode: false,
        submit: function(subscriber, acceptance, acceptAll, refuseAll) {
            LoadingHandler.start();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/widget/CookieNoticeWidget_submit',
                'data': {
                    'subscriber': subscriber,
                    'acceptance': acceptance,
                    'acceptAll': acceptAll,
                    'refuseAll': refuseAll
                },
                'async': true,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    if (response.view) {
                        $('#cookieNoticeFrame').html(response.view);
                    }
                    console.log('response.data.removeCookieNotice: ', response.data.removeCookieNotice);
                    console.log('response.data.removeCookieNoticeReason: ', response.data.removeCookieNoticeReason);
                    if (response.data.removeCookieNotice == true) {
                        location.reload();
                        // CookieInterface.removeCookieNotice();
                    } else {
                        LoadingHandler.stop();
                    }
                    if (CookieNoticeWidget.moreInfoMode == true) {
                        CookieNoticeWidget.moreInfo(null);
                    } else {
                        CookieNoticeWidget.lessInfo(null);
                    }
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        moreInfo: function(e) {
            CookieNoticeWidget.moreInfoMode = true;
            if (e !== null) {
                // console.log('e: ', e);
                // console.log('typeof(e): ',typeof(e));
                e.preventDefault();
            }
            $('.cookieNotice-lead').hide();
            $('.cookieNotice-description').show();
        },
        lessInfo: function(e) {
            CookieNoticeWidget.moreInfoMode = false;
            if (e !== null) {
                e.preventDefault();
            }
            $('.cookieNotice-lead').show();
            $('.cookieNotice-description').hide();
        },
    };
</script>