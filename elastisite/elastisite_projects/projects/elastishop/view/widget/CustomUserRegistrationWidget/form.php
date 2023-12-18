<?php
// dump($form);
// exit;
?>
<form name="UserPackage_userRegistration_form" id="UserPackage_userRegistration_form" method="POST" action="" autocomplete="off" enctype="multipart/form-data">

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_name');
// dump('UserPackage_userRegistration_UserAccount_Person_name');
// dump($message);
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_name" class="form-label"><?php echo trans('name'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_name" id="UserPackage_userRegistration_Person_name" 
                maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('name'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_name-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_username');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_username" class="form-label"><?php echo trans('username'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_username" id="UserPackage_userRegistration_Person_username" 
                maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('username'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_username-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_password');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_password" class="form-label"><?php echo trans('password'); ?></label>
        <div class="input-group has-validation">
            <input type="password" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_password" id="UserPackage_userRegistration_Person_password" 
                maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('password'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_password-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_retypedPassword');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_retypedPassword" class="form-label"><?php echo trans('retyped.password'); ?></label>
        <div class="input-group has-validation">
            <input type="password" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_retypedPassword" id="UserPackage_userRegistration_Person_retypedPassword" 
                maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('retypedPassword'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_retypedPassword-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_email');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_email" class="form-label"><?php echo trans('email'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_email" id="UserPackage_userRegistration_Person_email" 
                maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('email'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_email-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_mobile');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_mobile" class="form-label"><?php echo trans('mobile'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_mobile" id="UserPackage_userRegistration_Person_mobile" 
                maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('mobile'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_mobile-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_NewsletterSubscription_subscribed');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_NewsletterSubscription_subscribed" class="form-label"><?php echo trans('i.want.to.receive.newsletters'); ?></label>
        <div class="input-group has-validation">
            <select class="form-select inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_NewsletterSubscription_subscribed" id="UserPackage_userRegistration_NewsletterSubscription_subscribed" 
                aria-describedby="UserPackage_userRegistration_NewsletterSubscription_subscribed-validationMessage" required>
                <option value="*null*"><?php echo trans('please.choose'); ?></option>
                <option value="*yes*"<?php echo $form->getValueCollector()->getDisplayed('subscribed') == '*yes*' ? ' selected' : ''; ?>><?php echo trans('yes'); ?></option>
                <option value="*no*"<?php echo $form->getValueCollector()->getDisplayed('subscribed') == '*no*' ? ' selected' : ''; ?>><?php echo trans('no'); ?></option>
            </select>
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_NewsletterSubscription_subscribed-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 card-pack-header">
            <h4>Kiszállítási cím (Később adhat hozzá újabbakat)</h4>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_Address_0_Country_country');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;

// dump($form->getValueCollector()->getDisplayed('country'));
// dump($countries);
?>

    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_Address_0_Country_country" class="form-label"><?php echo trans('country'); ?></label>
        <div class="input-group has-validation">
            <select class="form-select inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_Address_0_Country_country" id="UserPackage_userRegistration_Person_Address_0_Country_country" 
                aria-describedby="UserPackage_userRegistration_Person_Address_0_Country_country-validationMessage" required>
                <option value="*null*"><?php echo trans('please.choose'); ?></option>
                <?php foreach ($countries as $country): ?>
                <option value="<?php echo $country->getId(); ?>"<?php echo $form->getValueCollector()->getDisplayed('country') == $country->getId() ? ' selected' : ''; ?>
                    ><?php echo trans($country->getTranslationReference().'.country'); ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_Address_0_Country_country-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_Address_0_zipCode');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;

// dump($form->getValueCollector()->getDisplayed('country'));
// dump($countries);
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_Address_0_zipCode" class="form-label"><?php echo trans('zip.code'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_Address_0_zipCode" id="UserPackage_userRegistration_Person_Address_0_zipCode" 
                maxlength="5" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('zipCode'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_Address_0_zipCode-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_Address_0_city');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;

// dump($form->getValueCollector()->getDisplayed('country'));
// dump($countries);
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_Address_0_city" class="form-label"><?php echo trans('city'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_Address_0_city" id="UserPackage_userRegistration_Person_Address_0_city" 
                maxlength="100" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('city'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_Address_0_city-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_Address_0_street');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;

// dump($form->getValueCollector()->getDisplayed('country'));
// dump($countries);
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_Address_0_street" class="form-label"><?php echo trans('street'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_Address_0_street" id="UserPackage_userRegistration_Person_Address_0_street" 
                maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('street'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_Address_0_street-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_Address_0_streetSuffix');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;

// dump($form->getValueCollector()->getDisplayed('country'));
// dump($countries);
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_Address_0_streetSuffix" class="form-label"><?php echo trans('street.suffix'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_Address_0_streetSuffix" id="UserPackage_userRegistration_Person_Address_0_streetSuffix" 
                maxlength="250" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('streetSuffix'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_Address_0_streetSuffix-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_Address_0_houseNumber');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;

// dump($form->getValueCollector()->getDisplayed('country'));
// dump($countries);
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_Address_0_houseNumber" class="form-label"><?php echo trans('house.number'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_Address_0_houseNumber" id="UserPackage_userRegistration_Person_Address_0_houseNumber" 
                maxlength="5" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('houseNumber'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_Address_0_houseNumber-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_Address_0_staircase');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;

// dump($form->getValueCollector()->getDisplayed('country'));
// dump($countries);
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_Address_0_staircase" class="form-label"><?php echo trans('staircase'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_Address_0_staircase" id="UserPackage_userRegistration_Person_Address_0_staircase" 
                maxlength="5" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('staircase'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_Address_0_staircase-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_Address_0_floor');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;
// dump($form->getValueCollector()->getDisplayed('country'));
// dump($countries);
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_Address_0_floor" class="form-label"><?php echo trans('floor'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_Address_0_floor" id="UserPackage_userRegistration_Person_Address_0_floor" 
                maxlength="5" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('floor'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_Address_0_floor-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

<?php
$message = $form->getMessage('UserPackage_userRegistration_Person_Address_0_door');
$isInvalidStr = '';
$displayedMessage = '';
if (isset($message) && $message != ''):
    $displayedMessage = trans($message);
    $isInvalidStr = ' is-invalid';
endif;

// dump($form->getValueCollector()->getDisplayed('country'));
// dump($countries);
?>
    <div class="mb-3">
        <label for="UserPackage_userRegistration_Person_Address_0_door" class="form-label"><?php echo trans('door.number'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField<?php echo $isInvalidStr; ?>" 
                name="UserPackage_userRegistration_Person_Address_0_door" id="UserPackage_userRegistration_Person_Address_0_door" 
                maxlength="5" placeholder="" value="<?php echo $form->getValueCollector()->getDisplayed('door'); ?>">
            <div class="invalid-feedback validationMessage" id="UserPackage_userRegistration_Person_Address_0_door-validationMessage"><?php echo $displayedMessage; ?></div>
        </div>
    </div>

</form>

<div id="userRegistrationSubmitContainer" style="display: inline;">
    <div class="mb-3">
        <button class="btn btn-primary" name="" id="" type="button" onclick="CustomRegistration.submit();" value=""><?php echo trans('save.changes'); ?></button>
    </div>
</div>

<?php
// dump($form->getValueCollector());
// dump($form->getMessages());
// exit;
?>