<?php

// dump($container->getConfig()->getProjectData('allowedHttpDomains'));
// dump($container->getUrl()->getHttpDomain());
// dump($container->getRequest()->getAll());
// dump($form->getEntity()->getPerson());

use framework\packages\StaffPackage\entity\StaffMember;
?>

<div id="adminStaffMembers-staffMemberId" style="display: none;"><?php echo $form->getEntity()->getId(); ?></div>

<div class="card-header" style="padding-bottom: 0px !important;">
    <ul class="nav nav-tabs" id="myTabs" role="tablist" style="border-bottom: 0px !important;">
        <li class="nav-item">
            <a class="navLink-priorized nav-link active staffMemberTab staffMemberTab-properties doNotTriggerHref" 
                id="tab1-tab" data-tabid="properties" data-toggle="tab" href="" onclick="StaffMemberModal.switchTab(event, 'properties');" role="tab" aria-controls="tab1" aria-selected="true">
                <?php echo trans('staff.member.tab.property.settings'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="navLink-priorized nav-link staffMemberTab staffMemberTab-statsPages doNotTriggerHref" 
                id="tab1-tab" data-tabid="statsPages" data-toggle="tab" href="" onclick="StaffMemberModal.switchTab(event, 'statsPages');" role="tab" aria-controls="tab1" aria-selected="true">
                <?php echo trans('staff.member.tab.stat.pages'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="navLink-priorized nav-link staffMemberTab staffMemberTab-statsView doNotTriggerHref" 
                id="tab1-tab" data-tabid="statsView" data-toggle="tab" href="" onclick="StaffMemberModal.switchTab(event, 'statsView');" role="tab" aria-controls="tab1" aria-selected="true">
                <?php echo trans('staff.member.tab.view.stats'); ?>
            </a>
        </li>
    </ul>
</div>

<!-- <div class="row productTabs">
    <div href="" data-tabid="properties" onclick="StaffMemberModal.switchTab(event, 'properties');" class="col-lg-3 staffMemberTab staffMemberTab-properties staffMemberTab-active doNotTriggerHref">
        <a class="doNotTriggerHref" href=""><?php echo trans('staff.member.tab.property.settings'); ?></a>
    </div>
    <div href="" data-tabid="statsPages" onclick="StaffMemberModal.switchTab(event, 'statsPages');" class="col-lg-3 staffMemberTab staffMemberTab-statsPages staffMemberTab-inactive doNotTriggerHref">
        <a class="doNotTriggerHref" href=""><?php echo trans('staff.member.tab.stat.pages'); ?></a>
    </div>
    <div href="" data-tabid="statsView" onclick="StaffMemberModal.switchTab(event, 'statsView');" class="col-lg-3 staffMemberTab staffMemberTab-statsView staffMemberTab-inactive doNotTriggerHref">
        <a class="doNotTriggerHref" href=""><?php echo trans('staff.member.tab.view.stats'); ?></a>
    </div>
</div> -->

<div style="height: 30px;"></div>

<div id="adminStaffMembers-statsView-container" style="display: none;">
</div>
<div id="adminStaffMembers-statsPages-container" style="display: none;">
</div>
<div id="adminStaffMembers-properties-container">
<?php
$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseBodySelector('#editorModalBody');
$formView->setResponseLabelSelector('#editorModalLabel');
// $formView->setIdReferenceName('userAccountId');
$formView->add('text')->setPropertyReference('name')->setLabel(trans('name'));
$formView->add('text')->setPropertyReference('organization')->setLabel(trans('organization'));
$formView->add('text')->setPropertyReference('division')->setLabel(trans('division'));
$formView->add('text')->setPropertyReference('username')->setLabel(trans('username'));
$formView->add('text')->setPropertyReference('password')->setLabel(trans('password'));
$formView->add('text')->setPropertyReference('trainedAt')->setLabel(trans('trained.at'));
$formView->add('text')->setPropertyReference('email')->setLabel(trans('email'));
$formView->add('text')->setPropertyReference('mobile')->setLabel(trans('mobile'));
// $formView->add('select')->setPropertyReference('isTester')->setLabel(trans('is.tester'))
//     ->addOption('1', 'yes')
//     ->addOption('0', 'no')
//     ;
$formView->add('select')->setPropertyReference('staffMemberStatus')->setLabel(trans('status'))
    ->addOption(StaffMember::STATUS_ACTIVE, 'active')
    ->addOption(StaffMember::STATUS_INACTIVE, 'inactive')
    ;
$formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
$formView->setFormMethodPath('admin/staff/member/'.($new ? 'new' : 'edit'));
$formView->displayForm()->displayScripts();

?>
</div>

<style>
.staffMemberTabs {
    padding-bottom: 20px;
    /* border-top: 1px solid #c0c0c0; */
}
.staffMemberTab-active {
    height: 100%;
    width: 100%;
    border-top: 1px solid #c0c0c0;
    border-left: 1px solid #c0c0c0;
    border-right: 1px solid #c0c0c0;
    padding: 10px;
    margin: 0px;
    z-index: 20;
    text-align: center;
    cursor: pointer;
}
.staffMemberTab-inactive {
    height: 100%;
    width: 100%;
    border: 1px solid #eaeaea;
    padding: 10px;
    margin: 0px;
    background-color: #d7d7d7;
    color: #fff;
    text-align: center;
    cursor: pointer;
}
</style>