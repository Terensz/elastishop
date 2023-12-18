<?php 

use framework\packages\UXPackage\service\ViewTools;
use projects\ASC\service\AscTechService;
use projects\ASC\service\AscUnitBuilderService;

App::getContainer()->wireService('projects/ASC/service/AscTechService');
App::getContainer()->wireService('projects/ASC/service/AscUnitBuilderService');

/**
 * $unitData:
*/
// [object]
//      ...obj
// [data] Array
//     [ascUnitId] 215002
//     [parentId] 215035
//     [subject] Target
//     [ascUnitIsDeletable] true
//     [createdAt] 2023-06-23 01:30:00
//     [mainEntryTitle] Ideal 1
//     [mainEntryDescription] dsadsasad
//     [mainEntryLanguage] hu
//     [ascEntryHeadId] 222003
//     [isDeletable] true
//     [dueType] null
//     [recurrencePattern] null
//     [dueDate] null
//     [dueTime] null
//     [responsible]
//     [status] null
//     [files] Empty array

?>

<div class="pc-container">
    <div class="pcoded-content card-container">
<?php  

/**
 * 
*/
$ascScaleId = $ascScale->getId();

/**
 * If we are under a primary subject (e.g.: in the "Goals"), the created child sucject will be a Goal.
 * But: if we are under a parent, we have to check in the TechService wich subject can be the child of the current parent.
*/
$childSubject = null;
$childSubjectData = null;
/**
 * 
*/
$addButtonEnabled = false;
$addToListRootPermitted = false;

/**
 * One possibility: we came here by the side menu.
*/
$parentSubjectData = null;
if ($subject) {
    $childSubject = $subject;
    $childSubjectData = AscTechService::getSubjectData($childSubject);
    if ($childSubjectData) {
        $parentSubjectData = AscTechService::getParentSubjectData($childSubject);
        /**
         * It's important: we should be able to add unit from here if the listed subject has no parent.
         * Because if it has, than you must enter a specific parent to also determine the parent unit, together with the subject.
        */
        if (empty($parentSubjectData)) {
            // dump($parentSubjectData);
            $addButtonEnabled = true;
            $addToListRootPermitted = true;
        }
    }
}

$childSubjectTranslationReference = $childSubjectData ? (isset($childSubjectData['translationReferenceSingular']) ? $childSubjectData['translationReferenceSingular'] : null ) : null;
$parentSubjectTranslationReference = isset($parentSubjectData) && $parentSubjectData ? (isset($parentSubjectData['translationReferencePlural']) ? $parentSubjectData['translationReferencePlural'] : null ) : null;
dump($childSubjectData);

if ($parentAscUnit && !isset($listViewData['list'][$parentAscUnit->getId()])):
    $parentAscUnitData = AscUnitBuilderService::assembleUnitData($parentAscUnit);
    $packHeaderUnitData = $parentAscUnitData;
    include('UnitCardPackHeader.php');
?>
    <div class="mb-4">
        <button type="button" onclick="AscScaleBuilder.addUnit(event, '<?php echo $ascScale->getId(); ?>', '<?php echo $parentAscUnitData['data']['subject']; ?>', <?php echo $parentAscUnit->getId(); ?>);"
            class="btn btn-success" id="dueEvent_remove"><?php echo trans('add.'.$childSubjectTranslationReference); ?></button>
    </div>
<?php
    // $childSubjectData = AscTechService::getChildSubjectData($parentAscUnit->getSubject());
    // $childSubject = $childSubjectData ? $childSubjectData['singularRefName'] : null;
    // /**
    //  * If we have a child subject, than we can enable the "Add" button.
    // */
    // if ($childSubject) {
    //     $addButtonEnabled = true;
    // }
endif;

?>

<?php if ($addToListRootPermitted): ?>
<div class="mb-4">
    <button type="button" onclick="AscScaleBuilder.addUnit(event, '<?php echo $ascScale->getId(); ?>', '<?php echo $childSubject; ?>', <?php echo !$parentAscUnitId ? 'null' : $parentAscUnitId; ?>);"
        class="btn btn-success" id="dueEvent_remove"><?php echo trans('add.'.$childSubjectTranslationReference); ?></button>
</div>
<?php elseif ($parentSubjectData): ?>
<?php 

