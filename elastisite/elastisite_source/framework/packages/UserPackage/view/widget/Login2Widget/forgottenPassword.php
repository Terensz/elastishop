<form name="UserPackage_forgottenPassword_form" id="UserPackage_forgottenPassword_form" method="POST" action="" enctype="multipart/form-data">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php echo trans('modify.password.info'); ?><br><br>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <div class="form-group formLabel">
                <label for="UserPackage_forgottenPassword_email">
                    <b>E-mail cím</b>
                </label>
            </div>
        </div>
<?php
$email = $container->getUser()->getEmail();
if (!$email) {
?>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <div class="input-group">
                    <input name="UserPackage_forgottenPassword_email" id="UserPackage_forgottenPassword_email" type="text" class="inputField-light form-control" value="<?php echo $email; ?>" aria-describedby="" placeholder="">

                </div>
                <div class="validationMessage error" id="UserPackage_forgottenPassword_email-validationMessage" style="padding-top:4px;"></div>
            </div>
        </div>
<?php
} else {
?>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="">
                <div style="padding-top: 10px;">
                    <?php echo $email; ?>
                </div>
                <div class="validationMessage error" id="UserPackage_forgottenPassword_email-validationMessage" style="padding-top:4px;"></div>
            </div>
        </div>
<?php
}
?>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <button name="UserPackage_forgottenPassword_submit" id="UserPackage_forgottenPassword_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="ForgottenPassword.sendInit();" value=""><?php echo trans('send'); ?></button>
            </div>
        </div>
    </div>
</form>
<div id="forgottenPasswordResponse"></div>

<script>
// var ForgottenPassword = {
//     processResponse: function(response, calledBy, onSuccessCallback) {
//         // console.log('ForgottenPassword.processResponse()');
//         dump(response);
//         if (typeof this[onSuccessCallback] === 'function') {
//             this[onSuccessCallback](response);
//         }
//     },
//     callAjax: function(calledBy, ajaxUrl, additionalData, onSuccessCallback) {
//         let baseData = {};
//         let ajaxData = $.extend({}, baseData, additionalData);
//         $.ajax({
//             'type' : 'POST',
//             'url' : ajaxUrl,
//             'data': ajaxData,
//             'async': true,
//             'success': function(response) {
//                 ElastiTools.checkResponse(response);
//                 ForgottenPassword.processResponse(response, calledBy, onSuccessCallback);
//             },
//             'error': function(request, error) {
//                 console.log(request);
//                 console.log(" Can't do because: " + error);
//             },
//         });
//     },
//     sendInit: function(event) {
//         if (event) {
//             event.preventDefault();
//         }
//         ForgottenPassword.callAjax('sendInit', '/ajax/forgottenPassword/send', {
//         }, 'sendCallback');
//     },
//     sendCallback: function(response) {
//         $('#editorModalBody').html(response.view);
//         LoadingHandler.stop();
//     },
// };
</script>