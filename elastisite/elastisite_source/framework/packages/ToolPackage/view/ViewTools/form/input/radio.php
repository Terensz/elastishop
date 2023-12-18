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

<?php
// dump($formViewElement);
    foreach ($formViewElement->getOptions() as $option):
        $selectedStr = $option['key'] == $formViewElement->getDisplayedValue() ? ' checked' : '';
?>
<div class="input-group">
                        <div class="form-check" style="overflow-wrap: break-word !important;">
                            <label class="form-check-label" style="display: inline-block !important; <?php echo $option['style'] ? $option['style'] : ''; ?>">
                                <input class="form-check-input" type="radio" name="{{ requestKey }}" id="{{ requestKey }}" value="<?php echo $option['key']; ?>"<?php echo $selectedStr; ?>>
                                <?php echo $option['translated'] ? trans($option['displayed']).$formViewElement->getValue() : $option['displayed']; ?>
                            </label>
                        </div>
</div>
<?php
    endforeach;
?>  
            </div>
            <div class="validationMessage error" id="{{ requestKey }}-validationMessage" style="padding-top:4px;">{{ validationMessage }}</div>
        </div>
    </div>
</div>
