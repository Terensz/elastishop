<form name="AdminStaffConfig_form" id="AdminStaffConfig_form" method="get" action="">
    <input type="hidden" autocomplete="false">
    <div class="row grid-title-row breakLongText">
        <div class="col-6 grid-title-cell grid-title-cell-background">
            <?php echo trans('appellation'); ?>
        </div>
        <div class="col-6 grid-title-cell grid-title-cell-background">
            <?php echo trans('value'); ?>
        </div>
    </div>
</form>

<div class="row grid-body-row breakLongText" id="">
    <div data-id="3" data-status="" class="col-6 grid-body-cell">
        <?php echo trans('week.start.day'); ?>
    </div>
    <div data-id="3" data-status="" class="col-6 grid-body-cell">
        <?php echo $staffSettings['StaffPackage_WeekStartDay']; ?>
    </div>
</div>
<div class="row grid-body-row breakLongText" id="">
    <div data-id="3" data-status="" class="col-6 grid-body-cell">
        <?php echo trans('week.start.time'); ?>
    </div>
    <div data-id="3" data-status="" class="col-6 grid-body-cell">
        <?php echo $staffSettings['StaffPackage_WeekStartTime']; ?>
    </div>
</div>
<div class="row grid-body-row breakLongText" id="">
    <div data-id="3" data-status="" class="col-6 grid-body-cell">
        <?php echo trans('week.serial.based.on'); ?>
    </div>
    <div data-id="3" data-status="" class="col-6 grid-body-cell">
        <?php echo $staffSettings['StaffPackage_WeekSerialBasedOn']; ?>
    </div>
</div>
<div class="row grid-body-row breakLongText" id="">
    <div data-id="3" data-status="" class="col-6 grid-body-cell">
        <?php echo trans('custom.first.stat.week.start.time'); ?>
    </div>
    <div data-id="3" data-status="" class="col-6 grid-body-cell">
        <?php echo $staffSettings['StaffPackage_CustomFirstStatWeekStartTime']; ?>
    </div>
</div>
<div class="row grid-body-row breakLongText" id="">
    <div data-id="3" data-status="" class="col-6 grid-body-cell">
        <?php echo trans('allow.page.code.usage.for.one.week.only'); ?>
    </div>
    <div data-id="3" data-status="" class="col-6 grid-body-cell">
        <?php echo $staffSettings['StaffPackage_AllowPageCodeUsageForOneWeekOnly']; ?>
    </div>
</div>
<div class="row grid-body-row breakLongText" id="">
    <div data-id="3" data-status="" class="col-6 grid-body-cell">
        <?php echo trans('allow.editing.expired.week'); ?>
    </div>
    <div data-id="3" data-status="" class="col-6 grid-body-cell">
        <?php echo $staffSettings['StaffPackage_AllowEditingExpiredWeek']; ?>
    </div>
</div>