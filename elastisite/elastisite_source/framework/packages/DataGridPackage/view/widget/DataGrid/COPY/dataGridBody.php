<?php

use framework\component\helper\StringHelper;

?>
<?php if (count($dataArray) == 0): ?>
    <div class="row grid-body-row breakLongText" style="padding: 10px;">
        <?php echo trans('no.result'); ?>
    </div>
<?php endif; ?>

<?php 
$rowCounter = 0;
$displayedIds = [];
// dump($dataGrid);
foreach ($dataArray as $row):
    $displayedIds[] = $row['id'];
    $cellColor = $dataGrid->getCellColor($row);
    $cellColorStr = $cellColor ? 'background-color: #'.trim($cellColor['background'], '#').'; color: #'.trim($cellColor['font'], '#').';' : '';
?>
    <div class="row grid-body-row breakLongText" id="<?php echo $dataGridId; ?>_<?php echo isset($row['id']) ? $row['id'] : $rowCounter; ?>" data-id="<?php echo isset($row['id']) ? $row['id'] : ''; ?>" data-status="<?php echo isset($row['statusId']) ? $row['statusId'] : ''; ?>">
<?php foreach ($columnParams as $column): ?>
        <?php foreach ($row as $dataFieldName => $dataFieldValue): ?>
            <?php 
            $dataFieldValue = $dataFieldValue ? html_entity_decode(strip_tags($dataFieldValue)) : $dataFieldValue;
            $dataFieldValue = $dataFieldValue ? preg_replace('/<(.|\n)*?>/', '', $dataFieldValue) : $dataFieldValue;
            $dataFieldValue = StringHelper::cutLongString($dataFieldValue, 50);
?>
            <?php if ($column['name'] == $dataFieldName): ?>
                <?php if (($column['name'] != 'id' || ($column['name'] == 'id' && $showId)) && $column['widthUnits']): ?>
                <div onclick="<?php echo $dataGridId; ?>.edit(<?php echo $row['id']; ?>);" data-id="<?php echo isset($row['id']) ? $row['id'] : ''; ?>" data-status="<?php echo isset($row['statusId']) ? $row['statusId'] : ''; ?>" 
                <?php echo $cellColorStr != '' ? ' style="'.$cellColorStr.'"' : ''; ?>class="col-<?php echo $column['widthUnits']; ?> grid-body-cell <?php echo $dataGridId; ?>-<?php echo isset($row['id']) ? $row['id'] : $rowCounter; ?>">
                <?php echo is_array($dataFieldValue) ? \framework\kernel\utility\BasicUtils::arrayToString($dataFieldValue) : $dataFieldValue; ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if ($column['role'] == 'deleteButton'): ?>
            <div style="cursor: default;<?php echo $cellColorStr; ?>" class="col-<?php echo $column['widthUnits']; ?> grid-body-cell <?php echo $dataGridId; ?>-<?php echo isset($row['id']) ? $row['id'] : $rowCounter; ?>">
            <?php if ($repository->isDeletable($row['id'])): ?>
                <a href="" class="triggerModal grid-deleteElement" onclick="<?php echo $dataGridId; ?>.deleteRequest(event, '<?php echo $row['id']; ?>');"><?php echo $column['title']; ?></a>
            <?php else: ?>
                <?php echo trans('undeletable'); ?>
            <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    </div>
<?php 
    $rowCounter++;
?>
<?php endforeach; ?>
<script>
    // almaDocReady
$(document).ready(function() {
    $('.multiselect-input').select2({
        placeholder: 'Select an option'
    });

    <?php echo $dataGridId; ?>.filteredIds = [<?php echo implode(',', $filteredIds) ?>];
    <?php echo $dataGridId; ?>.displayedIds = [<?php echo implode(',', $displayedIds) ?>];

    //console.log('dataGrid (<?php echo $dataGridId; ?>): ' , <?php echo $dataGridId; ?>);

<?php 
    foreach ($dataGrid->getGridData()->getDateColumns() as $dateColumn) {
?>
    $('#<?php echo $dataGridId; ?>_<?php echo $dateColumn; ?>').daterangepicker({
        showDropdowns: true,
        singleClasses: "",
        timePicker: false,
        cancelLabel: 'Clear',
        clearBtn: true,
        // autoApply: true,
        autoUpdateInput: true
        // autoUpdateInput: false
    });

    // $(document).on("click", ".cancelBtn", function(e) {
    //     e.preventDefault();
    //     // $('#<?php echo $dataGridId; ?>_<?php echo $dateColumn; ?>').daterangepicker("clearDates");
    //     console.log('cancelBtn!!! #<?php echo $dataGridId; ?>_<?php echo $dateColumn; ?>');
    //     $('#<?php echo $dataGridId; ?>_<?php echo $dateColumn; ?>').val('').daterangepicker("clear");
    //     // $('#<?php echo $dataGridId; ?>_<?php echo $dateColumn; ?>').val().daterangepicker("update");
    // });

    // $(document).on("click", ".applyBtn", function(e) {
    //     e.preventDefault();
    //     console.log($(this).parent().parent());
    // });

    // console.log('date: <?php echo $conditionPosts[$dateColumn]['value']; ?>');
<?php 
    if (in_array($conditionPosts[$dateColumn]['value'], [null, ''])):
?>
        $('#<?php echo $dataGridId; ?>_<?php echo $dateColumn; ?>').val('');
<?php
    endif;
    }
?>
});
</script>
