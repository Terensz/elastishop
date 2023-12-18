<?php
$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
$formView->add('text')->setPropertyReference('name')->setLabel(trans('name'));
// $formView->add('text')->setPropertyReference('nameEn')->setLabel(trans('english.name'));
// $formView->add('text')->setPropertyReference('code')->setLabel(trans('code'));
$productSelect = $formView->add('select')->setPropertyReference('productId')->setLabel(trans('product'));
$productSelect->addOption('*null*', trans('please.choose'));
foreach ($products as $product) {
    $productSelect->addOption(
        $product->getId(),
        $product->getName()
    );
}

$directionOfChangeSelect = $formView->add('select')->setPropertyReference('directionOfChange')->setLabel(trans('direction.of.change'));
$directionOfChangeSelect->addOption('*null*', trans('please.choose'));
foreach ($directionsOfChange as $key => $properties) {
    // dump($triggerOnTypeProperties);
    $directionOfChangeSelect->addOption(
        $key,
        $properties['title']
    );
}

$effectCausingStuffSelect = $formView->add('select')->setPropertyReference('effectCausingStuff')->setLabel(trans('effect.causing.stuff'));
$effectCausingStuffSelect->addOption('*null*', trans('please.choose'));
foreach ($effectCausingStuffs as $key => $properties) {
    // dump($triggerOnTypeProperties);
    $effectCausingStuffSelect->addOption(
        $key,
        $properties['title']
    );
}

$formView->add('text')->setPropertyReference('effectCausingValue')->setLabel(trans('effect.causing.value'));

$effectOperatorDescription = '<div style="font-style: italic;">'.trans('effect.operator.description').'</div>';

$formView->add('custom')->setPropertyReference(null)->addCustomData('view', $effectOperatorDescription);

$effectOperatorSelect = $formView->add('select')->setPropertyReference('effectOperator')->setLabel(trans('effect.operator'));
$effectOperatorSelect->addOption('*null*', trans('please.choose'));
foreach ($effectOperators as $key => $properties) {
    // dump($triggerOnTypeProperties);
    $effectOperatorSelect->addOption(
        $key,
        $properties['title']
    );
}

$formView->add('select')->setPropertyReference('status')->setLabel(trans('status'))
    ->addOption('1', 'active')
    ->addOption('0', 'disabled')
    ;
// dump($productCategorySelect);exit;
$formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
$formView->setFormMethodPath('admin/webshop/cartTrigger/edit');
$formView->displayForm()->displayScripts();

// dump($form);exit;
?>