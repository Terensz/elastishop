<?php

use framework\packages\FormPackage\entity\Form;
// use projects\ASC\entity\AscScale;
?>
<div id="ASC_editProjectTeam_projectTeamUserId" style="display: none;"><?php echo $form->getEntity()->getId() ? : ''; ?></div>
<div id="ASC_editProjectTeam_projectTeamId" style="display: none;"><?php echo $projectTeamId; ?></div>
<?php
$command = !isset($new) || !$new ? 'edit' : 'new';

$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
// $formView->setResponseViewObjectRoute('response.views.'.($command == 'new' ? 'newScaleView' : 'editScaleView'));
// $formView->setCallbackJSFunction('AscScaleLister.editScaleCallback(response);');
$formView->setFormMethodPath('asc/scaleLister/'.$command);

// $formView->add('text')->setPropertyReference('name')->setLabel(trans('name'));

// addOption($key, $displayed, bool $translated = true, string $style = null, $forceSelected = false)
$projectUserSelect = $formView->add('select')->setPropertyReference('projectUserId')->setLabel(trans('project.user'));
$projectUserSelect->addOption('*null*', 'please.choose', true, null, true);
// $projectUserSelectOptions = [
//     '1' => 'yes',
//     '0' => 'no',
// ];
foreach ($projectUserSelectOptions as $projectUserSelectOption) {
    $projectUserSelect->addOption($projectUserSelectOption['projectUserId'], $projectUserSelectOption['personString'], true, null);
}

// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));

$formView->displayForm();
// ->displayScripts();
// dump($form);
?>
<div class="mb-3">
    <!-- <button class="btn btn-primary" name="" id="" type="button" onclick="ProjectTeamwork.<?php echo $modalActionType; ?>ProjectTeam(event, true);" value="">Mentés</button> -->
    <?php if ($modalActionType == 'new'): ?>
    <button class="btn btn-primary" name="" id="" type="button" onclick="ProjectTeamwork.<?php echo $modalActionType; ?>ProjectTeamUser(event, true, '<?php echo $projectTeamId; ?>');" value="">Mentés</button>
    <?php endif; ?>
    <?php if ($modalActionType == 'edit'): ?>
    <button class="btn btn-primary" name="" id="" type="button" onclick="ProjectTeamwork.<?php echo $modalActionType; ?>ProjectTeamUser(event, true, '<?php echo $form->getEntity()->getId(); ?>', '<?php echo $projectTeamId; ?>');" value="">Mentés</button>
    <?php endif; ?>
</div>