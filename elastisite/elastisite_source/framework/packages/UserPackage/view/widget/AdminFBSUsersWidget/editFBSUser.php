<?php
// dump($form->getEntity());
// dump($container->getUser());

$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
$formView->add('text')->setPropertyReference('name')->setLabel(trans('name'));
$formView->add('text')->setPropertyReference('username')->setLabel(trans('username'));
$formView->add('text')->setPropertyReference('displayedPassword')->setLabel(trans('password'));
$formView->add('text')->setPropertyReference('email')->setLabel(trans('email'));
if ($container->getUser()->getId() != $form->getEntity()->getId() && $form->getEntity()->firstPermissionGroupIsHigherOrEquals($ownUserHighestPermissionGroup, $form->getEntity()->getHighestPermissionGroup())) {
    $formView->add('select')->setPropertyReference('highestPermissionGroup')->setLabel(trans('highest.permission.group'))
    ->addOption('guest', 'guest')
    ->addOption('user', 'user')
    ->addOption('projectAuditor', 'project.auditor')
    ->addOption('projectSupervisor', 'project.supervisor')
    ->addOption('projectAdmin', 'project.admin')
    ->addOption('systemAdmin', 'system.admin')
    ;
    
    $formView->add('select')->setPropertyReference('status')->setLabel(trans('status'))
    ->addOption('1', 'active')
    ->addOption('0', 'disabled')
    ;
}
$formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
$formView->setFormMethodPath('admin/FBSUser/edit');
$formView->displayForm()->displayScripts();

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