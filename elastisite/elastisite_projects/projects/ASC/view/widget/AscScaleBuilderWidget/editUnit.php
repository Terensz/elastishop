<?php

use framework\component\helper\StringHelper;
use framework\packages\EventPackage\entity\CalendarEvent;
use framework\packages\EventPackage\service\CalendarEventFactory;
use projects\ASC\entity\AscUnit;

App::getContainer()->wireService('projects/ASC/entity/AscUnit');
App::getContainer()->wireService('EventPackage/service/CalendarEventFactory');
App::getContainer()->wireService('EventPackage/entity/CalendarEvent');

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

.recurrenceDay-buttons {
    display: flex;
}

.recurrenceDay-button {
    width: 30px;
    height: 30px;
    margin: 5px;
    border: none; /* Szegély eltávolítása */
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    background-color: #ccc; /* Egységes szürke háttérszín */
    color: #333; /* Betűszín */
}

.recurrenceDay-button.active {
    background-color: #7267EE;
    color: #fff;
}

</style>

<form name="AscScaleBuilder_editUnit_form" id="AscScaleBuilder_editUnit_form" method="POST" action="" enctype="multipart/form-data">

<?php

$dueEventFactory = $ascUnit->getDueEventFactory();
$isDueEvent = $dueEventFactory->calendarEvent ? true : false;
$dueDate = $dueEventFactory->getStartDate();
$dueTimeHours = $dueEventFactory->getStartTimeHours();
$dueTimeMinutes = $dueEventFactory->getStartTimeMinutes();
$frequencyType = $dueEventFactory->getFrequencyType();
$recurrenceInterval = $dueEventFactory->getRecurrenceInterval();
$recurrenceUnit = $dueEventFactory->getRecurrenceUnit();
$recurrenceDayMon = StringHelper::intToBooleanString($dueEventFactory->getRecurrenceDayMon());
$recurrenceDayTue = StringHelper::intToBooleanString($dueEventFactory->getRecurrenceDayTue());
$recurrenceDayWed = StringHelper::intToBooleanString($dueEventFactory->getRecurrenceDayWed());
$recurrenceDayThu = StringHelper::intToBooleanString($dueEventFactory->getRecurrenceDayThu());
$recurrenceDayFri = StringHelper::intToBooleanString($dueEventFactory->getRecurrenceDayFri());
$recurrenceDaySat = StringHelper::intToBooleanString($dueEventFactory->getRecurrenceDaySat());
$recurrenceDaySun = StringHelper::intToBooleanString($dueEventFactory->getRecurrenceDaySun());

// dump($recurrenceDayWed);
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

    <div class="mb-3">
        <label for="AscScaleBuilder_editUnit_title" class="form-label"><?php echo trans('description'); ?></label>
        <div class="input-group has-validation">
            <textarea class="form-control inputField" name="AscScaleBuilder_editUnit_description" id="AscScaleBuilder_editUnit_description"><?php echo $description; ?></textarea>
            <div class="invalid-feedback validationMessage" id="AscScaleBuilder_editUnit_description-validationMessage"></div>
        </div>
    </div>

<?php
// dump($ascUnit);
// dump($responsible);
// $responsible
// $administrationStance
// dump(App::getContainer()->getUser()->getUserAccount()->getId());
$userAccountId = App::getContainer()->getUser()->getUserAccount()->getId();
?>

    <div class="form-group row mb-3">
        <div class="col-sm-6">
            <label for="AscScaleBuilder_editUnit_responsible" class="form-label"><?php echo trans('responsible.person'); ?></label>
            <div class="input-group has-validation">
                <!-- <input type="text" class="form-control" name="{{ requestKey }}" id="{{ requestKey }}"
                maxlength="250" placeholder="{{ placeholder }}" value="{{ displayedValue }}"> -->
                <select class="form-select inputField" name="AscScaleBuilder_editUnit_responsible" id="AscScaleBuilder_editUnit_responsible" aria-describedby="AscScaleBuilder_editUnit_responsible-validationMessage" required>
                    <!-- <option value="-"<?php echo !$responsible ? ' selected' : ''; ?>><?php echo trans('no.responsible.person.selected'); ?></option> -->
                    <option value="<?php echo $userAccountId; ?>"<?php echo $responsible == $userAccountId ? ' selected' : ''; ?>><?php echo App::getContainer()->getUser()->getUserAccount()->getPerson()->getFullName(); ?></option>
                    <?php foreach ($projectTeamworkData as $projectTeamworkDataRow): ?>
                        <?php
                        $teamMember = $projectTeamworkDataRow['projectUser']->getUserAccount();
                        ?>
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



