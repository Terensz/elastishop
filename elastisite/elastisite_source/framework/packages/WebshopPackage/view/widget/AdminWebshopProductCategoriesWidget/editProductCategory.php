<?php
$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
$formView->add('text')->setPropertyReference('name')->setLabel(trans('name'));
// $formView->add('text')->setPropertyReference('nameEn')->setLabel(trans('english.name'));
// $formView->add('text')->setPropertyReference('code')->setLabel(trans('code'));
$productCategorySelect = $formView->add('select')->setPropertyReference('productCategoryId')->setLabel(trans('parent.product.category'));
$productCategorySelect->addOption(0, trans('main.category'));
foreach ($productCategories as $productCategory) {
    $productCategorySelect->addOption(
        $productCategory->getId(), 
        $productCategory->getName()
    );
}
// $formView->add('select')->setPropertyReference('isIndependent')->setLabel(trans('independent.from.webshop'))
//     ->addOption('0', 'false')
//     ->addOption('1', 'true')
//     ;
$formView->add('select')->setPropertyReference('status')->setLabel(trans('status'))
    ->addOption('1', 'active')
    ->addOption('0', 'disabled')
    ;
// dump($productCategorySelect);exit;
$formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
$formView->setFormMethodPath('admin/webshop/productCategory/edit');
$formView->displayForm()->displayScripts();
// dump($formView);exit;
?>

<script>
$(document).ready(function() {
    $('textarea').keypress(function(e) {
        if (e.which == 13) {
            e.stopPropagation();
        }
    });
});
</script>
