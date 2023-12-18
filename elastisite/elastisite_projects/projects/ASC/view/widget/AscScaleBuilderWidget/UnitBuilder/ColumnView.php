<?php 

use framework\packages\UXPackage\service\ViewTools;
use projects\ASC\entity\AscUnit;
use projects\ASC\service\AscTechService;

App::getContainer()->wireService('UXPackage/service/ViewTools');
App::getContainer()->wireService('projects/ASC/entity/AscUnit');
App::getContainer()->wireService('projects/ASC/service/AscTechService');

// include('definitions/pathToBuilder.php');
// include($pathToBuilder.'UnitBuilder/html/UnitBuilder.php');

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

// dump($unitDataArray, ['detailedObjects' => false]);
?>

<div class="pc-container">
    <div class="pcoded-content card-container">

        <div class="row row-cols-1 row-cols-sm-3 row-cols-md-4 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-4">
            <?php foreach ($unitDataArray['columnViewParentData'] as $columnIndex => $columnUnitDataArray): ?>
            <div class="col">
                <div class="card">
                    <?php if ($columnUnitDataArray['parentUnitData']): ?>
                    <div class=" card-header d-flex justify-content-between align-items-center">
                        <div class="card-header-textContainer ellipsis-container" style="width: 100%">
                            <h6 class="mb-0 ellipsis-text">
                                <a class="ajaxCallerLink link-underlined" href="/asc/scaleBuilder/columnView/scale/<?php echo $ascScaleId; ?>/child/<?php echo $columnUnitDataArray['parentUnitData']['data']['ascUnitId']; ?>">
                                    <?php echo $columnUnitDataArray['parentUnitData']['data']['mainEntryTitle'] ?>
                                </a>
                            </h6>
                        </div>
                    </div>
                    <?php elseif (isset($columnUnitDataArray['headerData'])): ?>
                    <div class=" card-header d-flex justify-content-between align-items-center">
                        <div class="card-header-textContainer ellipsis-container" style="width: 100%">
                            <h6 class="mb-0 ellipsis-text">
                                <?php if ($columnUnitDataArray['headerData']['link']): ?>
                                <a class="ajaxCallerLink link-underlined" href="<?php echo $columnUnitDataArray['headerData']['link']; ?>">
                                    <?php echo $columnUnitDataArray['headerData']['title'] ?>
                                </a>
                                <?php else: ?>
                                    <?php echo $columnUnitDataArray['headerData']['title'] ?>
                                <?php endif; ?>
                            </h6>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="card-body m-0 p-0">
                        <?php foreach ($columnUnitDataArray['columnUnitsData'] as $unitData): ?>
                        <?php 
                            $titleStr = $unitData['data']['mainEntryTitle'] && !empty(trim($unitData['data']['mainEntryTitle'])) ? $unitData['data']['mainEntryTitle'] : '['.trans('no.title').']';
                        ?>
                            
                        <div class="columnView-cell<?php echo $unitData['data']['selected'] ? ' cell-selected' : ''; ?>">
                            <a class="ajaxCallerLink link-underlined" href="/asc/scaleBuilder/columnView/scale/<?php echo $ascScaleId; ?>/child/<?php echo $unitData['data']['ascUnitId']; ?>">
                                <p class="card-text">
                                    <?php echo $titleStr; ?>
                                </p>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

        <?php if ($unitDataArray['columnViewActualUnitData']): ?>
        <?php 
        $unitData = $unitDataArray['columnViewActualUnitData'];
        // $description = $unitData['object']->getAscEntryHead()->findDescription();
        $description = $unitData['data']['mainEntryDescription'];
        $footerImageSources = $unitData['data']['thumbnailSources'];

        $additionalStatusCardClassSring = '';
        $additionalTitleStatusString = '';
        if ($unitData['data']['status'] == AscUnit::STATUS_INACTIVE) {
            $additionalStatusCardClassSring = ' card-inactive';
            $additionalTitleStatusString = ' ('.trans('inactive').')';
        }

        ?>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <?php 
                    ViewTools::displayComponent('dashkit/card', [
                        'additionalCardClassString' => $additionalStatusCardClassSring,
                        'title' => '['.trans(AscTechService::findSubjectConfigValue($unitData['data']['subject'], 'translationReferenceSingular')).'] '.$unitData['data']['mainEntryTitle'].$additionalTitleStatusString,
                        'titleLink' => (!$unitData['data']['subjectIsParentOf'] ? null : '/asc/scaleBuilder/scale/'.$ascScaleId.'/parent/'.$unitData['data']['ascUnitId']),
                        'body' => $description ? html_entity_decode($description) : '',
                        // 'additionalCardHeaderClassString' => 'bg-primary',
                        // 'additionalCardHeaderLinkClassString' => 'text-white',
                        'editOnClick' => 'AscScaleBuilder.editUnit(event, \''.$ascScaleId.'\', \''.$unitData['data']['ascUnitId'].'\');',
                        'displayFooter' => true,
                        'footerImageSources' => $footerImageSources,
                        'deleteOnClick' => !$unitData['data']['isDeletable'] ? null :'AscScaleBuilder.initDeleteUnit(event, \''.$unitData['data']['ascUnitId'].'\', \''.$unitData['data']['mainEntryTitle'].'\');',
                    ]);
                ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<style>
    /* 
    .columnView-container {
        min-height: 300px;
    }
    .columnView-column {
        min-width: 240px;
    }
    */
    /* .columnList-cell-toggled {
        box-shadow: 0px 4px 6px rgba(114, 103, 238, 0.25), 0px -2px 10px rgba(114, 103, 238, 0.12), 0px 1px 18px rgba(114, 103, 238, 0.12);
        box-shadow: inset 0px 8px 6px -6px rgba(114, 103, 238, 0.5), inset 0px -8px 6px -6px rgba(114, 103, 238, 0.5), inset -8px 0px 6px -6px rgba(114, 103, 238, 0.5), inset 8px 0px 6px -6px rgba(114, 103, 238, 0.5);
    } */
</style>

<!-- <div class="pc-container">
    <div class="pcoded-content card-container">
        <div class="row row-cols-1 row-cols-sm-3 row-cols-md-4 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-4">
            <div class="col">
                <div class="card h-100">
                    <div class="text-white card-header d-flex justify-content-between align-items-center">
                        <div class="card-header-textContainer">
                            <h6 class="mb-0"><a class="link-underlined" href="">[Admin skála] Kigyúrom magam</a></h6>
                        </div>
                    </div>
                    <div class="card-body m-0 p-0">
                        <div class="columnView-cell cell-innerShadow">
                            <a class="ajaxCallerLink link-underlined" href=""><p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p></a>
                        </div>
                        <div class="columnView-cell">
                            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                        </div>
                        <div class="columnView-cell">
                            <p class="card-text">This content is a little bit shorter.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                </div>
                </div>
            </div>
        </div>
    </div>
</div> -->