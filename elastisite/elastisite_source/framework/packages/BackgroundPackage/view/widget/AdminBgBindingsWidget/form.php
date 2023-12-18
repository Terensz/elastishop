<?php

$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseLabelSelector('#editorModalLabel');
$formView->setResponseBodySelector('#editorModalBody');
$routeNameSelect = $formView->add('select')
    ->setPropertyReference('routeName')
    ->setLabel(trans('route.name'));

foreach ($routeMap as $routeMapElement) {
    if (isset($routeMapElement['title'])) {
        // $selectedStr = $pageBackground->getRouteName() == $routeMapElement['name'] ? ' selected' : '';
        $routeNameSelect->addOption(
            $routeMapElement['name'], 
            $container->getRoutingHelper()->getObviousParamChain($routeMapElement['paramChains'])
            .' - ('.trans($routeMapElement['title']).')', 
            false
        );
    }
}

$backgroundNameSelect = $formView->add('select')
    ->setPropertyReference('fbsBackgroundTheme')
    ->setLabel(trans('background.name'));
$backgroundNameSelect->addOption('*null*', '-');

foreach ($backgrounds as $background) {
    $backgroundNameSelect->addOption($background->getTheme(), $background->getTitle());
}

$formView->add('color')->setPropertyReference('backgroundColor')->setLabel(trans('background.color'));

$formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
$formView->setFormMethodPath('admin/background/binding/edit');
$formView->displayForm()->displayScripts();

?>
