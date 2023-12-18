<?php
$permGroupsRaw = $container->getUser()->getPermissionGroups();
$permGroups = [];
// $translatedPermGroups = [];
// $loginOk = false;
foreach ($permGroupsRaw as $permGroup) {
    $permGroup = framework\kernel\utility\BasicUtils::camelToSnakeCase($permGroup);
    // dump($permGroup);
    $permGroup = str_replace('_', '.', $permGroup);
    $permGroups[] = trans($permGroup);
}

if ($container->isGranted('viewProjectAdminContent')) {
    // $loginOk = true;
    $httpDomain = $container->getUrl()->getHttpDomain();
?>
<div class="widgetWrapper">
    <form id="GeneralLogin_form" name="GeneralLogin_form" action="" method="post" autocomplete="off">
        <div class="sideMenu-title">
            <?php echo trans('logged.in'); ?>: <br>
            <?php echo $container->getUser()->getName(); ?>
        </div>
<?php
// dump($permGroups);exit;
?>
<?php 
if (in_array('projectAdmin', $permGroupsRaw) || in_array('projectSupervisor', $permGroupsRaw) || in_array('systemAdmin', $permGroupsRaw)) { 
?>
        <div class="sideMenu-item">
            <?php echo trans('my.permission').': '.implode(', ', $permGroups).'<br>'; ?>
        </div>
<?php
}

    if (!in_array('projectAdmin', $permGroupsRaw) && !in_array('projectSupervisor', $permGroupsRaw) && !in_array('systemAdmin', $permGroupsRaw)) { 
        // $activeStr = $container->getRouting()->getPageRoute()->getName() == 'user_changePassword' ? ' sideMenu-active' : '';
?>
        <div class="sideMenu-item">
            <a class="" onclick="Login2Widget.changePasswordModalOpen(event);" href=""><?php echo trans('change.my.password'); ?></a>
        </div>
<?php
        $activeStr = $container->getRouting()->getPageRoute()->getName() == 'user_handlePersonalData' ? ' sideMenu-active' : '';
?>
        <div class="sideMenu-item">
            <a class="ajaxCallerLink<?php echo $activeStr; ?>" href="<?php echo $container->getRoutingHelper()->getLink('user_handlePersonalData'); ?>"><?php echo trans('handle.my.personal.data'); ?></a>
        </div>
<?php
        $activeStr = $container->getRouting()->getPageRoute()->getName() == 'user_removePersonalData' ? ' sideMenu-active' : '';
?>
        <!-- <div class="sideMenu-item">
            <a class="ajaxCallerLink<?php echo $activeStr; ?>" href="<?php echo $container->getRoutingHelper()->getLink('user_removePersonalData'); ?>"><?php echo trans('remove.my.personal.data'); ?></a>
        </div> -->

<?php
    }
    // include('framework/packages/LegalPackage/view/widget/UsersDocumentsWidget/widget.php');
?>
        <div class="sideMenu-item">
            <a onClick="Login2Widget.logout(event)" href=""><?php echo trans('logout'); ?></a>
        </div>
    </form>
</div>
<?php
}
?>
<script>
$(document).ready(function() {
    // Structure.call(window.location.href);
});
</script>
<?php
if ($message && $message['text'] == 'login.success') {
?>
<?php
}
?>
