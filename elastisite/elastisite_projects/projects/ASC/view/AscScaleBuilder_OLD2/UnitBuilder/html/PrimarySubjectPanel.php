<?php

use projects\ASC\service\AscSaveService;
use projects\ASC\service\AscTechService;

App::getContainer()->wireService('projects/ASC/service/AscTechService');
App::getContainer()->wireService('projects/ASC/service/AscSaveService');

?>

<div class="col-md-<?php echo $subjectPanelBootstrapWidthUnits; ?>" style="margin: 0px !important; padding-left: 2px; padding-right: 2px;">
    <div class="UnitBuilder-SubjectPanel-container">
        <?php
        // dump($subjectPanelBootstrapWidthUnits);
        ?>
<?php
    $counter = 0;
    $subjectPanelMainData = null;
    $realScenesPanelMainData = null;
    foreach ($subjectPanelData['mainProperties'] as $mainPropertyRow) {
        if ($counter == 0) {
            $subjectPanelMainData = $mainPropertyRow;
        }
        if ($counter == 1) {
            $realScenesPanelMainData = $mainPropertyRow;
        }
        $counter++;
    }

    $counter = 0;
    $primarySubject = null;
    foreach ($subjectPanelData['mainProperties'] as $loopPrimarySubject => $mainPropertyRow) {
        if ($counter == 0) {
            $primarySubject = $loopPrimarySubject;
        }
        $counter++;
    }

    include('PrimarySubjectPanelHeader.php');

    if ($primarySubject == AscTechService::SUBJECT_STAT):
        include('StatisticsPanel.php');
    else:

    // $position = 'null';
    // $onClick = "AscScaleBuilder.addUnit(event, ".$ascScale->getId().", '".$subjectPanelData['subjectName']."', '".$position."');";
    // include('AdderButton.php');
?>
        <div class="row">
<?php
        // dump($unitPanelData);
        foreach ($subjectPanelData['mainProperties'] as $unitPanelName => $unitPanelMainProperties):
            $position = 'null';
            $bootstrapWidthUnits = $numberOfUnitPanels == 2 ? '6' : '12';
?>
            <div class="col-md-<?php echo $bootstrapWidthUnits; ?>" style="padding: 0px !important; margin: 0px !important;">
            <?php
            $adderButtonOnClick = "AscScaleBuilder.addUnit(event, ".$ascScale->getId().", '".$unitPanelName."', '".$position."');";
            $adderButtonText = trans('add.'.$unitPanelMainProperties['translationReferenceSingular']);
            include('AdderButton.php');
            ?>
            </div>
<?php
        endforeach;
?>
        </div>

<?php
    $unitsOfParent = null;
    $subjectRowPanelCounter = 0;
    if (count($subjectPanelData['subjectRowPanelData']) == 0):
        $placeholderData = [
            'targetParentType' => AscSaveService::PLACEHOLDER_TARGET_PARENT_TYPE_PRIMARY_SUBJECT,
            'targetParentId' => null,
            'targetSubject' => $primarySubject,
            'text' => trans('subject.placeholder.'.$primarySubject),
            'registerUsage' => true,
            'wrapWithContainer' => false
        ];
        include('UnitWrapperPlaceholder.php');
    endif;
?>
    <?php foreach ($subjectPanelData['subjectRowPanelData'] as $subjectRowPanelDataRow): ?>
        <?php if (!$subjectRowPanelDataRow[$primarySubject]['parentId']): ?>
            <?php 
            $panelData = $subjectRowPanelDataRow;
            // dump($panelData);//exit;
            $unitType = 'primary';
            ?>
            <?php include('UnitRow.php'); ?>
        <?php endif; ?>
<?php
// dump($subjectRowPanelDataRow);
$subjectRowPanelCounter++;
?>
    <?php endforeach; ?>

<?php
        // dump($subjectPanelData['parentProperties']);
        // dump($subjectPanelData['parentData']);
        // Parent loopiing
        // dump($subjectPanelData);
?>

        <?php if ($subjectPanelData['parentProperties'] && is_array($subjectPanelData['parentData'])): ?>
<?php
// dump($subjectPanelData['subjectRowPanelData']);
// dump($subjectPanelData['parentProperties']);
// dump($subjectPanelData['parentData']);
$panelData = [];
$unitsOfParent = [];
// dump($subjectPanelData['parentData']['ascUnitId']);
?>
                <?php foreach ($subjectPanelData['parentData'] as $parentDataRow): ?>
<?php
// $unitData = $parentDataRow;
// dump($parentDataRow);
// dump($subjectRowPanelDataRow);
// dump($subjectRowPanelDataRow[$primarySubject]['parentId']);
// dump($parentDataRow['ascUnitId']);
// dump('.......');
// dump($subjectRowPanelDataRow);
// dump($subjectRowPanelDataRow[$primarySubject]['parentId']);
// dump($parentDataRow);
// if (!isset($subjectRowPanelDataRow[$primarySubject]['parentId'])) {
//     dump($subjectRowPanelDataRow);
// }
?>
                    <?php foreach ($subjectPanelData['subjectRowPanelData'] as $subjectRowPanelDataRow): ?>
                        <?php if ($subjectRowPanelDataRow[$primarySubject]['parentId'] == $parentDataRow['ascUnitId']): ?>
                            <?php 
                                // dump('Bent!');
                                $unitsOfParent[] = $subjectRowPanelDataRow;
                            ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php 
                // dump($unitsOfParent);

                $panelData[$subjectPanelData['parentProperties']['singularRefName']] = $parentDataRow;
                $unitType = 'parent';
                // dump($panelData);
                include('UnitParent.php');
                $panelData = [];
                ?>
                <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>

    </div>
</div>
