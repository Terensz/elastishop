<?php
// dump($form->getValueCollection());
// exit;
?>
<form name="UserPackage_userRegistration_form" id="UserPackage_userRegistration_form" method="POST" action="" autocomplete="off" enctype="multipart/form-data">

<?php
$message = $form->getMessage('UserPackage_userRegistration_UserAccount_Person_name');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_UserAccount_Person_name" class="form-label"><?php echo trans('name'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_UserAccount_Person_name" id="UserPackage_userRegistration_UserAccount_Person_name" 
                maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('name'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_UserAccount_Person_name-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_primaryLanguageCode');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_primaryLanguageCode" class="form-label"><?php echo trans('primary.language'); ?></label>
        <div class="input-group has-validation">
            <select class="form-select inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_primaryLanguageCode" id="UserPackage_userRegistration_primaryLanguageCode" 
                aria-describedby="UserPackage_userRegistration_primaryLanguageCode-validationMessage" required>
                <option value="*null*"><?php echo trans('please.choose'); ?></option>
<?php  
$languages = [
    [
        'code' => 'hu',
        'translationReference' => 'hungarian'
    ],
    [
        'code' => 'en',
        'translationReference' => 'english'
    ]
];
?>
<?php foreach ($languages as $language): ?>
                    <option value="<?php echo $language['code']; ?>"<?php echo $form->getValueCollector()->getDisplayed('primaryLanguageCode') == $language['code'] ? ' selected' : ''; ?>><?php echo trans($language['translationReference']); ?></option>
<?php endforeach; ?>
            </select>
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_primaryLanguageCode-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_UserAccount_Person_username');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_UserAccount_Person_username" class="form-label"><?php echo trans('username'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_UserAccount_Person_username" id="UserPackage_userRegistration_UserAccount_Person_username" 
                maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('username'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_UserAccount_Person_username-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_UserAccount_Person_password');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_UserAccount_Person_password" class="form-label"><?php echo trans('password'); ?></label>
        <div class="input-group has-validation">
            <input type="password" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_UserAccount_Person_password" id="UserPackage_userRegistration_UserAccount_Person_password" 
                maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('password'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_UserAccount_Person_password-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_UserAccount_Person_retypedPassword');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_UserAccount_Person_retypedPassword" class="form-label"><?php echo trans('retyped.password'); ?></label>
        <div class="input-group has-validation">
            <input type="password" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_UserAccount_Person_retypedPassword" id="UserPackage_userRegistration_UserAccount_Person_retypedPassword" 
                maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('retypedPassword'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_UserAccount_Person_retypedPassword-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_UserAccount_Person_email');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_UserAccount_Person_email" class="form-label"><?php echo trans('email'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_UserAccount_Person_email" id="UserPackage_userRegistration_UserAccount_Person_email" 
                maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('email'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_UserAccount_Person_email-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_UserAccount_NewsletterSubscription_subscribed');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_UserAccount_NewsletterSubscription_subscribed" class="form-label"><?php echo trans('i.want.to.receive.newsletters'); ?></label>
        <div class="input-group has-validation">
            <select class="form-select inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_UserAccount_NewsletterSubscription_subscribed" id="UserPackage_userRegistration_UserAccount_NewsletterSubscription_subscribed" 
                aria-describedby="UserPackage_userRegistration_primaryLanguageCode-validationMessage" required>
                <option value="*null*"><?php echo trans('please.choose'); ?></option>
                <option value="*yes*"<?php echo $form->getValueCollector()->getDisplayed('subscribed') == '*yes*' ? ' selected' : ''; ?>><?php echo trans('yes'); ?></option>
                <option value="*no*"<?php echo $form->getValueCollector()->getDisplayed('subscribed') == '*no*' ? ' selected' : ''; ?>><?php echo trans('no'); ?></option>
            </select>
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_UserAccount_NewsletterSubscription_subscribed-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

</form>

<div id="userRegistrationSubmitContainer" style="display: inline;">
    <div class="mb-3">
        <button class="btn btn-primary" name="" id="" type="button" onclick="CustomRegistration.submit();" value=""><?php echo trans('save.changes'); ?></button>
    </div>
</div>

<?php 
// dump($form->getMessages());
?>