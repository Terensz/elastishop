<?php 

use framework\packages\UXPackage\service\ViewTools;
use projects\ASC\entity\AscUnit;
use projects\ASC\service\AscTechService;
// use projects\ASC\service\AscUnitBuilderService;

App::getContainer()->wireService('UXPackage/service/ViewTools');
App::getContainer()->wireService('projects/ASC/entity/AscUnit');
App::getContainer()->wireService('projects/ASC/service/AscTechService');
// App::getContainer()->wireService('projects/ASC/service/AscUnitBuilderService');

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

<?php 

// $preListAddButton = true;
// if ($parentAscUnitData) {
//     $preListAddButton = false;
// }
// if ($preListAddButton) {
//     /**
//      * @var $ascScaleId;
//      * @var $newUnitSubject;
//      * @var $newUnitParentId;
//      * @var $newUnitAddButtonText;
//     */
//     $newUnitSubject = $subject;
//     $newUnitParentId = null;
//     $newUnitAddButtonText = trans('add.'.$subjectSingularTranslationReference);
//     include('AddUnitButton.php');
// }

// dump($listViewData);

// unitDataArray
foreach ($unitDataArray as $subject => $unitDataArrayOfSubject):
?>
        <div class="row">
            <div class="col-md-12 card-pack-header">
                <h4><?php echo trans(AscTechService::findSubjectConfigValue($subject, 'translationReferencePlural')); ?></h4>
            </div>
        </div>