<?php
/*
DUE TIME EVENT
*/
?>


    <?php
    // $dueTime = '12';

    if (!isset($frequencyType)) {
        $frequencyType = CalendarEvent::FREQUENCY_TYPE_ONE_TIME;
    }

    ?>

<?php

$dueTimeLinkInnerContainerStyle = $dueDate ? '' : ' style="display: none;"';
?>
        <?php if ($dueTimeHours): ?>
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
<style>
    .dueEvent-container {
        background-color: #f8f8f8;
        padding: 6px;
        box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;
    }
</style>
<?php
if ($isDueEvent) {
    $dueEventTriggerContainerStyleStr = ' style="display: none;"';
    $dueEventContainerStyleStr = '';
} else {
    $dueEventTriggerContainerStyleStr = '';
    $dueEventContainerStyleStr = ' style="display: none;"';
}
?>

<!-- <input name="dueEvent_" id="" type="hidden" value=""> -->

<div class="dueEvent-trigger-container"<?php echo $dueEventTriggerContainerStyleStr; ?>>
    <div class="mb-3">
        <button type="button" class="btn btn-success" id="dueEvent_trigger">Esedékesség hozzáadása</button>
    </div>
</div>
<div class="dueEvent-container"<?php echo $dueEventContainerStyleStr; ?>>

    <div class="mb-3">
        <button type="button" class="btn btn-info" id="dueEvent_remove">Esedékesség törlése</button>
    </div>

    <div class="form-group row mb-3">
        <div class="col-sm-4">

            <label for="AscScaleBuilder_editUnit_dueDate" class="form-label"><?php echo trans('due.date'); ?></label>
            <div class="input-group has-validation">

                <input type="text" class="form-control inputField" id="AscScaleBuilder_editUnit_dueDate" name="AscScaleBuilder_editUnit_dueDate"
                    value="<?php echo $dueDate ? : ''; ?>" placeholder="<?php echo trans('due.date'); ?>" maxlength="250">
                <div class="invalid-feedback validationMessage" id="AscScaleBuilder_editUnit_dueDate-validationMessage"></div>

            </div>
        </div>
        <div class="col-sm-4">
            <div class="AscScaleBuilder_editUnit_dueTimeInputContainer"<?php echo $dueTimeInputContainerStyle; ?>>

                <label for="AscScaleBuilder_editUnit_dueTimeHours" class="form-label"><?php echo trans('due.time.hours'); ?></label>
                <div class="input-group has-validation">
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
                    <div class="invalid-feedback validationMessage" id="AscScaleBuilder_editUnit_dueTimeHours-validationMessage"></div>
                </div>

            </div>
        </div>
        <div class="col-sm-4">
            <div class="AscScaleBuilder_editUnit_dueTimeInputContainer"<?php echo $dueTimeInputContainerStyle; ?>>

                <label for="AscScaleBuilder_editUnit_dueTimeMinutes" class="form-label"><?php echo trans('due.time.minutes'); ?></label>
                <div class="input-group has-validation">
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
                    <div class="invalid-feedback validationMessage" id="AscScaleBuilder_editUnit_dueTimeMinutes-validationMessage"></div>
                </div>

            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="AscScaleBuilder_editUnit_entireDay" name="AscScaleBuilder_editUnit_entireDay" value="1" checked>
            <label class="form-check-label"><?php echo trans('entire.day'); ?></label>
        </div>
    </div>

    <div class="mb-3">
        <label for="AscScaleBuilder_editUnit_frequencyType" class="form-label"><?php echo trans('due.type'); ?></label>
        <div class="input-group has-validation">
            <select id="AscScaleBuilder_editUnit_frequencyType" name="AscScaleBuilder_editUnit_frequencyType" class="form-select inputField">
                <option value="<?php echo CalendarEvent::FREQUENCY_TYPE_ONE_TIME; ?>"<?php echo $frequencyType == CalendarEvent::FREQUENCY_TYPE_ONE_TIME ? ' selected' : ''; ?>><?php echo trans('one.time'); ?></option>
                <option value="<?php echo CalendarEvent::FREQUENCY_TYPE_DAILY; ?>"<?php echo $frequencyType == CalendarEvent::FREQUENCY_TYPE_DAILY ? ' selected' : ''; ?>><?php echo trans('per.day'); ?></option>
                <option value="<?php echo CalendarEvent::FREQUENCY_TYPE_WEEKLY_MONDAY; ?>"<?php echo $frequencyType == CalendarEvent::FREQUENCY_TYPE_WEEKLY_MONDAY ? ' selected' : ''; ?>><?php echo trans('weekly.on.mondays'); ?></option>
                <option value="<?php echo CalendarEvent::FREQUENCY_TYPE_MONTHLY_FIRST_MONDAY; ?>"<?php echo $frequencyType == CalendarEvent::FREQUENCY_TYPE_MONTHLY_FIRST_MONDAY ? ' selected' : ''; ?>><?php echo trans('monthly.on.first.monday'); ?></option>
                <option value="<?php echo CalendarEvent::FREQUENCY_TYPE_ANNUAL_MAY_1ST; ?>"<?php echo $frequencyType == CalendarEvent::FREQUENCY_TYPE_ANNUAL_MAY_1ST ? ' selected' : ''; ?>><?php echo trans('annually.on.1st.of.may'); ?></option>
                <option value="<?php echo CalendarEvent::FREQUENCY_TYPE_WEEKDAYS; ?>"<?php echo $frequencyType == CalendarEvent::FREQUENCY_TYPE_WEEKDAYS ? ' selected' : ''; ?>><?php echo trans('on.weekdays'); ?></option>
                <option value="<?php echo CalendarEvent::FREQUENCY_TYPE_CUSTOM_RECURRENCE; ?>"<?php echo $frequencyType == CalendarEvent::FREQUENCY_TYPE_CUSTOM_RECURRENCE ? ' selected' : ''; ?>><?php echo trans('custom.recurrence'); ?></option>
            </select>
            <div class="invalid-feedback validationMessage" id="AscScaleBuilder_editUnit_frequencyType-validationMessage"></div>
        </div>
    </div>

