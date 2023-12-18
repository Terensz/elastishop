<style>
.nicEdit-main {
    border: 0px;
    padding: 0px;
    margin: 0px;
    outline:none;
    user-select: all;
    line-height: normal;
}
</style>

<div id="editVideo_id" style="display: none;"><?php echo $id; ?></div>
<div id="editVideo_originalCode" style="display: none;"><?php echo $video->getCode(); ?></div>
<div id="editVideo_originalExtension" style="display: none;"><?php echo $video->getExtension(); ?></div>
<div id="editVideo_code" style="display: none;"><?php echo $video->getCode(); ?></div>
<div id="editVideo_extension" style="display: none;"><?php echo $video->getExtension(); ?></div>
<div id="AdminVideosWidget_formContainer">
<?php 
// include('framework/packages/VideoPackage/view/widget/AdminVideosWidget/editVideoForm.php');
?>
</div>
<script>
var EditVideo = {
    uploadCallback: function(responseDataJson) {
        let responseData = JSON.parse(responseDataJson);
        console.log('uploadCallback!');
        console.log('responseData.uploadResult:', responseData.uploadResult);
        if (responseData.uploadResult.success === false) {
            $('#VideoPackage_editVideo_file-validationMessage').html(responseData.uploadResult.errorMessage);
        } else {
            // console.log('Filename: ' + responseData.uploadResult.data.fileName);
            $('#editVideo_code').html(responseData.uploadResult.data.fileName);
            $('#editVideo_extension').html(responseData.uploadResult.data.extension);
            EditVideo.loadForm(true, false);
            EditVideo.showOrHideSubmit();
        }
    },
    submitForm: function() {
        EditVideo.loadForm(true, true);
    },
    loadForm: function(submitted, closeModalIfValid) {
        console.log('loadForm!', submitted);
        var form = $('#VideoPackage_editVideo_form');
        var formData = form.serialize();
        var additionalData = {
            'id': $('#editVideo_id').html(),
            'code': $('#editVideo_code').html(),
            'extension': $('#editVideo_extension').html(),
            'submitted': submitted
        };
        ajaxData = formData + '&' + $.param(additionalData);
        console.log(ajaxData);
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/video/editForm',
            'data': ajaxData,
            'async': false,
            'success': function(response) {
                console.log(response.data);
                $('#AdminVideosWidget_formContainer').html(response.view);
                new nicEditor({buttonList :['fontSize','bold','italic','underline','ol','ul']}).panelInstance('VideoPackage_editVideo_description', {hasPanel : true, maxHeight: 200});
                $('.nicEdit-main').on('blur', function() {
                    let content = nicEditors.findEditor("VideoPackage_editVideo_description").getContent();
                    $('#VideoPackage_editVideo_description').html(content);
                });

                if (response.data.submitted == true && response.data.formIsValid == true && closeModalIfValid == true) {
                    EditVideo.saveSuccessful();
                }
            }
        });
    },
    unbindFile: function(e) {
        console.log('unbindFile!');
        e.preventDefault();
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/video/unbindFile',
            'data': {
                'id': $('#editVideo_id').html(),
                // 'code': $('#editVideo_code').html(),
                // 'extension': $('#editVideo_extension').html()
            },
            'async': false,
            'success': function(response) {
                if (response.data.success == true) {
                    $('#editVideo_code').html('');
                    $('#editVideo_extension').html('');
                    EditVideo.loadForm(true, false);
                    EditVideo.showOrHideSubmit();
                } else {
                    
                }
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                // LoadingHandler.stop();
            },
        });
    },
    bindFile: function() {
        console.log('Bindfile', $('#editVideo_originalCode').html());
        if (typeof($('#editVideo_originalCode').html()) == 'undefined') {
            return;
        }
        if ($('#editVideo_originalCode').html() != '' && $('#editVideo_originalExtension').html() != '') {
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/video/bindFile',
                'data': {
                    'id': $('#editVideo_id').html(),
                    'code': $('#editVideo_originalCode').html(),
                    'extension': $('#editVideo_originalExtension').html()
                },
                'async': false,
                'success': function(response) {
                    // if (response.data.success == true) {
                    //     if (calledOnModalClose == false) {
                    //         EditVideo.loadForm(true, false);
                    //         EditVideo.showOrHideSubmit();
                    //         $('#editVideo_code').html('');
                    //         $('#editVideo_extension').html('');
                    //     }
                    // } else {
                        
                    // }
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        }
    },
    showOrHideSubmit: function() {
        console.log('showOrHideSubmit!!!');
        if ($('#VideoPackage_editVideo_title').val() == '') {
            // console.log('VideoPackage_editVideo_title val: ' + $('#VideoPackage_editVideo_title').val());
            $('#VideoPackage_editVideo_submit').hide();
        } else {
            // console.log('VideoPackage_editVideo_title val: ' + $('#VideoPackage_editVideo_title').val());
            $('#VideoPackage_editVideo_submit').show();     
        }
    },
    saveSuccessful: function() {
        Structure.call('<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/videos');
        $('#editVideo_originalCode').html('');
        $('#editVideo_originalExtension').html('');
        $('#editorModal').modal('hide');
    }
};

$(document).ready(function() {
    EditVideo.loadForm(false, false);

    $('textarea').keypress(function(e) {
        if (e.which == 13) {
            e.stopPropagation();
        }
    });

    EditVideo.showOrHideSubmit();
    $('body').off('change', '#VideoPackage_editVideo_title');
    $('body').off('blur', '#VideoPackage_editVideo_title');
    $('body').off('keyup', '#VideoPackage_editVideo_title');
    $('body').on('change blur keyup', '#VideoPackage_editVideo_title', function(e) {
        EditVideo.showOrHideSubmit();
    });

    $('body').off('change', '#VideoPackage_editVideo_file');
    $('body').on('change', '#VideoPackage_editVideo_file', function(e) {
        console.log('VideoPackage_editVideo_file!!');
        var url = '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/video/upload';
        var file = $(this)[0].files[0];
        var upload = new Upload(file);
        if (file !== undefined) {
            upload.doUpload('VideoPackage_editVideo_file', url, 'EditVideo.uploadCallback', null);
        }
        // AtpmFileContainer.call();
    });

    $("#editorModal").unbind("hidden.bs.modal");
    $("#editorModal").on('hidden.bs.modal', function(e){
        EditVideo.bindFile();
    });
});
</script>
