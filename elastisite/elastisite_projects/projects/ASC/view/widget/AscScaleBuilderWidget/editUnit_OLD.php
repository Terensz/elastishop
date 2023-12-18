<?php

use projects\ASC\entity\AscUnit;
use projects\ASC\service\AscCalendarEventService;

App::getContainer()->wireService('projects/ASC/entity/AscUnit');
App::getContainer()->wireService('projects/ASC/service/AscCalendarEventService');

?>
<style>
.nicEdit-main {
    border: 0px;
    padding: 0px;
    margin: 0px;
    outline:none;
    user-select: all;
    line-height: normal;
}
</style>

<form name="AscScaleBuilder_editUnit_form" id="AscScaleBuilder_editUnit_form" method="POST" action="" enctype="multipart/form-data">

<?php
// $message = $form->getMessage('UserPackage_userRegistration_primaryLanguageCode');
// $isInvalidStr = '';
// $displayedMessage = '';
// if (isset($message) && $message != ''):
//     $displayedMessage = trans($message);
//     $isInvalidStr = ' is-invalid';
// endif;
?>

    <div class="mb-3">
        <label for="AscScaleBuilder_editUnit_title" class="form-label"><?php echo trans('title'); ?></label>
        <div class="input-group has-validation">
            <input type="text" class="form-control inputField" name="AscScaleBuilder_editUnit_title" id="AscScaleBuilder_editUnit_title" 
                value="<?php echo $title; ?>" maxlength="250" placeholder="">
            <div class="invalid-feedback validationMessage" id="AscScaleBuilder_editUnit_title-validationMessage"></div>
        </div>
    </div>

    <!-- <div class="row">
        <div class="col-sm-12 noPadding">
            <div class="form-group noPadding">
                <small class="form-text text-muted"><?php echo trans('title'); ?></small>
                <input id="AscScaleBuilder_editUnit_title" name="AscScaleBuilder_editUnit_title" 
                    type="text" class="form-control" placeholder="<?php echo trans('title'); ?>"
                    value="<?php echo $title; ?>">
                <div id="AscScaleBuilder_editUnit_title-error" class="fieldError text-danger"></div>
            </div>
        </div>
    </div> -->
    
    <div class="mb-3">
        <label for="AscScaleBuilder_editUnit_title" class="form-label"><?php echo trans('description'); ?></label>
        <div class="input-group has-validation">
            <textarea class="form-control inputField" name="AscScaleBuilder_editUnit_description" id="AscScaleBuilder_editUnit_description"><?php echo $description; ?></textarea>
            <div class="invalid-feedback validationMessage" id="AscScaleBuilder_editUnit_description-validationMessage"></div>
        </div>
    </div>

    <!-- <div class="row">
        <div class="col-sm-12 noPadding">
            <div class="form-group noPadding">
                <small class="form-text text-muted"><?php echo trans('description'); ?></small>
                <textarea name="AscScaleBuilder_editUnit_description" 
                    id="AscScaleBuilder_editUnit_description" 
                    class="textarea-input inputField form-control" 
                    aria-describedby="" placeholder=""><?php echo $description; ?></textarea>
                <div id="AscScaleBuilder_editUnit_description-error" class="fieldError text-danger"></div>
            </div>
        </div>
    </div> -->

