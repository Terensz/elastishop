<?php
namespace projects\ASC\service;

use App;
use framework\component\helper\DateUtils;
use framework\component\parent\Service;
use framework\packages\EventPackage\entity\CalendarEventActuality;
use framework\packages\EventPackage\repository\CalendarEventActualityRepository;
use framework\packages\UserPackage\entity\UserAccount;
use projects\ASC\repository\AscUnitRepository;

class AscCalendarEventActualityService extends Service
{
    const DASHBOARD_PERIOD_IN_HOURS = 168;

    const LABEL_UNCLASSIFIED = 'Unclassified';
    const LABEL_TODAYS = 'Todays';
    const LABEL_EXPIRED = 'Expired';
    const LABEL_CLOSED = 'Closed';
    const LABEL_POSTPONED = 'Postponed';

    public static function getDashboardData(UserAccount $userAccount, $skipCalendarEventCheck = false)
    {
        App::getContainer()->wireService('EventPackage/entity/CalendarEventActuality');
        App::getContainer()->wireService('projects/ASC/service/AscCalendarEventChecker');

        if (!$skipCalendarEventCheck) {
            AscCalendarEventChecker::initCalendarEventCheck($userAccount);
        }

        // dump($userAccount);
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $ascUnitRepository = new AscUnitRepository();
        $dashboardRawData = $ascUnitRepository->collectDashboardData($userAccount);
        $nowDateObject = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
        $nowDateTimeObject = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $listData = [
            self::LABEL_UNCLASSIFIED => [],
            self::LABEL_TODAYS => [],
            self::LABEL_EXPIRED => [],
            self::LABEL_CLOSED => [],
            self::LABEL_POSTPONED => []
        ];
        $label = self::LABEL_UNCLASSIFIED;

        // dump($dashboardRawData);exit;

        $closedTodayCount = 0;

        foreach ($dashboardRawData as $dashboardRawDataRow) {
            // 'calendarEvent_startDate' => $queryResultRow['calendar_event_start_date'],
            // 'calendarEvent_startTime' => $queryResultRow['calendar_event_start_time'],
            // 'calendarEvent_id' => $queryResultRow['calendar_event_id'],
            // 'calendarEventActuality_id' => $queryResultRow['calendar_event_actuality_id'],
            // 'calendarEventActuality_status' => $queryResultRow['calendar_event_actuality_status'],
            // 'calendarCheck_runDate' => $queryResultRow['calendar_check_run_date'],
            // 'ascUnitData' => $ascUnitData

            if (in_array($dashboardRawDataRow['calendarEventActuality_status'], [CalendarEventActuality::STATUS_CLOSED_SUCCESSFUL, CalendarEventActuality::STATUS_CLOSED_FAILED])) {
                $label = self::LABEL_CLOSED;
                $ascUnitModifiedAt = $dashboardRawDataRow['ascUnit_modifiedAt'];
                // $ascUnitModifiedAtObject = new \DateTime($ascUnitModifiedAt);
                if ($ascUnitModifiedAt) {
                    $ascUnitModifiedAtObject = \DateTime::createFromFormat('Y-m-d H:i:s', $ascUnitModifiedAt);
                    $ascUnitModifiedAtDate = $ascUnitModifiedAtObject->format('Y-m-d');
                    if ($ascUnitModifiedAtDate == date('Y-m-d')) {
                        $closedTodayCount++;
                    }
                }
            } else {
                $eventStartDate = \DateTime::createFromFormat('Y-m-d', $dashboardRawDataRow['calendarEvent_startDate']);
                // $expired = false;
                if ($dashboardRawDataRow['calendarEvent_startTime'] === null) {
                    if ($eventStartDate < $nowDateObject) {
                        $label = self::LABEL_EXPIRED;
                        // $expired = true;
                    }
                } else {
                    // Az eseménynek van időpontja is
                    $eventStartDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $dashboardRawDataRow['calendarEvent_startDate'] . ' ' . $dashboardRawDataRow['calendarEvent_startTime']);
                    if ($eventStartDateTime < $nowDateTimeObject) {
                        $label = self::LABEL_EXPIRED;
                        // $expired = true;
                    }
                }
    
                // $todays = false;
                if ($eventStartDate == $nowDateObject && $label != self::LABEL_EXPIRED) {
                    $label = self::LABEL_TODAYS;
                    // $todays = true;
                }    
            }

            $listData[$label][] = [
                'label' => $label,
                'calendarEventActuality_id' => $dashboardRawDataRow['calendarEventActuality_id'],
                'calendarEvent_startDate' => $dashboardRawDataRow['calendarEvent_startDate'],
                'calendarEvent_startTime' => $dashboardRawDataRow['calendarEvent_startTime'],
                'calendarCheck_runDate' => $dashboardRawDataRow['calendarCheck_runDate'],
                'ascUnit_mainEntryTitle' => $dashboardRawDataRow['ascUnitData']['data']['mainEntryTitle'],
                'calendarEventActuality_status' => $dashboardRawDataRow['calendarEventActuality_status'],
                'ascScale_id' => $dashboardRawDataRow['ascScale_id'],
                'ascUnit_id' => $dashboardRawDataRow['ascUnit_id'],
                // 'event_expired' => $expired,
                // 'event_todays' => $todays
            ];
        }
        // dump($all);

