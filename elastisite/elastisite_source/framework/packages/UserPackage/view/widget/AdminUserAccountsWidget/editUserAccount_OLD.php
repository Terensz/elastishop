<?php
// dump($form->getValueCollector()->getDisplayed('name'));
?>
<form name="UserPackage_editUserAccount_form" id="UserPackage_editUserAccount_form" method="POST" action="" enctype="multipart/form-data">
    <div class="form-group">
        <label for="UserPackage_editUserAccount_name"><?php echo trans('name'); ?></label>
        <input name="UserPackage_editUserAccount_name" id="UserPackage_editUserAccount_name" type="text"
            class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('name'); ?>" aria-describedby="" placeholder="">
    </div>
    <div class="form-group">
        <label for="UserPackage_editUserAccount_username"><?php echo trans('username'); ?></label>
        <input name="UserPackage_editUserAccount_username" id="UserPackage_editUserAccount_username" type="text"
            class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('username'); ?>" aria-describedby="" placeholder="">
    </div>
    <div class="form-group">
        <label for="UserPackage_editUserAccount_password"><?php echo trans('password'); ?></label>
        <input name="UserPackage_editUserAccount_password" id="UserPackage_editUserAccount_password" type="text"
            class="inputField form-control" value="" aria-describedby="" placeholder="">
    </div>
    <div class="form-group">
        <label for="UserPackage_editUserAccount_email"><?php echo trans('email'); ?></label>
        <input name="UserPackage_editUserAccount_email" id="UserPackage_editUserAccount_email" type="text"
            class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('email'); ?>" aria-describedby="" placeholder="">
    </div>
    <div class="form-group">
        <label for="UserPackage_editUserAccount_mobile"><?php echo trans('mobile'); ?></label>
        <input name="UserPackage_editUserAccount_mobile" id="UserPackage_editUserAccount_mobile" type="text"
            class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('mobile'); ?>" aria-describedby="" placeholder="">
    </div>
<?php
    $selectedStr0 = $form->getValueCollector()->getDisplayed('status') == 0 ? ' selected' : '';
    $selectedStr1 = $form->getValueCollector()->getDisplayed('status') == 1 ? ' selected' : '';
    $selectedStr2 = $form->getValueCollector()->getDisplayed('status') == 2 ? ' selected' : '';
?>
    <div class="form-group">
        <label for="UserPackage_editUserAccount_status"><?php echo trans('status'); ?></label>
        <select name="UserPackage_editUserAccount_status" id="UserPackage_editUserAccount_status" class="inputField form-control">
            <option value="1"<?php echo $selectedStr1; ?>><?php echo trans('active'); ?></option>
            <option value="2"<?php echo $selectedStr2; ?>><?php echo trans('proven'); ?></option>
            <option value="0"<?php echo $selectedStr0; ?>><?php echo trans('disabled'); ?></option>
        </select>
    </div>
    <button id="UserPackage_editUserAccount_submit" style="width: 200px;"
        type="button" class="btn btn-secondary btn-block"
        onclick="UserAccountGridAjaxInterface.save(<?php echo $userAccountId; ?>);"><?php echo trans('save.changes'); ?></button>
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
