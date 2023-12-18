<div class="row">
    <div class="col-md-12 card-pack-header">
        <h4><?php echo trans('terms.and.conditions.of.ordering'); ?></h4>
    </div>
</div>

<?php 
$cardErrorClass = ' card-success';
if ($errors['messages']['termsAndConditionsAccepted']) {
    $cardErrorClass = ' card-error';
}
?>
<div class="card<?php echo $cardErrorClass; ?>">
    <div class="bg-secondary text-white card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('accept.terms.and.conditions.title'); ?></h6>
            <?php 
            // dump('alma');
            // echo "&nbsp; (".App::getElapsedLoadingTime().")";
            ?>
        </div>
    </div>

    <?php if ($errors['messages']['termsAndConditionsAccepted']): ?>
    <div class="card-body text-danger">
    <?php echo $errors['messages']['termsAndConditionsAccepted']; ?>
    </div>
    <?php endif; ?>

    <div class="card-footer">

        <div class="form-check">
            <input <?php if (!$errors['messages']['termsAndConditionsAccepted']) { echo 'checked '; } ?>class="form-check-input" type="checkbox" value="" id="WebshopPackage_checkout_acceptTermsAndConditions" style="cursor: pointer;">
            <label class="form-check-label" for="flexCheckDefault">
            <?php echo trans('accept.terms.and.conditions.text'); ?>
            </label>
        </div>

    </div>
</div>