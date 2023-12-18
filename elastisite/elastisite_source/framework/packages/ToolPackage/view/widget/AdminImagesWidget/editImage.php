<?php
// dump($form);
?>
<form name="ToolPackage_editImage_form" id="ToolPackage_editImage_form" method="POST" action="" enctype="multipart/form-data">
    <div class="form-group">
        <label for="ToolPackage_editImage_title"><?php echo trans('title'); ?></label>
        <input name="ToolPackage_editImage_title" id="ToolPackage_editImage_title" type="text"
            class="inputField form-control" value="<?php echo $form->getValueCollector()->getDisplayed('title'); ?>" aria-describedby="" placeholder="">
    </div>
<?php
    $selectedStr = ' selected';
    $activeStr = $form->getValueCollector()->getDisplayed('active') ? $selectedStr : '';
    $inactiveStr = $activeStr == '' ? $selectedStr : '';
?>
    <div class="form-group">
        <label for="ToolPackage_editImage_active"><?php echo trans('active'); ?></label>
        <select name="ToolPackage_editImage_active" id="ToolPackage_editImage_active" class="inputField form-control">
            <option value="1"<?php echo $activeStr; ?>><?php echo trans('yes'); ?></option>
            <option value="0"<?php echo $inactiveStr; ?>><?php echo trans('no'); ?></option>
        </select>
    </div>
    <button id="ToolPackage_editImage_submit" style="width: 200px;"
        type="button" class="btn btn-secondary btn-block"
        onclick="ImageGridAjaxInterface.save(<?php echo $imageId; ?>);"><?php echo trans('save.changes'); ?></button>
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
