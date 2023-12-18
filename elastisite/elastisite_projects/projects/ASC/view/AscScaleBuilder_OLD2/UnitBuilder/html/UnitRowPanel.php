<?php

use projects\ASC\service\AscTechService;
use projects\ASC\service\AscSaveService;

App::getContainer()->wireService('projects/ASC/service/AscTechService');
App::getContainer()->wireService('projects/ASC/service/AscSaveService');

// dump($panelData);

if (!isset($numberOfUnitPanels)) {
    $numberOfUnitPanels = 1;
}
$bootstrapWidthUnits = $numberOfUnitPanels == 2 ? '6' : '12';
// dump($currentSubject);
// dump($panelData);
?>
<?php if (empty($panelData)): ?>

<?php endif; ?> 
        <div class="row">
<?php
// $subjectRowPanelCounter = 0;
?>  
            <?php foreach ($panelData as $subject => $unitData): ?>
<?php
// $subjectRowPanelCounter++;
// dump($subjectRowPanelCounter);
// dump();
// $unitPanelIndex = 0;
// if ($subject == AscTechService::SUBJECT_REAL_SCENE) {
//     /**
//      * Real scene eseten ez a ket verzio van: az aktuális subjectPanel a current- vagy a juxtaposedSubject-hez tartozik. 
//      * Elobbi esetben ez a kettes panel, utobbiban a 3-as.
//     */
//     $unitPanelIndex = $currentSubject == AscTechService::SUBJECT_IDEAL_SCENE ? 2 : 3;
// } elseif ($subject == AscTechService::SUBJECT_IDEAL_SCENE) {
//     $unitPanelIndex = $subject == $currentSubject ? 1 : 2;
// } else {
//     if ()
//     // if ($subject == )
// }
// dump($unitData);
$idStr = '';
if ($unitData) {
    $title = $unitData['mainEntryTitle'];
    $parentId = $unitData['parentId'];
    $description = $unitData['mainEntryDescription'];
    $language = $unitData['mainEntryLanguage'];
    $ascUnitId = $unitData['ascUnitId'];
    $isDeletable = $unitData['ascUnitIsDeletable'];
    $deletedItemReferenceTitle = '';
    $idStr = ' id="UnitBuilder-UnitPanel-'.$subject.'-'.$ascUnitId.'"';
    // $unitType = 'primary';
}

// dump($unitData);
?>
            <div class="col-md-<?php echo $bootstrapWidthUnits; ?>" style="padding: 0px !important; margin: 0px !important;">
<?php if ($unitData): ?> 
                <?php 
                // dump($subject);
                // if ($unitData) {
                // }
                include('UnitWrapper.php'); 
                ?>
<?php else: ?>
    <?php if ($subjectRowPanelCounter == 0): ?> 
        <div class="UnitBuilder-UnitIconbar-placeholder-frame">
<?php 
// dump($subjectRowPanelCounter);
$placeholderData = [
    'targetParentType' => AscSaveService::PLACEHOLDER_TARGET_PARENT_TYPE_PRIMARY_SUBJECT,
    'targetParentId' => null,
    'targetSubject' => $subject,
    'text' => trans('subject.placeholder.'.$subject),
    'registerUsage' => true,
    'wrapWithContainer' => false
];
include('UnitWrapperPlaceholder.php');
?>
        </div>
    <?php endif; ?> 
<?php endif; ?> 
<?php
// dump($unitType);
// dump($unitsOfParent);
?>
            </div>
<?php
?>  
            <?php endforeach; ?> 
        </div>