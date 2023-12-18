<?php

use framework\packages\FormPackage\entity\Form;
use projects\ASC\service\AscTechService;

// use projects\ASC\entity\AscScale;
// dump($subject);
// dump($ascUnitId);
// dump($form->getValueCollector()->getDisplayed('ascUnitId'));
?>
<div id="ASC_editProjectTeam_projectTeamId" style="display: none;"><?php echo $form->getEntity()->getId() ? : ''; ?></div>
<?php
$command = !isset($new) || !$new ? 'edit' : 'new';

$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
// $formView->setResponseViewObjectRoute('response.views.'.($command == 'new' ? 'newScaleView' : 'editScaleView'));
// $formView->setCallbackJSFunction('AscScaleLister.editScaleCallback(response);');
$formView->setFormMethodPath('asc/scaleLister/'.$command);

$formView->add('text')->setPropertyReference('name')->setLabel(trans('name'));

$childrenIncludedSelect = $formView->add('select')->setPropertyReference('childrenIncluded')->setLabel(trans('children.included'));
$forceSelected = !$form->getValueCollector()->getDisplayed('childrenIncluded') ? true : false;
$childrenIncludedSelect->addOption('*null*', 'please.choose', true, null, $forceSelected);
$childrenIncludedOptions = [
    '1' => 'yes',
    '0' => 'no',
];
foreach ($childrenIncludedOptions as $childrenIncludedOptionKey => $childrenIncludedOptionValue) {
    $childrenIncludedSelect->addOption($childrenIncludedOptionKey, $childrenIncludedOptionValue);
}

$subjectSelect = $formView->add('select')->setPropertyReference('subject')->setLabel(trans('filtering.units.to.subject'));
$forceSelected = !$subject ? true : false;
$subjectSelect->addOption('*null*', 'do.not.use.filter', true, null, $forceSelected);
App::getContainer()->wireService('projects/ASC/service/AscTechService');
$subjectOptions = AscTechService::getSubjectOptionArray();
foreach ($subjectOptions as $subjectKey => $subjectName) {
    $forceSelected = $subject && $subject == $subjectKey ? true : false;
    $subjectSelect->addOption($subjectKey, $subjectName, true, null, $forceSelected);
}

// dump($ascUnitId);
$ascUnitSelect = $formView->add('select')->setPropertyReference('ascUnitId')->setLabel(trans('asc.unit'));
$forceSelected = !$ascUnitId ? true : false;
$ascUnitSelect->addOption('*null*', 'can.be.chosen', true, null, $forceSelected);
// $ascUnitOptions = [
//     '1' => 'yes',
//     '0' => 'no',
// ];
foreach ($ascUnitOptions as $ascUnitOptionKey => $ascUnitOptionValue) {
    $forceSelected = $ascUnitId && $ascUnitId == $ascUnitOptionKey ? true : false;
    $ascUnitSelect->addOption($ascUnitOptionKey, $ascUnitOptionValue, true, null, $forceSelected);
}

// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));

$formView->displayForm();
// ->displayScripts();
// dump($form);
?>

<div class="mb-3">
    <!-- <button class="btn btn-primary" name="" id="" type="button" onclick="ProjectTeamwork.<?php echo $modalActionType; ?>ProjectTeam(event, true);" value="">Mentés</button> -->
    <?php if ($modalActionType == 'new'): ?>
    <button class="btn btn-primary" name="" id="" type="button" onclick="ProjectTeamwork.<?php echo $modalActionType; ?>ProjectTeam(event, true);" value="">Mentés</button>
    <?php endif; ?>
    <?php if ($modalActionType == 'edit'): ?>
    <button class="btn btn-primary" name="" id="" type="button" onclick="ProjectTeamwork.<?php echo $modalActionType; ?>ProjectTeam(event, true, '<?php echo $form->getEntity()->getId(); ?>');" value="">Mentés</button>
    <?php endif; ?>
</div>

<script>
    $('document').ready(function() {
        $('body').off('change', '#ASC_editProjectTeam_subject');
        $('body').on('change', '#ASC_editProjectTeam_subject', function() {
            console.log('csendzs!!!');
            <?php if ($modalActionType == 'new'): ?>
                ProjectTeamwork.<?php echo $modalActionType; ?>ProjectTeam(null, 'reloadOnly');
            <?php endif; ?>
            <?php if ($modalActionType == 'edit'): ?>
                ProjectTeamwork.<?php echo $modalActionType; ?>ProjectTeam(null, 'reloadOnly', '<?php echo $form->getEntity()->getId(); ?>');
            <?php endif; ?>
        });
    });
</script>