<?php
    foreach ($unitDataArrayOfSubject as $parentKey => $unitDataArray): 

        /**
         * @var $sectionParentAscUnitData
         * The complete list view have a series of parents, they determine the list sections.
         * 
         * ----------------------
         * List section header, determined by a parent.
         * (Notice, that parentless units also listed, their key is: AscUnitBuilderService::NULL_KEY)
        */
        $sectionParentAscUnitData = isset($listViewData['parents'][$parentKey]) ? $listViewData['parents'][$parentKey] : null;
        $packHeaderUnitData = null;
        $sectionAddButtonEnabled = false;
        if ($parentKey != $nullParentKey && $sectionParentAscUnitData) {
            $sectionAddButtonEnabled = true;
            if ($sectionParentAscUnitData) {
                $packHeaderUnitData = $sectionParentAscUnitData;
                include('UnitCardPackHeader.php');
            }
        }

        // $subject = $sectionParentAscUnitData['data']['subject'];
        // // $subjectData = $subject ? AscTechService::getSubjectData($subject) : null;
        // // $subjectSingularTranslationReference = $subjectData ? $subjectData['translationReferenceSingular'] : null;
        // // $subjectPluralTranslationReference = $subjectData ? $subjectData['translationReferencePlural'] : null;
        // $childSubjectData = AscTechService::getChildSubjectData($processedRequestData['subject']);
        // $childSubject = $childSubjectData ? $childSubjectData['singularRefName'] : null;
        // $childSubjectSingularTranslationReference = $childSubjectData ? $childSubjectData['translationReferenceSingular'] : null;
        // $childSubjectPluralTranslationReference = $childSubjectData ? $childSubjectData['translationReferencePlural'] : null;

        $parentSubjectData = AscTechService::getParentSubjectData($subject);
        if (empty($parentSubjectData)) {
            $preListAddButton = true;
        }

        /**
         * @var bool $sectionAddButtonEnabled
         * In every list section we display an "Add ..." button, if the parent is real. 
         * (Parentless units have their special key: $nullParentKey (comes from AscUnitBuilderService::NULL_KEY))
        */ 
        // dump($sectionParentAscUnitData);
        if ($sectionAddButtonEnabled) {
            /**
             * @var $ascScaleId;
             * @var $newUnitSubject;
             * @var $newUnitParentId;
             * @var $newUnitAddButtonText;
            */
            /**
             * In this case, there is a /parent/ in the URL.
            */
            if ($parentAscUnitData) {
                $sectionChildSubjectData = $sectionParentAscUnitData['data']['subjectIsParentOf'];
                if ($sectionChildSubjectData) {
                    $newUnitParentId = $sectionParentAscUnitData['data']['ascUnitId'];
                    $newUnitSubject = $sectionParentAscUnitData['data']['subject'];
                    $newUnitAddButtonText = trans('add.'.$sectionChildSubjectData['translationReferenceSingular']);
                    include('AddUnitButton.php');
                }
            /**
             * In this case, there is a /subject/ in the URL.
            */
            } else {
                // dump($sectionParentAscUnitData['data']['ascUnitId']);
                $newUnitParentId = $sectionParentAscUnitData['data']['ascUnitId'];
                $newUnitSubject = $subject;
                $newUnitAddButtonText = trans('add.'.$subjectSingularTranslationReference);
                include('AddUnitButton.php');
            }
        }
        ?>

        <?php 
        $sectionSequence = 0;
        foreach ($unitDataArray as $unitData): 
        ?>
                <div class="row">
                    <div class="col-xl-12 col-md-12">
                <?php
                $debug = $unitData['data'];
                $debug['thumbnailSources'] = is_array($debug['thumbnailSources']) ? count($debug['thumbnailSources']) : null;
                $debug['subjectIsParentOf'] = null;
                $debug['subjectIsChildOf'] = null;
                // dump($debug);

                $debugString = '';
                // $debugString = '('.$unitData['data']['ascUnitId'].')';

                $additionalStatusCardClassSring = '';
                $additionalTitleStatusString = '';
                if ($unitData['data']['status'] == AscUnit::STATUS_INACTIVE) {
                    $additionalStatusCardClassSring = ' card-inactive';
                    $additionalTitleStatusString = ' ('.trans('inactive').')';
                }
                if ($unitData['data']['status'] == AscUnit::STATUS_CLOSED_SUCCESSFUL) {
                    $additionalStatusCardClassSring = ' card-closed-successful';
                    $additionalTitleStatusString = ' ('.trans('closed').')';
                }

                ViewTools::displayComponent('dashkit/card', [
                    'additionalCardClassString' => ' UnitBuilder-Unit UnitBuilder-Unit-draggable'.$additionalStatusCardClassSring,
                    'attributes' => [
                        'id' => 'UnitBuilder-Unit-'.$unitData['data']['ascUnitId'],
                        'data-unitid' => $unitData['data']['ascUnitId'],
                        'data-subject' => $unitData['data']['subject'],
                        'data-parentid' => $unitData['data']['parentId'],
                        'data-sequence' => $sectionSequence
                    ],
                    'containerAttributes' => [
                        'id' => 'UnitBuilder-Unit-'.$unitData['data']['ascUnitId'],
                        'data-unitid' => $unitData['data']['ascUnitId'],
                        'data-subject' => $unitData['data']['subject'],
                        'data-parentid' => $unitData['data']['parentId'],
                        'data-sequence' => $sectionSequence
                    ],
                    'title' => $debugString.'['.trans(AscTechService::findSubjectConfigValue($unitData['data']['subject'], 'translationReferenceSingular')).'] '.$unitData['data']['mainEntryTitle'].$additionalTitleStatusString,
                    'titleLink' => (!$unitData['data']['subjectIsParentOf'] ? null : '/asc/scaleBuilder/scale/'.$ascScaleId.'/parent/'.$unitData['data']['ascUnitId']),
                    'body' => $unitData['data']['mainEntryDescription'] ? html_entity_decode($unitData['data']['mainEntryDescription']) : '',
                    // 'additionalCardHeaderClassString' => 'bg-primary',
                    // 'additionalCardHeaderLinkClassString' => 'text-white',
                    'editOnClick' => 'AscScaleBuilder.editUnit(event, \''.$ascScaleId.'\', \''.$unitData['data']['ascUnitId'].'\');',
                    'displayFooter' => true,
                    'footerImageSources' => $unitData['data']['thumbnailSources'],
                    'deleteOnClick' => !$unitData['data']['isDeletable'] ? null :'AscScaleBuilder.initDeleteUnit(event, \''.$unitData['data']['ascUnitId'].'\', \''.$unitData['data']['mainEntryTitle'].'\');',
                ]);
                ?>
                    </div>
                </div>
        <?php 
        $sectionSequence++;
        endforeach;
        ?>
    <?php 
    endforeach;
endforeach;
?>