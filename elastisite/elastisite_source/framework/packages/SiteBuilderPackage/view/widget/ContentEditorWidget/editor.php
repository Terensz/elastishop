<?php

use framework\packages\FrontendPackage\service\ResponsivePageService;

?>
<div class="editorArea">
<?php
include('framework/packages/ToolPackage/view/upload/js.php');
?>
<div id="contentEditorBoard_editorToolbar_container_<?php echo $contentEditorId; ?>" class="contentEditorToolbar-container">
<?php
echo $editorToolbarView;
?>
</div>
<div id="contentViewer_container_<?php echo $contentEditorId; ?>" class="">
<?php
echo $viewerView;
?>
</div>
<style>
.contentEditorToolbar-container {
    background-color: #cfcfcf;
    box-shadow: inset rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
}
.toolbar-inputContainer {
    padding: 10px;
}
.toolbar-input {
    box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
}
.toolbar-inputField {
    background-color: #eaeaea !important;
    cursor: pointer;
}
.contentEditorToolbar-unitCase-rail {
    background-color: #cfcfcf;
    padding-top: 10px;
}
.contentEditorToolbar-unitCase-container {
    margin-bottom: 10px;
    margin-left: 10px;
    margin-right: 10px;
    /* height: 42px; */
    background-color: #a7dae8;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
    padding: 10px;
}
.contentEditorToolbar-unit-rail {
    /* background-color: #b6b6b6; */
    padding-top: 10px;
}
.contentEditorToolbar-unit-container {
    margin-bottom: 10px;
    margin-left: 10px;
    margin-right: 10px;
    min-height: 42px;
    background-color: #eaeaea;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
}
.contentEditorToolbar-unit-description {
    padding: 10px;
    /* cursor: pointer; */
}
.contentEditorToolbar-unit-operations {
    background-color: #a7c7d9;
    /* cursor: pointer; */
}
.contentViewer-unitCase-container {
    cursor: pointer;
    padding: 0px;
    margin: 0px;
}
.contentViewer-unit-container {
    padding: 0px;
    margin: 0px;
}
/* .contentViewer-unit {
    border: 1px solid #3081b3;
} */
/* .contentViewer-unit-draggable:hover {
    border: 1px solid #3081b3;
} */
.sortable {
    list-style-type: none;
}
.sortable li span {
    position: absolute;
}
.ui-sortable {
    margin-left: 0px;
    padding-left: 0px;
}

/* .verticalCenterOuterWrapper {
    float: right;
    position: relative;
    left: -50%;
    text-align: left;
} */

