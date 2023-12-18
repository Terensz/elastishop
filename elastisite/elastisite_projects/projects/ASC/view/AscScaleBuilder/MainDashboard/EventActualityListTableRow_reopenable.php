<?php

use framework\packages\EventPackage\entity\CalendarEventActuality;
use projects\ASC\service\AscCalendarEventActualityService;

App::getContainer()->wireService('EventPackage/entity/CalendarEventActuality');
App::getContainer()->wireService('projects/ASC/service/AscCalendarEventActualityService');

$iconClassString = "";
if ($dataRow['calendarEventActuality_status'] == CalendarEventActuality::STATUS_CLOSED_SUCCESSFUL) {
    $iconClassString = "icon-thumbs-up f-16  text-success";
}
if ($dataRow['calendarEventActuality_status'] == CalendarEventActuality::STATUS_CLOSED_FAILED) {
    $iconClassString = "icon-thumbs-down f-16  text-danger";
}
// dump();
// $iconClassString = "icon-thumbs-up f-16  text-success";

$statusString = trans('closed');
$dueDateString = empty($dataRow['calendarEvent_startDate']) ? $dataRow['calendarEvent_startDate'] : $dataRow['calendarEvent_startDate'];
$trStyleString = $pageCounter != $actualPage ? ' style="display: none;"' : '';
?>

<tr class="pager-<?php echo $category; ?>-tr pager-<?php echo $category; ?>-page-<?php echo $pageCounter; ?>"<?php echo $trStyleString; ?>>
    <td>
        <i class="icon feather <?php echo $iconClassString; ?>"></i>
    </td>
    <td>
        <div class="card-tableCell-iconContainer" style="line-height: 1;">
            <a class="" href="" onclick="AscScaleBuilder.editUnit(event, '<?php echo $dataRow['ascScale_id'] ?>', '<?php echo $dataRow['ascUnit_id'] ?>');">
                <img src="/public_folder/plugin/Bootstrap-icons/edit.svg">
            </a>
            <!-- <img src="/public_folder/plugin/Bootstrap-icons/edit.svg"> -->
        </div>
    </td>
    <td><?php echo $dataRow['ascUnit_mainEntryTitle']; ?></td>
    <!-- <td><?php echo $statusString; ?></td> -->
    <td><?php echo $dueDateString; ?></td>
    <td><a class="link-underlined" href="" onclick="EventActualityList.reopen(event, '<?php echo $dataRow['calendarEventActuality_id']; ?>', true);"><?php echo trans('reopen'); ?></a></td>
</tr>