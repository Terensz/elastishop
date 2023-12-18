<?php
if (!App::getContainer()->getUser()->getUserAccount()->getId()) {
    include('accessDenied.php');
    return;
}

include('definitions/pathToBuilder.php');
?>

<script src="/public_folder/plugin/Dropzone/dropzone.min.js"></script>
<link rel="stylesheet" href="/public_folder/plugin/Dropzone/dropzone.min.css" type="text/css" />
<script src="/public_folder/plugin/CKEditor/ckeditor/ckeditor.js"></script>


<?php
include('Navbar.php');
// include('definitions/pathToBuilder.php');
// include($pathToBuilder.'ScaleLister/css/ScaleHeaderStyle.php');
?>

<div class="pc-container">
    <div class="pcoded-content card-container">
        <div id="AscScaleLister_eventActualityList_container"><?php echo $views['eventActualityListView']; ?></div>

        <div id="AscScaleLister_controlPanel_container"><?php echo $views['controlPanelView']; ?></div>

        <div id="AscScaleLister_ownScaleList_container"><?php echo $views['ownScaleListView']; ?></div>

        <div id="AscScaleLister_ownInactiveScaleList_container"><?php echo $views['ownInactiveScaleListView']; ?></div>

        <div id="AscScaleLister_teamScaleList_container"><?php echo $views['teamScaleListView']; ?></div>

        <div id="AscScaleLister_othersScaleList_container"><?php echo $views['othersScaleListView']; ?></div>
    </div>
</div>

<?php include($pathToBuilder.'ImageGallery/ImageGallery.php'); ?>
<?php include($pathToBuilder.'Scripts/AscScaleBuilderScripts.php'); ?>

<script>
    var AscScaleLister = {
        processResponse: function(response, calledBy, onSuccessCallback) {
            if (typeof this[onSuccessCallback] === 'function') {
                this[onSuccessCallback](response);
            }

            if (response.views && typeof(response.views.eventActualityListView) == 'string' && response.views.eventActualityListView != 'null') {
                $('#AscScaleLister_eventActualityList_container').html(response.views.eventActualityListView);
            }
            if (response.views && typeof(response.views.ownScaleListView) == 'string' && response.views.ownScaleListView != 'null') {
                $('#AscScaleLister_ownScaleList_container').html(response.views.ownScaleListView);
            }
            // if (response.views && typeof(response.views.ownInactiveScaleListView) == 'string' && response.views.ownInactiveScaleListView != 'null' && response.views.ownInactiveScaleListView != '') {
            //     $('#AscScaleLister_ownInactiveScaleList_container').html(response.views.ownInactiveScaleListView);
            // }
            if (response.views && typeof(response.views.ownInactiveScaleListView) == 'string' && response.views.ownInactiveScaleListView != 'null') {
                $('#AscScaleLister_ownInactiveScaleList_container').html(response.views.ownInactiveScaleListView);
            }
            if (response.views && typeof(response.views.teamScaleListView) == 'string' && response.views.teamScaleListView != 'null') {
                $('#AscScaleLister_teamScaleList_container').html(response.views.teamScaleListView);
            }
            if (response.views && typeof(response.views.othersScaleListView) == 'string' && response.views.othersScaleListView != 'null') {
                $('#AscScaleLister_othersScaleList_container').html(response.views.othersScaleListView);
            }
            if (response.views && typeof(response.views.controlPanelView) == 'string' && response.views.controlPanelView != 'null') {
                $('#AscScaleLister_controlPanel_container').html(response.views.controlPanelView);
            }
            // if (response.views && typeof(response.views.newView) == 'string' && response.views.newView != 'null' && response.views.newView != '') {
            //     $('#editorModalBody').html(response.views.newView);
            //     $('#editorModal').modal('show');
            // }
            if (response.views && typeof(response.views.editView) == 'string' && response.views.editView != 'null' && response.views.editView != '') {
                $('#editorModalBody').html(response.views.editView);
                $('#editorModal').modal('show');
            }
            if (response.data.label && typeof(response.data.label) == 'string' && response.data.label != 'null' && response.data.label != '') {
                $('#editorModalLabel').html(response.data.label);
            }
            if (response.data.closeModal == 'true') {
                $('#editorModal').modal('hide');
            }
        },
        callAjax: function(calledBy, ajaxUrl, additionalData, onSuccessCallback) {
            let self = this;
            let baseData = {};
            let ajaxData = $.extend({}, baseData, additionalData);
            $.ajax({
                'type' : 'POST',
                'url' : '/' + ajaxUrl,
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    console.log(response);
                    self.processResponse(response);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        // listScales: function() {
        //     AscScaleLister.callAjax('listScales', 'asc/scaleLister/', {})
        // },
        newScale: function() {
            AscScaleLister.callAjax('newScale', 'asc/scaleLister/newScale', {});
        },
        editScale: function(e, id) {
            e.preventDefault();
            // $('#editorModal').modal('show');
            AscScaleLister.callAjax('editScale', 'asc/scaleLister/editScale', {
                'id': id
            });
        },
        editScaleSubmit: function(e, id) {
            e.preventDefault();
            // $('#editorModal').modal('show');
            AscScaleLister.callAjax('editScale', 'asc/scaleLister/editScale', {
                'id': id
            });
        },
        editScaleCallback: function(response) {
            let closeModal = response.data.closeModal;
            AscScaleLister.processResponse(response);
            if (closeModal == 'true') {
                $('#editorModal').modal('hide');
            }
        },
        // loadEditor: function() {

        // },
        initDeleteScale: function(id, title) {
            if (id == undefined || id === null || id === false) {
                return false;
            }
            let rawText = '<?php echo trans('deleting.admin.scale').': <br> <b>[title]</b> <br>'.trans('are.you.sure'); ?>';
            let text = rawText.replace('[title]', title);
            $('#confirmModalBody').html(text);
            $('#confirmModalConfirm').attr('onClick', "AscScaleLister.deleteScaleConfirmed(" + id + ");");
            $('#confirmModal').modal('show');
        },
        deleteScaleConfirmed: function(id) {
            AscScaleLister.callAjax('deleteScale', {
                'id': id
            })
            $('#confirmModal').modal('hide');
        }
    };

    $('document').ready(function() {

    });
</script>