<?php 
// dump($ascUnit);
// dump($responsible);
// $responsible
// $administrationStance
// dump(App::getContainer()->getUser()->getUserAccount()->getId());
$userAccountId = App::getContainer()->getUser()->getUserAccount()->getId();
?>

    <div class="form-group row">
        <div class="col-sm-6">
            <label for="AscScaleBuilder_editUnit_responsible" class="form-label"><?php echo trans('responsible.person'); ?></label>
            <div class="input-group has-validation">
                <!-- <input type="text" class="form-control" name="{{ requestKey }}" id="{{ requestKey }}" 
                maxlength="250" placeholder="{{ placeholder }}" value="{{ displayedValue }}"> -->
                <select class="form-select inputField" name="AscScaleBuilder_editUnit_responsible" id="AscScaleBuilder_editUnit_responsible" aria-describedby="AscScaleBuilder_editUnit_responsible-validationMessage" required>
                    <option value="-"<?php echo !$responsible ? ' selected' : ''; ?>><?php echo trans('no.responsible.person.selected'); ?></option>
                    <option value="<?php echo $userAccountId; ?>"<?php echo $responsible == $userAccountId ? ' selected' : ''; ?>><?php echo App::getContainer()->getUser()->getUserAccount()->getPerson()->getFullName(); ?></option>
                    <?php foreach ($teamMembers as $teamMember): ?>
                        <option value="<?php echo $teamMember->getId(); ?>"<?php echo $responsible == $userAccountId ? ' selected' : ''; ?>><?php echo $teamMember->getPerson()->getFullName(); ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback validationMessage" id="AscScaleBuilder_editUnit_responsible-validationMessage"></div>
            </div>
        </div>
        <div class="col-sm-6">
            <label for="AscScaleBuilder_editUnit_responsible" class="form-label"><?php echo trans('administration.stance'); ?></label>
            <div class="input-group has-validation">
                <!-- <input type="text" class="form-control" name="{{ requestKey }}" id="{{ requestKey }}" 
                maxlength="250" placeholder="{{ placeholder }}" value="{{ displayedValue }}"> -->
                <select class="form-select inputField" name="AscScaleBuilder_editUnit_administrationStance" id="AscScaleBuilder_editUnit_administrationStance" aria-describedby="AscScaleBuilder_editUnit_administrationStance-validationMessage" required>
                    <option value="-"<?php echo !$administrationStance ? ' selected' : ''; ?>><?php echo trans('no.administration.stance.selected'); ?></option>
                    <option value="<?php echo AscUnit::ADMINISTRATION_STANCE_PHONE_CALL ?>"<?php echo $administrationStance == AscUnit::ADMINISTRATION_STANCE_PHONE_CALL ? ' selected' : ''; ?>><?php echo trans('phone.call'); ?></option>
                    <!-- <option value="<?php echo AscUnit::ADMINISTRATION_STANCE_UNSUCCESSFUL_PHONE_CALL ?>"<?php echo $administrationStance == AscUnit::ADMINISTRATION_STANCE_UNSUCCESSFUL_PHONE_CALL ? ' selected' : ''; ?>><?php echo trans('unsuccessful.phone.call'); ?></option> -->
                    <option value="<?php echo AscUnit::ADMINISTRATION_STANCE_PERSONAL ?>"<?php echo $administrationStance == AscUnit::ADMINISTRATION_STANCE_PERSONAL ? ' selected' : ''; ?>><?php echo trans('personal'); ?></option>
                    <option value="<?php echo AscUnit::ADMINISTRATION_STANCE_CORRESPONDENCE ?>"<?php echo $administrationStance == AscUnit::ADMINISTRATION_STANCE_CORRESPONDENCE ? ' selected' : ''; ?>><?php echo trans('correspondence'); ?></option>
                    <option value="<?php echo AscUnit::ADMINISTRATION_STANCE_CONCEPT_CREATION ?>"<?php echo $administrationStance == AscUnit::ADMINISTRATION_STANCE_CONCEPT_CREATION ? ' selected' : ''; ?>><?php echo trans('concept.creation'); ?></option>
                </select>
                <div class="invalid-feedback validationMessage" id="AscScaleBuilder_editUnit_administrationStance-validationMessage"></div>
            </div>
        </div>
    </div>

    <!-- <div class="row noPadding">
        <div class="col-sm-6 noPadding">
            <div class="form-group noPadding">
                <small class="form-text text-muted"><?php echo trans('responsible.person'); ?></small>
                <select id="AscScaleBuilder_editUnit_responsible" name="AscScaleBuilder_editUnit_responsible" 
                    class="form-control">
                    <option value="-"<?php echo !$responsible ? ' selected' : ''; ?>><?php echo trans('no.responsible.person.selected'); ?></option>
                    <option value="<?php echo $userAccountId; ?>"<?php echo $responsible == $userAccountId ? ' selected' : ''; ?>><?php echo App::getContainer()->getUser()->getUserAccount()->getPerson()->getFullName(); ?></option>
                    <?php foreach ($teamMembers as $teamMember): ?>
                        <option value="<?php echo $teamMember->getId(); ?>"<?php echo $responsible == $userAccountId ? ' selected' : ''; ?>><?php echo $teamMember->getPerson()->getFullName(); ?></option>
                    <?php endforeach; ?>
                </select>
                <div id="AscScaleBuilder_editUnit_responsible-error" class="fieldError text-danger"></div>
            </div>
        </div>
        <div class="col-sm-6 noPadding">
            <div class="form-group noPadding">
                <small class="form-text text-muted"><?php echo trans('administration.stance'); ?></small>
                <select id="AscScaleBuilder_editUnit_administrationStance" name="AscScaleBuilder_editUnit_administrationStance" 
                    class="form-control">
                    <option value="-"<?php echo !$administrationStance ? ' selected' : ''; ?>><?php echo trans('no.administration.stance.selected'); ?></option>
                    <option value="<?php echo AscUnit::ADMINISTRATION_STANCE_PHONE_CALL ?>"<?php echo $administrationStance == AscUnit::ADMINISTRATION_STANCE_PHONE_CALL ? ' selected' : ''; ?>><?php echo trans('phone.call'); ?></option>
                    <option value="<?php echo AscUnit::ADMINISTRATION_STANCE_PERSONAL ?>"<?php echo $administrationStance == AscUnit::ADMINISTRATION_STANCE_PERSONAL ? ' selected' : ''; ?>><?php echo trans('personal'); ?></option>
                    <option value="<?php echo AscUnit::ADMINISTRATION_STANCE_CORRESPONDENCE ?>"<?php echo $administrationStance == AscUnit::ADMINISTRATION_STANCE_CORRESPONDENCE ? ' selected' : ''; ?>><?php echo trans('correspondence'); ?></option>
                    <option value="<?php echo AscUnit::ADMINISTRATION_STANCE_CONCEPT_CREATION ?>"<?php echo $administrationStance == AscUnit::ADMINISTRATION_STANCE_CONCEPT_CREATION ? ' selected' : ''; ?>><?php echo trans('concept.creation'); ?></option>
                </select>
                <div id="AscScaleBuilder_editUnit_administrationStance-error" class="fieldError text-danger"></div>
            </div>
        </div>
    </div> -->

    <?php 
    // $dueTime = '12';

    if (!isset($dueType)) {
        $dueType = null;
    }

    ?>
