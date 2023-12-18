<?php
// dump($form->getValueCollection());
// exit;
?>
<form name="UserPackage_userRegistration_form" id="UserPackage_userRegistration_form" method="POST" action="" autocomplete="off" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_name"><?php echo trans('name'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <input name="UserPackage_userRegistration_Person_name" id="UserPackage_userRegistration_Person_name" type="text"
                    class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('name'); ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_username"><?php echo trans('username'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <input readonly autocomplete="off" name="UserPackage_userRegistration_Person_username" id="UserPackage_userRegistration_Person_username" type="text"
                    onfocus="this.removeAttribute('readonly');" class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('username'); ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_password"><?php echo trans('password'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <input readonly autocomplete="off" name="UserPackage_userRegistration_Person_password" id="UserPackage_userRegistration_Person_password" type="password"
                    onfocus="this.removeAttribute('readonly');" class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('password'); ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_retypedPassword"><?php echo trans('retyped.password'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <input readonly autocomplete="off" name="UserPackage_userRegistration_Person_retypedPassword" id="UserPackage_userRegistration_Person_retypedPassword" type="password"
                    onfocus="this.removeAttribute('readonly');" class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('retypedPassword'); ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_email"><?php echo trans('email'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <input name="UserPackage_userRegistration_Person_email" id="UserPackage_userRegistration_Person_email" type="text"
                    class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('email'); ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_mobile"><?php echo trans('mobile'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <input name="UserPackage_userRegistration_Person_mobile" id="UserPackage_userRegistration_Person_mobile" type="text"
                    class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('mobile'); ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_NewsletterSubscription_subscribed"><?php echo trans('i.want.to.receive.newsletters'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
<?php

?>
                <select name="UserPackage_userRegistration_NewsletterSubscription_subscribed" id="UserPackage_userRegistration_NewsletterSubscription_subscribed" class="form-control inputField">
                    <option value="*null*"><?php echo trans('please.choose'); ?></option>
                    <option value="*yes*"<?php echo $form->getValueCollector()->getDisplayed('subscribed') == '*yes*' ? ' selected' : ''; ?>><?php echo trans('yes'); ?></option>
                    <option value="*no*"<?php echo $form->getValueCollector()->getDisplayed('subscribed') == '*no*' ? ' selected' : ''; ?>><?php echo trans('no'); ?></option>
                </select>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_Address_0_Country_country"><?php echo trans('country'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
            <?php
                // dump($form);
                // if ($form->isSubmitted()) {
                //     dump($form->getValueCollector()->getCollection());
                // }
                // dump($form->getValueCollector()->getDisplayed('countryId'));
            ?>
                <select name="UserPackage_userRegistration_Person_Address_0_Country_country" id="UserPackage_userRegistration_Person_Address_0_Country_country" class="form-control inputField">

<?php if (count($countries) > 1): ?>
                    <option value="0"><?php echo trans('please.choose'); ?></option>
<?php endif; ?>
<?php
    foreach ($countries as $country) {
        $countrySelected = $form->getValueCollector()->getDisplayed('UserPackage_userRegistration_Person_Address_0_country') == $country->getId() ? ' selected' : '';
?>
                    <option value="<?php echo $country->getId(); ?>"<?php echo $countrySelected; ?>><?php echo trans($country->getTranslationReference().'.country'); ?></option>
<?php
    }
?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_Address_0_city"><?php echo trans('city'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <input name="UserPackage_userRegistration_Person_Address_0_city" id="UserPackage_userRegistration_Person_Address_0_city" type="text"
                    class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('city'); ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_Address_0_zipCode"><?php echo trans('zip.code'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <input name="UserPackage_userRegistration_Person_Address_0_zipCode" id="UserPackage_userRegistration_Person_Address_0_zipCode" type="text"
                    class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('zipCode'); ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_Address_0_street"><?php echo trans('street.name'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <input name="UserPackage_userRegistration_Person_Address_0_street" id="UserPackage_userRegistration_Person_Address_0_street" type="text"
                    class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('street'); ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_Address_0_streetSuffix"><?php echo trans('street.suffix'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <select name="UserPackage_userRegistration_Person_Address_0_streetSuffix" id="UserPackage_userRegistration_Person_Address_0_streetSuffix" class="form-control inputField">

<?php if (count($streetSuffixes) > 1): ?>
                    <option value="0"><?php echo trans('please.choose'); ?></option>
<?php endif; ?>
<?php
    foreach ($streetSuffixes as $streetSuffixKey => $streetSuffix) {
        $streetSuffixSelected = $form->getValueCollector()->getDisplayed('streetSuffix') == trans($streetSuffix) ? ' selected' : '';
?>
                    <option value="<?php echo trans($streetSuffix); ?>"<?php echo $streetSuffixSelected; ?>><?php echo trans($streetSuffix); ?></option>
<?php
    }
?>
                </select>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_Address_0_houseNumber"><?php echo trans('house.number'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <input name="UserPackage_userRegistration_Person_Address_0_houseNumber" id="UserPackage_userRegistration_Person_Address_0_houseNumber" type="text"
                    class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('houseNumber'); ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_Address_0_staircase"><?php echo trans('staircase'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <input name="UserPackage_userRegistration_Person_Address_0_staircase" id="UserPackage_userRegistration_Person_Address_0_staircase" type="text"
                    class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('staircase'); ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_Address_0_floor"><?php echo trans('floor'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <input name="UserPackage_userRegistration_Person_Address_0_floor" id="UserPackage_userRegistration_Person_Address_0_floor" type="text"
                    class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('floor'); ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
            <div class="form-group formLabel">
                <label for="UserPackage_userRegistration_Person_Address_0_door"><?php echo trans('door.number'); ?></label>
            </div>
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div class="form-group">
                <input name="UserPackage_userRegistration_Person_Address_0_door" id="UserPackage_userRegistration_Person_Address_0_door" type="text"
                    class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('door'); ?>" aria-describedby="" placeholder="">
            </div>
        </div>
    </div>


    <div id="pleaseAcceptTerms" style="display: none;">
        <div class="row">
            <div class="col-sm-5 col-md-5 col-lg-5">
            </div>
            <div class="col-sm-7 col-md-7 col-lg-7">
                <?php echo trans('accept.terms.to.activate.button'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <div id="userRegistrationLegalLinkContainer">
                <a href="" onclick="UserRegistrationForm.legalModalOpen(event);"><?php echo trans('read.terms.and.conditions'); ?></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-5 col-lg-5">
        </div>
        <div class="col-sm-7 col-md-7 col-lg-7">
            <input name="UserPackage_userRegistration_legalText" id="UserPackage_userRegistration_legalText" type="hidden" />
        </div>
    </div>
</form>
<script>
    $('document').ready(function() {

        if ($('#UserPackage_userRegistration_submit').attr('disabled') == 'disabled') {
            $('#pleaseAcceptTerms').show();
        }
    });
</script>
