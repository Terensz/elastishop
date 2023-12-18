<?php
namespace projects\ASC\service;

use App;
use framework\component\helper\DateUtils;
use framework\component\parent\Service;
use framework\packages\EventPackage\entity\CalendarEvent;
use framework\packages\EventPackage\repository\CalendarEventRepository;
use framework\packages\EventPackage\service\CalendarEventChecker;
use framework\packages\StatisticsPackage\service\CustomWeekManager;
use framework\packages\UserPackage\entity\UserAccount;
use framework\packages\UserPackage\repository\UserAccountRepository;
use projects\ASC\entity\AscScale;
use projects\ASC\entity\AscUnit;

// use projects\ASC\repository\AscUnitRepository;

class AscCalendarEventChecker extends Service
{
    const DISPLAY_RESULT_NONE = 'None';
    // public static function getDashboardData_OLD(UserAccount $userAccount, $ascScale = null)
    // {
    //     App::getContainer()->wireService('UserPackage/entity/UserAccount');
    //     App::getContainer()->wireService('projects/ASC/service/AscRequestService');

    //     // $todayDate = date('Y-m-d');
    //     // $periodStartDateTime = new \DateTime($todayDate);
    //     // $periodStartDateTime->modify("-" . self::DASHBOARD_PERIOD_IN_HOURS . " hours");
    //     // $periodStart = $periodStartDateTime->format('Y-m-d');
    //     // $periodEndDateTime = new \DateTime($todayDate);
    //     // $periodEndDateTime->modify("+" . self::DASHBOARD_PERIOD_IN_HOURS . " hours");
    //     // $periodEnd = $periodEndDateTime->format('Y-m-d');
    //     // $dayOfWeek = date('l');

    //     $processedRequestData = AscRequestService::getProcessedRequestData();
    
    //     $todaysOneTimeCalendarEventData = AscCalendarEventService::getTodaysOneTimeCalendarEventData($userAccount, $ascScale);
    //     $todaysRecurringCalendarEventData = AscCalendarEventService::getTodaysRecurringCalendarEventData($userAccount, $ascScale);
    //     $expiredOneTimeCalendarEventData = AscCalendarEventService::getExpiredOneTimeCalendarEventData($userAccount, $ascScale);
    //     $expiredRecurringCalendarEventData = AscCalendarEventService::getExpiredRecurringCalendarEventData($userAccount, $ascScale);
    //     $closedCalendarEventData = AscCalendarEventService::getClosedCalendarEventData($userAccount, $ascScale);
    //     $postponedCalendarEventData = AscCalendarEventService::getPostponedCalendarEventData($userAccount, $ascScale);

    //     // dump($todaysOneTimeCalendarEventData);exit;

    //     $return = [
    //         'ascScale' => $processedRequestData['ascScale'],
    //         'todaysOneTimeCalendarEventData' => $todaysOneTimeCalendarEventData,
    //         'todaysRecurringCalendarEventData' => $todaysRecurringCalendarEventData,
    //         'expiredOneTimeCalendarEventData' => $expiredOneTimeCalendarEventData,
    //         'expiredRecurringCalendarEventData' => $expiredRecurringCalendarEventData,
    //         'closedCalendarEventData' => $closedCalendarEventData,
    //         'postponedCalendarEventData' => $postponedCalendarEventData,
    //         'sumTodays' => (count($todaysOneTimeCalendarEventData) + count($todaysRecurringCalendarEventData)),
    //         'sumExpired' => (count($expiredOneTimeCalendarEventData) + count($expiredRecurringCalendarEventData)),
    //         'sumClosed' => count($closedCalendarEventData),
    //         'sumPostponed' => count($postponedCalendarEventData)
    //     ];

    //     // dump($return);exit;

    //     return $return;
    // }