<?php 
// dump($ascUnit);
// $dueDateContainerStyle = '';
$dueDateContainerStyle = $dueType && $dueType == AscCalendarEventService::DUE_TYPE_ONE_TIME ? '' : ' style="display: none;"';
$recurrencePatternContainerStyle = $dueType && $dueType != AscCalendarEventService::DUE_TYPE_ONE_TIME ? '' : ' style="display: none;"';
?>

    <div class="row noPadding" style="padding: 0px !important; margin: 0px !important;">
        <div class="col-sm-6 noPadding">
            <div class="row">
                <div class="col-sm-6 noPadding">
                    <div class="form-group noPadding">
                        <small class="form-text text-muted"><?php echo trans('due.type'); ?></small>
                        <select id="AscScaleBuilder_editUnit_dueType" name="AscScaleBuilder_editUnit_dueType" 
                            class="form-control">
                            <option value="-"<?php echo !$dueType ? ' selected' : ''; ?>><?php echo trans('no.due.type.selected'); ?></option>
                            <option value="<?php echo AscCalendarEventService::DUE_TYPE_ONE_TIME ?>"<?php echo $dueType == AscCalendarEventService::DUE_TYPE_ONE_TIME ? ' selected' : ''; ?>><?php echo trans('one.time'); ?></option>
                            <option value="<?php echo AscCalendarEventService::DUE_TYPE_WEEKLY_RECURRENCE ?>"<?php echo $dueType == AscCalendarEventService::DUE_TYPE_WEEKLY_RECURRENCE ? ' selected' : ''; ?>><?php echo trans('weekly.recurring'); ?></option>
                        </select>
                        <div id="AscScaleBuilder_editUnit_dueType-error" class="fieldError text-danger"></div>
                    </div>
                </div>
                <div class="col-sm-6 noPadding">

                    <div id="AscScaleBuilder_editUnit_dueDateContainer"<?php echo $dueDateContainerStyle; ?>>
                        <div class="form-group noPadding">
                            <small class="form-text text-muted"><?php echo trans('due.date'); ?></small>
                            <input id="AscScaleBuilder_editUnit_dueDate" name="AscScaleBuilder_editUnit_dueDate" 
                                type="text" class="form-control" placeholder="<?php echo trans('due.date'); ?>"
                                value="<?php echo $dueDate ? : ''; ?>">
                            <div id="AscScaleBuilder_editUnit_dueDate-error" class="fieldError text-danger"></div>
                        </div>
                    </div>
                    <?php
                        /*
                        Recurrence pattern
                        ------------------
                        When dueType is not one-time, you can set recurrence pattern which fits to the dueType.
                        E.g.: if dueType is: weeklyRecurrence, than the pattern will stand of the weekday names. 
                        We will also use the dueTimeHours and dueTimeMinutes fields below.
                        */
                    ?>
                    <div id="AscScaleBuilder_editUnit_recurrencePatternContainer"<?php echo $recurrencePatternContainerStyle; ?>>
                        <div class="form-group noPadding">
                            <small class="form-text text-muted"><?php echo trans('recurrence.day'); ?></small>
                            <select id="AscScaleBuilder_editUnit_recurrencePattern" name="AscScaleBuilder_editUnit_recurrencePattern" 
                                class="form-control">
                                <option value="-"<?php echo !$recurrencePattern ? ' selected' : ''; ?>><?php echo trans('no.recurrence.pattern.selected'); ?></option>
                                <option value="<?php echo AscCalendarEventService::RECURRENCE_PATTERN_MONDAY ?>"<?php echo $recurrencePattern == AscCalendarEventService::RECURRENCE_PATTERN_MONDAY ? ' selected' : ''; ?>><?php echo trans('monday'); ?></option>
                                <option value="<?php echo AscCalendarEventService::RECURRENCE_PATTERN_TUESDAY ?>"<?php echo $recurrencePattern == AscCalendarEventService::RECURRENCE_PATTERN_TUESDAY ? ' selected' : ''; ?>><?php echo trans('tuesday'); ?></option>
                                <option value="<?php echo AscCalendarEventService::RECURRENCE_PATTERN_WEDNESDAY ?>"<?php echo $recurrencePattern == AscCalendarEventService::RECURRENCE_PATTERN_WEDNESDAY ? ' selected' : ''; ?>><?php echo trans('wednesday'); ?></option>
                                <option value="<?php echo AscCalendarEventService::RECURRENCE_PATTERN_THURSDAY ?>"<?php echo $recurrencePattern == AscCalendarEventService::RECURRENCE_PATTERN_THURSDAY ? ' selected' : ''; ?>><?php echo trans('thursday'); ?></option>
                                <option value="<?php echo AscCalendarEventService::RECURRENCE_PATTERN_FRIDAY ?>"<?php echo $recurrencePattern == AscCalendarEventService::RECURRENCE_PATTERN_FRIDAY ? ' selected' : ''; ?>><?php echo trans('friday'); ?></option>
                                <option value="<?php echo AscCalendarEventService::RECURRENCE_PATTERN_SATURDAY ?>"<?php echo $recurrencePattern == AscCalendarEventService::RECURRENCE_PATTERN_SATURDAY ? ' selected' : ''; ?>><?php echo trans('saturday'); ?></option>
                                <option value="<?php echo AscCalendarEventService::RECURRENCE_PATTERN_SUNDAY ?>"<?php echo $recurrencePattern == AscCalendarEventService::RECURRENCE_PATTERN_SUNDAY ? ' selected' : ''; ?>><?php echo trans('sunday'); ?></option>
                            </select>
                            <div id="AscScaleBuilder_editUnit_recurrencePattern-error" class="fieldError text-danger"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 noPadding" style="display: flex; flex-direction: column; justify-content: center;">
