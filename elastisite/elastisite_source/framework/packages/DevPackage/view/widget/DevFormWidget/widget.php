<?php

$formNameBase = 'devForm';
$propertyAlias = 'alma';

// dump($container->getConfig()->getProjectData('allowedHttpDomains'));
// dump($container->getUrl()->getHttpDomain());
// dump($form);

$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#widgetContainer-mainContent');
$formView->add('text')->setPropertyReference('name')->setLabel(trans('name'));
$formView->add('text')->setPropertyReference('username')->setLabel(trans('username'));
$formView->add('text')->setPropertyReference('postalAddress')->setLabel(trans('postal.address'));
$formView->add('submit')->setPropertyReference('submit')->setValue(trans('send'));
$formView->setFormMethodPath('/dev/form/widget');
$formView->displayForm()->displayScripts();

?>