    // This starts
    public static function initCalendarEventCheck(UserAccount $userAccount = null, AscScale $ascScale = null)
    {
        if (!$userAccount) {
            App::getContainer()->wireService('UserPackage/repository/UserAccountRepository');
            $repo = new UserAccountRepository();
            $userAccounts = $repo->findAll();
            // dump($userAccounts);exit;

            foreach ($userAccounts as $userAccount) {
                self::initCalendarEventCheckForUserAccount($userAccount, null);
            }
        } else {
            self::initCalendarEventCheckForUserAccount($userAccount, $ascScale);
        }
    }

    public static function initCalendarEventCheckForUserAccount(UserAccount $userAccount, AscScale $ascScale = null, $displayResultFormat = self::DISPLAY_RESULT_NONE)
    {
        App::getContainer()->wireService('projects/ASC/entity/AscUnit');
        App::getContainer()->wireService('projects/ASC/entity/AscScale');

        $detailedQueryParamsBase = [];
        // Example: $detailedQueryParams[] = ['refKey' => 'ced.start_date', 'paramKey' => 'ced_start_date', 'operator' => '<=', 'value' => $currentWeekDates['weekStartDate']],
    
        $detailedQueryParamsBase[] = ['refKey' => 'cal_ev.event_type', 'paramKey' => 'cal_event_type', 'operator' => '=', 'value' => AscUnit::DUE_CALENDAR_EVENT_TYPE];
    
        if ($ascScale) {
            $detailedQueryParamsBase[] = ['refKey' => 'unit.asc_scale_id', 'paramKey' => 'asc_scale_id', 'operator' => '=', 'value' => $ascScale->getId()];
        }

        $detailedQueryParamsBase[] = ['refKey' => 'cal_chk_ev_act.id', 'paramKey' => 'cal_chk_ev_act_id', 'operator' => 'IS', 'value' => null];

        $detailedQueryParams = $detailedQueryParamsBase;

        $additionalSelectString = "
        unit.id as 'unit_id',
        unit.status as 'unit_status',
        cal_ev_act.id as 'calendar_event_actuality_id',
        cal_ev_act.status as 'calendar_event_actuality_status',
        cal_chk_ev_act.id as 'calendar_check_event_actuality_id',
        cal_ev_d.id as 'calendar_event_delay_id',
        ";

        $additionalJoinString = "
        JOIN asc_unit as unit ON unit.calendar_event_id = cal_ev.id
        JOIN asc_scale as scale ON scale.id = unit.asc_scale_id 
        JOIN user_account as user_acc ON user_acc.id = unit.responsible 
        LEFT JOIN calendar_event_actuality cal_ev_act ON cal_ev_act.calendar_event_id = cal_ev.id 
        LEFT JOIN calendar_check_event_actuality cal_chk_ev_act ON cal_chk_ev_act.calendar_event_actuality_id = cal_ev_act.id 
        LEFT JOIN calendar_event_delay cal_ev_d ON cal_ev_d.id = cal_ev_act.calendar_event_delay_id
        ";

        $oneTimeEventsActualityData = self::getOneTimeCalendarEventActualityData($userAccount, $additionalSelectString, $additionalJoinString, $detailedQueryParams);
        $recurringEventsActualityData = self::getRecurringCalendarEventActualityData($userAccount, $additionalSelectString, $additionalJoinString, $detailedQueryParams);

        $actualityData = array_merge($oneTimeEventsActualityData, $recurringEventsActualityData);

        App::getContainer()->wireService('EventPackage/service/CalendarEventChecker');
        CalendarEventChecker::check($userAccount, $actualityData);

        if ($displayResultFormat != self::DISPLAY_RESULT_NONE) {
            dump($oneTimeEventsActualityData);
            dump($recurringEventsActualityData);exit;
    
            
            // $calendarEventData = [];
    
            // # Checking todays's one-time events
            // $additionalCalendarEventData = self::getTodaysOneTimeCalendarEventData($userAccount, $additionalSelectString, $additionalJoinString, $detailedQueryParams);
            // $calendarEventData = array_merge($calendarEventData, $additionalCalendarEventData);
    
            // # Checking todays's recurring events
            // $additionalCalendarEventData = self::getTodaysRecurringCalendarEventData($userAccount, $additionalSelectString, $additionalJoinString, $detailedQueryParams);
            // $calendarEventData = array_merge($calendarEventData, $additionalCalendarEventData);
    
            // # Checking expired one-time events
            // $additionalCalendarEventData = self::getExpiredOneTimeCalendarEventData($userAccount, $additionalSelectString, $additionalJoinString, $detailedQueryParams);
            // $calendarEventData = array_merge($calendarEventData, $additionalCalendarEventData);
    
            // # Checking expired recurring events
            // // $additionalCalendarEventData = self::getExpiredRecurringCalendarEventData($userAccount, $additionalJoinString, $detailedQueryParams);
            // // $calendarEventData = array_merge($calendarEventData, $additionalCalendarEventData);
    
            // # Checking todays's closed events
            // $additionalCalendarEventData = self::getTodaysClosedCalendarEventData($userAccount, $additionalSelectString, $additionalJoinString, $detailedQueryParams);
            // $calendarEventData = array_merge($calendarEventData, $additionalCalendarEventData);
    
            // # Checking postponed events
            // $additionalCalendarEventData = self::getPostponedCalendarEventData($userAccount, $additionalSelectString, $additionalJoinString, $detailedQueryParams);
            // $calendarEventData = array_merge($calendarEventData, $additionalCalendarEventData);
    
            // dump($calendarEventData);exit;
    
            // CalendarEventChecker::check(
            //     $userAccount,
            //     $calendarEventData
            // );
        }
    }

