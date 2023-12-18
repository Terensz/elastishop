<form id="LoginGuideWidget_loginForm" name="LoginGuideWidget_loginForm" action="" method="post" autocomplete="off">
<?php
$displayedMessage = '';
$isInvalidStr = '';
if (isset($message)) {
    // dump($message['text']);
    if ($message != '' && $message['text'] != 'login.success') {
        $displayedMessage = trans($message['text']);
        $isInvalidStr = ' is-invalid';
    }
}
?>
        <div class="mb-3">
            {{ csrfTokenInput }}
            <label for="LoginWidget_username" class="form-label"><?php echo trans('username'); ?></label>
            <div class="input-group has-validation">
                <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" name="LoginWidget_username" id="LoginWidget_username" maxlength="250" placeholder="" value="<?php echo $usernamePost; ?>">
                <div class="invalid-feedback validationMessage" id="LoginWidget_username-validationMessage"><?php echo $displayedMessage; ?></div>
            </div>
        </div>

        <div class="mb-3">
            <label for="LoginWidget_password" class="form-label"><?php echo trans('password'); ?></label>
            <div class="input-group has-validation">
                <input type="password" class="form-control inputField" name="LoginWidget_password" id="LoginWidget_password" maxlength="250" placeholder="" value="<?php echo $passwordPost; ?>">
                <div class="invalid-feedback validationMessage" id="LoginWidget_password-validationMessage"></div>
            </div>
        </div>

        <div class="mb-3">
            <button class="btn btn-primary" name="" id="" type="button" onclick="LoginHandler.loginSubmit();" value=""><?php echo trans('login'); ?></button>
        </div>

    </form>