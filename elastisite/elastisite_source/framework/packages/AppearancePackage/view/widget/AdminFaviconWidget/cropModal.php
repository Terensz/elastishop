<img id="faviconImage" width="400" src="/accessory/favicon">
<form name="AppearancePackage_cropFavicon_form" id="AppearancePackage_cropFavicon_form" method="POST" action="" enctype="multipart/form-data">
<div class="row">
    <div class="form-group">
        <button id="AppearancePackage_cropFavicon_crop" name="AppearancePackage_cropFavicon_crop"
            onclick="AdminFavicon.crop()"
            type="button" class="btn btn-primary"><?php echo trans('crop.image'); ?></button>
    </div>
</div>
</form>

<script>
    var $image = $('#faviconImage');
    $image.cropper({
        data: {'width': 72, 'height': 72},
        crop: function(event) {}
    });
    var cropper = $image.data('cropper');
</script>