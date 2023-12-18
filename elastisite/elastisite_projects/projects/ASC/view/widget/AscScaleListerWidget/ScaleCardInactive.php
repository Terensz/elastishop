<?php

use framework\packages\UXPackage\service\ViewTools;

// include('definitions/pathToCommonComponents.php');
// dump($pathToCommonComponents);
// include($pathToCommonComponents.'ScaleTeamTable.php');

$entryHead = $ascScale->getAscEntryHead();
$entry = $entryHead->getAscEntry();
?>

<div class="col">
<?php
    $title = $entryHead->findTitle();
    $titleLink = '/asc/scaleBuilder/scale/'.$ascScale->getId();
    $body = $entryHead->findDescription();
    $editOnClick = 'AscScaleLister.editScale(event, \''.$ascScale->getId().'\');';
    // ViewTools::displayComponent('dashkit/card', [
    //     'title' => $entryHead->findTitle(),
    //     'titleLink' => '/asc/scaleBuilder/scale/'.$ascScale->getId(),
    //     'body' => $entryHead->findDescription(),
    //     'additionalCardHeaderClassString' => 'bg-primary',
    //     'additionalCardHeaderLinkClassString' => 'text-white',
    //     'editOnClick' => 'AscScaleLister.editScale(event, \''.$ascScale->getId().'\');'
    // ]);

    // 'scaleOwnerData' => ProjectTeamworkService::getScaleOwnerData($ascScale),
    // 'projectTeamworkData' => $ascScaleRepo->getProjectTeamworkData($ascScale),
    // 'scaleTeamUnconfirmedInviteData' => $ascScaleRepo->getScaleTeamUnconfirmedInviteData($ascScale)
    if (isset($scaleDataRow['projectTeamworkData']))
?>
    <div class="card card-inactive">
        <div class="card-header d-flex justify-content-between align-items-center">
        <!-- <div class="bg-primary card-header d-flex justify-content-between align-items-center"> -->
            <div class="card-header-iconContainer-left" style="line-height: 1;">
                <a class="" href="" onclick="<?php echo $editOnClick; ?>">
                    <img src="/public_folder/plugin/Bootstrap-icons/edit.svg">
                </a>
            </div>
            <div class="card-header-textContainer">
                <h6 class="mb-0">
                    <a class="link-underlined ajaxCallerLink text-black" href="<?php echo $titleLink; ?>">
                        <?php echo $title; ?>
                    </a>
                </h6>
            </div>
        </div>
        <div class="card-body">
            <span>
                <?php echo $body; ?>
                <?php 
                if (isset($scaleOwnerData) && isset($projectTeamworkData) && isset($scaleTeamUnconfirmedInviteData)) {
                    include('definitions/pathToCommonComponents.php');
                    include($pathToCommonComponents.'ScaleTeamTable.php');
                }
                ?>
            </span>
        </div>
    </div>

</div>