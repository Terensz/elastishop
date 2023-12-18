<?php 
$formView = $viewTools->create('form')->setForm($form);
// $formView->setResponseBodySelector('#editorModalBody');
// $formView->setResponseLabelSelector('#editorModalLabel');

$formView->add('text')->setPropertyReference('name')->setLabel(trans('name'));
$formView->add('text')->setPropertyReference('taxId')->setLabel(trans('tax.id'));

$routeNameSelect = $formView->add('select')
    ->setPropertyReference('country')
    ->setLabel(trans('country'));

foreach ($countries as $country) {
    $routeNameSelect->addOption(
        $country->getId(), 
        $country->getTranslationReference().'.country', 
        true
    );
}
$formView->add('text')->setPropertyReference('zipCode')->setLabel(trans('zip.code'));
$formView->add('text')->setPropertyReference('city')->setLabel(trans('city'));
$formView->add('text')->setPropertyReference('street')->setLabel(trans('street.name'));

$streetSuffixSelect = $formView->add('select')->setPropertyReference('streetSuffix')->setLabel(trans('street.suffix'));
$streetSuffixSelect->addOption('*null*', '-- '.trans('please.choose').' --');

foreach ($streetSuffixes as $streetSuffixKey => $streetSuffix) {
    $streetSuffixSelect->addOption(trans($streetSuffix), trans($streetSuffix));
}

$formView->add('text')->setPropertyReference('houseNumber')->setLabel(trans('house.number'));
$formView->add('text')->setPropertyReference('staircase')->setLabel(trans('staircase'));
$formView->add('text')->setPropertyReference('floor')->setLabel(trans('floor'));
$formView->add('text')->setPropertyReference('door')->setLabel(trans('door.number'));
// $formView->add('textarea')->setPropertyReference('description')->setLabel(trans('description'));
// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
// $formView->add('hidden')->setPropertyReference('actionType')->setValue($actionType);
$formView->displayForm();
?>
<div id="WebshopPackage_editOrganization_actionType" style="display:none;"><?php echo $actionType; ?></div>
<div class="mb-3">
    <button class="btn btn-primary" name="" id="" type="button" onclick="Webshop.editOrganizationSubmit(event, <?php echo $form->getEntity()->getId() ? : 'null'; ?>);" value=""><?php echo trans('save'); ?></button>
</div>

<?php 
// dump($form->getEntity());
?>