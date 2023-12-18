<div class="widgetWrapper">
<?php echo $textView; ?>
</div>
<script>
    // var RefusedBarionCookie = {
    //     remove: function(e) {
    //         LoadingHandler.start();
    //         e.preventDefault();
    //         $.ajax({
    //             'type' : 'POST',
    //             'url' : '<?php echo $httpDomain; ?>/widget/CookieConsentWidget_removeRefusedConsent',
    //             'data': {
    //                 'subscriber': 'Barion'
    //             },
    //             'async': true,
    //             'success': function(response) {
    //                 location.reload();
    //                 // if (response.data.success == true) {
    //                 //     location.reload();
    //                 // }
    //                 // LoadingHandler.stop();
    //             },
    //             'error': function(request, error) {
    //                 console.log(request);
    //                 console.log(" Can't do because: " + error);
    //                 LoadingHandler.stop();
    //             },
    //         });
    //         console.log('RefusedBarionCookie.remove!!!!');
    //     }
    // };
</script>