<?php 
$dueTimeLinkInnerContainerStyle = $dueDate ? '' : ' style="display: none;"';
?>
        <?php if (($dueType == AscCalendarEventService::DUE_TYPE_ONE_TIME && $dueTimeHours !== null) || ($dueType == AscCalendarEventService::DUE_TYPE_WEEKLY_RECURRENCE)): ?>
<?php 
$dueTimeInputContainerStyle = '';
$dueTimeLinkContainerStyle = ' style="display: none;"';
?>
        <?php else: ?>
<?php 
$dueTimeInputContainerStyle = ' style="display: none;"';
$dueTimeLinkContainerStyle = '';
?>
        <?php endif; ?>
<?php 
// dump($dueTimeHours);
// dump($dueTimeInputContainerStyle);
?>
            <div id="AscScaleBuilder_editUnit_dueTimeInputContainer"<?php echo $dueTimeInputContainerStyle; ?>>
                <div class="row">
                    <div class="col-sm-6 noPadding">
                        <div class="form-group noPadding">
                            <small class="form-text text-muted"><?php echo trans('due.time.hours'); ?></small>
                            <select id="AscScaleBuilder_editUnit_dueTimeHours" name="AscScaleBuilder_editUnit_dueTimeHours" 
                                class="form-control">
                                <option value="-"<?php echo !$dueTimeHours ? ' selected' : ''; ?>><?php echo trans('no.time.selected'); ?></option>
                                <?php for ($hour = 0; $hour < 24; $hour++) {
                                    for ($minute = 0; $minute < 60; $minute += 60) {
                                        $hourLoop = sprintf("%02d", $hour);
                                        $selectedStr = $dueTimeHours == $hourLoop ? ' selected' : '';
                                        echo "<option value='$hourLoop'".$selectedStr.">$hourLoop</option>";
                                    }
                                } ?>
                            </select>
                            <div id="AscScaleBuilder_editUnit_dueTimeHours-error" class="fieldError text-danger"></div>
                        </div>
                    </div>
                    <div class="col-sm-6 noPadding">
                        <div class="form-group noPadding">
                            <small class="form-text text-muted"><?php echo trans('due.time.minutes'); ?></small>
                            <select id="AscScaleBuilder_editUnit_dueTimeMinutes" name="AscScaleBuilder_editUnit_dueTimeMinutes" 
                                class="form-control">
                                <?php
                                    for ($minute = 0; $minute < 60; $minute += 10) {
                                        $minuteLoop = sprintf("%02d", $minute);
                                        $selectedStr = $dueTimeMinutes == $minuteLoop ? ' selected' : '';
                                        echo "<option value='$minuteLoop'".$selectedStr.">$minuteLoop</option>";
                                    }
                                ?>
                            </select>
                            <div id="AscScaleBuilder_editUnit_dueTimeMinutes-error" class="fieldError text-danger"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="AscScaleBuilder_editUnit_dueTimeLinkContainer"<?php echo $dueTimeLinkContainerStyle; ?>>
                <div id="AscScaleBuilder_editUnit_dueTimeLinkInnerContainer"<?php echo $dueTimeLinkInnerContainerStyle; ?>>
                    <div class="text-center" style="height: 100%; display: flex; align-items: center; justify-content: center;">
                        <a href="" onclick="EditUnitDatePickerHelper.showHours(event);" class="text-muted"><?php echo trans('add.time'); ?></a>
                    </div>
                </div>
            </div>
        
        </div>
    </div>

    <div id="AscScaleBuilder_editUnit_due-error" class="fieldError text-danger"><?php echo $dueErrorStr; ?></div>
