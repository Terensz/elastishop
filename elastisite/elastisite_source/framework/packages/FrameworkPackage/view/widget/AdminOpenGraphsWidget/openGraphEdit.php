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
<div id="AdminOpenGraphsWidget_formContainer">
</div>
<script>
var OpenGraphEdit = {
    submitForm: function() {
        OpenGraphEdit.loadForm(true, true);
    },
    loadForm: function(submitted, closeModalIfValid) {
        console.log('loadForm!', submitted);
        var form = $('#FrameworkPackage_openGraphEdit_form');
        var formData = form.serialize();
        var additionalData = {
            'id': $('#openGraphEdit_id').html(),
            'imageHeaderId': $('#FrameworkPackage_openGraphEdit_imageHeaderId').val(),
            'openGraphImageHeaderId': $('#FrameworkPackage_openGraphEdit_openGraphImageHeaderId').val(),
            // 'code': $('#openGraphEdit_code').html(),
            // 'extension': $('#openGraphEdit_extension').html(),
            'submitted': submitted
        };
        ajaxData = formData + '&' + $.param(additionalData);
        // console.log(ajaxData);
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/editForm',
            'data': ajaxData,
            'async': false,
            'success': function(response) {
                LoadingHandler.stop();
                console.log(response.data);
                $('#editorModalLabel').html(response.data.label);
                $('#openGraphEdit_id').html(response.data.id);
                $('#FrameworkPackage_openGraphEdit_imageHeaderId').val(response.data.openGraphImage);
                $('#AdminOpenGraphsWidget_formContainer').html(response.view);
                // console.log('ajax response',response);
                if (submitted) {
                    AdminOpenGraphsGrid.list(true);
                }
                if (response.data.submitted == true && response.data.formIsValid == true && closeModalIfValid == true) {
                    OpenGraphEdit.saveSuccessful();
                }
            }
        });
    },
    showOrHideSubmit: function() {
        console.log('showOrHideSubmit!!!');
        // if ($('#FrameworkPackage_openGraphEdit_title').val() == '') {
        //     // console.log('FrameworkPackage_openGraphEdit_title val: ' + $('#FrameworkPackage_openGraphEdit_title').val());
        //     $('#FrameworkPackage_openGraphEdit_submit').hide();
        // } else {
        //     // console.log('FrameworkPackage_openGraphEdit_title val: ' + $('#FrameworkPackage_openGraphEdit_title').val());
        //     $('#FrameworkPackage_openGraphEdit_submit').show();
        // }
    },
    saveSuccessful: function() {
        Structure.call('<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraphs');
        $('#openGraphEdit_id').html('');
        // $('#openGraphEdit_originalCode').html('');
        // $('#openGraphEdit_originalExtension').html('');
        $('#editorModal').modal('hide');
    }
};

$(document).ready(function() {
    OpenGraphEdit.loadForm(false, false);

    // $('body').on('click', '#confirmModalConfirm', function() {
    //     console.log('confirmModalConfirm click!!');
    //     OpenGraphImageHandler.loadGallery();
    // });

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

    // $('body').off('change', '#FrameworkPackage_openGraphEdit_file');
    // $('body').on('change', '#FrameworkPackage_openGraphEdit_file', function(e) {
    //     console.log('FrameworkPackage_openGraphEdit_file!!');
    //     var url = '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/uploadImage';
    //     var file = $(this)[0].files[0];
    //     var upload = new Upload(file);
    //     if (file !== undefined) {
    //         upload.doUpload('FrameworkPackage_openGraphEdit_file', url, 'OpenGraphEdit.uploadCallback', null, {
    //             'id': $('#openGraphEdit_id').html()
    //         });
    //     }
    //     // AtpmFileContainer.call();
    // });

    $("#editorModal").unbind("hidden.bs.modal");
    $("#editorModal").on('hidden.bs.modal', function(e){
        // OpenGraphEdit.bindFile();
        // Structure.call('<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraphs');
        // AdminOpenGraphsGrid.list(true);

    });
});
</script>
