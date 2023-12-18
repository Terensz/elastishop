<?php
    // dump($formViewElement->getDisplayedValue());
    // $selectedStr0 = $form->getValueCollector()->getDisplayed('status') == 0 ? ' selected' : '';
    // $selectedStr1 = $form->getValueCollector()->getDisplayed('status') == 1 ? ' selected' : '';
    // $selectedStr2 = $form->getValueCollector()->getDisplayed('status') == 2 ? ' selected' : '';
    // dump($formViewElement->getDisplayedValue());
?>
<div class="row">
    <div class="col-xs-12 col-sm-{{ labelRate }} col-md-{{ labelRate }} col-lg-{{ labelRate }}">
        <div class="form-group formLabel{{ labelAdditionalClass }}">
            <label for="{{ requestKey }}">
                <b>{{ label }}</b>
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-{{ inputRate }} col-md-{{ inputRate }} col-lg-{{ inputRate }}">
        <div class="form-group">
            <div class="input-group">
                <select name="{{ requestKey }}" id="{{ requestKey }}" class="inputField form-select dropdown form-control">
<?php
    foreach ($formViewElement->getOptions() as $option):
        $selectedStr = $option['key'] == $formViewElement->getDisplayedValue() ? ' selected' : '';
?>
                        <option clss="option-<?php echo $option['key']; ?>" <?php echo $option['style'] ? ' style="'.$option['style'].'"' : ''; ?>value="<?php echo $option['key']; ?>"<?php echo $selectedStr; ?>><?php echo (
                            $option['translated'] ? trans($option['displayed']).$formViewElement->getValue() : $option['displayed']); 
                        ?></option>
<?php
    endforeach;
?>  
                </select>
            </div>
            <div class="validationMessage error" id="{{ requestKey }}-validationMessage" style="padding-top:4px;">{{ validationMessage }}</div>
        </div>
    </div>
</div>
