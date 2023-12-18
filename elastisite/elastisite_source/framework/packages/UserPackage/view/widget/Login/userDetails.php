<?php

use framework\packages\UserPackage\service\Permission;

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

if ($container->isGranted('viewUserContent')):
    // $loginOk = true;
    $httpDomain = $container->getUrl()->getHttpDomain();
?>

<ul class="pc-navbar">
                
    <li class="pc-item pc-caption">
        <label><?php echo trans('user.details'); ?></label>
    </li>
<!-- <div class="widgetWrapper"> -->
    <li>
        <div class="m-3">
            <form id="GeneralLogin_form" name="GeneralLogin_form" action="" method="post" autocomplete="off">
                <div class="sideMenu-title">
                    <?php echo trans('logged.in'); ?>: <br>
                    <?php echo $container->getUser()->getName(); ?>
                </div>

                <!-- <div class="widgetWrapper-textContainer widgetWrapper-textContainer-bottomMargin"> -->
            <?php if (in_array(Permission::TESTER_PERMISSION_GROUP, $container->getUser()->getPermissionGroups())): ?>
                <div style="color: #fc1452;"><?php echo trans('tester.account'); ?></div>
                <div class="widgetWrapper-info"><?php echo trans('tester.account.info'); ?></div>
            <?php endif; ?>
<?php
// dump($permGroups);exit;
?>
<?php 
            if (in_array('projectAdmin', $permGroupsRaw) || in_array('projectSupervisor', $permGroupsRaw) || in_array('systemAdmin', $permGroupsRaw)):
?>
                <div class="sideMenu-item">
                    <?php echo trans('my.permission').': '.implode(', ', $permGroups).'<br>'; ?>
                </div>
<?php
            endif;

            if (!in_array('projectAdmin', $permGroupsRaw) && !in_array('projectSupervisor', $permGroupsRaw) && !in_array('systemAdmin', $permGroupsRaw)):
            // $activeStr = $container->getRouting()->getPageRoute()->getName() == 'user_changePassword' ? ' sideMenu-active' : '';
?>
                <div class="sideMenu-item">
                    <a class="" onclick="Login.changePasswordModalOpen(event);" href=""><?php echo trans('change.my.password'); ?></a>
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
            endif;
    // include('framework/packages/LegalPackage/view/widget/UsersDocumentsWidget/widget.php');
?>
                <div class="sideMenu-item">
                    <a onClick="Login.logout(event)" href=""><?php echo trans('logout'); ?></a>
                </div>
            </form>
        </div>
    </li>
<?php
endif;
?>
<script>
$(document).ready(function() {
    CP.load();
    console.log('CP');
    // Structure.call(window.location.href);
});
</script>
<?php
if ($message && $message['text'] == 'login.success'):
?>
<?php
endif;
?>