</style>
<script>
    var EditorDragger_<?php echo $contentEditorId; ?> = {
        element: null,
        dragElement: function(elmnt) {
            // console.log('dragElement!!');
            EditorDragger_<?php echo $contentEditorId; ?>.element = elmnt;
            var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
            if (document.getElementById(elmnt.id + "header")) {
                document.getElementById(elmnt.id + "header").onmousedown = EditorDragger_<?php echo $contentEditorId; ?>.dragMouseDown;
            } else {
                elmnt.onmousedown = EditorDragger_<?php echo $contentEditorId; ?>.dragMouseDown;
            }
        },
        dragMouseDown: function(e) {
            // if ($('body').width() < <?php echo ResponsivePageService::CONTENT_EDITOR_WORKS_AT_MIN_WIDTH; ?>) {
            //     return ;
            // }
            // console.log('dragMouseDown!!');
            e = e || window.event;
            e.preventDefault();
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = EditorDragger_<?php echo $contentEditorId; ?>.closeDragElement;
            document.onmousemove = EditorDragger_<?php echo $contentEditorId; ?>.elementDrag;
        },
        elementDrag: function(e) {
            let elmnt = EditorDragger_<?php echo $contentEditorId; ?>.element;
            e = e || window.event;
            e.preventDefault();
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
            elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        },
        closeDragElement: function() {
            document.onmouseup = null;
            document.onmousemove = null;
        }
    };

    var ContentEditorToolbar_<?php echo $contentEditorId; ?> = {
        reload: function(submitForm) {
            LoadingHandler.start();
            var ajaxData = {};
            var form = $('#contentEditorBoard_toolbar_form');
            var formData = form.serialize();
            var additionalData = {
                'contentEditorId': '<?php echo $contentEditorId; ?>',
                'viewerRounded': '<?php echo $viewerRounded; ?>',
                'submitForm': submitForm
            };
            ajaxData = submitForm ? formData + '&' + $.param(additionalData) : additionalData;
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/ContentEditorWidget/editor/reload',
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    LoadingHandler.stop();
                    ElastiTools.checkResponse(response);
                    $('#contentEditorBoard_editorToolbar_container_<?php echo $contentEditorId; ?>').html(response.views.editorToolbarView);
                    $('#contentViewer_container_<?php echo $contentEditorId; ?>').html(response.views.viewerView);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        upload: function(event) {
            var url = '<?php echo $httpDomain; ?>/ContentEditorWidget/editor/uploadContentEditorBackgroundImage';
            var file = $(event)[0].files[0];
            var upload = new Upload(file);
            if (file !== undefined) {
                upload.doUpload('contentEditorBoard_toolbar_contentEditorBackgroundImage_<?php echo $contentEditorId; ?>', url, 'ContentEditorToolbar_<?php echo $contentEditorId; ?>.uploadCallback', null, {
                    'contentEditorId': '<?php echo $contentEditorId; ?>'
                });
            }
        },
        uploadCallback: function(responseDataJson) {
            ContentEditorToolbar_<?php echo $contentEditorId; ?>.reload(false);
        },
        addContentEditorUnitCase: function(e) {
            e.preventDefault();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/ContentEditorWidget/editor/addContentEditorUnitCase',
                'data': {
                    'contentEditorId': '<?php echo $contentEditorId; ?>'
                },
                'async': true,
                'success': function(response) {
                    ContentEditorToolbar_<?php echo $contentEditorId; ?>.reload(false);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        removeContentEditorUnitCase: function(e, contentEditorUnitCaseId) {
            e.preventDefault();
            // console.log('Remove: ' + contentEditorUnitId);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/ContentEditorWidget/editor/removeContentEditorUnitCase',
                'data': {
                    'contentEditorUnitCaseId': contentEditorUnitCaseId
                },
                'async': true,
                'success': function(response) {
                    ContentEditorToolbar_<?php echo $contentEditorId; ?>.reload(false);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        editContentEditorUnitCase: function(e, contentEditorUnitCaseId) {
            e.preventDefault();
            $('#editorModal').modal('show');
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/ContentEditorWidget/editor/editContentEditorUnitCase',
                'data': {
                    'id': contentEditorUnitCaseId
                },
                'async': true,
                'success': function(response) {
                    $('#editorModalLabel').html(response.data.label);
                    $('#editorModalBody').html(response.view);
                    // ContentEditorToolbar_<?php echo $contentEditorId; ?>.reload(false);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        addContentEditorUnit: function(e, contentEditorUnitCaseId) {
            e.preventDefault();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/ContentEditorWidget/editor/addContentEditorUnit',
                'data': {
                    'contentEditorId': '<?php echo $contentEditorId; ?>',
                    'contentEditorUnitCaseId': contentEditorUnitCaseId
                },
                'async': true,
                'success': function(response) {
                    ContentEditorToolbar_<?php echo $contentEditorId; ?>.reload(false);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        removeContentEditorUnit: function(e, contentEditorUnitId) {
            e.preventDefault();
            // console.log('Remove: ' + contentEditorUnitId);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/ContentEditorWidget/editor/removeContentEditorUnit',
                'data': {
                    'contentEditorUnitId': contentEditorUnitId
                },
                'async': true,
                'success': function(response) {
                    ContentEditorToolbar_<?php echo $contentEditorId; ?>.reload(false);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        editContentEditorUnit: function(e, contentEditorUnitId) {
            e.preventDefault();
            $('#editorModal').modal('show');
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/ContentEditorWidget/editor/editContentEditorUnit',
                'data': {
                    'id': contentEditorUnitId
                },
                'async': true,
                'success': function(response) {
                    $('#editorModalLabel').html(response.data.label);
                    $('#editorModalBody').html(response.view);
                    // ContentEditorToolbar_<?php echo $contentEditorId; ?>.reload(false);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        updateContentEditorUnitCasePosition: function(contentEditorUnitHtmlId, verticalPositioningDirection, verticalPosition, horizontalPositioningDirection, horizontalPosition) {

            // console.log('updateContentEditorUnitCasePosition');return;

            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/ContentEditorWidget/editor/updateContentEditorUnitCasePosition',
                'data': {
                    'contentEditorUnitHtmlId': contentEditorUnitHtmlId,
                    'verticalPosition': verticalPosition,
                    'horizontalPosition': horizontalPosition
                },
                'async': true,
                'success': function(response) {
                    console.log('updateContentEditorUnitCasePosition');
                    console.log(response.data);
                    if (response.data.saved == true) {
                        if (response.data.success == true) {
                            Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('position.saved'); ?>');
                        } else {
                            Structure.throwErrorToast('<?php echo trans('system.message'); ?>', '<?php echo trans('position.save.failed'); ?>');
                        }
                        ContentEditorToolbar_<?php echo $contentEditorId; ?>.reload(false);
                    }
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        }
    };

    var ContentEditorSorter_<?php echo $contentEditorId; ?> = {
        sortContentEditorUnitCases: function() {
            let contentEditorUnitCaseIds = [];
            $('.unitCase-sorting-item-<?php echo $contentEditorId; ?>').each(function() {
                contentEditorUnitCaseIds.push($(this).attr('data-id'));
            });
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/ContentEditorWidget/editor/sortContentEditorUnitCases',
                'data': {
                    'contentEditorUnitCaseIds': contentEditorUnitCaseIds
                },
                'async': true,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    // console.log(response);

                    if (response.data.success == true) {
                        Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('elements.order.changed'); ?>');
                    } else {
                        Structure.throwErrorToast('<?php echo trans('system.message'); ?>', '<?php echo trans('elements.order.save.failed'); ?>');
                    }

                    ContentEditorToolbar_<?php echo $contentEditorId; ?>.reload(false);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        sortContentEditorUnits: function(unitCaseId) {
            let contentEditorUnitIds = [];
            $('.unit-sorting-item-' + unitCaseId).each(function() {
                contentEditorUnitIds.push($(this).attr('data-id'));
            });
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/ContentEditorWidget/editor/sortContentEditorUnits',
                'data': {
                    'contentEditorUnitIds': contentEditorUnitIds
                },
                'async': true,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    // console.log(response);

                    if (response.data.success == true) {
                        Structure.throwToast('<?php echo trans('system.message'); ?>', '<?php echo trans('elements.order.changed'); ?>');
                    } else {
                        Structure.throwErrorToast('<?php echo trans('system.message'); ?>', '<?php echo trans('elements.order.save.failed'); ?>');
                    }

                    ContentEditorToolbar_<?php echo $contentEditorId; ?>.reload(false);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        }
    };

    $('document').ready(function() {
        $('body').off('dblclick', '.contentViewer-unit-container');
        $('body').on('dblclick', '.contentViewer-unit-container', function(e) {
            console.log();
            e.preventDefault();
            let contentEditorUnitId = $(this).attr('data-id');
            ContentEditorToolbar_<?php echo $contentEditorId; ?>.editContentEditorUnit(e, contentEditorUnitId);
        });

        $('#contentEditorBoard_toolbar_shadow').off('change');
        $('#contentEditorBoard_toolbar_shadow').on('change', function() {
            $('#contentViewer_container_<?php echo $contentEditorId; ?>').removeClass('contentEditor-shadow-none');
            $('#contentViewer_container_<?php echo $contentEditorId; ?>').removeClass('contentEditor-shadow-type1');
            $('#contentViewer_container_<?php echo $contentEditorId; ?>').removeClass('contentEditor-shadow-type2');
            $('#contentViewer_container_<?php echo $contentEditorId; ?>').removeClass('contentEditor-shadow-type3');
            $('#contentViewer_container_<?php echo $contentEditorId; ?>').removeClass('contentEditor-shadow-type4');
            $('#contentViewer_container_<?php echo $contentEditorId; ?>').addClass('contentEditor-shadow-' + $(this).val());
        });

        $('body').off('change', '.contentEditorToolbar-inputField-<?php echo $contentEditorId; ?>');
        $('body').on('change', '.contentEditorToolbar-inputField-<?php echo $contentEditorId; ?>', function() {
            ContentEditorToolbar_<?php echo $contentEditorId; ?>.reload(true);
        });

        $('body').off('change', '#contentEditorBoard_toolbar_contentEditorBackgroundImage_<?php echo $contentEditorId; ?>');
        $('body').on('change', '#contentEditorBoard_toolbar_contentEditorBackgroundImage_<?php echo $contentEditorId; ?>', function(e) {
            ContentEditorToolbar_<?php echo $contentEditorId; ?>.upload(this);
        });

        // $('body').off('dblclick', '.contentViewer-unit');
        // $('body').on('dblclick', '.contentViewer-unit', function(e) {

        // });

        $('body').off('dblclick mouseover mousedown', '.contentViewer-unit-draggable');
        $('body').on('dblclick mouseover mousedown', '.contentViewer-unit-draggable', function(e) {

            if (!this || typeof(EditorDragger_<?php echo $contentEditorId; ?>) !== 'object' || EditorDragger_<?php echo $contentEditorId; ?> === null) {
                return;
            }
            // console.log(e.type);
            // e.stopImmediatePropagation();
            // if (e.type == 'dblclick') {
            //     e.stopImmediatePropagation();
            //     e.stopPropagation();
            //     e.preventDefault();
            //     // let contentEditorUnitId = $(this).attr('data-id');
            //     // ContentEditorToolbar_<?php echo $contentEditorId; ?>.editContentEditorUnit(e, contentEditorUnitId);
            //     return;
            // }

            if (e.type == 'mousedown') {
                // if ($('body').width() < <?php echo ResponsivePageService::CONTENT_EDITOR_WORKS_AT_MIN_WIDTH; ?>) {
                //     Structure.throwErrorToast('<?php echo trans('system.message'); ?>', '<?php echo trans('drag.and.replace.function.only.works.on.screen.width.greater.than.small.tablet'); ?>');
                //     return ;
                // }


                let contentEditorUnitHtmlId = $(this).attr('id');
                let positionObject = $(this).position();

                if ($(this).hasClass('contentViewer-unit-horizontalPositioningDirection-right')) {
                    $(this).css('bottom', '');
                    $(this).css('top', positionObject.top + 'px');
                }

                if ($(this).hasClass('contentViewer-unit-horizontalPositioningDirection-right')) {
                    $(this).css('right', '');
                    $(this).css('left', positionObject.left + 'px');
                }
            }

            EditorDragger_<?php echo $contentEditorId; ?>.dragElement(this);

            // if (this && typeof(EditorDragger_<?php echo $contentEditorId; ?>) === 'object' && EditorDragger_<?php echo $contentEditorId; ?> !== null) {
            //     EditorDragger_<?php echo $contentEditorId; ?>.dragElement(this);
            // }
        });

        $('body').off('mouseover, mouseup', '.contentViewer-unit-draggable');
        $('body').on('mouseover, mouseup', '.contentViewer-unit-draggable', function() {
            // if ($('body').width() < <?php echo ResponsivePageService::CONTENT_EDITOR_WORKS_AT_MIN_WIDTH; ?>) {
            //     return ;
            // }

            if (!this || typeof(EditorDragger_<?php echo $contentEditorId; ?>) !== 'object' || EditorDragger_<?php echo $contentEditorId; ?> === null) {
                return;
            }

            let contentEditorUnitHtmlId = $(this).attr('id');
            let positionObject = $(this).position();

            let horizontalPositioningDirection = null;
            let horizontalPosition = 0;
            let updateHorizontalPosition = false;

            let verticalPositioningDirection = null;
            let verticalPosition = 0;
            let updateVerticalPosition = false;

            if ($(this).hasClass('contentViewer-unit-verticalPositioningDirection-bottom')) {
                $(this).css('bottom', '');
                let fullHeight = $('#contentViewer_container_<?php echo $contentEditorId; ?>').height();
                let thisElementHeight = $(this).height();
                verticalPositioningDirection = 'bottom';
                verticalPosition = fullHeight - thisElementHeight - positionObject.top;
                updateVerticalPosition = true;

                $(this).css('top', '');
                $(this).css('bottom', verticalPosition + 'px');

            } else if($(this).hasClass('contentViewer-unit-verticalPositioningDirection-top')) {
                verticalPositioningDirection = 'top';
                verticalPosition = positionObject.top;
                updateVerticalPosition = true;
            }

            if ($(this).hasClass('contentViewer-unit-horizontalPositioningDirection-right')) {
                $(this).css('right', '');
                let fullWidth = $('#contentViewer_container_<?php echo $contentEditorId; ?>').width();
                let thisElementWidth = $(this).width();
                horizontalPositioningDirection = 'right';
                horizontalPosition = fullWidth - thisElementWidth - positionObject.left;
                updateHorizontalPosition = true;

                $(this).css('left', '');
                $(this).css('right', horizontalPosition + 'px');
            } else if($(this).hasClass('contentViewer-unit-horizontalPositioningDirection-left')) {
                horizontalPositioningDirection = 'left';
                horizontalPosition = positionObject.left;
                updateHorizontalPosition = true;
            }

            ContentEditorToolbar_<?php echo $contentEditorId; ?>.updateContentEditorUnitCasePosition(contentEditorUnitHtmlId, verticalPositioningDirection, verticalPosition, horizontalPositioningDirection, horizontalPosition);

            // if (this && typeof(EditorDragger_<?php echo $contentEditorId; ?>) === 'object' && EditorDragger_<?php echo $contentEditorId; ?> !== null) {
            //     ContentEditorToolbar_<?php echo $contentEditorId; ?>.updateContentEditorUnitCasePosition(contentEditorUnitHtmlId, verticalPositioningDirection, verticalPosition, horizontalPositioningDirection, horizontalPosition);
            // }
        });
    });
</script>
</div>
