<div class="widgetWrapper">
    <div class="article-container">
        <div class="article-head">
            <div class="article-title"><?php echo trans('change.my.password'); ?></div>
        </div>
        <div class="article-teaser"><?php echo trans('change.my.password'); ?></div>
        <form name="UserPackage_changePassword_form" id="UserPackage_changePassword_form" method="POST" autocomplete="off" action="" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-5 col-md-5 col-lg-5">
                    <button id="UserPackage_changePassword_submit"
                        type="button" class="btn btn-secondary btn-block"
                        onclick="ChangePassword.send();"><?php echo trans('send.email'); ?></button>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6">
                </div>
            </div>
        </form>
        <div class="articleFooter">
        </div>
    </div>
</div>

<script>
var ChangePassword = {
    send: function() {
        var ajaxData = {};
        var form = $('#UserPackage_passwordChange_form');
        ajaxData = form.serialize();
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/ajax/forgottenPassword/send',
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                LoginWidget.call(false);
                UsersDocumentsWidget.call();
                $('#widgetContainer-mainContent').html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    }
};
</script>