        // dump($res);
        // dump('getDashboardData');exit;

        return [
            'listData' => $listData,
            'sum' => [
                'todays' => count($listData[self::LABEL_TODAYS]),
                'expired' => count($listData[self::LABEL_EXPIRED]),
                'closed' => $closedTodayCount,
                'postponed' => count($listData[self::LABEL_POSTPONED])
            ]
        ];

        // $todayDate = date('Y-m-d');
    
        // $periodStartDateTime = new \DateTime($todayDate);
        // $periodStartDateTime->modify("-" . self::DASHBOARD_PERIOD_IN_HOURS . " hours");
        // $periodStart = $periodStartDateTime->format('Y-m-d');
    
        // $periodEndDateTime = new \DateTime($todayDate);
        // $periodEndDateTime->modify("+" . self::DASHBOARD_PERIOD_IN_HOURS . " hours");
        // $periodEnd = $periodEndDateTime->format('Y-m-d');

        // $dayOfWeek = date('l');
    
        // $todaysOneTimeDueUnits = AscCalendarEventService::getOneTimeDueUnits($todayDate, $todayDate);
        // $todaysWeeklyRecurrenceDueUnits = AscCalendarEventService::getWeeklyRecurrenceDueUnits($dayOfWeek, $todayDate, $todayDate);
        // $expiredOneTimeDueUnits = AscCalendarEventService::getExpiredOneTimeDueUnits($periodStart, $periodEnd); // Módosítás: Lekérjük az "expired" egységeket
        // // $expiredWeeklyRecurrenceDueUnits = AscCalendarEventService::getExpiredWeeklyRecurrenceDueUnits($periodStart, $periodEnd); // Módosítás: Lekérjük az "expired" egységeket
        // $closedOneTimeDueUnits = AscCalendarEventService::getClosedOneTimeDueUnits($periodStart, $periodEnd); // Módosítás: Lekérjük a "closed" egységeket
        // $closedWeeklyRecurrenceDueUnits = AscCalendarEventService::getClosedWeeklyRecurrenceDueUnits($periodStart, $periodEnd); // Módosítás: Lekérjük a "closed" egységeket
        // $postponedOneTimeDueUnits = AscCalendarEventService::getOneTimeDueUnits($todayDate, $periodEnd);
        // $postponedWeeklyRecurrenceDueUnits = AscCalendarEventService::getWeeklyRecurrenceDueUnits($todayDate, $periodEnd);

        // return [
        //     'todaysOneTimeDueUnits' => $todaysOneTimeDueUnits,
        //     'todaysWeeklyRecurrenceDueUnits' => $todaysWeeklyRecurrenceDueUnits,
        //     'expiredOneTimeDueUnits' => $expiredOneTimeDueUnits,
        //     // 'expiredWeeklyRecurrenceDueUnits' => $expiredWeeklyRecurrenceDueUnits,
        //     'closedOneTimeDueUnits' => $closedOneTimeDueUnits,
        //     'closedWeeklyRecurrenceDueUnits' => $closedWeeklyRecurrenceDueUnits,
        //     'postponedOneTimeDueUnits' => $postponedOneTimeDueUnits,
        //     'postponedWeeklyRecurrenceDueUnits' => $postponedWeeklyRecurrenceDueUnits
        // ];
    }
}