<?php
    if (!isset($recurrenceUnit)) {
        $recurrenceUnit = null;
    }
    if (!isset($recurrenceInterval)) {
        $recurrenceInterval = 1;
    }

    $customRecurrenceContainerStyle = '';
    if ($frequencyType != CalendarEvent::FREQUENCY_TYPE_CUSTOM_RECURRENCE) {
        $customRecurrenceContainerStyle = ' style="display: none;"';
    }
?>

    <div id="customRecurrence_container"<?php echo $customRecurrenceContainerStyle; ?>>

        <div class="form-group row mb-3">
            <div class="col-sm-6">
                <label for="AscScaleBuilder_editUnit_recurrenceInterval" class="form-label"><?php echo trans('recurrence.interval'); ?></label>
                <div class="input-group has-validation">
                    <input type="number" min="1" max="9999" class="form-control inputField" name="AscScaleBuilder_editUnit_recurrenceInterval" id="AscScaleBuilder_editUnit_recurrenceInterval"
                        value="<?php echo $recurrenceInterval; ?>">
                    <div class="invalid-feedback validationMessage" id="AscScaleBuilder_editUnit_recurrenceInterval-validationMessage"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <label for="AscScaleBuilder_editUnit_recurrenceUnit" class="form-label"><?php echo trans('recurrence.unit'); ?></label>
                <div class="input-group has-validation">
                    <select id="AscScaleBuilder_editUnit_recurrenceUnit" name="AscScaleBuilder_editUnit_recurrenceUnit" class="form-select inputField">
                        <option value="<?php echo CalendarEvent::RECURRENCE_UNIT_DAY; ?>"<?php echo $recurrenceUnit == CalendarEvent::RECURRENCE_UNIT_DAY ? ' selected' : ''; ?>><?php echo trans('day'); ?></option>
                        <option value="<?php echo CalendarEvent::RECURRENCE_UNIT_WEEK; ?>"<?php echo $recurrenceUnit == CalendarEvent::RECURRENCE_UNIT_WEEK ? ' selected' : ''; ?>><?php echo trans('week'); ?></option>
                        <option value="<?php echo CalendarEvent::RECURRENCE_UNIT_MONTH; ?>"<?php echo $recurrenceUnit == CalendarEvent::RECURRENCE_UNIT_MONTH ? ' selected' : ''; ?>><?php echo trans('month'); ?></option>
                        <option value="<?php echo CalendarEvent::RECURRENCE_UNIT_YEAR; ?>"<?php echo $recurrenceUnit == CalendarEvent::RECURRENCE_UNIT_YEAR ? ' selected' : ''; ?>><?php echo trans('year'); ?></option>
                    </select>
                    <div class="invalid-feedback validationMessage" id="AscScaleBuilder_editUnit_recurrenceUnit-validationMessage"></div>
                </div>
            </div>
        </div>

