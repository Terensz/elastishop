<form name="UserPackage_forgottenPassword_form" id="UserPackage_forgottenPassword_form" method="POST" action="" enctype="multipart/form-data">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php echo trans('modify.password.info'); ?><br><br>
        </div>
    </div>

<?php
if (!$userEmail) :
?>
<?php
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_forgottenPassword_email" class="form-label"><?php echo trans('email'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_forgottenPassword_email" id="UserPackage_forgottenPassword_email" 
                maxlength="250" placeholder="" value="<?php echo $email; ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_UserAccount_Person_email-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
else:
?>
    <div class="row">
        <div class="col-sm-12 noPadding">
            <div class="">
                <div style="padding-top: 10px;">
                    <?php echo $userEmail; ?>
                </div>
            </div>
        </div>
    </div>
<?php
endif;
?>

    <div class="row">
        <div class="col-sm-12 noPadding">
            <div id="userRegistrationSubmitContainer" style="display: inline;">
                <div class="form-group">
                    <button name="UserPackage_forgottenPassword_submit" id="UserPackage_forgottenPassword_submit" type="button" class="btn btn-secondary btn-block" 
                        onclick="ForgottenPassword.send();" value=""><?php echo trans('send'); ?></button>
                </div>
            </div>
        </div>
    </div>

</form>
<div id="forgottenPasswordResponse"></div>

<script>
    var ForgottenPassword = {
        send: function() {
            // console.log('ForgottenPassword.send');
            var ajaxData = {};
            var form = $('#UserPackage_forgottenPassword_form');
            ajaxData = form.serialize();
            $.ajax({
                'type' : 'POST',
                'url' : '/ajax/forgottenPassword/send',
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    $('#editorModalBody').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        }
    };
</script>