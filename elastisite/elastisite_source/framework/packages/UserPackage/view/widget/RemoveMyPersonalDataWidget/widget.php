<div class="widgetWrapper">
    <div class="article-container">
        <div class="article-head">
            <div class="article-title"><?php echo trans('remove.my.personal.data'); ?></div>
        </div>
        <div class="article-content">
            <?php echo trans('remove.presonal.data.warning', [['from' => '[httpDomain]', 'to' => $container->getUrl()->getHttpDomain()]]); ?>
            <br><br>
        </div>
            <form name="UserPackage_removePersonalData_form" id="UserPackage_removePersonalData_form" method="POST" autocomplete="off" action="" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-sm-5 col-md-5 col-lg-5">
                        <div class="form-group formLabel">
                            <label for="UserPackage_removePersonalData_agreement"><?php echo trans('i.accept.remove.data.consequences'); ?></label>
                        </div>
                    </div>
                    <div class="col-sm-7 col-md-7 col-lg-7">
                        
                        <div class="form-group">
                            <!-- <input autocomplete="off" name="UserPackage_removePersonalData_agreement" id="UserPackage_removePersonalData_agreement" type="password" onfocus="this.removeAttribute('readonly');" class="inputField form-control" value="" aria-describedby="" placeholder=""> -->
                            <div style="margin-left: 20px;" class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="UserPackage_removePersonalData_agreement" name="UserPackage_removePersonalData_agreement" value="1">
                            </div>
                            <div style="padding-top: 24px;" id="UserPackage_removePersonalData_agreement-error" class="fieldError text-danger"><?php echo $agreementErrorMessage; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5 col-md-5 col-lg-5">
                        <div class="form-group formLabel">
                            <label for="UserPackage_removePersonalData_password"><?php echo trans('my.user.account.password'); ?></label>
                        </div>
                    </div>
                    <div class="col-sm-7 col-md-7 col-lg-7">
                        <div class="form-group">
                            <input autocomplete="off" name="UserPackage_removePersonalData_password" id="UserPackage_removePersonalData_password" type="password" onfocus="this.removeAttribute('readonly');" class="inputField form-control" value="" aria-describedby="" placeholder="">
                            <div id="UserPackage_userRegistration_name-error" class="fieldError text-danger"><?php echo $passwordErrorMessage; ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5 col-md-5 col-lg-5">
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <button id="UserPackage_removePersonalData_submit"
                        type="button" class="btn btn-secondary btn-block"
                        onclick="RemovePersonalData.remove();"><?php echo trans('i.remove.my.user.account'); ?></button>
                    </div>
                </div>
            </form>
        <div class="article-teaser">
        <?php echo trans('click.here.to.recover.password'); ?>
        </div>
        <div class="articleFooter">
        </div>
    </div>
</div>

<script>
var RemovePersonalData = {
    remove: function() {
        var ajaxData = {};
        var form = $('#UserPackage_removePersonalData_form');
        ajaxData = form.serialize();
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/user/RemoveMyPersonalDataWidget',
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
