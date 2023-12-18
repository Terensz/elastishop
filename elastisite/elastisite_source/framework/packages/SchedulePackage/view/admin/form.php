<form name="SchedulePackage_eventEdit_form" id="SchedulePackage_eventEdit_form" method="POST" action="" enctype="multipart/form-data">
    <div class="form-group">
        <label for="SchedulePackage_eventEdit_title"><?php echo trans('title'); ?></label>
        <input name="SchedulePackage_eventEdit_title" id="SchedulePackage_eventEdit_title" type="text"
            class="inputField form-control" value="<?php echo $form['default']['title']; ?>" aria-describedby="" placeholder="">
    </div>

    <div class="form-group">
        <label for="SchedulePackage_eventEdit_description"><?php echo trans('description'); ?></label>
        <input name="SchedulePackage_eventEdit_description" id="SchedulePackage_eventEdit_description" type="text"
            class="inputField form-control" value="<?php echo $form['default']['description']; ?>" aria-describedby="" placeholder="">
    </div>

    <div class="form-group">
        <label for="SchedulePackage_eventEdit_startDate"><?php echo trans('start.date'); ?></label>
        <input name="SchedulePackage_eventEdit_startDate" id="SchedulePackage_eventEdit_startDate" type="text"
            class="inputField form-control" value="<?php echo $form['default']['startDate']; ?>" aria-describedby="" placeholder="">
    </div>

    <div class="form-group">
        <label for="SchedulePackage_eventEdit_endDate"><?php echo trans('end.date'); ?></label>
        <input name="SchedulePackage_eventEdit_endDate" id="SchedulePackage_eventEdit_endDate" type="text"
            class="inputField form-control" value="<?php echo $form['default']['endDate']; ?>" aria-describedby="" placeholder="">
    </div>

    <div class="form-group">
        <label for="SchedulePackage_eventEdit_maxSubscribers"><?php echo trans('max.subscribers'); ?></label>
        <input name="SchedulePackage_eventEdit_maxSubscribers" id="SchedulePackage_eventEdit_maxSubscribers" type="text"
            class="inputField form-control" value="<?php echo $form['default']['maxSubscribers']; ?>" aria-describedby="" placeholder="">
    </div>

<?php
    $selectedNoStr = (!$form['entity']->getActive()) ? ' selected' : '';
    $selectedYesStr = ($form['entity']->getActive() === true) ? ' selected' : '';
?>
    <div class="form-group">
        <label for="SchedulePackage_eventEdit_active"><?php echo trans('active'); ?></label>
        <select name="SchedulePackage_eventEdit_active" id="SchedulePackage_eventEdit_active" class="inputField form-control">
            <option value="*true*"<?php echo $selectedYesStr; ?>><?php echo trans('yes'); ?></option>
            <option value="*false*"<?php echo $selectedNoStr; ?>><?php echo trans('no'); ?></option>
        </select>
    </div>

    <button id="SchedulePackage_eventEdit_submit"
        class="btn btn-secondary btn-block editSaveButton" style="width: 200px;" type="button"
        onclick="EventEdit.save(<?php echo $articleId; ?>);"><?php echo trans('event.save.changes'); ?></button>
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
