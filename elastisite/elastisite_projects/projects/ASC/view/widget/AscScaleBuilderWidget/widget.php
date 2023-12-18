<?php

use projects\ASC\repository\AscUnitRepository;
use projects\ASC\service\AscConfigService;

App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
App::getContainer()->wireService('projects/ASC/service/AscConfigService');

?>

<script src="/public_folder/plugin/Dropzone/dropzone.min.js"></script>
<link rel="stylesheet" href="/public_folder/plugin/Dropzone/dropzone.min.css" type="text/css" />
<script src="/public_folder/plugin/CKEditor/ckeditor/ckeditor.js"></script>

<?php 
if (!$currentAscScaleAvailable) {
    include('invalidScale.php');
    return;
}

if (!App::getContainer()->getUser()->getUserAccount()->getId()) {
    include('accessDenied.php');
    return;
}

include('definitions/pathToBuilder.php');
?>

<style>
#AscScaleBuilder_BuilderInterface_container {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
}
</style>

<div id="AdminScaleBuilder-container" class="AdminScaleBuilder-fontSize-normalText">

    <!-- Navbar -->
    <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark sideNavbar-hamburger-container" id="PrimarySubjectBar_hamburger_container">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#AscScaleBuilder_PrimarySubjectBar_container" aria-controls="AscScaleBuilder_PrimarySubjectBar_container" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav> -->

    <div id="AscScaleBuilder_BuilderInterface_container" style="display: flex;">

        <!-- Sidebar -->
        <!-- collapse -->
        <nav class="pc-sidebar collapse show sideNavbar-container" id="AscScaleBuilder_PrimarySubjectBar_container" style="width: 280px; height: 100% !important; z-index: 0 !important;">
            <?php echo $views['PrimarySubjectBarView']; ?>
        </nav>

        <!-- Main Content -->
        <div class="pc-container">
            <div id="AscScaleBuilder_ControlPanel_container" style="width: 100%;">
                <?php echo $views['ControlPanelView']; ?>
            </div>
            <div id="AscScaleBuilder_Content_container" style="width: 100%;">
            <?php 
            // include('exampleContent.php');
            ?>
            <?php echo $views['UnitBuilderView']; ?>
            </div>
        </div>

    </div>
</div> <!-- / #AdminScaleBuilder-container -->

<?php include($pathToBuilder.'ImageGallery/ImageGallery.php'); ?>
<?php include($pathToBuilder.'Scripts/AscScaleBuilderScripts.php'); ?>

