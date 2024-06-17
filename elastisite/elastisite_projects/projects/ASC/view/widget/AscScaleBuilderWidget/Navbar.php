    <!-- <form id="AscScaleLister_form" name="AscScaleLister_form">
    </form> -->

<?php

use projects\ASC\service\AscTechService;
use projects\ASC\service\AscRequestService;

App::getContainer()->wireService('projects/ASC/service/AscTechService');
App::getContainer()->wireService('projects/ASC/service/AscRequestService');

    $juxtaposedSubjectStr = '';
    if ($juxtaposedSubject) {
        $juxtaposedSubjectStr = ' + <span><b>'.trans($juxtaposedSubjectTranslationReference).'</b></span>';
    }
    // dump($parentAscUnit->getAscEntryHead()->findEntry()->getTitle());

    // dump($viewType);

    // $viewType 
    //
?>

<?php  
// $viewType can be: AscRequestService::VIEW_TYPE_LIST_VIEW or AscRequestService::VIEW_TYPE_COLUMN_VIEW
?>

<style>
    .controlPanel-icon-frame {
        padding-top: 4px;
    }
</style>

<div class="ControlPanel">
    <nav class="navbar-light bg-light sideNavbar-hamburger-container page-header" id="ControlPanel-page-header" style="width: 100%;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex align-items-center">
                        
                        <button class="navbar-toggler btn btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#AscScaleBuilder_PrimarySubjectBar_container" 
                                aria-controls="AscScaleBuilder_PrimarySubjectBar_container" aria-expanded="false" aria-label="Toggle navigation" style="margin-right: 20px;">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                    <?php
                        $listIconClass = ($viewType === AscRequestService::VIEW_TYPE_LIST_VIEW) ? 'material-icons-two-tone text-primary mb-1 me-2' : 'material-icons-two-tone text-secondary mb-1 me-2';
                        $viewIconClass = ($viewType === AscRequestService::VIEW_TYPE_COLUMN_VIEW) ? 'material-icons-two-tone text-primary mb-1 me-2' : 'material-icons-two-tone text-secondary mb-1 me-2';
                        $viewLink = '/'.App::getContainer()->getUrl()->getParamChain();
                        $viewLinkList = str_replace('/child/', '/parent/', $viewLink);
                        $viewLinkList = str_replace('/columnView/', '/', $viewLinkList);
                        $viewLinkColumn = str_replace('/parent/', '/child/', $viewLink);
                        $viewLinkColumn = str_replace('/columnView/', '/', $viewLinkColumn);
                        $viewLinkColumn = str_replace('/scaleBuilder/', '/scaleBuilder/columnView/', $viewLinkColumn);
                    ?>
                        <a href="<?php echo $viewLinkColumn; ?>" class="controlPanel-icon-frame ajaxCallerLink">
                            <i class="<?php echo $viewIconClass; ?>">view_week</i>
                        </a>

                        <a href="<?php echo $viewLinkList; ?>" class="controlPanel-icon-frame ajaxCallerLink">
                            <i class="<?php echo $listIconClass; ?>">list</i>
                        </a>

                        <div class="page-header-title">
                            <h5 class="m-b-10"><?php echo trans('scale.builder'); ?></h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="ajaxCallerLink" href="/asc/dashboard"><?php echo trans('dashboard'); ?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="ajaxCallerLink" href="/asc/scaleBuilder/scale/<?php echo $ascScale->getId(); ?>"><?php echo $ascScale->getAscEntryHead()->findEntry()->getTitle(); ?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <?php if (!$parentAscUnit && (($currentSubjectTranslationReference != '') || ($juxtaposedSubjectStr != ''))): ?>
                                    <?php echo trans($currentSubjectTranslationReference); ?> <?php echo $juxtaposedSubjectStr; ?>
                                <?php elseif ($parentAscUnit): ?>
                                    <?php 
                                    $parentEntryHead = $parentAscUnit->getAscEntryHead();
                                    $parentEntry = $parentEntryHead ? $parentEntryHead->findEntry() : null;
                                    $parentTitle = $parentEntry ? $parentEntry->getTitle() : '';
                                    ?>
                                    <?php echo '['.trans(AscTechService::findSubjectConfigValue($parentAscUnit->getSubject(), 'translationReferenceSingular')).'] '.$parentTitle; ?>
                                <?php else: ?>
                                    <?php echo trans('this.admin.scale'); ?>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>

