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
    #AdminScaleBuilder-container {
        background-color: #ffffff;
    }
</style>

<div id="AdminScaleBuilder-styleSheet-container">
    <div id="AscScaleBuilder-styleSheet-GeneralStyles-sizingStyles">
    <?php include($pathToBuilder.'GeneralStyles/sizingStyles.php'); ?>
    </div>
    <div id="AscScaleBuilder-styleSheet-GeneralStyles-coloringStyles">
    <?php include($pathToBuilder.'GeneralStyles/coloringStyles.php'); ?>
    </div>
    <div id="AscScaleBuilder-styleSheet-GeneralStyles-staticStyles">
    <?php include($pathToBuilder.'GeneralStyles/staticStyles.php'); ?>
    </div>
    <div id="AscScaleBuilder-styleSheet-UnitBuilder-UnitBuilderGeneralStyle">
    <?php include($pathToBuilder.'UnitBuilder/css/UnitBuilderGeneralStyle.php'); ?>
    </div>
    <div id="AscScaleBuilder-styleSheet-UnitBuilder-UnitPanelStyle">
    <?php include($pathToBuilder.'UnitBuilder/css/UnitPanelStyle.php'); ?>
    </div>
    <div id="AscScaleBuilder-styleSheet-UnitBuilder-SubjectPanelStyle">
    <?php include($pathToBuilder.'UnitBuilder/css/SubjectPanelStyle.php'); ?>
    </div>
    <div id="AscScaleBuilder-styleSheet-UnitBuilder-UnitStyle">
    <?php include($pathToBuilder.'UnitBuilder/css/UnitStyle.php'); ?>
    </div>
    <div id="AscScaleBuilder-styleSheet-UnitBuilder-UnitIconbarStyle">
    <?php include($pathToBuilder.'UnitBuilder/css/UnitIconbarStyle.php'); ?>
    </div>
    <div id="AscScaleBuilder-styleSheet-PrimarySubjectBar-PrimarySubjectBarStyle">
    <?php include($pathToBuilder.'PrimarySubjectBar/css/PrimarySubjectBarStyle.php'); ?>
    </div>
</div>

<div id="AdminScaleBuilder-container" class="AdminScaleBuilder-fontSize-normalText">
    <div class="widgetWrapper" style="margin-top: 10px; margin-left: 10px; margin-right: 10px; margin-bottom: 10px;">
        <div id="AscScaleBuilder_ControlPanel_container"><?php echo $views['ControlPanelView']; ?></div>
    </div>

    <style>
    #AscScaleBuilder_PrimarySubjectBar_containerWrapper {
        /* background-color: #EDEDED; */
        /* margin-top: 10px !important; */
        margin-right: 4px !important;
        margin-bottom: 0px;
        padding-top: 0px !important;
        padding-bottom: 0px;
        box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;
    }
    #AscScaleBuilder_PrimarySubjectBar_swiper {
        background-color: #E8E8E8;
        width: 20px !important; 
        border: 1px solid #dedede; 
        padding: 0px; 
        margin: 0px;
        position: relative;
        cursor: pointer;
    }
    #AscScaleBuilder_PrimarySubjectBar_swiper:hover {
        background-color: #dedede;
    }
    #AscScaleBuilder_PrimarySubjectBar_swiper::before,
    #AscScaleBuilder_PrimarySubjectBar_swiper::after {
        content: "";
        position: absolute;
        width: 2px;
        height: 20px;
        background-color: #bfbfbf;
        color: #bfbfbf;
        left: 50%;
        transform: translateX(-50%);
        top: 50%;
        margin-top: -10px;
    }
    #AscScaleBuilder_PrimarySubjectBar_swiper::before {
        margin-left: -2px;
    }
    #AscScaleBuilder_PrimarySubjectBar_swiper::after {
        margin-left: 2px;
    }
    </style>

    <div id="AscScaleBuilder_listView_container">
        <?php if (App::getContainer()->getSession()->get('AscScaleBuilder-primarySubjectBarState') == AscConfigService::PRIMARY_SUBJECTBAR_STATE_OPENED): ?>
        <div class="row">
            <div class="col-lg-3 col-xl-2" style="padding: 0px; margin: 0px;">
                <div id="AscScaleBuilder_PrimarySubjectBar_containerWrapper">
                    <div class="d-flex">
                        <div id="AscScaleBuilder_PrimarySubjectBar_container" style="width: 100%;"><?php echo $views['PrimarySubjectBarView']; ?></div>
                        <div id="AscScaleBuilder_PrimarySubjectBar_swiper"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-10" style="padding: 0px; margin: 0px;">
                <div id="AscScaleBuilder_UnitBuilder_container" style="padding: 0px !important; margin: 0px !important;"><?php echo $views['UnitBuilderView']; ?></div>
            </div>
        </div>
        <?php else: ?>
        <style>
        .one-row {
            display: flex;
            /* align-items: center; */
        }
        #AscScaleBuilder_PrimarySubjectBar_swiper {
            height: auto;
            margin-right: 10px;
            background-color: #a4e1e7;
        }
        #AscScaleBuilder_UnitBuilder_container {
            padding: 0 !important;
            margin: 0 !important;
        }
        </style>

        <div class="one-row">
            <?php  
            // dump(App::getContainer()->getSession()->get('AscScaleBuilder-primarySubjectBarState'));
            ?>
            <div id="AscScaleBuilder_PrimarySubjectBar_swiper"></div>
            <div id="AscScaleBuilder_UnitBuilder_container" style="width: 100% !important;"><?php echo $views['UnitBuilderView']; ?></div>
        </div>
        <?php endif; ?>
    </div>
    <div id="AscScaleBuilder_columnView_container">
        col view!
    </div>
