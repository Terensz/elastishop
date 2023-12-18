<?php
namespace projects\ASC\service;

use App;
use framework\component\helper\DateUtils;
use framework\component\parent\Service;
use projects\ASC\repository\AscUnitRepository;

class AscCalendarEventService extends Service
{
    const DASHBOARD_PERIOD_IN_HOURS = 168;

    // These will not needed in the new calendarEvent scheduling method.
    // -----
    // const RECURRENCE_DAY_MONDAY = 'Monday';
    // const RECURRENCE_DAY_TUESDAY = 'Tuesday';
    // const RECURRENCE_DAY_WEDNESDAY = 'Wednesday';
    // const RECURRENCE_DAY_THURSDAY = 'Thursday';
    // const RECURRENCE_DAY_FRIDAY = 'Friday';
    // const RECURRENCE_DAY_SATURDAY = 'Saturday';
    // const RECURRENCE_DAY_SUNDAY = 'Sunday';

    // These will not needed in the new calendarEvent scheduling method.
    // -----
    // const RECURRENCE_PATTERNS = [
    //     self::DUE_TYPE_WEEKLY_RECURRENCE => [
    //         self::RECURRENCE_PATTERN_MONDAY,
    //         self::RECURRENCE_PATTERN_TUESDAY,
    //         self::RECURRENCE_PATTERN_WEDNESDAY,
    //         self::RECURRENCE_PATTERN_THURSDAY,
    //         self::RECURRENCE_PATTERN_FRIDAY,
    //         self::RECURRENCE_PATTERN_SATURDAY,
    //         self::RECURRENCE_PATTERN_SUNDAY
    //     ]
    // ];

    // Probably this method will be removed
    // This will not needed in the new calendarEvent scheduling method.
    // -------
    // public static function isValidRecurrencePatternFormat($dueType, $recurrencePattern)
    // {
    //     if (isset(self::RECURRENCE_PATTERNS[$dueType])) {
    //         if (in_array($recurrencePattern, self::RECURRENCE_PATTERNS[$dueType])) {
    //             return true;
    //         }
    //     }
    //     return false;
    // }


    /**
     * Dashboard data
    */

    public static function getDashboardData()
    {
        $todayDate = date('Y-m-d');
    
        $periodStartDateTime = new \DateTime($todayDate);
        $periodStartDateTime->modify("-" . self::DASHBOARD_PERIOD_IN_HOURS . " hours");
        $periodStart = $periodStartDateTime->format('Y-m-d');
    
        $periodEndDateTime = new \DateTime($todayDate);
        $periodEndDateTime->modify("+" . self::DASHBOARD_PERIOD_IN_HOURS . " hours");
        $periodEnd = $periodEndDateTime->format('Y-m-d');

        $dayOfWeek = date('l');
    
        $todaysOneTimeDueUnits = AscCalendarEventService::getOneTimeDueUnits($todayDate, $todayDate);
        $todaysWeeklyRecurrenceDueUnits = AscCalendarEventService::getWeeklyRecurrenceDueUnits($dayOfWeek, $todayDate, $todayDate);
        $expiredOneTimeDueUnits = AscCalendarEventService::getExpiredOneTimeDueUnits($periodStart, $periodEnd); // Módosítás: Lekérjük az "expired" egységeket
        // $expiredWeeklyRecurrenceDueUnits = AscCalendarEventService::getExpiredWeeklyRecurrenceDueUnits($periodStart, $periodEnd); // Módosítás: Lekérjük az "expired" egységeket
        $closedOneTimeDueUnits = AscCalendarEventService::getClosedOneTimeDueUnits($periodStart, $periodEnd); // Módosítás: Lekérjük a "closed" egységeket
        $closedWeeklyRecurrenceDueUnits = AscCalendarEventService::getClosedWeeklyRecurrenceDueUnits($periodStart, $periodEnd); // Módosítás: Lekérjük a "closed" egységeket
        $postponedOneTimeDueUnits = AscCalendarEventService::getOneTimeDueUnits($todayDate, $periodEnd);
        $postponedWeeklyRecurrenceDueUnits = AscCalendarEventService::getWeeklyRecurrenceDueUnits($todayDate, $periodEnd);

        return [
            'todaysOneTimeDueUnits' => $todaysOneTimeDueUnits,
            'todaysWeeklyRecurrenceDueUnits' => $todaysWeeklyRecurrenceDueUnits,
            'expiredOneTimeDueUnits' => $expiredOneTimeDueUnits,
            // 'expiredWeeklyRecurrenceDueUnits' => $expiredWeeklyRecurrenceDueUnits,
            'closedOneTimeDueUnits' => $closedOneTimeDueUnits,
            'closedWeeklyRecurrenceDueUnits' => $closedWeeklyRecurrenceDueUnits,
            'postponedOneTimeDueUnits' => $postponedOneTimeDueUnits,
            'postponedWeeklyRecurrenceDueUnits' => $postponedWeeklyRecurrenceDueUnits
        ];
    }
    

    public static function getOneTimeDueUnits($startDate = null, $endDate = null, $excludeClosed = true, $includeClosed = false)
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $dates = DateUtils::mendDates($startDate, $endDate);
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];
        
        $objects = [];
        // $objects = AscUnitRepository::getDueUnits(self::DUE_TYPE_ONE_TIME, $startDate, $endDate, null, null, $excludeClosed, $includeClosed);
        // Ide írd a lekérdezés futtatásához szükséges kódot, és visszatérhetsz az eredményekkel

        return $objects;
    }
    
    public static function getWeeklyRecurrenceDueUnits($dayOfWeek = null, $startDate = null, $endDate = null, $excludeClosed = true, $includeClosed = false)
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
        $dates = DateUtils::mendDates($startDate, $endDate);
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        $objects = [];
        // $objects = AscUnitRepository::getDueUnits(self::DUE_TYPE_WEEKLY_RECURRENCE, $startDate, $endDate, $dayOfWeek, null, $excludeClosed, $includeClosed);
        // getDueUnits($dueType, $startDate, $endDate, $recurrencePattern = null, $responsible = null, $excludeClosed = true, $includeClosed = false)

        return $objects;
    }

    public static function getExpiredOneTimeDueUnits($startDate, $endDate)
    {
        // dump($startDate);dump($endDate);
        return AscCalendarEventService::getOneTimeDueUnits($startDate, $endDate); // Módosítás: Hozzáadtuk az "expired" paramétert
    }

    // public static function getExpiredWeeklyRecurrenceDueUnits($startDate, $endDate)
    // {
    //     // dump($startDate);dump($endDate);
    //     return AscCalendarEventService::getWeeklyRecurrenceDueUnits(null, $startDate, $endDate, false, true); // Módosítás: Hozzáadtuk az "expired" paramétert
    // }

    public static function getClosedOneTimeDueUnits($startDate, $endDate)
    {
        // dump($startDate);dump($endDate);
        return AscCalendarEventService::getOneTimeDueUnits($startDate, $endDate, false, true); // Módosítás: Hozzáadtuk a "closed" paramétert
    }

    public static function getClosedWeeklyRecurrenceDueUnits($startDate, $endDate)
    {
        // dump($startDate);dump($endDate);
        return AscCalendarEventService::getWeeklyRecurrenceDueUnits(null, $startDate, $endDate, false, true); // Módosítás: Hozzáadtuk a "closed" paramétert
    }
}