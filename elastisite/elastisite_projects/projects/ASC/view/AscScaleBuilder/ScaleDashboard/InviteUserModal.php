<form name="ASC_inviteUser_form" id="ASC_inviteUser_form" method="POST" action="" enctype="multipart/form-data">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        Meghívás a csapatba<br><br>
        </div>
    </div>

<?php
if (!isset($name)) {
    $name = '';
}
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="ASC_inviteUser_name" class="form-label"><?php echo trans('name'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="ASC_inviteUser_name" id="ASC_inviteUser_name" 
                maxlength="250" placeholder="" value="<?php echo $name; ?>">
            <div class="invalid-feedback validationMessage" id="ASC_inviteUser_name-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
if (!isset($email)) {
    $email = '';
}
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="ASC_inviteUser_email" class="form-label"><?php echo trans('email'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="ASC_inviteUser_email" id="ASC_inviteUser_email" 
                maxlength="250" placeholder="" value="<?php echo $email; ?>">
            <div class="invalid-feedback validationMessage" id="ASC_inviteUser_email-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 noPadding">
            <div id="userRegistrationSubmitContainer" style="display: inline;">
                <div class="form-group">
                    <button name="ASC_inviteUser_submit" id="ASC_inviteUser_submit" type="button" class="btn btn-secondary btn-block" 
                        onclick="ASCInviteUser.send();" value=""><?php echo trans('send'); ?></button>
                </div>
            </div>
        </div>
    </div>

</form>
<div id="ASCInviteUserResponse"></div>

<script>
    var ASCInviteUser = {
        send: function() {
            // console.log('ASCInviteUser.send');
            var ajaxData = {};
            var form = $('#ASC_inviteUser_form');
            ajaxData = form.serialize();
            LoadingHandler.start();
            $.ajax({
                'type' : 'POST',
                'url' : '/asc/inviteUser/send',
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    $('#editorModal').modal('hide');
                    // console.log(response);
                    if (response.data.success == true) {
                        Structure.throwToast('<?php echo trans('system.message'); ?>', response.data.message);
                    }
                    if (response.data.success == false) {
                        Structure.throwErrorToast('<?php echo trans('system.message'); ?>', response.data.message);
                    }
                    // $('#editorModalBody').html(response.view);
                    Structure.call();
                    LoadingHandler.stop();
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        }
    };
</script>