<?php

use framework\component\helper\DateUtils;
use framework\packages\StaffPackage\service\StaffSettingService;

App::getContainer()->wireService('StaffPackage/service/StaffSettingService');

?>
<form name="StaffPackage_editConfig_form" id="StaffPackage_editConfig_form" method="POST" action="" enctype="multipart/form-data">

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group formLabel">
                <label for="StaffPackage_WeekStartDay">
                    <b><?php echo trans('week.start.day'); ?></b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <div class="input-group">
                    <select name="StaffPackage_WeekStartDay" id="StaffPackage_WeekStartDay" class="inputField form-control">
                        <option value="<?php echo DateUtils::DAY_MONDAY; ?>"<?php echo $staffSettings['StaffPackage_WeekStartDay'] == DateUtils::DAY_MONDAY ? ' selected' : ''; ?>><?php echo trans('monday'); ?></option>
                        <option value="<?php echo DateUtils::DAY_TUESDAY; ?>"<?php echo $staffSettings['StaffPackage_WeekStartDay'] == DateUtils::DAY_TUESDAY ? ' selected' : ''; ?>><?php echo trans('tuesday'); ?></option>
                        <option value="<?php echo DateUtils::DAY_WEDNESDAY; ?>"<?php echo $staffSettings['StaffPackage_WeekStartDay'] == DateUtils::DAY_WEDNESDAY ? ' selected' : ''; ?>><?php echo trans('wednesday'); ?></option>
                        <option value="<?php echo DateUtils::DAY_THURSDAY; ?>"<?php echo $staffSettings['StaffPackage_WeekStartDay'] == DateUtils::DAY_THURSDAY ? ' selected' : ''; ?>><?php echo trans('thursday'); ?></option>
                        <option value="<?php echo DateUtils::DAY_FRIDAY; ?>"<?php echo $staffSettings['StaffPackage_WeekStartDay'] == DateUtils::DAY_FRIDAY ? ' selected' : ''; ?>><?php echo trans('friday'); ?></option>
                        <option value="<?php echo DateUtils::DAY_SATURDAY; ?>"<?php echo $staffSettings['StaffPackage_WeekStartDay'] == DateUtils::DAY_SATURDAY ? ' selected' : ''; ?>><?php echo trans('saturday'); ?></option>
                        <option value="<?php echo DateUtils::DAY_SUNDAY; ?>"<?php echo $staffSettings['StaffPackage_WeekStartDay'] == DateUtils::DAY_SUNDAY ? ' selected' : ''; ?>><?php echo trans('sunday'); ?></option>
                    </select>
                </div>
                <div class="validationMessage error" id="StaffPackage_WeekStartDay-validationMessage" style="padding-top:4px;"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group formLabel">
                <label for="StaffPackage_WeekStartTime">
                    <b><?php echo trans('week.start.time'); ?></b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <div class="input-group">
                    <select name="StaffPackage_WeekStartTime" id="StaffPackage_WeekStartTime" class="inputField form-control">
<?php for ($i = 4; $i < 23; $i++): ?>
<?php
    $hour = str_pad($i, 2, '0', STR_PAD_LEFT); // Óra formázása két számjegyűre
    $hourString = $hour . ':00';
?>
                        <option value="<?php echo $hourString; ?>"<?php echo $staffSettings['StaffPackage_WeekStartTime'] == $hourString ? ' selected' : ''; ?>><?php echo $hourString; ?></option>
