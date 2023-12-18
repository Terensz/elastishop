<form name="AppearancePackage_openGraph_form" id="AppearancePackage_openGraph_form" action="" method="POST">
<?php
$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#widgetContainer-mainContent');
$formView->add('text')->setPropertyReference('title')->setLabel(trans('title'));
$formView->add('textarea')->setPropertyReference('description')->setLabel(trans('description'));
// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
// $formView->add('text')->setPropertyReference('postalAddress')->setLabel(trans('postal.address'));
// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('send'));
$formView->setFormMethodPath('/admin/openGraph/widget');
$formView->displayForm(false, false)->displayScripts();

// dump($_COOKIE);
?>
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <button name="AppearancePackage_openGraph_submit" id="AppearancePackage_openGraph_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="AdminOpenGraph.loadForm(true);" value=""><?php echo trans('save'); ?></button>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="AppearancePackage_openGraph_form-message" style="padding-top:4px;"></div>
    </div>
</div>
<?php 
if ($imageSrc):
?>
<a class="triggerModal" href="" onclick="AdminOpenGraph.deleteImage(event);"><?php echo trans('delete.image'); ?></a>
<img src="<?php echo $imageSrc; ?>" style="width: 100%; margin-left: auto; margin-right: auto;">
<?php 
else:
?>
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <div class="form-group formLabel">
                <label for="AppearancePackage_openGraph_image">
                    <b><?php echo trans('image'); ?></b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <div class="custom-file mt-3 mb-3">
                    <input type="file" class="custom-file-input" id="AppearancePackage_openGraph_image" name="AppearancePackage_openGraph_image">
                    <label class="custom-file-label" for="customFile"><?php echo trans('upload.image'); ?></label>
                </div>
            </div>
        </div>
    </div>
<?php 
endif;
?>