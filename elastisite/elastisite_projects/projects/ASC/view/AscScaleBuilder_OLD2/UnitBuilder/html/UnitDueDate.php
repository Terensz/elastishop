<?php

use projects\ASC\entity\AscUnit;
use projects\ASC\service\AscCalendarEventService;

App::getContainer()->wireService('projects/ASC/service/AscCalendarEventService');
App::getContainer()->wireService('projects/ASC/entity/AscUnit');

// dump($unitData['dueType']);
// dump($unitData['status']);
?>
<?php if ($unitData['status'] != AscUnit::STATUS_CLOSED_SUCCESSFUL || $unitData['status'] == AscUnit::STATUS_CLOSED_SUCCESSFUL && $unitData['dueType'] == AscCalendarEventService::DUE_TYPE_WEEKLY_RECURRENCE): ?>
<div style="padding-top: 4px; padding-left: 4px;">
    <?php if ($unitData['dueType'] == AscCalendarEventService::DUE_TYPE_ONE_TIME && $unitData['status'] != AscUnit::STATUS_CLOSED_SUCCESSFUL): ?>
    <span class="UnitBuilder-Unit-dueDate"><?php echo trans('due.date') . ': <b>' . $unitData['dueDate'] . '</b>'; ?></span>
    <?php endif; ?>
    <?php if ($unitData['dueType'] == AscCalendarEventService::DUE_TYPE_WEEKLY_RECURRENCE): ?>
    <span class="UnitBuilder-Unit-dueDate"><?php echo trans('recurring.due') . '. ' . trans('recurrence.time') . ': <b>' . trans(strtolower($unitData['recurrencePattern'])) . ', ' . $unitData['dueTime'] . '</b>'; ?></span>
    <?php endif; ?>
</div>
<?php endif; ?>