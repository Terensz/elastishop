<?php 
// dump($dataGrid); 
if ($dataGrid->dumpDebug == true) {
    dump($dataGrid); 
    // dump($viewTools);
}
?>

<form name="<?php echo $dataGridId; ?>_form" id="<?php echo $dataGridId; ?>_form" method="get" action="">
    <input type="hidden" autocomplete="false">
    <div class="row grid-title-row breakLongText">
<?php
//dump($conditionPosts);
//dump($multiselectValues);
$colSumAdder = 0;
$colCounter = 0;
// $originalOrdererStr = '<a class="pseudoLink" href="" style="display: inline;" onclick="'.$dataGridId.'.orderBy({{ orderByProp }}, {{ orderByDirection }})">
// <img class="window-button grid-title-cell-background" style="width: 12px; color: #ffffff; border: 0px;" src="'.$container->getUrl()->getHttpDomain().'/public_folder/plugin/Bootstrap-icons/chevron{{ orderByChevron }}.svg">
// </a>';
// $originalOrdererStr = '<a class="pseudoLink" href="" style="display: inline;" onclick="'.$dataGridId.'.orderBy({{ orderByProp }}, {{ orderByDirection }})">
// <img class="window-button grid-title-cell-background" style="width: 12px; color: #ffffff; border: 0px;" src="'.$container->getUrl()->getHttpDomain().'/public_folder/plugin/Bootstrap-icons/chevron{{ orderByChevron }}.svg">
// </a>';
$originalOnclickStr = ' onclick="'.$dataGridId.'.orderBy({{orderByProp}}, {{orderByDirection}});"';
foreach ($columnParams as $column) {
    if (($column['name'] != 'id' || ($column['name'] == 'id' && $showId)) && $column['widthUnits'] && ($column['role'] != 'data' || ($column['role'] == 'data' && in_array($column['name'], $usedFieldNames)))) {
        if ($allowManualOrder) {
            $orderByProp = $column['name'];
            $orderByChevron = '-expand';
            if ($orderByField == $column['name']) {
                if ($orderByDirection == 'DESC') {
                    $orderByChevron = '-down';
                    $orderByDirection = 'ASC';
                } else {
                    $orderByChevron = '-up';
                    $orderByDirection = 'DESC';
                }
            }

            $onclickStr = str_replace('{{orderByProp}}', "'".$orderByProp."'", $originalOnclickStr);
            $onclickStr = str_replace('{{orderByDirection}}', "'".$orderByDirection."'", $onclickStr);
            $cursorStr = 'pointer';
        }
        if (!$allowManualOrder || $column['role'] == 'deleteButton') {
            $onclickStr = '';
            $cursorStr = 'default';
        }
?>
        <div class="col-<?php echo $column['widthUnits']; ?> grid-title-cell grid-title-cell-background">
            <div class="" style="padding-bottom: 4px;">
<?php
    if (isset($conditionPosts[$column['propertyName']]['type']) && $conditionPosts[$column['propertyName']]['type'] == 'text'):
?>
                <input class="form-control dataGrid-text" autocomplete="false" type="text" id="<?php echo $dataGridId; ?>_<?php echo $column['propertyName']; ?>" name="<?php echo $dataGridId; ?>_<?php echo $column['propertyName']; ?>" value="<?php echo isset($conditionPosts[$column['propertyName']]['value']) ? $conditionPosts[$column['propertyName']]['value'] : ''; ?>">
<?php
    elseif (isset($conditionPosts[$column['propertyName']]['type']) && $conditionPosts[$column['propertyName']]['type'] == 'multiselect'):
?>
                <select id="<?php echo $dataGridId; ?>_<?php echo $column['propertyName']; ?>" name="<?php echo $dataGridId; ?>_<?php echo $column['propertyName']; ?>[]" multiple class="multiselect-input">
<?php
        foreach ($multiselectValues[$column['propertyName']] as $multiselect):
            $multiValues = $conditionPosts[$column['propertyName']]['value'];
            $selectedStr = is_array($multiValues) && in_array($multiselect['value'], $multiValues) ? ' selected' : '';
?>
                    <option value="<?php echo $multiselect['value']; ?>"<?php echo $selectedStr; ?>><?php echo $multiselect['displayed']; ?></option>
<?php
        endforeach;
?>
                </select>
<?php
    elseif (isset($conditionPosts[$column['propertyName']]['type']) && $conditionPosts[$column['propertyName']]['type'] == 'date'):
?>
                 <input class="form-control" type="text" id="<?php echo $dataGridId; ?>_<?php echo $column['propertyName']; ?>" name="<?php echo $dataGridId; ?>_<?php echo $column['propertyName']; ?>" value="<?php echo isset($conditionPosts[$column['propertyName']]['value']) ? $conditionPosts[$column['propertyName']]['value'] : ''; ?>">
<?php
    endif;
?>
            </div>
            <div style="cursor:<?php echo $cursorStr; ?>;"<?php echo $onclickStr; ?>>
<?php echo trans($column['title']); ?>
<?php if ($allowManualOrder && $column['role'] != 'deleteButton'): ?>
                    <img class="window-button grid-title-cell-background" style="width: 12px; color: #ffffff; border: 0px;" src="/public_folder/plugin/Bootstrap-icons/chevron<?php echo $orderByChevron; ?>.svg">
<?php endif ?>
            </div>
        </div>
<?php
        $colSumAdder += abs($column['widthUnits']);
        $colCounter++;
    }
}

?>
    </div>
</form>