<!-- 
<div class="">
Parent!!
</div> -->
<?php 
// $placeholderData = [
//     'targetParentType' => AscSaveService::PLACEHOLDER_TARGET_PARENT_TYPE_UNIT,
//     'targetParentId' => $planAscUnitId,
//     'targetSubject' => AscTechService::SUBJECT_PROGRAM,
//     'text' => trans('unit.placeholder.program'),
//     'registerUsage' => true,
//     'wrapWithContainer' => true
// ];
// include('UnitWrapperPlaceholder.php'); 
?>
<?php
$unitType = 'parent';
include('UnitRowPanel.php');
?>