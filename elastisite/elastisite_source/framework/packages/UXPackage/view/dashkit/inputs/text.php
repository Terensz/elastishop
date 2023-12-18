<?php  
if (!isset($requestKey)) {
    $requestKey = '{{requestKey}}';
}
if (!isset($label)) {
    $label = '{{label}}';
}
if (!isset($isInvalidClassString)) {
    // $isInvalidClassString = '';
    $isInvalidClassString = ' is-invalid';
}
if (!isset($placeholder)) {
    $placeholder = '';
}
if (!isset($displayedValue)) {
    $displayedValue = '';
}
if (!isset($validationMessage)) {
    $validationMessage = '';
}
?>
<div class="mb-3">
    <label for="<?php echo $requestKey; ?>" class="form-label"><?php echo $label; ?></label>
    <div class="input-group has-validation">
        <input type="text" class="form-control inputField<?php echo $isInvalidClassString; ?>" name="<?php echo $requestKey; ?>" id="<?php echo $requestKey; ?>" 
        maxlength="250" placeholder="<?php echo $placeholder; ?>" value="<?php echo $displayedValue; ?>">
        <div class="invalid-feedback validationMessage" id="<?php echo $requestKey; ?>-validationMessage"><?php echo $validationMessage; ?></div>
    </div>
</div>