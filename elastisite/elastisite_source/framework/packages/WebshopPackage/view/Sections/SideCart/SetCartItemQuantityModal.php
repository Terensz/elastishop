<?php  
// dump($productData);
// dump($offeredQuantity);
?>

<?php if (!empty($productData)): ?>
<div>
    <h4 class="mb-1 ellipsis-text">
        <b><?php echo $productData['name']; ?></b>
    </h4>
</div>

<!-- <form name="WebshopPackage_SetCartItemQuantity_form">
    <div class="mb-3">
        <label for="WebshopPackage_SetCartItemQuantity_newQuantity" class="form-label"><?php echo trans('quantity'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField" name="WebshopPackage_SetCartItemQuantity_newQuantity" id="WebshopPackage_SetCartItemQuantity_newQuantity" maxlength="3" placeholder="" pattern="[0-9]{1,3}" title="Please enter a valid number (up to 3 digits)" value="<?php echo $offeredQuantity; ?>" required>
            <div class="invalid-feedback validationMessage" id="WebshopPackage_SetCartItemQuantity_newQuantity-validationMessage"></div>
        </div>
    </div>
</form> -->

<script>
// $(document).ready(function() {
//     $('#WebshopPackage_SetCartItemQuantity_newQuantity').mask('999');
// });
</script>


        <form name="WebshopPackage_SetCartItemQuantity_form">
            <div class="mb-3">
                <label for="WebshopPackage_SetCartItemQuantity_newQuantity" class="form-label"><?php echo trans('quantity'); ?></label>
                <?php if (isset($options['displaySaveButton'])): ?>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                <?php endif; ?>
                        <div class="input-group has-validation">
                            <button class="btn btn-outline-secondary" type="button" id="WebshopPackage_SetCartItemQuantity_decrementValue">-</button>
                            <input type="text" class="form-control inputField" name="WebshopPackage_SetCartItemQuantity_newQuantity" id="WebshopPackage_SetCartItemQuantity_newQuantity" 
                                maxlength="3" placeholder="" pattern="[0-9]{1,3}" title="Please enter a valid number (up to 3 digits)" value="<?php echo $offeredQuantity; ?>" required>
                            <button class="btn btn-outline-secondary" type="button" id="WebshopPackage_SetCartItemQuantity_incrementValue">+</button>
                            <div class="invalid-feedback validationMessage" id="WebshopPackage_SetCartItemQuantity_newQuantity-validationMessage"></div>
                        </div>
                    <?php if (isset($options['displaySaveButton'])): ?>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <button type="button" class="btn btn-primary" onclick="Webshop.setCartItemQuantitySubmit(event, '<?php echo $productData['activePrice']['offerId']; ?>', <?php echo $closeModalAfterSubmit ? 'true': 'false'; ?>);">Mentés</button>
                    </div>
                    <?php endif; ?>
                <?php if (isset($options['displaySaveButton'])): ?>
                </div>
                <?php endif; ?>
            </div>
        </form>

<script>
var SetCartItemQuantity = {
    incrementValue: function() {
        var value = parseInt($('#WebshopPackage_SetCartItemQuantity_newQuantity').val(), 10);
        if (!isNaN(value) && value < 999) {
            $('#WebshopPackage_SetCartItemQuantity_newQuantity').val(value + 1);
        }
    },
    decrementValue: function() {
        var value = parseInt($('#WebshopPackage_SetCartItemQuantity_newQuantity').val(), 10);
        if (!isNaN(value) && value > 0) {
            $('#WebshopPackage_SetCartItemQuantity_newQuantity').val(value - 1);
        }
    }
};
$(document).ready(function() {
    $('#WebshopPackage_SetCartItemQuantity_newQuantity').mask('999');

    $('#WebshopPackage_SetCartItemQuantity_incrementValue').off('click');
    $('#WebshopPackage_SetCartItemQuantity_incrementValue').on('click', function() {
        SetCartItemQuantity.incrementValue();
    });

    $('#WebshopPackage_SetCartItemQuantity_decrementValue').off('click');
    $('#WebshopPackage_SetCartItemQuantity_decrementValue').on('click', function() {
        SetCartItemQuantity.decrementValue();
    });
});
</script>
<?php endif; ?>