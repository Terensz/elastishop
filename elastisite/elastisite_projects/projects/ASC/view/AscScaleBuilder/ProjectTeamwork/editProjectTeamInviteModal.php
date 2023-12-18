<?php

use framework\packages\FormPackage\entity\Form;
// use projects\ASC\entity\AscScale;

// dump($form->getEntity());
?>
<div id="ASC_editProjectTeam_projectTeamInviteId" style="display: none;"><?php echo $form->getEntity()->getId() ? : ''; ?></div>
<?php
$command = !isset($new) || !$new ? 'edit' : 'new';

$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
// $formView->setResponseViewObjectRoute('response.views.'.($command == 'new' ? 'newScaleView' : 'editScaleView'));
// $formView->setCallbackJSFunction('AscScaleLister.editScaleCallback(response);');
$formView->setFormMethodPath('asc/scaleLister/'.$command);

$formView->add('text')->setPropertyReference('projectUserFullName')->setLabel(trans('project.user.name'));
$formView->add('text')->setPropertyReference('projectUserEmail')->setLabel(trans('project.user.email'));

// $childrenIncludedSelect = $formView->add('select')->setPropertyReference('childrenIncluded')->setLabel(trans('children.included'));
// $childrenIncludedSelect->addOption('*null*', 'please.choose');
// $childrenIncludedOptions = [
//     '1' => 'yes',
//     '0' => 'no',
// ];
// foreach ($childrenIncludedOptions as $childrenIncludedOptionKey => $childrenIncludedOptionValue) {
//     $childrenIncludedSelect->addOption($childrenIncludedOptionKey, $childrenIncludedOptionValue, true, null);
// }

// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));

$formView->displayForm();
// ->displayScripts();
// dump($form);
?>
<div class="mb-3">
    <?php if ($modalActionType == 'new'): ?>
    <button class="btn btn-primary" name="" id="" type="button" onclick="ProjectTeamwork.<?php echo $modalActionType; ?>ProjectTeamInvite(event, true);" value="">Mentés</button>
    <?php endif; ?>
    <?php if ($modalActionType == 'edit'): ?>
    <button class="btn btn-primary" name="" id="" type="button" onclick="ProjectTeamwork.<?php echo $modalActionType; ?>ProjectTeamInvite(event, true, '<?php echo $form->getEntity()->getId(); ?>');" value="">Mentés</button>
    <?php endif; ?>
</div>