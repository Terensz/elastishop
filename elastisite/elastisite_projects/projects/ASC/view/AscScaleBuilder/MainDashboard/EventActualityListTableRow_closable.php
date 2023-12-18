<?php

use framework\packages\EventPackage\entity\CalendarEventActuality;
use projects\ASC\service\AscCalendarEventActualityService;

App::getContainer()->wireService('EventPackage/entity/CalendarEventActuality');
App::getContainer()->wireService('projects/ASC/service/AscCalendarEventActualityService');

$iconClassString = "";
if ($label == AscCalendarEventActualityService::LABEL_TODAYS) {
    $iconClassString = "icon-bell f-16  text-primary";
}
if ($label == AscCalendarEventActualityService::LABEL_EXPIRED) {
    $iconClassString = "icon-alert-circle f-16  text-danger";
}
if ($label == AscCalendarEventActualityService::LABEL_POSTPONED) {
    $iconClassString = "icon-bell f-16  text-secondary";
}
// dump($dataRow);
// $mainEntry = $ascUnit->getAscEntryHead()->findEntry();
// $mainEntryTitle = $mainEntry ? $mainEntry->getTitle() : '';
// $statusString = $ascUnit->getStatus() == CalendarEventActuality::STATUS_CLOSED_SUCCESSFUL ? trans('closed') : trans('in.progress');
$statusString = trans('in.progress');
$dueDateString = empty($dataRow['calendarEvent_startDate']) ? $dataRow['calendarEvent_startDate'] : $dataRow['calendarEvent_startDate'];
// include('EventActualityListTableRow.php');
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
    <td><a class="link-underlined" href="" onclick="EventActualityList.close(event, 'successful', '<?php echo $dataRow['calendarEventActuality_id']; ?>', true);"><?php echo trans('close.successful'); ?></a></td>
    <td><a class="link-underlined" href="" onclick="EventActualityList.close(event, 'failed', '<?php echo $dataRow['calendarEventActuality_id']; ?>', true);"><?php echo trans('close.unsuccessful'); ?></a></td>
</tr>