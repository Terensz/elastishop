<?php

use projects\ASC\entity\AscUnit;
use projects\ASC\service\AscSaveService;
// use projects\ASC\service\AscTechService;

App::getContainer()->wireService('projects/ASC/entity/AscUnit');
App::getContainer()->wireService('projects/ASC/service/AscSaveService');
// App::getContainer()->wireService('projects/ASC/service/AscTechService');

?>

<div class="UnitBuilder-UnitBody">
<?php 
if ($unitData['status'] == AscUnit::STATUS_CLOSED_SUCCESSFUL) {
    include('UnitClosed.php');
}
if ($unitData['dueType']) {
    include('UnitDueDate.php');
}
if ($unitData['responsible']) {
    include('UnitResponsible.php');
}
?>

    <?php if ($description && $description != ''): ?>
    <div class="UnitBuilder-UnitBody-description" style=""><?php echo html_entity_decode($description); ?></div>
    <?php endif; ?>

<?php
if (count($unitData['files']) > 0) {
    include('UnitFiles.php');
}
?>

<?php  
// dump($unitType);
if (!isset($unitsOfParent) || !is_array($unitsOfParent)) {
    $unitsOfParent = [];
}
?>

<?php if ($unitType == 'parent'): ?>
<?php  
// dump($unitsOfParent);
$currentUnitsOfParent = $unitsOfParent;
$unitsOfParent = null;
?>
        <?php for ($i = 0; $i < count($currentUnitsOfParent); $i++): ?>
        <?php 
        $panelData = $currentUnitsOfParent[$i];
        $unitType = 'primary';
        // dump($panelData);
        // $unitOfParent = true;
        include('UnitRow.php');
        ?>
        <?php endfor; ?>

    <?php if (count($currentUnitsOfParent) == 0): ?>
    <div class="UnitBuilder-UnitIconbar-placeholder-frame">
<?php
$placeholderData = [
    'targetParentType' => AscSaveService::PLACEHOLDER_TARGET_PARENT_TYPE_UNIT,
    'targetParentId' => $ascUnitId,
    'targetSubject' => $currentSubject,
    'text' => trans('subject.placeholder.'.$currentSubject),
    'registerUsage' => true,
    'wrapWithContainer' => false
];
// dump($placeholderData);
include('UnitWrapperPlaceholder.php');
?>
    </div>
    <?php endif; ?>

<?php endif; ?>
</div>
