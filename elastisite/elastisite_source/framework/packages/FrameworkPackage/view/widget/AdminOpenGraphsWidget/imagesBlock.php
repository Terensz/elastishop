<?php
// dump($currentFaviconName);exit;
include('framework/packages/ToolPackage/view/upload/js.php');
// dump(get_defined_vars());exit;
?>

<style>
    .thumbnail-frame-outer {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkqAcAAIUAgUW0RjgAAAAASUVORK5CYII=');
        color: #fcfcfc;
        vertical-align: middle;
        border-radius: 6px;
        box-shadow: 0 4px 6px #353535;
        width: 155px;
        height: 100px;
        float: left;
        /* margin-left: 6px;
        margin-right: 6px; */
        margin-top: 10px;
        margin-bottom: 10px;
        margin-left: 6px;
        margin-right: 6px;
    }
    .thumbnail-frame-inner {
        background-color: #9b9b9b;
        height: 100%;
        border-radius: 6px;
        padding: 6px;
        /* margin: auto; */
    }
    /* .thumbnail-image {
        width: 100%;
        border-radius: 1px;
        box-shadow: 1px 1px 1px #515151;
        background-position: center top;
        background-size: cover;
        overflow: none;
        margin-bottom: 0px;
        max-height: 90px;
    } */
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0">
                <?php echo trans('gallery'); ?>
            </h6>
        </div>
    </div>
    <div class="card-body">
        <span>
            <form name="FrameworkPackage_openGraphImageHandler_form" id="FrameworkPackage_openGraphImageHandler_form" method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <!-- <label for="FrameworkPackage_openGraphImageHandler_image">
                        <b><?php echo trans('upload.new.image.to.the.gallery'); ?></b>
                    </label> -->
                    <!-- <div class="custom-file mt-3 mb-12" style="padding-top: 0px !important; margin-top: 0px !important;">
                        <input type="file" class="custom-file-input" id="FrameworkPackage_openGraphImageHandler_image" name="FrameworkPackage_openGraphImageHandler_image">
                        <label class="ajaxCallerLink custom-file-label" for="customFile">
                            <?php echo trans('upload.image'); ?>
                        </label>
                    </div> -->

                    <div>
                        <!-- <label for="formFileLg" class="form-label">Large file input example</label> -->
                        <label for="FrameworkPackage_openGraphImageHandler_image">
                            <b><?php echo trans('upload.new.image.to.the.gallery'); ?></b>
                        </label>
                        <input class="form-control form-control-lg" id="FrameworkPackage_openGraphImageHandler_image" name="FrameworkPackage_openGraphImageHandler_image" type="file">
                    </div>
                </div>
            </form>
        </span>
    </div>
    <div class="card-footer">
    <div id="AdminOpenGraphs_imageGallery"></div>
    </div>
</div>

<!-- <div class="widgetWrapper">
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-12">
            <form name="FrameworkPackage_openGraphImageHandler_form" id="FrameworkPackage_openGraphImageHandler_form" method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="FrameworkPackage_openGraphImageHandler_image">
                    <b><?php echo trans('upload.new.image.to.the.gallery'); ?></b>
                    </label>
                    <div class="custom-file mt-3 mb-12" style="padding-top: 0px !important; margin-top: 0px !important;">
                        <input type="file" class="custom-file-input" id="FrameworkPackage_openGraphImageHandler_image" name="FrameworkPackage_openGraphImageHandler_image">
                        <label class="ajaxCallerLink custom-file-label" for="customFile"><?php echo trans('upload.image'); ?></label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> -->

