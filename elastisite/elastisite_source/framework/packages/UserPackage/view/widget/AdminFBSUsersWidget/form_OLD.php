<?php
// dump($form);
?>
<form name="UserPackage_editFBSUser_form" id="UserPackage_editFBSUser_form" method="POST" action="" enctype="multipart/form-data">
    <div class="form-group">
        <label for="UserPackage_editFBSUser_name"><?php echo trans('name'); ?></label>
        <input name="UserPackage_editFBSUser_name" id="UserPackage_editFBSUser_name" type="text"
            class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('name'); ?>" aria-describedby="" placeholder="">
    </div>
    <div class="form-group">
        <label for="UserPackage_editFBSUser_username"><?php echo trans('username'); ?></label>
        <input name="UserPackage_editFBSUser_username" id="UserPackage_editFBSUser_username" type="text"
            class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('username'); ?>" aria-describedby="" placeholder="">
    </div>
    <div class="form-group">
        <label for="UserPackage_editFBSUser_password"><?php echo trans('password'); ?></label>
        <input name="UserPackage_editFBSUser_password" id="UserPackage_editFBSUser_password" type="text"
            class="inputField form-control" value="" aria-describedby="" placeholder="">
    </div>
    <div class="form-group">
        <label for="UserPackage_editFBSUser_email"><?php echo trans('email'); ?></label>
        <input name="UserPackage_editFBSUser_email" id="UserPackage_editFBSUser_email" type="text"
            class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('email'); ?>" aria-describedby="" placeholder="">
    </div>
<?php if ($container->getUser()->getId() != $userId) { ?>
    <div class="form-group">
        <label for="UserPackage_editFBSUser_permissionGroups"><?php echo trans('permission.group'); ?></label>
        <select name="UserPackage_editFBSUser_permissionGroups" id="UserPackage_editFBSUser_permissionGroups" class="inputField form-control">
            <option value="guest"<?php echo $guestSelectedStr; ?>><?php echo trans('guest'); ?></option>
            <option value="user"<?php echo $userSelectedStr; ?>><?php echo trans('user'); ?></option>
            <option value="projectAdmin"<?php echo $projectAdminSelectedStr; ?>><?php echo trans('site.admin'); ?></option>
            <option value="systemAdmin"<?php echo $systemAdminSelectedStr; ?>><?php echo trans('system.admin'); ?></option>
        </select>
    </div>
<?php } ?>
    <button id="UserPackage_editFBSUser_submit" style="width: 200px;"
        type="button" class="btn btn-secondary btn-block"
        onclick="FBSUserGridAjaxInterface.save(<?php echo $userId; ?>);"><?php echo trans('save.changes'); ?></button>
</form>

<script>
$(document).ready(function() {
    $('textarea').keypress(function(e) {
        if (e.which == 13) {
            e.stopPropagation();
        }
    });
});
</script>
