<form name="FrameworkPackage_openGraphEdit_form" id="FrameworkPackage_openGraphEdit_form" method="POST" action="" enctype="multipart/form-data">
<?php
// dump($formBuilder);
// dump($formIsValid);
// dump($form->getEntity());//exit;
include('framework/packages/ToolPackage/view/upload/js.php');

$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->add('text')->setPropertyReference('title')->setLabel(trans('title'));
$formView->add('textarea')->setPropertyReference('description')->setLabel(trans('description'));
// $formView->add('select')->setPropertyReference('status')->setLabel(trans('status'))
//     ->addOption('1', 'active')
//     ->addOption('0', 'disabled')
// ;

if ($form->getEntity()->getCode() && $form->getEntity()->getExtension()) {
    $displayStr = (!$form->getEntity()->getCode() && !$form->getEntity()->getExtension()) ? 'style="display: none;"' : '';
    $customView = '
    <div class="row" id="FrameworkPackage_openGraphEdit_videoPreview-container"'.$displayStr.'>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <div class="form-group formLabel">
                <label for="FrameworkPackage_openGraphEdit_description">
                    <b>'.trans('file').'</b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group formLabel" style="float: left;">
                <div style="text-align: left;">
                    <span>
                    <a class="triggerModal" href="" onclick="OpenGraphEdit.unbindImage(event);">'.trans('delete.file').'</a>
                    <!--&nbsp; - &nbsp;<span id="FrameworkPackage_openGraphEdit_imageFileName">'.($form->getEntity()->getCode() && $form->getEntity()->getExtension() ? $form->getEntity()->getCode().'.'.$form->getEntity()->getExtension() : '').'</span>-->
                    </span>
                </div>
                <div>
                  <img class="webshopThumbnail" style="width: 100%;" src="'.$container->getUrl()->getHttpDomain().'/openGraph/image/'.$form->getEntity()->getCode().'.'.$form->getEntity()->getExtension().'">
                </div>
            </div>
        </div>
    </div>
    ';
    $formView->add('custom')->setPropertyReference(null)->setLabel(trans('image'))->addCustomData('view', $customView);
} else {
    $formView->add('file')->setPropertyReference('file')->setLabel(trans('image'));
}
// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));

// $formView->add('text')->setPropertyReference('postalAddress')->setLabel(trans('postal.address'));
// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('send'));
$formView->setFormMethodPath('admin/openGraph/edit');
$formView->displayForm(false, false)->displayScripts();

?>

    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <button name="FrameworkPackage_openGraphEdit_submit" id="FrameworkPackage_openGraphEdit_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="OpenGraphEdit.submitForm();" value=""><?php echo trans('save'); ?></button>
           </div>
        </div>
    </div>
</form>
