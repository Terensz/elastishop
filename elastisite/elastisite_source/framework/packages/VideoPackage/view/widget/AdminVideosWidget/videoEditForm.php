<form name="VideoPackage_editVideo_form" id="VideoPackage_editVideo_form" method="POST" action="" enctype="multipart/form-data">
<?php
// dump($formBuilder);
// dump($formIsValid);
// dump($form->getEntity());//exit;
include('framework/packages/ToolPackage/view/upload/js.php');

$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
$formView->add('text')->setPropertyReference('title')->setLabel(trans('title'));
$formView->add('textarea')->setPropertyReference('description')->setLabel(trans('description'));
// $formView->add('select')->setPropertyReference('status')->setLabel(trans('status'))
//     ->addOption('1', 'active')
//     ->addOption('0', 'disabled')
// ;

if ($form->getEntity()->getCode() && $form->getEntity()->getExtension()) {
    $displayStr = (!$form->getEntity()->getCode() && !$form->getEntity()->getExtension()) ? 'style="display: none;"' : '';
    $customView = '
    <div class="row" id="VideoPackage_editVideo_videoPreview-container"'.$displayStr.'>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <div class="form-group formLabel">
                <label for="VideoPackage_editVideo_description">
                    <b>'.trans('file').'</b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group formLabel" style="float: left;">
                <div style="text-align: left;">
                    <span>
                    <a class="triggerModal" href="" onclick="EditVideo.unbindFile(event);">'.trans('delete.file').'</a>
                    <!--&nbsp; - &nbsp;<span id="VideoPackage_editVideo_videoFileName">'.($form->getEntity()->getCode() && $form->getEntity()->getExtension() ? $form->getEntity()->getCode().'.'.$form->getEntity()->getExtension() : '').'</span>-->
                    </span>
                </div>
                <div>
                    <video id="videoPlayer" width="320" controls>
                        <source id="VideoPackage_editVideo_videoSource" src="'.$container->getUrl()->getHttpDomain().'/videoPlayer/play/'.$form->getEntity()->getCode().'.'.$form->getEntity()->getExtension().'" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>
    ';
    $formView->add('custom')->setPropertyReference(null)->setLabel(trans('image'))->addCustomData('view', $customView);
} else {
    $formView->add('file')->setPropertyReference('file')->setLabel(trans('video'));
}
// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));

// $formView->add('text')->setPropertyReference('postalAddress')->setLabel(trans('postal.address'));
// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('send'));
$formView->setFormMethodPath('admin/video/edit');
$formView->displayForm(false, false)->displayScripts();

?>

    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <button name="VideoPackage_editVideo_submit" id="VideoPackage_editVideo_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="EditVideo.submitForm();" value=""><?php echo trans('save'); ?></button>
           </div>
        </div>
    </div>
</form>

    <!-- <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <div class="form-group formLabel">
                <label for="VideoPackage_editVideo_file">
                    <b><?php echo trans('video'); ?></b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <div class="custom-file mt-3 mb-3">
                    <input type="file" class="custom-file-input" id="VideoPackage_editVideo_file" name="VideoPackage_editVideo_file">
                    <label class="custom-file-label" for="customFile"><?php echo trans('upload.video'); ?></label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <button name="VideoPackage_editVideo_submit" id="VideoPackage_editVideo_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="VideoPackageEditVideoForm.call();" value="">Mentés</button>
            </div>
        </div>
    </div> -->