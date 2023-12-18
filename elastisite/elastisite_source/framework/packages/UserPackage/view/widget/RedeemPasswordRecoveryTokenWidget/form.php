<div class="widgetWrapper">
    <div class="article-container">
        <div class="article-head">
            <div class="article-title"><?php echo trans('change.account.password'); ?></div>
        </div>
        <div class="article-teaser">
        </div>
            <form name="UserPackage_changePassword_form" id="UserPackage_changePassword_form" method="POST" autocomplete="off" action="" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-sm-5 col-md-5 col-lg-5">
                        <div class="form-group formLabel">
                            <label for="UserPackage_changePassword_password"><?php echo trans('new.password'); ?></label>
                        </div>
                    </div>
                    <div class="col-sm-7 col-md-7 col-lg-7">
                        <div class="form-group">
                            <input autocomplete="off" name="UserPackage_changePassword_password" id="UserPackage_changePassword_password" type="password" onfocus="this.removeAttribute('readonly');" class="inputField form-control" value="<?php echo $form->getValueCollector()->getPosted('UserPackage_changePassword_password'); ?>" aria-describedby="" placeholder="">
                            <div id="UserPackage_changePassword_password-error" class="fieldError text-danger"><?php echo $form->getMessage('UserPackage_changePassword_password'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5 col-md-5 col-lg-5">
                        <div class="form-group formLabel">
                            <label for="UserPackage_changePassword_retypedPassword"><?php echo trans('new.password.retyped'); ?></label>
                        </div>
                    </div>
                    <div class="col-sm-7 col-md-7 col-lg-7">
                        <div class="form-group">
                            <input autocomplete="off" name="UserPackage_changePassword_retypedPassword" id="UserPackage_changePassword_retypedPassword" type="password" onfocus="this.removeAttribute('readonly');" class="inputField form-control" value="<?php echo $form->getValueCollector()->getPosted('UserPackage_changePassword_retypedPassword'); ?>" aria-describedby="" placeholder="">
                            <div id="UserPackage_changePassword_retypedPassword-error" class="fieldError text-danger"><?php echo $form->getMessage('UserPackage_changePassword_retypedPassword'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5 col-md-5 col-lg-5">
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <button id="UserPackage_userRegistration_submit"
                        type="button" class="btn btn-secondary btn-block"
                        onclick="RedeemPasswordRecoveryToken.call();"><?php echo trans('i.change.account.password'); ?></button>
                    </div>
                </div>
            </form>
        <div class="articleFooter">
        </div>
    </div>
</div>
<script>
    var RedeemPasswordRecoveryToken = {
        call: function() {
            var ajaxData = {};
            var form = $('#UserPackage_changePassword_form');
            ajaxData = form.serialize();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/RedeemPasswordRecoveryTokenWidget',
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
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
<?php 

// dump($form);

?>