<?php
if (!isset($recurrenceDayMon)) {
    $recurrenceDayMon = 'false';
}
if (!isset($recurrenceDayTue)) {
    $recurrenceDayTue = 'false';
}
if (!isset($recurrenceDayWed)) {
    $recurrenceDayWed = 'false';
}
if (!isset($recurrenceDayThu)) {
    $recurrenceDayThu = 'false';
}
if (!isset($recurrenceDayFri)) {
    $recurrenceDayFri = 'false';
}
if (!isset($recurrenceDaySat)) {
    $recurrenceDaySat = 'false';
}
if (!isset($recurrenceDaySun)) {
    $recurrenceDaySun = 'false';
}

// dump($recurrenceDayTue);
// dump($recurrenceDayFri);
?>
        <div class="form-group row mb-3">
            <div class="recurrenceDay-buttons">
                <button type="button" id="AscScaleBuilder_editUnit_recurrenceDayMon" class="recurrenceDay-button<?php echo $recurrenceDayMon == 'true' ? ' active' : ''; ?>" data-selected="<?php echo $recurrenceDayMon; ?>">H</button>
                <button type="button" id="AscScaleBuilder_editUnit_recurrenceDayTue" class="recurrenceDay-button<?php echo $recurrenceDayTue == 'true' ? ' active' : ''; ?>" data-selected="<?php echo $recurrenceDayTue; ?>">K</button>
                <button type="button" id="AscScaleBuilder_editUnit_recurrenceDayWed" class="recurrenceDay-button<?php echo $recurrenceDayWed == 'true' ? ' active' : ''; ?>" data-selected="<?php echo $recurrenceDayWed; ?>">Sz</button>
                <button type="button" id="AscScaleBuilder_editUnit_recurrenceDayThu" class="recurrenceDay-button<?php echo $recurrenceDayThu == 'true' ? ' active' : ''; ?>" data-selected="<?php echo $recurrenceDayThu; ?>">Cs</button>
                <button type="button" id="AscScaleBuilder_editUnit_recurrenceDayFri" class="recurrenceDay-button<?php echo $recurrenceDayFri == 'true' ? ' active' : ''; ?>" data-selected="<?php echo $recurrenceDayFri; ?>">P</button>
                <button type="button" id="AscScaleBuilder_editUnit_recurrenceDaySat" class="recurrenceDay-button<?php echo $recurrenceDaySat == 'true' ? ' active' : ''; ?>" data-selected="<?php echo $recurrenceDaySat; ?>">Sz</button>
                <button type="button" id="AscScaleBuilder_editUnit_recurrenceDaySun" class="recurrenceDay-button<?php echo $recurrenceDaySun == 'true' ? ' active' : ''; ?>" data-selected="<?php echo $recurrenceDaySun; ?>">V</button>
            </div>
        </div>

    </div><!-- / customRecurrence_container -->

</div>

<?php
// dump($ascUnit);
// $dueDateContainerStyle = '';
// $recurrencePatternContainerStyle = '';
// $dueDateContainerStyle = $frequencyType && $frequencyType == AscCalendarEventService::DUE_TYPE_ONE_TIME ? '' : ' style="display: none;"';
// $recurrencePatternContainerStyle = $frequencyType && $frequencyType != AscCalendarEventService::DUE_TYPE_ONE_TIME ? '' : ' style="display: none;"';
?>

