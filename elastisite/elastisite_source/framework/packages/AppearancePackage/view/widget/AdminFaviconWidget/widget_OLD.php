<?php
include('framework/packages/ToolPackage/view/upload/js.php');
?>
<script src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/cropper/cropper.js"></script>
<style>
#faviconImage {
  max-width: 100%; /* This rule is very important, please do not ignore this! */
}
</style>
<link rel="stylesheet" href="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/cropper/cropper.css">
<?php
if ($currentFaviconName) {
    if ($width > 72 || $height > 72) {
?>

<img id="faviconImage" width="100" src="<?php echo $container->getUrl()->getHttpDomain(); ?>/favicon">
<form name="AppearancePackage_cropFavicon_form" id="AppearancePackage_cropFavicon_form" method="POST" action="" enctype="multipart/form-data">
    <div class="form-group">
        <button id="AppearancePackage_cropFavicon_crop" name="AppearancePackage_cropFavicon_crop"
            type="button" class="btn btn-primary"><?php echo trans('crop.image'); ?></button>
    </div>
</form>
<div id="cropperScripts">
    <script>
    var $image = $('#faviconImage');
    $image.cropper({
        data: {'width': 72, 'height': 72},
        crop: function(event) {}
    });
    var cropper = $image.data('cropper');
    </script>
</div>
<?php
    } else {
?>
<img width="100" src="<?php echo $container->getUrl()->getHttpDomain(); ?>/favicon">
<?php
    }
}
?>
<br><br>
<?php echo trans('change.favicon'); ?>
<form name="AppearancePackage_adminFavicon_form" id="AppearancePackage_adminFavicon_form" method="POST" action="" enctype="multipart/form-data">
    <div class="form-group">
        <div class="custom-file mt-3 mb-3">
          <input type="file" class="custom-file-input" id="AppearancePackage_adminFavicon_file" name="AppearancePackage_adminFavicon_file">
          <label class="custom-file-label" for="customFile"><?php echo trans('upload.image'); ?></label>
        </div>
    </div>
</form>
<script>
var AdminFavicon = {
     changeFavicon: function(faviconPath) {
        var link = document.querySelector("link[rel*='icon']") || document.createElement('link');
        link.type = 'image/x-icon';
        link.rel = 'shortcut icon';
        link.href = '<?php echo $container->getUrl()->getHttpDomain(); ?>/' + faviconPath;
        document.getElementsByTagName('head')[0].appendChild(link);
    },
    uploadCroppedCanvas: function() {
        var canvas;
        var croppedCanvas = cropper.getCroppedCanvas();
        canvas = cropper.getCroppedCanvas();
        canvas.toBlob(function (blob) {
            var formData = new FormData();
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/favicon/uploadCroppedCanvas',
                'data': {'canvas': canvas.toDataURL()},
                'async': true,
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
    }
};

$(document).ready(function() {
    $('#AppearancePackage_adminFavicon_file').change(function() {
    // $('body').on('change', '#AppearancePackage_adminFavicon_file', function() {
        var url = '<?php echo $container->getUrl()->getHttpDomain(); ?>/admin/favicon/upload';
        var file = $(this)[0].files[0];
        var upload = new Upload(file);
        if (file !== undefined) {
            upload.doUpload('AppearancePackage_adminFavicon_file', url, 'AdminFavicon.changeFavicon', 'faviconPath');
            AdminFaviconWidget.call();
        }
    });

    $('#AppearancePackage_cropFavicon_crop').click(function() {
        console.log('crop!!4');
        AdminFavicon.uploadCroppedCanvas();
        AdminFaviconWidget.call();
        // Favicon.reload();
        const delay = ms => new Promise(res => setTimeout(res, ms));
        const delayFaviconReload = async () => {
            await delay(2000);
            Favicon.show();
        };
        delayFaviconReload();
        // $('#cropperScripts').remove();
        // AdminFaviconWidget.call();
        // Structure.loadWidget('AdminFaviconWidget');
        // AdminFaviconWidget.call();
    });
});
</script>
