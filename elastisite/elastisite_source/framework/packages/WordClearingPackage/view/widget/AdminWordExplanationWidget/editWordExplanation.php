<?php

$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseLabelSelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
$formView->add('text')->setPropertyReference('keyText')->setLabel(trans('key.text'));
$formView->add('textarea')->setPropertyReference('explanation')->setLabel(trans('explanation'));
$formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
// $formView->add('text')->setPropertyReference('postalAddress')->setLabel(trans('postal.address'));
// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('send'));
$formView->setFormMethodPath('admin/wordExplanation/edit');
$formView->displayForm()->displayScripts();

?>

<script>
$(document).ready(function() {
    new nicEditor({buttonList :['fontSize','bold','italic','underline','ol','ul']}).panelInstance('WordClearingPackage_editWordExplanation_explanation', {hasPanel : true});

    // $('body').on('blur', '.nicEdit-main', function() {
    $('.nicEdit-main').on('blur', function() {
        let content = nicEditors.findEditor("WordClearingPackage_editWordExplanation_explanation").getContent();
        $('#WordClearingPackage_editWordExplanation_explanation').html(content);
    });

    $('textarea').keypress(function(e) {
        if (e.which == 13) {
            e.stopPropagation();
        }
    });
});
</script>