<?php endfor; ?>
                    </select>
                </div>
                <div class="validationMessage error" id="StaffPackage_WeekStartTime-validationMessage" style="padding-top:4px;"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group formLabel">
                <label for="StaffPackage_WeekSerialBasedOn">
                    <b><?php echo trans('week.serial.based.on'); ?></b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <div class="input-group">
                    <select name="StaffPackage_WeekSerialBasedOn" id="StaffPackage_WeekSerialBasedOn" class="inputField form-control">
                        <option value="<?php echo StaffSettingService::WEEK_SERIAL_BASED_ON_YEAR['translationReference']; ?>"<?php
                            echo $staffSettings['StaffPackage_WeekSerialBasedOn'] == StaffSettingService::WEEK_SERIAL_BASED_ON_YEAR['translationReference'] ? ' selected' : ''; ?>><?php echo trans('year'); ?></option>
                        <option value="<?php echo StaffSettingService::WEEK_SERIAL_BASED_ON_STAFF_MEMBER_TRAINED_AT['translationReference']; ?>"<?php
                            echo $staffSettings['StaffPackage_WeekSerialBasedOn'] == StaffSettingService::WEEK_SERIAL_BASED_ON_STAFF_MEMBER_TRAINED_AT['translationReference'] ? ' selected' : ''; ?>><?php echo trans('staff.member.trained.at'); ?></option>
                        <option value="<?php echo StaffSettingService::WEEK_SERIAL_BASED_ON_CUSTOM_FIRST_STAT_WEEK_START_TIME['translationReference']; ?>"<?php
                            echo $staffSettings['StaffPackage_WeekSerialBasedOn'] == StaffSettingService::WEEK_SERIAL_BASED_ON_CUSTOM_FIRST_STAT_WEEK_START_TIME['translationReference'] ? ' selected' : ''; ?>><?php echo trans('custom.first.stat.week.start.time'); ?></option>
                    </select>
                </div>
                <div class="validationMessage error" id="StaffPackage_WeekSerialBasedOn-validationMessage" style="padding-top:4px;"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group formLabel">
                <label for="StaffPackage_CustomFirstStatWeekStartTime">
                    <b><?php echo trans('custom.first.stat.week.start.time'); ?></b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <div class="input-group">
                    <select name="StaffPackage_CustomFirstStatWeekStartTime" id="StaffPackage_CustomFirstStatWeekStartTime" class="inputField form-control">
<?php for ($i = 4; $i < 23; $i++): ?>
<?php
    $hour = str_pad($i, 2, '0', STR_PAD_LEFT); // Óra formázása két számjegyűre
    $hourString = $hour . ':00';
?>
                        <option value="<?php echo $hourString; ?>"<?php echo $staffSettings['StaffPackage_WeekStartTime'] == $hourString ? ' selected' : ''; ?>><?php echo $hourString; ?></option>
<?php endfor; ?>
                    </select>
                </div>
                <div class="validationMessage error" id="StaffPackage_CustomFirstStatWeekStartTime-validationMessage" style="padding-top:4px;"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group formLabel">
                <label for="StaffPackage_AllowPageCodeUsageForOneWeekOnly">
                    <b><?php echo trans('allow.page.code.usage.for.one.week.only'); ?></b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <div class="input-group">
                    <select name="StaffPackage_AllowPageCodeUsageForOneWeekOnly" id="StaffPackage_AllowPageCodeUsageForOneWeekOnly" class="inputField form-control">
                        <option value="true"<?php echo $staffSettings['StaffPackage_AllowPageCodeUsageForOneWeekOnly'] == trans('true') ? ' selected' : ''; ?>><?php echo trans('true'); ?></option>
                        <option value="false"<?php echo $staffSettings['StaffPackage_AllowPageCodeUsageForOneWeekOnly'] == trans('false') ? ' selected' : ''; ?>><?php echo trans('false'); ?></option>
                    </select>
                </div>
                <div class="validationMessage error" id="StaffPackage_AllowPageCodeUsageForOneWeekOnly-validationMessage" style="padding-top:4px;"></div>
            </div>
        </div>
    </div>

<?php
    // dump($staffSettings['StaffPackage_AllowEditingExpiredWeek']);
?>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group formLabel">
                <label for="StaffPackage_AllowEditingExpiredWeek">
                    <b><?php echo trans('allow.editing.expired.week'); ?></b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <div class="input-group">
                    <select name="StaffPackage_AllowEditingExpiredWeek" id="StaffPackage_AllowEditingExpiredWeek" class="inputField form-control">
                        <option value="true"<?php echo $staffSettings['StaffPackage_AllowEditingExpiredWeek'] == trans('true') ? ' selected' : ''; ?>><?php echo trans('true'); ?></option>
                        <option value="false"<?php echo $staffSettings['StaffPackage_AllowEditingExpiredWeek'] == trans('false') ? ' selected' : ''; ?>><?php echo trans('false'); ?></option>
                    </select>
                </div>
                <div class="validationMessage error" id="StaffPackage_AllowEditingExpiredWeek-validationMessage" style="padding-top:4px;"></div>
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="form-group">
            <button name="StaffPackage_editConfig_submit" id="StaffPackage_editConfig_submit" type="button" class="btn btn-secondary btn-block"
            style="width: 200px;" onclick="AdminStaffConfig.edit(event, true);" value=""><?php echo trans('save'); ?></button>
        </div>
    </div>
</div>
