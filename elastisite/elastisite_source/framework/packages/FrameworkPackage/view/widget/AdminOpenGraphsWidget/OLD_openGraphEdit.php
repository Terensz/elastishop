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

<div id="openGraphEdit_id" style="display: none;"><?php echo $id; ?></div>
<div id="openGraphEdit_originalCode" style="display: none;"><?php echo $openGraph->getCode(); ?></div>
<div id="openGraphEdit_originalExtension" style="display: none;"><?php echo $openGraph->getExtension(); ?></div>
<div id="openGraphEdit_code" style="display: none;"><?php echo $openGraph->getCode(); ?></div>
<div id="openGraphEdit_extension" style="display: none;"><?php echo $openGraph->getExtension(); ?></div>
<div id="AdminOpenGraphsWidget_formContainer">
</div>
<script>
var OpenGraphEdit = {
    uploadCallback: function(responseDataJson) {
        // AdminOpenGraphsWidget.call(false);
        AdminOpenGraphsGrid.list(true);
        let responseData = JSON.parse(responseDataJson);
        console.log('uploadCallback!');
        console.log('responseData.uploadResult:', responseData.uploadResult);
        if (responseData.uploadResult.success === false) {
            $('#FrameworkPackage_openGraphEdit_file-validationMessage').html(responseData.uploadResult.errorMessage);
        } else {
            // console.log('Filename: ' + responseData.uploadResult.data.fileName);
            $('#openGraphEdit_id').html(responseData.id);
            $('#openGraphEdit_code').html(responseData.uploadResult.data.fileName);
            $('#openGraphEdit_extension').html(responseData.uploadResult.data.extension);
            OpenGraphEdit.loadForm(false, false);
            OpenGraphEdit.showOrHideSubmit();
        }
    },
    submitForm: function() {
        OpenGraphEdit.loadForm(true, true);
    },
    loadForm: function(submitted, closeModalIfValid) {
        console.log('loadForm!', submitted);
        var form = $('#FrameworkPackage_openGraphEdit_form');
        var formData = form.serialize();
        var additionalData = {
            'id': $('#openGraphEdit_id').html(),
            'code': $('#openGraphEdit_code').html(),
            'extension': $('#openGraphEdit_extension').html(),
            'submitted': submitted
        };
        ajaxData = formData + '&' + $.param(additionalData);
        console.log(ajaxData);
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/editForm',
            'data': ajaxData,
            'async': false,
            'success': function(response) {
                console.log(response.data);
                $('#AdminOpenGraphsWidget_formContainer').html(response.view);
                new nicEditor({buttonList :['fontSize','bold','italic','underline','ol','ul']}).panelInstance('FrameworkPackage_openGraphEdit_description', {hasPanel : true, maxHeight: 200});
                $('.nicEdit-main').on('blur', function() {
                    let content = nicEditors.findEditor("FrameworkPackage_openGraphEdit_description").getContent();
                    $('#FrameworkPackage_openGraphEdit_description').html(content);
                });
                console.log('ajax response',response);
                // Structure.call('<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraphs');
                // AdminOpenGraphsWidget.call(false);
                if (submitted) {
                    AdminOpenGraphsGrid.list(true);
                }
                if (response.data.submitted == true && response.data.formIsValid == true && closeModalIfValid == true) {
                    OpenGraphEdit.saveSuccessful();
                }
            }
        });
    },
    unbindImage: function(e) {
        console.log('unbindImage!');
        e.preventDefault();
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/deleteImage',
            'data': {
                'id': $('#openGraphEdit_id').html(),
                // 'code': $('#openGraphEdit_code').html(),
                // 'extension': $('#openGraphEdit_extension').html()
            },
            'async': false,
            'success': function(response) {
                if (response.data.success == true) {
                    $('#openGraphEdit_code').html('');
                    $('#openGraphEdit_extension').html('');
                    OpenGraphEdit.loadForm(true, false);
                    OpenGraphEdit.showOrHideSubmit();
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
    // bindFile: function() {
    //     console.log('Bindfile', $('#openGraphEdit_originalCode').html());
    //     if (typeof($('#openGraphEdit_originalCode').html()) == 'undefined') {
    //         return;
    //     }
    //     if ($('#openGraphEdit_originalCode').html() != '' && $('#openGraphEdit_originalExtension').html() != '') {
    //         $.ajax({
    //             'type' : 'POST',
    //             'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/bindImage',
    //             'data': {
    //                 'id': $('#openGraphEdit_id').html(),
    //                 'code': $('#openGraphEdit_originalCode').html(),
    //                 'extension': $('#openGraphEdit_originalExtension').html()
    //             },
    //             'async': false,
    //             'success': function(response) {
    //                 // if (response.data.success == true) {
    //                 //     if (calledOnModalClose == false) {
    //                 //         OpenGraphEdit.loadForm(true, false);
    //                 //         OpenGraphEdit.showOrHideSubmit();
    //                 //         $('#openGraphEdit_code').html('');
    //                 //         $('#openGraphEdit_extension').html('');
    //                 //     }
    //                 // } else {

    //                 // }
    //             },
    //             'error': function(request, error) {
    //                 console.log(request);
    //                 console.log(" Can't do because: " + error);
    //                 // LoadingHandler.stop();
    //             },
    //         });
    //     }
    // },
    showOrHideSubmit: function() {
        console.log('showOrHideSubmit!!!');
        if ($('#FrameworkPackage_openGraphEdit_title').val() == '') {
            // console.log('FrameworkPackage_openGraphEdit_title val: ' + $('#FrameworkPackage_openGraphEdit_title').val());
            $('#FrameworkPackage_openGraphEdit_submit').hide();
        } else {
            // console.log('FrameworkPackage_openGraphEdit_title val: ' + $('#FrameworkPackage_openGraphEdit_title').val());
            $('#FrameworkPackage_openGraphEdit_submit').show();
        }
    },
    saveSuccessful: function() {
        Structure.call('<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraphs');
        $('#openGraphEdit_id').html('');
        $('#openGraphEdit_originalCode').html('');
        $('#openGraphEdit_originalExtension').html('');
        $('#editorModal').modal('hide');
    }
};

$(document).ready(function() {
    OpenGraphEdit.loadForm(false, false);

    $('textarea').keypress(function(e) {
        if (e.which == 13) {
            e.stopPropagation();
        }
    });

    OpenGraphEdit.showOrHideSubmit();
    $('body').off('change', '#FrameworkPackage_openGraphEdit_title');
    $('body').off('blur', '#FrameworkPackage_openGraphEdit_title');
    $('body').off('keyup', '#FrameworkPackage_openGraphEdit_title');
    $('body').on('change blur keyup', '#FrameworkPackage_openGraphEdit_title', function(e) {
        OpenGraphEdit.showOrHideSubmit();
    });

    $('body').off('change', '#FrameworkPackage_openGraphEdit_file');
    $('body').on('change', '#FrameworkPackage_openGraphEdit_file', function(e) {
        console.log('FrameworkPackage_openGraphEdit_file!!');
        var url = '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/uploadImage';
        var file = $(this)[0].files[0];
        var upload = new Upload(file);
        if (file !== undefined) {
            upload.doUpload('FrameworkPackage_openGraphEdit_file', url, 'OpenGraphEdit.uploadCallback', null, {
                'id': $('#openGraphEdit_id').html()
            });
        }
        // AtpmFileContainer.call();
    });

    $("#editorModal").unbind("hidden.bs.modal");
    $("#editorModal").on('hidden.bs.modal', function(e){
        // OpenGraphEdit.bindFile();
        // Structure.call('<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraphs');
        // AdminOpenGraphsGrid.list(true);

    });
});
</script>
