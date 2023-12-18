<?php 
// dump($cartData);
$customerNote = $temporaryAccountData['temporaryPerson']['customerNote'];
$recipientName = $temporaryAccountData['temporaryPerson']['recipientName'];
$email = $temporaryAccountData['temporaryPerson']['email'];
$mobile = $temporaryAccountData['temporaryPerson']['mobile'];

$cardErrorClass = ' card-success';
if ($errors['messages']['recipientNameFilled'] || $errors['messages']['mobileFilled']) {
    $cardErrorClass = ' card-error';
}

?>
<div class="card<?php echo $cardErrorClass; ?>">

    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('delivery.information'); ?></h6>
        </div>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <label for="WebshopPackage_checkout_recipientName" class="form-label"><?php echo trans('recipient'); ?></label>
            <div class="input-group has-validation">
                <input type="text" class="form-control inputField webshop-checkout-deliveryInformationInput<?php if ($errors['messages']['recipientNameFilled']) { echo ' is-invalid';} ?>" name="WebshopPackage_checkout_recipientName" id="WebshopPackage_checkout_recipientName" maxlength="250" placeholder="" value="<?php echo $recipientName; ?>">
                <div class="invalid-feedback validationMessage" id="WebshopPackage_checkout_recipientName-validationMessage"><?php if ($errors['messages']['recipientNameFilled']) { echo $errors['messages']['recipientNameFilled'];} ?></div>
            </div>
        </div>
        <?php  
        // if ($errors['recipientNameFilled']) {
        //     // $recipientNameFilledErrorS
        // }
        ?>
        <div class="mb-3">
            <label for="WebshopPackage_checkout_email" class="form-label"><?php echo trans('contact.email'); ?></label>
            <div class="input-group has-validation">
                <input type="text" class="form-control inputField webshop-checkout-deliveryInformationInput<?php if ($errors['messages']['emailValid']) { echo ' is-invalid';} ?>" name="WebshopPackage_checkout_email" id="WebshopPackage_checkout_email" maxlength="250" placeholder="" value="<?php echo $email; ?>">
                <div class="invalid-feedback validationMessage" id="WebshopPackage_checkout_email-validationMessage"><?php if ($errors['messages']['emailValid']) { echo $errors['messages']['emailValid'];} ?></div>
            </div>
        </div>
        <div class="mb-3">
            <label for="WebshopPackage_checkout_mobile" class="form-label"><?php echo trans('contact.mobile'); ?></label>
            <div class="input-group has-validation">
                <input type="text" class="form-control inputField webshop-checkout-deliveryInformationInput<?php if ($errors['messages']['mobileFilled']) { echo ' is-invalid';} ?>" name="WebshopPackage_checkout_mobile" id="WebshopPackage_checkout_mobile" maxlength="250" placeholder="" value="<?php echo $mobile; ?>">
                <div class="invalid-feedback validationMessage" id="WebshopPackage_checkout_mobile-validationMessage"><?php if ($errors['messages']['mobileFilled']) { echo $errors['messages']['mobileFilled'];} ?></div>
            </div>
        </div>
        <div class="mb-3">
            <label for="WebshopPackage_checkout_customerNote" class="form-label"><?php echo trans('note'); ?></label>
            <div class="input-group has-validation">
                <input type="text" class="form-control inputField webshop-checkout-deliveryInformationInput" name="WebshopPackage_checkout_customerNote" id="WebshopPackage_checkout_customerNote" maxlength="250" placeholder="" value="<?php echo $customerNote; ?>">
                <div class="invalid-feedback validationMessage" id="WebshopPackage_checkout_customerNote-validationMessage"></div>
            </div>
        </div>
        <div class="mb-3">
            <div id="WebshopPackage_checkout_saveDeliveryInformation_container" style="display: none;">
                <button class="btn btn-primary" id="WebshopPackage_checkout_saveDeliveryInformation" type="button" onclick="Webshop.saveDeliveryInformation(event);" value=""><?php echo trans('save.delivery.information'); ?></button>
            </div>
        </div>
    </div>

</div>

<script>

</script>