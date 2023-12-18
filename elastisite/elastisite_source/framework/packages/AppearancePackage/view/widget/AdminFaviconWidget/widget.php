<?php
// dump($currentFaviconName);exit;
include('framework/packages/ToolPackage/view/upload/js.php');
// dump(get_defined_vars());exit;
?>
<script src="/public_folder/plugin/cropper/cropper.js"></script>
<style>
#faviconImage {
max-width: 100%; /* This rule is very important, please do not ignore this! */
}
.faviconPreview {
    max-width: 100px;
    margin-bottom: 20px;
}
</style>

<link rel="stylesheet" href="/public_folder/plugin/cropper/cropper.css">

<div class="card">
    <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('meaning.of.words'); ?></h6>
        </div>
    </div>
    <div class="card-body">
        <span>
        <?php echo '<b>'.trans('favicon').'</b>: '.trans('whatis.favicon'); ?>
        </span>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0"><?php echo trans('handle.favicon'); ?></h6>
        </div>
    </div>
    <div class="card-body">
        <form name="AppearancePackage_adminFavicon_form" id="AppearancePackage_adminFavicon_form" method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <button id="AppearancePackage_adminFavicon_initUpload" name="AppearancePackage_adminFavicon_initUpload"
                    onclick="AdminFavicon.initUpload()"
                    type="button" class="btn btn-secondary"><?php echo $currentFaviconName ? trans('change.favicon') : trans('upload.favicon'); ?>
                </button>
            </div>
        <?php 
        if ($currentFaviconName):
            echo trans('current.favicon');
        ?>
        <br>
        <img class="faviconPreview" src="/accessory/favicon/<?php echo mt_rand(); ?>">
        <?php 
        else:
            echo '';
        endif;
        ?>
        <?php 
        if ($currentFaviconName):
        ?>
            <div class="form-group">
                <button id="AppearancePackage_adminFavicon_initCrop" name="AppearancePackage_adminFavicon_initCrop"
                    onclick="AdminFavicon.initCrop()"
                    type="button" class="btn btn-secondary"><?php echo trans('crop.favicon'); ?>
                </button>
            </div>
            <!-- <div class="form-group">
                <button id="AppearancePackage_cropFavicon_refresh" name="AppearancePackage_cropFavicon_refresh"
                    type="button" class="btn btn-primary"><?php echo trans('refresh.favicon'); ?>
                </button>
            </div> -->
        <?php 
        endif;
        ?>
        </form>
    </div>
</div>

<script>
// var Favicon = {
// 	show: function() {
// 		$('#favicon').attr('href', '/accessory/favicon/' + Math.random());
// 	},
// 	reload: function() {
// 		Favicon.show();
// 		// const delay = ms => new Promise(res => setTimeout(res, ms));
// 		// const delayFaviconReload = async () => {
// 		// 	await delay(3000);
// 		// 	Favicon.show();
// 		// };
// 	}
// };
var AdminFavicon = {
    initUpload: function() {
        $('#editorModalLabel').html('<?php echo trans('change.favicon'); ?>');
        $('#editorModalBody').html('');
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/favicon/uploadModal',
            'data': {},
            'async': false,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#editorModalBody').html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
        $('#editorModal').modal('show');
        // BackgroundEdit.call(null);
    },
    // upload: function() {

    // },
    initCrop: function() {
        $('#editorModalLabel').html('<?php echo trans('crop.favicon'); ?>');
        $('#editorModalBody').html('');
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/favicon/cropModal',
            'data': {},
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#editorModalBody').html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
        $('#editorModal').modal('show');
        // BackgroundEdit.call(null);
    },
    crop: function() {
        console.log('AdminFavicon.crop()');
        AdminFavicon.uploadCroppedCanvas();
        AdminFaviconWidget.call();
        $('#editorModal').modal('hide');
    },
    changeFavicon: function(faviconPath) {
        var link = document.querySelector("link[rel*='icon']") || document.createElement('link');
        link.type = 'image/x-icon';
        link.rel = 'shortcut icon';
        link.href = '<?php echo $container->getUrl()->getHttpDomain(); ?>/' + faviconPath;
        document.getElementsByTagName('head')[0].appendChild(link);
        console.log('Ajax callback');
        AdminFaviconWidget.call();
        AdminFavicon.refreshPreview();
    },
    uploadCroppedCanvas: function() {
        console.log('AdminFavicon.uploadCroppedCanvas()');
        var canvas;
        var croppedCanvas = cropper.getCroppedCanvas();
        canvas = cropper.getCroppedCanvas();
        canvas.toBlob(function (blob) {
            var formData = new FormData();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/favicon/uploadCroppedCanvas',
                'data': {'canvas': canvas.toDataURL()},
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    // const delay = ms => new Promise(res => setTimeout(res, ms));
                    // const delayFaviconReload = async () => {
                    //     console.log('delayFaviconReload');
                    //     await delay(3000);
                    //     console.log('delayFaviconReload 2');
                    //     Favicon.reload();
                    // };
                    // delayFaviconReload();
                    // console.log('uploadCroppedCanvas success');
                    AdminFaviconWidget.call();
                    AdminFavicon.refreshPreview();
                    // Structure.call();
                    // Favicon.reload();
                    // var params = AdminFaviconWidget.getParameters();
                    // $(params.responseSelector).html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        });
    },
    refreshPreview: function() {
        $('.faviconPreview').attr('src', '/accessory/favicon/' + Math.random());
        $('#favicon').attr('href', '/accessory/favicon/' + Math.random());
    }
};

$(document).ready(function() {
    $('#editorModalBody').off('change', '#AppearancePackage_uploadFavicon_file');
    $('#editorModalBody').on('change', '#AppearancePackage_uploadFavicon_file', function() {
        // AdminFavicon.upload();
        // console.log('AppearancePackage_uploadFavicon_file!!!!');
    // $('body').on('change', '#AppearancePackage_uploadFavicon_file', function() {
        var url = '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/favicon/upload';
        var file = $(this)[0].files[0];
        var upload = new Upload(file);
        if (file !== undefined) {
            upload.doUpload('AppearancePackage_uploadFavicon_file', url, 'AdminFavicon.changeFavicon', 'faviconPath');
            // AdminFaviconWidget.call();
            $('#editorModal').modal('hide');
        }
    });

    // $('#AppearancePackage_adminFavicon_edit').click(function() {
    //     $('#editorModalBody').html('');
    //     $('#editorModalLabel').html('');
    //     $('#editorModal').modal('show');
    //     BackgroundEdit.call(null);
    // });

    // $('#AppearancePackage_cropFavicon_crop').click(function() {
    //     console.log('crop!!4');
    //     AdminFavicon.uploadCroppedCanvas();
    //     AdminFaviconWidget.call();
    //     // Favicon.reload();
    //     // const delay = ms => new Promise(res => setTimeout(res, ms));
    //     // const delayFaviconReload = async () => {
    //     //     await delay(2000);
    //     //     Favicon.show();
    //     // };
    //     // delayFaviconReload();
    //     // $('#cropperScripts').remove();
    //     // AdminFaviconWidget.call();
    //     // Structure.loadWidget('AdminFaviconWidget');
    //     // AdminFaviconWidget.call();
    // });

    // $('#AppearancePackage_cropFavicon_refresh').click(function() {
    //     // Favicon.show();
    //     AdminFavicon.refreshPreview();
    // });
});
</script>