</div> <!-- / #AdminScaleBuilder-container -->

<script>
    var AscScaleBuilder = {
        processResponse: function(response) {
            // console.log(response);

            if (response.data.callback && response.data.callback == 'addUnitCallback') {
                AscScaleBuilder.addUnitCallback(response);
            }
            if (response.data.callback && response.data.callback == 'editUnitCallback') {
                AscScaleBuilder.editUnitCallback(response);
            }
            if (response.data.callback && response.data.callback == 'addJuxtaposedSubjectCallback') {
                AscScaleBuilder.addJuxtaposedSubjectCallback(response);
            }
            if (response.data.callback && response.data.callback == 'moveUnitCallback') {
                AscScaleBuilder.moveUnitCallback(response);
            }
            if (response.data.callback && response.data.callback == 'deleteUnitCallback') {
                AscScaleBuilder.deleteUnitCallback(response);
            }
            if (response.data.callback && response.data.callback == 'applySettingCallback') {
                AscScaleBuilder.applySettingCallback(response);
            }

            if (response.views && typeof(response.views.ControlPanelView) == 'string' && response.views.ControlPanelView != 'null' && response.views.ControlPanelView != '') {
                $('#AscScaleBuilder_ControlPanel_container').html(response.views.ControlPanelView);
            }
            if (response.views && typeof(response.views.PrimarySubjectBarView) == 'string' && response.views.PrimarySubjectBarView != 'null' && response.views.PrimarySubjectBarView != '') {
                $('#AscScaleBuilder_PrimarySubjectBar_container').html(response.views.PrimarySubjectBarView);
            }
            if (response.views && typeof(response.views.UnitBuilderView) == 'string' && response.views.UnitBuilderView != 'null' && response.views.UnitBuilderView != '') {
                $('#AscScaleBuilder_UnitBuilder_container').html(response.views.UnitBuilderView);
            }

            if (response.data && typeof(response.data.modalLabel) != 'undefined') {
                $('#editorModalLabel').html(response.data.modalLabel);
            }

            if (response.data && typeof(response.data.closeModal) != 'undefined' && response.data.closeModal == true) {
                $('#editorModal').modal('hide');
            }
            // if (response.views && typeof(response.views.othersScaleListView) == 'string' && response.views.othersScaleListView != 'null' && response.views.othersScaleListView != '') {
            //     $('#AscScaleLister_othersScaleList_container').html(response.views.othersScaleListView);
            // }
            // if (response.views && typeof(response.views.controlPanelView) == 'string' && response.views.controlPanelView != 'null' && response.views.controlPanelView != '') {
            //     $('#AscScaleLister_controlPanel_container').html(response.views.controlPanelView);
            // }
            // if (response.views && typeof(response.views.newView) == 'string' && response.views.newView != 'null' && response.views.newView != '') {
            //     $('#editorModalBody').html(response.views.newView);
            //     $('#editorModal').modal('show');
            // }
            // if (response.views && typeof(response.views.editView) == 'string' && response.views.editView != 'null' && response.views.editView != '') {
            //     $('#editorModalBody').html(response.views.editView);
            //     $('#editorModal').modal('show');
            // }
            // if (response.data.label && typeof(response.data.label) == 'string' && response.data.label != 'null' && response.data.label != '') {
            //     $('#editorModalLabel').html(response.data.label);
            // }
            // if (response.data.closeModal == 'true') {
            //     $('#editorModal').modal('hide');
            // }

            LoadingHandler.stop();
        },
        callAjax: function(command, additionalData) {
            let baseData = {};
            let ajaxData = $.extend({}, baseData, additionalData);
            LoadingHandler.start();
            $.ajax({
                'type' : 'POST',
                'url' : '/asc/scaleBuilder/' + command,
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    LoadingHandler.stop();
                    ElastiTools.checkResponse(response);
                    console.log(response);
                    AscScaleBuilder.processResponse(response);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        listScales: function() {
            AscScaleBuilder.callAjax('listScales', {})
        },
        addUnit: function(e, scaleId, subject, parentId) {
            e.preventDefault();
            // console.log('AscScaleBuilder.addUnit');
            AscScaleBuilder.callAjax('addUnit', {
                // 'scaleId': scaleId,
                'subject': subject,
                'parentId': parentId
            })
        },
        addUnitCallback: function(response) {
            if (response.data.savedUnitId) {
                AscScaleBuilder.editUnit(response.data.savedUnitId);
            }
        },
        editUnit: function(unitId) {
            // return true;
            AscScaleBuilder.callAjax('editUnit', {
                'unitId': unitId,
                'submitted': false
            })
        },
        editUnitSubmit: function(unitId) {
            $('#AscScaleBuilder_editUnit_description').val(CKEDITOR.instances.AscScaleBuilder_editUnit_description.getData());

            AscScaleBuilder.callAjax('editUnit', {
                'unitId': unitId,
                'submitted': true,
                'title': $('#AscScaleBuilder_editUnit_title').val(),
                'description': $('#AscScaleBuilder_editUnit_description').val(),
                'responsible': $('#AscScaleBuilder_editUnit_responsible').val(),
                'administrationStance': $('#AscScaleBuilder_editUnit_administrationStance').val(),
                'dueType': $('#AscScaleBuilder_editUnit_dueType').val(),
                'recurrencePattern': $('#AscScaleBuilder_editUnit_recurrencePattern').val(),
                'dueDate': $('#AscScaleBuilder_editUnit_dueDate').val(),
                'dueTimeHours': $('#AscScaleBuilder_editUnit_dueTimeHours').val(),
                'dueTimeMinutes': $('#AscScaleBuilder_editUnit_dueTimeMinutes').val(),
                'status': $('#AscScaleBuilder_editUnit_status').val(),
            })
        },
        editUnitCallback: function(response) {
            console.log('editUnitCallback');
            $('#editorModalBody').html(response.view);
            $('#editorModal').modal('show');
            // $('.UnitBuilder-UnitWrapper-placeholder').hide();
        },
        addJuxtaposedSubject: function() {
            AscScaleBuilder.callAjax('addJuxtaposedSubject', {})
        },
        addJuxtaposedSubjectCallback: function(response) {
            console.log('addJuxtaposedSubjectCallback!');
            $('#editorModal').modal('show');
            $('#editorModalBody').html(response.view);
        },
        // loadEditor: function() {

        // },
        moveUnit: function(movedUnitId, targetSubject, targetParentType, targetParentId, targetUnitId, aheadOrBehind) {
            var ajaxData = {
                'unitId': movedUnitId,
                'subject': targetSubject,
                'parentType': targetParentType,
                'parentId': targetParentId,
                'targetUnitId': targetUnitId,
                'aheadOrBehind': aheadOrBehind
            };
            console.log('moveUnit ajaxData: ');
            console.log(ajaxData);

            AscScaleBuilder.callAjax('moveUnit', ajaxData);
        },
        moveUnitTriggerSortOnlyError: function(response) {
            Structure.throwErrorToast('<?php echo trans('error'); ?>', '<?php echo trans('elements.containing.other.elements.can.only.be.sorted'); ?>');
            Structure.call();
        },
        moveUnitCallback: function(response) {
            if (response.data.errorMessage != null) {
                Structure.throwErrorToast('<?php echo trans('error'); ?>', response.data.errorMessage);
            } else {
                Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('move.successful'); ?>');
            }
        },
        initDeleteUnit: function(id, title) {
            if (id == undefined || id === null || id === false) {
                return false;
            }
            let rawText = '<?php echo trans('deleting.element').': <br> <b>[title]</b> <br>'.trans('are.you.sure'); ?>';
            let text = rawText.replace('[title]', title);
            $('#confirmModalBody').html(text);
            $('#confirmModalConfirm').attr('onClick', "AscScaleBuilder.deleteUnitConfirmed(" + id + ");");
            $('#confirmModal').modal('show');
        },
        deleteUnitConfirmed: function(id) {
            AscScaleBuilder.callAjax('deleteUnit', {
                'id': id
            })
            $('#confirmModal').modal('hide');
        },
        deleteUnitCallback: function(response) {
            Structure.call();
        },
        applySetting: function(data) {
            AscScaleBuilder.callAjax('applySetting', data);
        },
        applySettingCallback: function(response) {
            Structure.call();
        },
        triggerFullScreen: function() {
            var container = $('#AdminScaleBuilder-container');
            container.css('z-index', '1000');
            container.css('position', 'fixed');
            container.css('top', '10px');
            container.css('bottom', '10px');
            container.css('left', '10px');
            container.css('right', '10px');
            container.css('height', window.innerHeight - 20 + 'px'); // Képernyő magasságából levonjuk a padding értékét
            container.css('width', window.innerWidth - 20 + 'px'); // Képernyő szélességéből levonjuk a padding értékét
            container.css('overflow', 'auto');

            $('.icon_fullscreen').hide();
            $('.icon_exit_fullscreen').show();

            $(document).on('keydown', function(event) {
                if (event.keyCode === 27) { // Escape billentyu
                    AscScaleBuilder.triggerExitFullScreen();
                }
            });
        },
        triggerExitFullScreen: function() {
            Structure.call();
            LoadingHandler.stop();
        }
        // initDeleteScale: function(id, title) {
        //     if (id == undefined || id === null || id === false) {
        //         return false;
        //     }
        //     let rawText = '<?php echo trans('deleting.admin.scale').': <br> <b>[title]</b> <br>'.trans('are.you.sure'); ?>';
        //     let text = rawText.replace('[title]', title);
        //     $('#confirmModalBody').html(text);
        //     $('#confirmModalConfirm').attr('onClick', "AscScaleBuilder.deleteScaleConfirmed(" + id + ");");
        //     $('#confirmModal').modal('show');
        // },
        // deleteScaleConfirmed: function(id) {
        //     AscScaleLister.callAjax('deleteScale', {
        //         'id': id
        //     })
        //     $('#confirmModal').modal('hide');
        // }
    };

    $('document').ready(function() {
        $('#editorModal').off('hidden.bs.modal');
        $('#editorModal').on('hidden.bs.modal', function () {
            Structure.call();
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

        $("#AscScaleBuilder_UnitBuilder_container").sortable({
            items: ".UnitBuilder-Unit-draggable",
            cancel: ".UnitBuilder-Unit-droponly",
            // revert: true,
            placeholder: 'UnitBuilder-Unit-placeholder',
            // handle: ".UnitBuilder-Unit-drophandler",
            beforeStart: function(event, ui) {
                console.log('beforeStart!');
                $('.UnitBuilder-UnitWrapper-placeholder').show();
            },
            update: function(event, ui) {
                var draggedElement = ui.item;  // Az elem, amit elengedtel
                var movedUnitId = draggedElement.attr('data-unitid');
                var movedUnitSubject = draggedElement.attr('data-subject');
                var movedUnitSortOnly = draggedElement.hasClass('UnitBuilder-Unit-draggable-sortOnly');
                console.log('draggedElement', draggedElement);
                let targetWrapper = draggedElement.parent();
                let targetParentType = targetWrapper.attr('data-parenttype');
                let targetParentId = targetWrapper.attr('data-parentid');
                let targetSubject = targetWrapper.attr('data-subject');
                let targetUnitId = targetWrapper.attr('data-unitid');
                var aheadOrBehind = null;
                // var ids = [];
                var counter = 0;

                console.log('targetWrapper:', targetWrapper);

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

                console.log('movedUnitId: ' + movedUnitId);
                console.log('movedUnitSubject: ' + movedUnitSubject);
                console.log('movedUnitSortOnly: ', movedUnitSortOnly);
                console.log('targetSubject: ' + targetSubject);
                // console.log('targetWrapperHtmlId: ' + targetWrapperHtmlId);
                console.log('targetParentId: ' + targetParentId);
                console.log('aheadOrBehind: ' + aheadOrBehind);

                if ((movedUnitSortOnly && movedUnitSubject == targetSubject) || !movedUnitSortOnly) {
                    AscScaleBuilder.moveUnit(movedUnitId, targetSubject, targetParentType, targetParentId, targetUnitId, aheadOrBehind);
                } else {
                    // AscScaleBuilder.moveUnitTriggerSortOnlyError();
                }

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