<script>

    $('document').ready(function() {

        AscScaleBuilder.hideOrShowPrimarySubjectBar();

        $(window).resize(function() {
            AscScaleBuilder.hideOrShowPrimarySubjectBar();
        });

        $('#editorModal').off('hidden.bs.modal');
        $('#editorModal').on('hidden.bs.modal', function () {
            AscScaleBuilder.refreshList();
        });

        $('#sheetContainer').off('click', '#AscScaleBuilder_PrimarySubjectBar_swiper');
        $('#sheetContainer').on('click', '#AscScaleBuilder_PrimarySubjectBar_swiper', function() {
            console.log('AscScaleBuilder_PrimarySubjectBar_swiper');
            AscScaleBuilder.applySetting({
                'swipePrimarySubjectBar': true
            });
        });

        $('#sheetContainer').off('click', '.UnitBuilder-SubjectDragAndDropper');
        $('#sheetContainer').on('click', '.UnitBuilder-SubjectDragAndDropper', function() {
            console.log('alma!!!3');
            AscScaleBuilder.addJuxtaposedSubject();
        });

        // $('document').off('hide.bs.modal', '#editorModal');
        // $('document').on('hide.bs.modal', '#editorModal', function(){
        //     console.log('hide.bs.modal');
        //     if (CKEDITOR.instances.body) CKEDITOR.instances.body.destroy();
        //     ElastiTools.removeVeil();
        // });

        $("#AscScaleBuilder_Content_container").sortable({
            items: ".UnitBuilder-Unit-draggable",
            // cancel: ".UnitBuilder-Unit-droponly",
            // revert: true,
            // placeholder: 'UnitBuilder-Unit-placeholder',
            // handle: ".UnitBuilder-Unit-drophandler",
            // beforeStart: function(event, ui) {
            //     console.log('beforeStart!');
            //     $('.UnitBuilder-UnitWrapper-placeholder').show();
            // },
            // update: function(event, ui) {
            //     var draggedElement = ui.item;  // Az elem, amit elengedtel
            //     var movedUnitId = draggedElement.attr('data-unitid');
            //     var movedUnitSubject = draggedElement.attr('data-subject');
            //     console.log('========= move =========');
            //     console.log(movedUnitId);
            //     console.log(ui);
            //     // AscScaleBuilder.moveUnit(movedUnitId, targetSubject, targetParentType, targetParentId, targetUnitId, aheadOrBehind);
            // },
            update: function(event, ui) {
                var draggedElement = ui.item;  // Az elem, amit elengedtel
                var movedUnitId = draggedElement.attr('data-unitid');
                var movedUnitSubject = draggedElement.attr('data-subject');
                let movedUnitSequence = draggedElement.attr('data-sequence');

                var movedUnitSortOnly = true;

                let targetWrapper = draggedElement.parent();
                let targetParentType = targetWrapper.attr('data-parenttype');
                let targetParentId = targetWrapper.attr('data-parentid');
                let targetSubject = targetWrapper.attr('data-subject');
                let targetUnitId = targetWrapper.attr('data-unitid');
                let targetUnitSequence = targetWrapper.attr('data-sequence');

                // var aheadOrBehind = '<?php echo AscUnitRepository::MOVE_TO_POSITION_BEHIND; ?>';
                var aheadOrBehind = null;

                var counter = 0;

                // console.log('draggedElement', draggedElement);
                // console.log('targetWrapper:', targetWrapper);
                // return;

                targetWrapper.find('.UnitBuilder-Unit').each(function() {
                    // ids.push($(this).attr('data-unitid'));
                    let loopUnitId = $(this).attr('data-unitid');
                    console.log('loop unitId: ' + loopUnitId);
                    if (loopUnitId == movedUnitId) {
                        // console.log('Megvan a moved');
                        if (counter == 0) {
                            aheadOrBehind = '<?php echo AscUnitRepository::MOVE_TO_POSITION_AHEAD; ?>';
                        } else {
                            aheadOrBehind = '<?php echo AscUnitRepository::MOVE_TO_POSITION_BEHIND; ?>';
                        }
                    } else {
                        toUnitId = loopUnitId;
                    }
                    counter++;
                });

                // console.log('movedUnitId: ' + movedUnitId);
                // console.log('movedUnitSubject: ' + movedUnitSubject);
                // console.log('movedUnitSortOnly: ', movedUnitSortOnly);
                // console.log('targetSubject: ' + targetSubject);
                // // console.log('targetWrapperHtmlId: ' + targetWrapperHtmlId);
                // console.log('targetUnitId: ' + targetUnitId);
                // console.log('aheadOrBehind: ' + aheadOrBehind);
                // return;

                AscScaleBuilder.moveUnit(movedUnitId, targetSubject, targetParentType, targetParentId, targetUnitId, aheadOrBehind);

                // if ((movedUnitSortOnly && movedUnitSubject == targetSubject) || !movedUnitSortOnly) {
                //     AscScaleBuilder.moveUnit(movedUnitId, targetSubject, targetParentType, targetParentId, targetUnitId, aheadOrBehind);
                // } else {
                //     // AscScaleBuilder.moveUnitTriggerSortOnlyError();
                // }

                // $('.UnitBuilder-UnitWrapper-placeholder').hide();
            },
            start: function(event, ui) {
                var draggedElement = ui.item;
                draggedElement.find('.UnitBuilder-UnitBody').find('.UnitBuilder-UnitBody-secondarySubjectContainer').find('.UnitBuilder-UnitWrapper-placeholder').remove();
                // let footerHtml = draggedElement.find('.UnitBuilder-UnitBody').find('.UnitBuilder-UnitBody-secondarySubjectContainer').html().trim();
                // console.log('footerHtml: ' + footerHtml);
                // if (footerHtml == '') {
                //     draggedElement.find('.UnitBuilder-UnitBody').find('.UnitBuilder-UnitBody-secondarySubjectContainer').remove();
                // }
                // $('.UnitBuilder-UnitWrapper-placeholder').show();
            },
            stop: function(event, ui) {
                console.log('stop!');
                // $('.UnitBuilder-UnitWrapper-placeholder').hide();
            }
        });

    });
</script>