<script>
var OpenGraphImageHandler = {
    unbindImage: function(e) {
        e.preventDefault();
        let id = $('#openGraphEdit_id').html();
        // console.log('unbindImage id: ' + id);
        // console.log('openGraphImageHeaderId: ' + $('#FrameworkPackage_openGraphEdit_openGraphImageHeaderId').val());
        if (id != null && id != '') {
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/unbindImage',
                'data': {
                    'id': id,
                    'openGraphImageHeaderId': $('#FrameworkPackage_openGraphEdit_openGraphImageHeaderId').val()
                    // 'code': $('#editVideo_code').html(),
                    // 'extension': $('#editVideo_extension').html()
                },
                'async': false,
                'success': function(response) {
                    $('#FrameworkPackage_openGraphEdit_imageHeaderId').val('');
                    $('#FrameworkPackage_openGraphEdit_openGraphImageHeaderId').val('');
                    // console.log(response);
                    OpenGraphEdit.loadForm(false, false);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        } else {
            $('#FrameworkPackage_openGraphEdit_openGraphImageHeaderId').val('');
            OpenGraphImageHandler.refreshImageContainer(null);
        }
    },
    deleteGalleryImage: function(e, id) {
        e.preventDefault();
        console.log(id);
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/deleteGalleryImage',
            'data': {
                'id': id,
                // 'code': $('#editVideo_code').html(),
                // 'extension': $('#editVideo_extension').html()
            },
            'async': false,
            'success': function(response) {
                OpenGraphImageHandler.loadGallery();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                // LoadingHandler.stop();
            },
        });
    },
    loadGallery: function() {
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/imageGallery',
            'data': {
                // 'id': $('#editVideo_id').html(),
                // 'code': $('#editVideo_code').html(),
                // 'extension': $('#editVideo_extension').html()
            },
            'async': false,
            'success': function(response) {
                // console.log(response);
                $('#AdminOpenGraphs_imageGallery').html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                // LoadingHandler.stop();
            },
        });
    },
    loadSelectorGallery: function() {
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/selectorGallery',
            'data': {
                // 'id': $('#editVideo_id').html(),
                // 'code': $('#editVideo_code').html(),
                // 'extension': $('#editVideo_extension').html()
            },
            'async': false,
            'success': function(response) {
                // console.log(response);
                $('#AdminOpenGraphs_selectorGallery').html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                // LoadingHandler.stop();
            },
        });
    },
    selectingImage: function(e, imageHeaderId) {
        e.preventDefault();
        console.log('selectingImage!! imageHeaderId: ' + imageHeaderId);
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/selectingImage',
            'data': {
                // 'id': $('#openGraphEdit_id').html(),
                'imageHeaderId': imageHeaderId,
                // 'code': $('#editVideo_code').html(),
                // 'extension': $('#editVideo_extension').html()
            },
            'async': false,
            'success': function(response) {
                // console.log(response);
                // $('#FrameworkPackage_openGraphEdit_imageHeaderId').val(response.imageHeaderId);
                // console.log(response.result);
                if (response.result == true) {
                    // console.log('tru!');
                    $('#FrameworkPackage_openGraphEdit_imageHeaderId').val(imageHeaderId);
                    OpenGraphImageHandler.refreshImageContainer(imageHeaderId);
                    // $('#FrameworkPackage_openGraphEdit_imageHeaderId-validationMessage').html('');
                }
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                // LoadingHandler.stop();
            },
        });
    },
    refreshImageContainer: function(imageHeaderId) {
        LoadingHandler.start();
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/getImageContainer',
            'data': {
                'id': $('#openGraphEdit_id').html(),
                'imageHeaderId': imageHeaderId
            },
            'async': true,
            'success': function(response) {
                LoadingHandler.stop();
                $('#FrameworkPackage_openGraphEdit_imageHeaderId').val(imageHeaderId);
                $('#FrameworkPackage_openGraphEdit_openGraphImageHeaderId').val(response.data.openGraphImageHeaderId);
                $('#FrameworkPackage_openGraphEdit_imageContainer').html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    upload: function(event) {
        var url = '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/openGraph/uploadImage';
        var file = $(event)[0].files[0];
        var upload = new Upload(file);
        if (file !== undefined) {
            upload.doUpload('FrameworkPackage_openGraphImageHandler_image', url, 'OpenGraphImageHandler.uploadCallback', null, {
                'id': $('#openGraphEdit_id').html()
            });
        }
    },
    uploadCallback: function(responseDataJson) {
        LoadingHandler.stop();
        let responseData = JSON.parse(responseDataJson);
        OpenGraphImageHandler.loadGallery();
        if (responseData.success == false) {
            Structure.throwErrorToast('<?php echo trans('error'); ?>', '<?php echo trans('image.upload.error'); ?>');
        }
        // console.log('responseData.uploadResult:', responseData.uploadResult);
    }
};

$('document').ready(function() {
    // console.log('imagesWidget docready');
    OpenGraphImageHandler.loadGallery();
    // $('document').off('change', '#FrameworkPackage_openGraphImageHandler_image');
    // $('document').on('change', '#FrameworkPackage_openGraphImageHandler_image', function(e) {
    //     console.log('img change!');
    //     OpenGraphImageHandler.upload(this);
    // });

    // $('body').off('change', '#FrameworkPackage_openGraphImageHandler_image');
    $('#FrameworkPackage_openGraphImageHandler_image').off('change');
    $('#FrameworkPackage_openGraphImageHandler_image').on('change', function(e) {
        console.log('img change!');
        // console.log('LoadingHandler.start()');
        OpenGraphImageHandler.upload(this);
    });
});
</script>
