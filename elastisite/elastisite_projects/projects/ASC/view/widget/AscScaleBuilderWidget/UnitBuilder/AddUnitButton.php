<?php 

/**
 * @var int $ascScaleId;
 * @var string $newUnitSubject;
 * @var int $newUnitParentId;
 * @var string $newUnitAddButtonText;
*/

if (!$newUnitParentId || (!is_string($newUnitParentId) && !is_numeric($newUnitParentId))) {
    $newUnitParentId = 'null';
}

?>
<div class="mb-4">
    <button type="button" onclick="AscScaleBuilder.addUnit(event, '<?php echo $ascScaleId; ?>', '<?php echo $newUnitSubject; ?>', <?php echo $newUnitParentId; ?>);"
        class="btn btn-success"><?php echo $newUnitAddButtonText; ?></button>
</div>