?>
<div class="mb-4">
    <button type="button" onclick="AscScaleBuilder.jumpToSubject(event, '<?php echo $ascScale->getId(); ?>', '<?php echo $parentSubjectData ? $parentSubjectData['singularRefName'] : ''; ?>');"
        class="btn btn-primary" id="dueEvent_remove"><?php echo trans('jump.to.'.$parentSubjectTranslationReference); ?></button>
</div>
<?php endif; ?>

        <?php foreach ($listViewData['list'] as $parentKey => $unitDataArray): ?>
            <?php
            $parentUnitData = isset($listViewData['parents'][$parentKey]) ? $listViewData['parents'][$parentKey] : null;

            $packHeaderUnitData = null;
            $addButtonEnabled = false;
            if ($parentKey != AscUnitBuilderService::NULL_KEY) {
                $addButtonEnabled = true;
                if ($parentUnitData) {
                    $packHeaderUnitData = $parentUnitData;
                    include('UnitCardPackHeader.php');
                }
            }
            ?>
            <?php if ($addButtonEnabled): ?>
            <div class="mb-4">
                <button type="button" onclick="AscScaleBuilder.addUnit(event, '<?php echo $ascScale->getId(); ?>', '<?php echo $childSubject; ?>', <?php echo !$parentUnitData ? 'null' : $parentUnitData['data']['ascUnitId']; ?>);"
                    class="btn btn-success" id="dueEvent_remove"><?php echo trans('add.'.$childSubjectTranslationReference); ?></button>
            </div>
            <?php endif; ?>

            <?php foreach ($unitDataArray as $unitData): ?>
            <div class="row">
                <div class="col-xl-12 col-md-12">
            <?php
            $debug = $unitData['data'];
            $debug['files'] = is_array($debug['files']) ? count($debug['files']) : null;
            $debug['subjectIsParentOf'] = null;
            $debug['subjectIsChildOf'] = null;
            // dump($debug);

            $debugString = '';
            // $debugString = '('.$unitData['data']['ascUnitId'].')';

                // dump($unitData['data']['isDeletable']);
                // dump($unitData);
                $description = $unitData['object']->getAscEntryHead()->findDescription();
                $footerImageSources = [];
                foreach ($unitData['data']['files'] as $ascUnitFile) {
                    $footerImageSources[] = '/asc/unitImage/thumbnail/'.$ascUnitFile->getCode();
                }
                // dump($unitData['data']);
                // dump($unitData['data']['subjectIsParentOf']);
                ViewTools::displayComponent('dashkit/card', [
                    'additionalCardClassString' => 'UnitBuilder-Unit-draggable',
                    'attributes' => [
                        'id' => 'UnitBuilder-Unit-'.$unitData['data']['ascUnitId'],
                        'data-unitid' => $unitData['data']['ascUnitId'],
                        'data-subject' => $unitData['data']['subject'],
                        'data-parentid' => $unitData['data']['parentId'],
                    ],
                    'containerAttributes' => [
                        'id' => 'UnitBuilder-Unit-'.$unitData['data']['ascUnitId'],
                        'data-unitid' => $unitData['data']['ascUnitId'],
                        'data-subject' => $unitData['data']['subject'],
                        'data-parentid' => $unitData['data']['parentId'],
                    ],
                    'title' => $debugString.'['.trans(AscTechService::findSubjectConfigValue($unitData['data']['subject'], 'translationReferenceSingular')).'] '.$unitData['data']['mainEntryTitle'],
                    'titleLink' => (!$unitData['data']['subjectIsParentOf'] ? null : '/asc/scaleBuilder/scale/'.$ascScale->getId().'/parent/'.$unitData['data']['ascUnitId']),
                    'body' => $description ? html_entity_decode($description) : '',
                    // 'additionalCardHeaderClassString' => 'bg-primary',
                    // 'additionalCardHeaderLinkClassString' => 'text-white',
                    'editOnClick' => 'AscScaleBuilder.editUnit(event, \''.$ascScale->getId().'\', \''.$unitData['object']->getId().'\');',
                    'displayFooter' => true,
                    'footerImageSources' => $footerImageSources,
                    'deleteOnClick' => !$unitData['data']['isDeletable'] ? null :'AscScaleBuilder.initDeleteUnit(event, \''.$unitData['object']->getId().'\', \''.$unitData['data']['mainEntryTitle'].'\');',
                ]);
            ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>