<?php
// dump($status);
?>

    <div class="mb-3">
        <label for="AscScaleBuilder_editUnit_status" class="form-label"><?php echo trans('status'); ?></label>
        <div class="input-group has-validation">
            <select id="AscScaleBuilder_editUnit_status" name="AscScaleBuilder_editUnit_status"
                class="form-select inputField">
                <option value="<?php echo AscUnit::STATUS_INACTIVE; ?>"<?php echo $status == AscUnit::STATUS_INACTIVE ? ' selected' : ''; ?>><?php echo trans('inactive'); ?></option>
                <option value="<?php echo AscUnit::STATUS_ACTIVE; ?>"<?php echo $status == AscUnit::STATUS_ACTIVE ? ' selected' : ''; ?>><?php echo trans('active'); ?></option>
                <option value="<?php echo AscUnit::STATUS_CLOSED_SUCCESSFUL; ?>"<?php echo $status == AscUnit::STATUS_CLOSED_SUCCESSFUL ? ' selected' : ''; ?>><?php echo trans('closed.as.successful'); ?></option>
            </select>
            <div class="invalid-feedback validationMessage" id="AscScaleBuilder_editUnit_status-validationMessage"></div>
        </div>
    </div>

    <div class="mb-3">
        <button class="btn btn-primary" name="" id="" type="button" onclick="AscScaleBuilder.editUnitSubmit(<?php echo $unitId; ?>);" value=""><?php echo trans('save'); ?></button>
    </div>

</form>

<div style="padding-top: 20px;"></div>
<div class="card-footer" id="AscScaleBuilder_Uploader_previewBar">
</div>
<!-- <div id="AscScaleBuilder_Uploader_previewBar"></div> -->