    // Checker method in the project
    public static function getOneTimeCalendarEventActualityData(UserAccount $userAccount, string $additionalSelectString, string $additionalJoinString, array $detailedQueryParams, \DateTime $requestedDateTimeObject = null)
    {
        App::getContainer()->wireService('EventPackage/entity/CalendarEvent');
        App::getContainer()->wireService('EventPackage/repository/CalendarEventRepository');

        $additionalDetailedQueryParams = [
            ['refKey' => 'cal_ev.frequency_type', 'paramKey' => 'cal_ev_frequency_type', 'operator' => '=', 'value' => CalendarEvent::FREQUENCY_TYPE_ONE_TIME],
            // ['refKey' => 'cal_ev.start_date', 'paramKey' => 'cal_ev_start_date', 'operator' => ($todayDate == $requestedDate ? '=' : '<='), 'value' => $requestedDate],
            // ['refKey' => 'unit.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_ACTIVE]
        ];
        $detailedQueryParams = array_merge($detailedQueryParams, $additionalDetailedQueryParams);

        $eventsActualityData = CalendarEventRepository::getOneTimeCalendarEventActualityData(
            $userAccount,
            $additionalSelectString,
            $additionalJoinString,
            $detailedQueryParams,
            'oneTimeEvent',
            false
        );

        return $eventsActualityData;
    }

    public static function getRecurringCalendarEventActualityData(UserAccount $userAccount = null, string $additionalSelectString, string $additionalJoinString, array $detailedQueryParams, \DateTime $requestedDateObject = null)
    {
        App::getContainer()->wireService('EventPackage/repository/CalendarEventRepository');

        $additionalDetailedQueryParams = [
            ['refKey' => 'cal_ev.frequency_type', 'paramKey' => 'cal_ev_frequency_type', 'operator' => '<>', 'value' => CalendarEvent::FREQUENCY_TYPE_ONE_TIME],
            // ['refKey' => 'unit.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_ACTIVE],
            // ['refKey' => 'cal_ev.start_date', 'paramKey' => 'cal_ev_start_date', 'operator' => ($todayDate == $requestedDate ? '=' : '<='), 'value' => $requestedDate]
        ];
        $detailedQueryParams = array_merge($detailedQueryParams, $additionalDetailedQueryParams);

        $eventsActualityData = CalendarEventRepository::getRecurringCalendarEventActualityData(
            $userAccount,
            $additionalSelectString,
            $additionalJoinString,
            $detailedQueryParams,
            'recurringEvent',
            false
        );

        return $eventsActualityData;
    }
}