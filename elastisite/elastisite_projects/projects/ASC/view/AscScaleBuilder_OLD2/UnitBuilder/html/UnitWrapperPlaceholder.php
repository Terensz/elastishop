<?php  
$debug = '';
$idStr = '';

// $placeholderData = [
//     'targetParentType' => AscSaveService::PLACEHOLDER_TARGET_PARENT_TYPE_UNIT,
//     'targetParentId' => $originalAscUnitId,
//     'targetSubject' => AscTechService::SUBJECT_PROGRAM,
//     'text' => trans('unit.placeholder.program'),
//     'registerUsage' => true,
//     'wrapWithContainer' => true
// ];

$targetParentIndex = $placeholderData['targetParentId'] ? : $placeholderData['targetSubject'];

// $debug = '('.$placeholderData['targetParentType'].'-'.$placeholderData['targetParentId'].'-'.$placeholderData['targetSubject'].') ';

// dump($subject);
// dump($parentId);

// dump($targetParentIndex);
// dump($placeholdersAlreadyApplied);

// dump('ALMA!!!');
// dump($targetParentIndex);
// dump($placeholdersAlreadyApplied);

// if ($targetParentIndex && !isset($placeholdersAlreadyApplied[$targetParentIndex])):
    // dump($targetParentIndex);
if ($targetParentIndex):
    if ($placeholderData['registerUsage']) {
        $placeholdersAlreadyApplied[$targetParentIndex] = true;
    }
    $idStr = '';

    // dump('ALMA2!!!');
?>
<?php if ($placeholderData['wrapWithContainer']): ?>
<div class="UnitBuilder-UnitBody-secondarySubjectContainer UnitBuilder-UnitBody-footer">
<?php endif; ?>
    <div class="UnitBuilder-UnitPanel UnitBuilder-UnitWrapper UnitBuilder-UnitWrapper-placeholder 
        UnitBuilder-UnitWrapper-<?php echo $placeholderData['targetSubject']; ?>"<?php echo $idStr; ?>
        data-parenttype="<?php echo $placeholderData['targetParentType']; ?>"
        data-parentid="<?php echo $placeholderData['targetParentId']; ?>"
        data-subject="<?php echo $placeholderData['targetSubject']; ?>"
        data-unitid="placeholder"
        >
        <div class="UnitBuilder-Unit UnitBuilder-Unit-placeholder 
            AdminScaleBuilder-placeholder-size AdminScaleBuilder-placeholder-colors AdminScaleBuilder-placeholder-static 
            UnitBuilder-Unit-draggable UnitBuilder-Unit-droponly" 
            data-unitid="placeholder"
            data-subject="<?php echo $placeholderData['targetSubject']; ?>"
            >
            <!-- data-parentid="<?php echo isset($parentId) ? $parentId : ''; ?>"
            data-targetparenttype="<?php echo $placeholderData['targetParentType']; ?>"
            data-targetsubject="<?php echo $placeholderData['targetSubject']; ?>" -->
            <div class="UnitBuilder-UnitIconbar-labelContainer UnitBuilder-UnitIconbar-placeholder-labelContainer AdminScaleBuilder-fontSize-smallText">
                <?php 
                echo $debug.$placeholderData['text'];
                // echo $debug.trans('unit.placeholder.text'); 
                ?>
            </div>
        </div>
    </div>
<?php if ($placeholderData['wrapWithContainer']): ?>
</div>
<?php endif; ?>
<?php 
endif;
?>