<!-- <div style="padding-top: 20px;"></div> -->
<form class="dropzone" id="dropzoneForm" enctype="multipart/form-data"></form>

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

    var EditUnitDueDate = {

    };

    var EditUnitDueDate = {
        pickerInstance: null,
        initDatePicker: function() {
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
                    EditUnitDueDate.refreshVisibility();
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
        destroyPicker: function() {
            if (this.pickerInstance) {
                this.pickerInstance.destroy();
                this.pickerInstance = null;
            }
        },
        refreshVisibility: function() {
            if ($('#AscScaleBuilder_editUnit_frequencyType').val() == '<?php echo CalendarEvent::FREQUENCY_TYPE_CUSTOM_RECURRENCE; ?>') {
                $('#customRecurrence_container').show();
            } else {
                $('#customRecurrence_container').hide();
            }

            let entireDayIsChecked = $('#AscScaleBuilder_editUnit_entireDay').is(':checked');
            if (entireDayIsChecked) {
                $('.AscScaleBuilder_editUnit_dueTimeInputContainer').hide();
            } else {
                $('.AscScaleBuilder_editUnit_dueTimeInputContainer').show();
            }

            // if ($('#AscScaleBuilder_editUnit_frequencyType').val() == '-') {
            //     // No doeType selected
            //     $('#AscScaleBuilder_editUnit_dueDateContainer').hide();
            //     $('#AscScaleBuilder_editUnit_recurrencePatternContainer').hide();
            //     $('#AscScaleBuilder_editUnit_dueTimeInputContainer').hide();
            //     $('#AscScaleBuilder_editUnit_dueTimeLinkContainer').hide();
            // } else {
            //     // frequencyType: oneTime selected
            //     if ($('#AscScaleBuilder_editUnit_frequencyType').val() == '<?php echo CalendarEvent::FREQUENCY_TYPE_ONE_TIME; ?>') {
            //         EditUnitDueDate.refreshAsFrequencyTypeOneTime();
            //     } else if ($('#AscScaleBuilder_editUnit_frequencyType').val() == '<?php echo CalendarEvent::FREQUENCY_TYPE_ONE_TIME; ?>') {
            //         EditUnitDueDate.refreshAsFrequencyTypeWeeklyRecurrence();
            //     } else {
            //         // Impossible situation, yet :-)
            //     }
            // }
        },
        refreshAsFrequencyTypeOneTime: function() {
            console.log('refreshAsFrequencyTypeOneTime');
            // We set the recurrencePattern to null
            $('#AscScaleBuilder_editUnit_recurrencePattern').val('-');
            // We hide the recurrencePatternContainer
            $('#AscScaleBuilder_editUnit_recurrencePatternContainer').hide();
            // We show the dueDateContainer
            $('#AscScaleBuilder_editUnit_dueDateContainer').show();

            if ($('#AscScaleBuilder_editUnit_dueDate').val() == '') {
                // dueDate is empty
                EditUnitDueDate.hideDueTimeInputContainer();
            } else {
                // dueDate is filled
                // EditUnitDatePickerHelper.showDueTimeInputContainer();
                console.log($('#AscScaleBuilder_editUnit_dueTimeHours').val());
                if ($('#AscScaleBuilder_editUnit_dueTimeHours').val() == '-') {
                    // IF: No hours selected
                    EditUnitDueDate.hideDueTimeInputContainer(true);
                } else {
                    EditUnitDueDate.showDueTimeInputContainer();
                }
            }
        },
        refreshAsFrequencyTypeWeeklyRecurrence: function() {
            // We show the recurrencePatternContainer
            $('#AscScaleBuilder_editUnit_recurrencePatternContainer').show();
            // We hide the dueDateContainer
            $('#AscScaleBuilder_editUnit_dueDateContainer').hide();
            EditUnitDueDate.showDueTimeInputContainer();
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
        EditUnitDueDate.destroyPicker();
        EditUnitDueDate.initDatePicker();

        $('#AscScaleBuilder_editUnit_recurrenceInterval').mask("0000", { reverse: true });

        $("#AscScaleBuilder_editUnit_recurrenceInterval").on("keypress", function(event) {
            var key = event.key;
            if (key.toLowerCase() === "e") {
                event.preventDefault();
            }
        });

        $('body').off('change', '#AscScaleBuilder_editUnit_frequencyType');
        $('body').on('change', '#AscScaleBuilder_editUnit_frequencyType', function() {
            EditUnitDueDate.refreshVisibility();
        });

        $('body').off('change', '#AscScaleBuilder_editUnit_dueTimeHours');
        $('body').on('change', '#AscScaleBuilder_editUnit_dueTimeHours', function() {
            let value = $(this).val();
            if (value == '-') {
                $('#AscScaleBuilder_editUnit_dueTimeMinutes').val('00');
                $('#AscScaleBuilder_editUnit_entireDay').prop('checked', true);
            }
            EditUnitDueDate.refreshVisibility();
        });

        $('body').off('click', '.recurrenceDay-button');
        $('body').on('click', '.recurrenceDay-button', function() {
            let id = $(this).attr('id');
            let isSelected = $('#' + id).attr('data-selected');

            if (isSelected == 'false') {
                $('#' + id).addClass('active');
                $('#' + id).attr('data-selected', true);
            } else {
                $('#' + id).removeClass('active');
                $('#' + id).attr('data-selected', false);
            }
        });

        $('body').off('click', '#dueEvent_trigger');
        $('body').on('click', '#dueEvent_trigger', function() {
            $('.dueEvent-trigger-container').hide();
            $('.dueEvent-container').show();
        });

        $('body').off('click', '#dueEvent_remove');
        $('body').on('click', '#dueEvent_remove', function() {
            $('.dueEvent-trigger-container').show();
            $('.dueEvent-container').hide();
        });

        $('body').off('click', '#AscScaleBuilder_editUnit_entireDay');
        $('body').on('click', '#AscScaleBuilder_editUnit_entireDay', function() {
            EditUnitDueDate.refreshVisibility();
        });

        // AscScaleBuilder_editUnit_entireDay

        // console.log('A Tempus Dominus inicializálása');
        // $('#AscScaleBuilder_editUnit_dueDate').datetimepicker({
        //     format: 'YYYY-MM-DD HH:mm:ss', // Itt a kívánt dátum formátumot adhatod meg
        //     // További beállításokat itt adhatsz meg...
        // });
    });
</script>
