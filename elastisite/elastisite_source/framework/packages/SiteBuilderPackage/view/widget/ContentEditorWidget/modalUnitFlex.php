<div class="widgetWrapper-info">
<?php echo trans('link.placeholder.info'); ?>
</div>
<div class="widgetWrapper-info">
<?php echo trans('page.title.placeholder.info'); ?>
</div>
<?php  
// dump($form);
$formView = $viewTools->create('form')->setForm($form);
// $formView->setResponseLabelSelector('#editorModalLabel');
// $formView->setResponseBodySelector('#ElastiSite_editContentEditorUnit_form');
// $formView->setFormMethodPath('ContentEditorWidget/editor/editContentEditorUnit/form');
$formView->add('hidden')->setPropertyReference('contentEditorUnitId')->setLabel(trans('contentEditorUnitId'));
$formView->add('textarea')->setPropertyReference('description')->setLabel(trans('text'));
$fontSelect = $formView->add('radio')
    ->setPropertyReference('font')
    ->setLabel(trans('font'));
foreach ($fonts->registeredFonts as $font) {
    if ($font->displayedOnLists) {
        $fontSelect->addOption($font->originalFontFamily, $font->originalFontFamily.($font->isDefault ? ' ('.trans('default').')' : ''), false, 'font-family: '.$font->originalFontFamily.' !important;');
    }
}
$formView->add('text')->setPropertyReference('fontSize')->setLabel(trans('font.size'));
$formView->add('color')->setPropertyReference('fontColor')->setLabel(trans('font.color'));

$textAlignSelect = $formView->add('select')
    ->setPropertyReference('textAlign')
    ->setLabel(trans('text.align'));
foreach ($textAlignOptions as $textAlignOptionKey => $textAlignOptionTitle) {
    $textAlignSelect->addOption($textAlignOptionKey, $textAlignOptionTitle, true);
}

$textShadowStyleSelect = $formView->add('select')
    ->setPropertyReference('textShadowStyle')
    ->setLabel(trans('text.shadow.style'));
foreach ($textShadowStyles as $textShadowKey => $textShadowStyle) {
    $textShadowStyleSelect->addOption($textShadowKey, $textShadowKey, true);
}

// $formViewDummy = clone $formView;
// // $formViewDummy->setForm(null);
// dump($formViewDummy);

$formView->displayForm(false, false);
?>
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <button name="" id="" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="ElastiSiteEditContentEditorUnitForm.call(<?php echo $form->getEntity()->getId() ? : 'null'; ?>);" value=""><?php echo trans('save'); ?></button>
            </div>
        </div>
    </div>

<script>
    $('document').ready(function() {
        $('#ElastiSite_editContentEditorUnit_positionTop').mask('9999');
        $('#ElastiSite_editContentEditorUnit_positionRight').mask('9999');
        $('#ElastiSite_editContentEditorUnit_width').mask('9999');
    });
</script>