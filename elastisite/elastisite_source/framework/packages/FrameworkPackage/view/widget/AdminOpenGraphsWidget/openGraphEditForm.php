<form name="FrameworkPackage_openGraphEdit_form" id="FrameworkPackage_openGraphEdit_form" method="POST" action="" enctype="multipart/form-data">
<?php

// dump($dataGrid);
// dump($formBuilder);
// dump($formIsValid);
// dump($form->getEntity());//exit;
$openGraph = $form->getEntity();
// dump($openGraph);
include('framework/packages/ToolPackage/view/upload/js.php');

$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
$formView->add('text')->setPropertyReference('title')->setLabel(trans('title'));
$formView->add('simpleTextarea')->setPropertyReference('description')->setLabel(trans('description'));
$formView->setFormMethodPath('admin/openGraph/edit');
$formView->displayForm(false, false)->displayScripts();

$imageHeaderId = '';
if ($openGraph->getMainOpenGraphImageHeader()) {
    $imageHeaderId = $openGraph->getMainOpenGraphImageHeader()->getImageHeader()->getId();
}

$openGraphImageHeaderId = '';
if ($openGraph->getMainOpenGraphImageHeader()) {
    $openGraphImageHeaderId = $openGraph->getMainOpenGraphImageHeader()->getId();
}

?>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <input name="FrameworkPackage_openGraphEdit_imageHeaderId" id="FrameworkPackage_openGraphEdit_imageHeaderId" type="hidden" value="<?php echo $imageHeaderId; ?>">
                <input name="FrameworkPackage_openGraphEdit_openGraphImageHeaderId" id="FrameworkPackage_openGraphEdit_openGraphImageHeaderId" type="hidden" value="<?php echo $openGraphImageHeaderId; ?>">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="FrameworkPackage_openGraphEdit_imageContainer" style="padding-bottom: 10px;">
                <?php include('imageContainer.php'); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <div class="validationMessage error" id="FrameworkPackage_openGraphEdit_openGraphImageHeaderId-validationMessage" style="padding-top:4px;"><?php echo $openGraphImageErrorMessage; ?></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <button name="FrameworkPackage_openGraphEdit_submit" id="FrameworkPackage_openGraphEdit_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="OpenGraphEdit.submitForm();" value=""><?php echo trans('save'); ?></button>
            </div>
        </div>
    </div>
</form>
<script>
    $('document').ready(function() {
        // new nicEditor({buttonList :['fontSize','bold','italic','underline','ol','ul']}).panelInstance('FrameworkPackage_openGraphEdit_description', {hasPanel : true, maxHeight: 200});
        // $('.nicEdit-main').on('blur', function() {
        //     let content = nicEditors.findEditor("FrameworkPackage_openGraphEdit_description").getContent();
        //     $('#FrameworkPackage_openGraphEdit_description').html(content);
        // });
    });
</script>