<?php 
// dump('PrimarySubjectPanelHeader');
?>
<div class="UnitBuilder-UnitIconbar-iconbar AdminScaleBuilder-iconbar-size UnitBuilder-UnitPanel-header-<?php echo $subjectSpot; ?>" >
    <div class="UnitBuilder-UnitIconbar-labelContainer">
        <div class="UnitBuilder-UnitIconbar-labelContainer-inner">
        <?php echo trans($subjectPanelMainData['translationReferencePlural']); ?>      
        </div>
    </div>
<?php if (count($subjectSpots) == 2): ?>
<?php 
        $subjectSpotsCopy = $subjectSpots;
        $key = array_search($subjectSpot, $subjectSpots);
        array_splice($subjectSpotsCopy, $key, 1);
        $otherSubjectSpot = $subjectSpotsCopy[0];
        $otherUnitPanels = array_keys($unitBuilderData['subjectPanels'][$otherSubjectSpot]['mainProperties']);
        $otherUnitPanel = null;
        $counter = 0;

        foreach ($otherUnitPanels as $otherUnitPanelLoop) {
            if ($counter == 0) {
                $otherUnitPanel = $otherUnitPanelLoop;
            }
            $counter++;
        }

?>
    <div href="/asc/scaleBuilder/scale/<?php echo $ascScale->getId(); ?>/subject/<?php echo $otherUnitPanel; ?>" class="UnitBuilder-UnitIconbar-right-iconContainer">
        <div class="ajaxCaller" class="UnitBuilder-UnitIconbar-icon AdminScaleBuilder-iconHeight UnitBuilder-UnitIconbar-right-icon">
            <img class="AdminScaleBuilder-icon-image AdminScaleBuilder-icon-size" src="/image/icon_delete.png">
        </div>
    </div>
<?php else: ?>
    <div class="UnitBuilder-UnitIconbar-right-iconContainer UnitBuilder-SubjectDragAndDropper" class="btn btn-success"><?php echo trans('add.subject.panel'); ?></div>
<?php endif; ?>
</div>