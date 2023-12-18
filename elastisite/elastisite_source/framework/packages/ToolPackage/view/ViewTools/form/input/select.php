<div class="mb-3">
    <?php  
    // dump($formViewElement->getOptions());
    // dump($formViewElement->getDisplayedValue());
    // dump($formViewElement->getDisplayedValue() && $option['key'] === $formViewElement->getDisplayedValue());
    // dump($formViewElement);
    ?>
    <label for="{{ requestKey }}" class="form-label">{{ label }}</label>
    <div class="input-group has-validation">
        <!-- <input type="text" class="form-control" name="{{ requestKey }}" id="{{ requestKey }}" 
        maxlength="250" placeholder="{{ placeholder }}" value="{{ displayedValue }}"> -->
        <select class="form-select inputField{{ isInvalidString }}" name="{{ requestKey }}" id="{{ requestKey }}" aria-describedby="{{ requestKey }}-validationMessage" required>
<?php
    foreach ($formViewElement->getOptions() as $option):
        // $debug = 
        $selectedStr = (($formViewElement->getDisplayedValue() === null && $option['key'] == '*null*') || 
            ($formViewElement->getDisplayedValue() !== null && $option['key'] == $formViewElement->getDisplayedValue())) ? ' selected' : '';
        if ($option['forceSelected']) {
            $selectedStr = ' selected';
        }
?>
            <option class="option-<?php echo $option['key']; ?>" <?php echo $option['style'] ? ' style="'.$option['style'].'"' : ''; ?>value="<?php echo $option['key']; ?>"<?php echo $selectedStr; ?>><?php echo (
                $option['translated'] ? trans($option['displayed']).$formViewElement->getValue() : $option['displayed']); 
            ?></option>
<?php
    endforeach;
?>  
        </select>
        <div class="invalid-feedback validationMessage" id="{{ requestKey }}-validationMessage">{{ validationMessage }}</div>
    </div>
</div>
