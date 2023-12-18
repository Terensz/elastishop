<?php  
if (!isset($requestKey)) {
    $requestKey = '{{requestKey}}';
}
if (!isset($label)) {
    $label = '{{label}}';
}
if (!isset($isInvalidClassString)) {
    $isInvalidClassString = '';
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

// Example $optionRow:
// '20' => [
//     'displayedValue' => '20',
//     'translateDisplayedValue' => false,
//     'optionValue'=> '20'
// ],
?>
<div class="mb-3">
    <?php  
    // dump($formViewElement->getOptions());
    // dump($formViewElement->getDisplayedValue());
    // dump($formViewElement->getDisplayedValue() && $option['key'] === $formViewElement->getDisplayedValue());
    // dump($formViewElement);
    ?>
    <label for="<?php echo $requestKey; ?>" class="form-label"><?php echo $label; ?></label>
    <div class="input-group has-validation">
        <select class="form-select inputField<?php echo $isInvalidClassString; ?>" name="<?php echo $requestKey; ?>" id="<?php echo $requestKey; ?>" aria-describedby="<?php echo $requestKey; ?>-validationMessage" required>
<?php
    foreach ($options as $optionKey => $optionRow):
        // $debug = 
        $selectedStr = '';
        // $selectedStr = (
        //     ($optionRow['displayedValue'] === null && $optionRow['optionKey'] == '*null*') || 
        //     ($optionRow['displayedValue'] !== null && $optionRow['optionKey'] == $optionRow['displayedValue'])
        // ) ? ' selected' : '';

        if ($optionRow['selected']) {
            $selectedStr = ' selected';
        }
        $optionStyleString = '';
        if (isset($optionRow['style'])) {
            $optionStyleString = $optionRow['style'];
        }
?>
            <option class="option-<?php echo $optionKey; ?>" <?php echo $optionStyleString; ?>value="<?php echo $optionKey; ?>"<?php echo $selectedStr; ?>><?php echo (
                $optionRow['displayedValue']); 
            ?></option>
<?php
    endforeach;
?>  
        </select>
        <div class="invalid-feedback validationMessage" id="<?php echo $requestKey; ?>-validationMessage"><?php echo $validationMessage; ?></div>
    </div>
</div>