<?php 
// dump($status);
?>
    <div class="row" style="padding-bottom: 10px;">
        <div class="col-sm-12 noPadding">
            <div class="form-group noPadding">
                <small class="form-text text-muted"><?php echo trans('due.type'); ?></small>
                <select id="AscScaleBuilder_editUnit_status" name="AscScaleBuilder_editUnit_status" 
                    class="form-control">
                    <option value="<?php echo AscUnit::STATUS_INACTIVE ?>"<?php echo $status == AscUnit::STATUS_INACTIVE ? ' selected' : ''; ?>><?php echo trans('inactive'); ?></option>
                    <option value="<?php echo AscUnit::STATUS_ACTIVE ?>"<?php echo $status == AscUnit::STATUS_ACTIVE ? ' selected' : ''; ?>><?php echo trans('active'); ?></option>
                    <option value="<?php echo AscUnit::STATUS_CLOSED_SUCCESSFUL ?>"<?php echo $status == AscUnit::STATUS_CLOSED_SUCCESSFUL ? ' selected' : ''; ?>><?php echo trans('closed.successful'); ?></option>
                </select>
                <div id="AscScaleBuilder_editUnit_status-error" class="fieldError text-danger"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 noPadding">
            <div class="input-group">

                <button name="" id="" type="button" class="btn btn-secondary btn-block" onclick="AscScaleBuilder.editUnitSubmit(<?php echo $unitId; ?>);" value=""><?php echo trans('save'); ?></button>

            </div>
        </div>
    </div>
