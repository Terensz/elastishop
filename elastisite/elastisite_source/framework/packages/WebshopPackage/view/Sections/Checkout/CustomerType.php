<?php 
$cardErrorClass = ' card-success';
if ($errors['messages']['customerTypeSelected']) {
    $cardErrorClass = ' card-error';
}
$customerType = $temporaryAccountData['temporaryPerson']['customerType'];
?>
<div class="card<?php echo $cardErrorClass; ?>">

    <div class="bg-secondary text-white card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('customer.type'); ?></h6>
        </div>
    </div>

    <?php if ($errors['messages']['customerTypeSelected']): ?>
    <div class="card-body text-danger">
    <?php echo $errors['messages']['customerTypeSelected']; ?>
    </div>
    <?php endif; ?>

    <div class="card-footer">
    <?php 
    // dump($customerType);
    // dump('alma');
    // echo "&nbsp; (".App::getElapsedLoadingTime().")";
    ?>
        <input type="hidden" id="WebshopPackage_checkout_customerType_original" value="<?php echo empty($customerType) ? '' : $customerType; ?>">
        <div class="form-check">
            <input <?php if ($customerType == 'PrivatePerson') { echo 'checked '; } ?>class="form-check-input WebshopPackage_checkout_customerType_option" data-customertype="PrivatePerson" type="radio" name="WebshopPackage_checkout_customerType[]" id="WebshopPackage_checkout_customerType_PrivatePerson">
            <label class="form-check-label" for="WebshopPackage_checkout_customerType_PrivatePerson">
                <?php echo trans('private.person'); ?>
            </label>
        </div>
        <div class="form-check">
            <input <?php if ($customerType == 'Organization') { echo 'checked '; } ?>class="form-check-input WebshopPackage_checkout_customerType_option" data-customertype="Organization" type="radio" name="WebshopPackage_checkout_customerType[]" id="WebshopPackage_checkout_customerType_Organization">
            <label class="form-check-label" for="WebshopPackage_checkout_customerType_Organization">
                <?php echo trans('organization'); ?>
            </label>
        </div>

    </div>
</div>