</form>

<div style="padding-top: 20px;"></div>
<div id="AscScaleBuilder_Uploader_previewBar"></div>

<!-- <div style="padding-top: 20px;"></div> -->
<form class="dropzone" id="dropzoneForm"></form>

<script>    // FormScripts
    var EditUnit = {

    }; 

    var EditUnitUpload = {
        loadPreviewBar: function() {
            $.ajax({
                'type' : 'POST',
                'url' : '/asc/upload/previewBar',
                'data': {
                    'unitId': '<?php echo $unitId; ?>'
                },
                'async': true,
                'success': function(response) {
                    // console.log(response);
                    $('#AscScaleBuilder_Uploader_previewBar').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
        // initDelete: function(e, fileId) {
        //     e.preventDefault();
        //     if (fileId == undefined || fileId === null || fileId === false) {
        //         return false;
        //     }
        //     let text = '<?php echo trans('deleting.file').' <br>'.trans('are.you.sure'); ?>';
        //     $('#confirmModalBody').html(text);
        //     $('#confirmModalConfirm').attr('onClick', "Upload.deleteConfirmed(" + fileId + ");");
        //     $('#confirmModal').modal('show');
        // },
        // deleteConfirmed: function(fileId) {
        initDelete: function(e, fileId) {
            e.preventDefault();
            $.ajax({
                'type' : 'POST',
                'url' : '/asc/upload/delete',
                'data': {
                    'fileId': fileId
                },
                'async': true,
                'success': function(response) {
                    EditUnitUpload.loadPreviewBar();
                    // console.log(response);
                    // $('#AscScaleBuilder_Uploader_previewBar').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
    };

    var EditUnitDatePickerHelper = {
        pickerInstance: null,
        init: function() {
            // $('#AscScaleBuilder_editUnit_dueDate').datetimepicker();
            var fieldObject = document.getElementById('AscScaleBuilder_editUnit_dueDate');
            this.pickerInstance = new Pikaday({
                field: fieldObject,
                // firstDay: 1,
                // minDate: new Date(),
                // maxDate: new Date(2020, 12, 31),
                // yearRange: [2000,2020],
                toString(date, format) { // using moment
                    console.log(date);
                    return moment(date).format('YYYY-MM-DD');
                },
                onSelect: function(date) {
                    EditUnitDatePickerHelper.refreshVisibility();
                },
                i18n: {
                    previousMonth: 'Előző hónap',
                    nextMonth: 'Következő hónap',
                    months: ['Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December'],
                    weekdays: ['Vasárnap', 'Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat'],
                    weekdaysShort: ['V', 'H', 'K', 'Sze', 'Cs', 'P', 'Szo'],
                    today: 'Ma',
                    clear: 'Törlés',
                    close: 'Bezárás',
                },
            });
        },
        destroy: function() {
            if (this.pickerInstance) {
                this.pickerInstance.destroy();
                this.pickerInstance = null;
            }
        },
        refreshVisibility: function() {
            if ($('#AscScaleBuilder_editUnit_dueType').val() == '-') {
                // No doeType selected
                $('#AscScaleBuilder_editUnit_dueDateContainer').hide();
                $('#AscScaleBuilder_editUnit_recurrencePatternContainer').hide();
                $('#AscScaleBuilder_editUnit_dueTimeInputContainer').hide();
                $('#AscScaleBuilder_editUnit_dueTimeLinkContainer').hide();
            } else {
                // dueType: oneTime selected
                if ($('#AscScaleBuilder_editUnit_dueType').val() == '<?php echo AscCalendarEventService::DUE_TYPE_ONE_TIME; ?>') {
                    EditUnitDatePickerHelper.refreshAsDueTypeOneTime();
                } else if ($('#AscScaleBuilder_editUnit_dueType').val() == '<?php echo AscCalendarEventService::DUE_TYPE_WEEKLY_RECURRENCE; ?>') {
                    EditUnitDatePickerHelper.refreshAsDueTypeWeeklyRecurrence();
                } else {
                    // Impossible situation, yet :-)
                }





            }
        },
        refreshAsDueTypeOneTime: function() {
            console.log('refreshAsDueTypeOneTime');
            // We set the recurrencePattern to null
            $('#AscScaleBuilder_editUnit_recurrencePattern').val('-');
            // We hide the recurrencePatternContainer
            $('#AscScaleBuilder_editUnit_recurrencePatternContainer').hide();
            // We show the dueDateContainer
            $('#AscScaleBuilder_editUnit_dueDateContainer').show();
            
            if ($('#AscScaleBuilder_editUnit_dueDate').val() == '') {
                // dueDate is empty
                EditUnitDatePickerHelper.hideDueTimeInputContainer();
            } else {
                // dueDate is filled
                // EditUnitDatePickerHelper.showDueTimeInputContainer();
                console.log($('#AscScaleBuilder_editUnit_dueTimeHours').val());
                if ($('#AscScaleBuilder_editUnit_dueTimeHours').val() == '-') {
                    // IF: No hours selected
                    EditUnitDatePickerHelper.hideDueTimeInputContainer(true);
                } else {
                    EditUnitDatePickerHelper.showDueTimeInputContainer();
                }
            }
        },
        refreshAsDueTypeWeeklyRecurrence: function() {
            // We show the recurrencePatternContainer
            $('#AscScaleBuilder_editUnit_recurrencePatternContainer').show();
            // We hide the dueDateContainer
            $('#AscScaleBuilder_editUnit_dueDateContainer').hide();
            EditUnitDatePickerHelper.showDueTimeInputContainer();
            $('#AscScaleBuilder_editUnit_dueDate').val('');
            
            // if ($('#AscScaleBuilder_editUnit_dueDate').val() == '') {
            //     // dueDate is empty
            //     EditUnitDatePickerHelper.hideDueTimeInputContainer(true);
            // } else {
            //     // dueDate is filled
            //     EditUnitDatePickerHelper.showDueTimeInputContainer();
            //     // if ($('#AscScaleBuilder_editUnit_dueTimeHours').val() != '-') {
            //     //     // IF: No hours selected
            //     //     EditUnitDatePickerHelper.showDueTimeInputContainer();
            //     // } else {
            //     //     EditUnitDatePickerHelper.hideDueTimeInputContainer(true);
            //     // }
            // }
        },
        showDueTimeInputContainer: function() {
            $('#AscScaleBuilder_editUnit_dueTimeInputContainer').show();
            $('#AscScaleBuilder_editUnit_dueTimeLinkContainer').hide();
            $('#AscScaleBuilder_editUnit_dueTimeLinkInnerContainer').hide();
        },
        hideDueTimeInputContainer: function(showLink) {
            $('#AscScaleBuilder_editUnit_dueTimeInputContainer').hide();
            if (showLink) {
                $('#AscScaleBuilder_editUnit_dueTimeLinkContainer').show();
                $('#AscScaleBuilder_editUnit_dueTimeLinkInnerContainer').show();
            } else {
                $('#AscScaleBuilder_editUnit_dueTimeLinkContainer').hide();
                $('#AscScaleBuilder_editUnit_dueTimeLinkInnerContainer').hide();
            }
            
        },
        showHours: function(e) {
            e.preventDefault();
            $('#AscScaleBuilder_editUnit_dueTimeInputContainer').show();
            $('#AscScaleBuilder_editUnit_dueTimeLinkContainer').hide();
        },
        hideHours: function(e) {
            e.preventDefault();
            $('#AscScaleBuilder_editUnit_dueTimeInputContainer').show();
            $('#AscScaleBuilder_editUnit_dueTimeLinkContainer').hide();
        }
    }

    var EditUnitDropzoneHelper = {
        init: function() {
            Dropzone.autoDiscover = false;
            $("#dropzoneForm").dropzone({
                url: "/asc/upload",
                maxFilesize: 5 * 1024 * 1024, // 5 MB
                acceptedFiles: "image/*", // Csak képfájlokat fogad el
                maxFiles: 5, // Legfeljebb 5 fájlt lehet egyszerre feltölteni
                dictDefaultMessage: "Húzza ide a fájlokat a feltöltéshez",
                success: function(file, response) {
                    // A fájl feltöltése sikeres volt
                    console.log("Fájl feltöltése sikeres:", file.name, response);
                },
                init: function() {
                    this.on("sending", function(file, xhr, formData) {
                        // Fájl feltöltése előtt végrehajtandó műveletek
                        console.log("Fájl feltöltése előtt:", file.name);
                        formData.append("unitId", '<?php echo $unitId; ?>');
                    });
                    this.on("complete", function(file) {
                        // Thumbnail eltávolítása a felületről a feltöltés után
                        this.removeFile(file);
                        EditUnitUpload.loadPreviewBar();
                    });
                }
            });

            // Dropzone.options.dropzoneForm = {
            //     url: "upload.php",
            //     maxFilesize: 5 * 1024 * 1024, // 5 MB
            //     acceptedFiles: "image/*", // Csak képfájlokat fogad el
            //     maxFiles: 5, // Legfeljebb 5 fájlt lehet egyszerre feltölteni
            //     dictDefaultMessage: "Húzd ide a fájlokat a feltöltéshez",
            //     // Egyéb beállításokat is itt adhatsz meg
            // };
            // console.log(Dropzone.options.dropzoneForm);
        }
    };

    var initEditor = function() {
        var ckeditor = CKEDITOR.replace('AscScaleBuilder_editUnit_description', {
            toolbar : 'Basic',
            width: '100%',
            uiColor : '#c0c0c0',
            toolbarGroups: [
                { name: 'colors', groups: [ 'colors' ] },
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'align', 'paragraph' ] },
            ],
            // toolbarGroups: [
            //     // { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            //     // { name: 'editing', groups: [ 'editing' ] },
            //     // { name: 'links', groups: [ 'links' ] },
            //     // { name: 'insert', groups: [ 'insert' ] },
            //     { name: 'colors', groups: [ 'colors' ] },
            //     // { name: 'styles', groups: [ 'styles' ] },
            //     // { name: 'forms', groups: [ 'forms' ] },
            //     // { name: 'tools', groups: [ 'tools' ] },
            //     // { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            //     // { name: 'others', groups: [ 'others' ] },
            //     // '/',
            //     { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            //     { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'paragraph' ] },
            //     // { name: 'about', groups: [ 'about' ] }
            // ],
            language: '<?php echo App::getContainer()->getSession()->getLocale(); ?>',
            removePlugins: 'image',
            // cloudServices_tokenUrl: '/upload/token',
            // cloudServices_uploadUrl: '/upload/alma',
            filebrowserBrowseUrl: '/browser/browse.php',
            filebrowserImageBrowseUrl: '/browser/browse.php?type=Images',
            filebrowserUploadUrl: '/upload/file/?ckeditor',
            filebrowserImageUploadUrl: '/upload/image/?ckeditor',
            removeButtons: 'Subscript,Superscript,Save,NewPage,Cut,Copy,Paste,PasteText,Font,PasteFromWord,Smiley,PageBreak,Iframe,Scayt,Maximize,Styles,About',
            removePlugins: 'easyimage,cloudservices'
        });
        // CKEDITOR.addCss(".cke_editable{ cursor:text; font: 18px DefaultFont; color: #2a2a2a; }");
        CKEDITOR.addCss(".cke_editable{ cursor:text; font: 18px Arial; color: #2a2a2a; }");
        return ckeditor;
    };

    initEditor();

    $(document).ready(function() {
        EditUnitUpload.loadPreviewBar();
        EditUnitDropzoneHelper.init();
        EditUnitDatePickerHelper.destroy();
        EditUnitDatePickerHelper.init();

        $('body').off('change', '#AscScaleBuilder_editUnit_dueType');
        $('body').on('change', '#AscScaleBuilder_editUnit_dueType', function() {
            EditUnitDatePickerHelper.refreshVisibility();
        });

        $('body').off('change', '#AscScaleBuilder_editUnit_dueTimeHours');
        $('body').on('change', '#AscScaleBuilder_editUnit_dueTimeHours', function() {
            let value = $(this).val();
            if (value == '-') {
                $('#AscScaleBuilder_editUnit_dueTimeMinutes').val('00');
            }
            EditUnitDatePickerHelper.refreshVisibility();
        });

        // console.log('A Tempus Dominus inicializálása');
        // $('#AscScaleBuilder_editUnit_dueDate').datetimepicker({
        //     format: 'YYYY-MM-DD HH:mm:ss', // Itt a kívánt dátum formátumot adhatod meg
        //     // További beállításokat itt adhatsz meg...
        // });
    });
</script>