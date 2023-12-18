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
    public static function getDashboardData_OLD(UserAccount $userAccount, $ascScale = null)
    {
        App::getContainer()->wireService('UserPackage/entity/UserAccount');
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');

        // $todayDate = date('Y-m-d');
        // $periodStartDateTime = new \DateTime($todayDate);
        // $periodStartDateTime->modify("-" . self::DASHBOARD_PERIOD_IN_HOURS . " hours");
        // $periodStart = $periodStartDateTime->format('Y-m-d');
        // $periodEndDateTime = new \DateTime($todayDate);
        // $periodEndDateTime->modify("+" . self::DASHBOARD_PERIOD_IN_HOURS . " hours");
        // $periodEnd = $periodEndDateTime->format('Y-m-d');
        // $dayOfWeek = date('l');

        $processedRequestData = AscRequestService::getProcessedRequestData();
    
        $todaysOneTimeCalendarEventData = AscCalendarEventService::getTodaysOneTimeCalendarEventData($userAccount, $ascScale);
        $todaysRecurringCalendarEventData = AscCalendarEventService::getTodaysRecurringCalendarEventData($userAccount, $ascScale);
        $expiredOneTimeCalendarEventData = AscCalendarEventService::getExpiredOneTimeCalendarEventData($userAccount, $ascScale);
        $expiredRecurringCalendarEventData = AscCalendarEventService::getExpiredRecurringCalendarEventData($userAccount, $ascScale);
        $closedCalendarEventData = AscCalendarEventService::getClosedCalendarEventData($userAccount, $ascScale);
        $postponedCalendarEventData = AscCalendarEventService::getPostponedCalendarEventData($userAccount, $ascScale);

        // dump($todaysOneTimeCalendarEventData);exit;

        $return = [
            'ascScale' => $processedRequestData['ascScale'],
            'todaysOneTimeCalendarEventData' => $todaysOneTimeCalendarEventData,
            'todaysRecurringCalendarEventData' => $todaysRecurringCalendarEventData,
            'expiredOneTimeCalendarEventData' => $expiredOneTimeCalendarEventData,
            'expiredRecurringCalendarEventData' => $expiredRecurringCalendarEventData,
            'closedCalendarEventData' => $closedCalendarEventData,
            'postponedCalendarEventData' => $postponedCalendarEventData,
            'sumTodays' => (count($todaysOneTimeCalendarEventData) + count($todaysRecurringCalendarEventData)),
            'sumExpired' => (count($expiredOneTimeCalendarEventData) + count($expiredRecurringCalendarEventData)),
            'sumClosed' => count($closedCalendarEventData),
            'sumPostponed' => count($postponedCalendarEventData)
        ];

        // dump($return);exit;

        return $return;
    }

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

    public static function initCalendarEventCheckForUserAccount(UserAccount $userAccount, AscScale $ascScale = null)
    {
        App::getContainer()->wireService('projects/ASC/entity/AscUnit');
        App::getContainer()->wireService('projects/ASC/entity/AscScale');

        $detailedQueryParamsBase = [];
        // Example: $detailedQueryParams[] = ['refKey' => 'ced.start_date', 'paramKey' => 'ced_start_date', 'operator' => '<=', 'value' => $currentWeekDates['weekStartDate']],
    
        $detailedQueryParamsBase[] = ['refKey' => 'cal_ev.event_type', 'paramKey' => 'cal_event_type', 'operator' => '=', 'value' => AscUnit::DUE_CALENDAR_EVENT_TYPE];
    
        if ($ascScale) {
            $detailedQueryParamsBase[] = ['refKey' => 'unit.asc_scale_id', 'paramKey' => 'asc_scale_id', 'operator' => '=', 'value' => $ascScale->getId()];
        }

        $detailedQueryParams = $detailedQueryParamsBase;

        $additionalSelectString = "unit.id as 'unit_id',";

        $additionalJoinString = "
        JOIN asc_unit as unit ON unit.calendar_event_id = cal_ev.id
        JOIN asc_scale as scale ON scale.id = unit.asc_scale_id 
        JOIN user_account as user_acc ON user_acc.id = scale.user_account_id 
        ";
        
        $calendarEventData = [];

        # Checking todays's one-time events
        $additionalCalendarEventData = self::getTodaysOneTimeCalendarEventData($userAccount, $additionalSelectString, $additionalJoinString, $detailedQueryParams);
        $calendarEventData = array_merge($calendarEventData, $additionalCalendarEventData);

        # Checking todays's recurring events
        $additionalCalendarEventData = self::getTodaysRecurringCalendarEventData($userAccount, $additionalSelectString, $additionalJoinString, $detailedQueryParams);
        $calendarEventData = array_merge($calendarEventData, $additionalCalendarEventData);

        # Checking expired one-time events
        $additionalCalendarEventData = self::getExpiredOneTimeCalendarEventData($userAccount, $additionalSelectString, $additionalJoinString, $detailedQueryParams);
        $calendarEventData = array_merge($calendarEventData, $additionalCalendarEventData);

        # Checking expired recurring events
        // $additionalCalendarEventData = self::getExpiredRecurringCalendarEventData($userAccount, $additionalJoinString, $detailedQueryParams);
        // $calendarEventData = array_merge($calendarEventData, $additionalCalendarEventData);

        # Checking todays's closed events
        $additionalCalendarEventData = self::getTodaysClosedCalendarEventData($userAccount, $additionalSelectString, $additionalJoinString, $detailedQueryParams);
        $calendarEventData = array_merge($calendarEventData, $additionalCalendarEventData);

        # Checking postponed events
        $additionalCalendarEventData = self::getPostponedCalendarEventData($userAccount, $additionalSelectString, $additionalJoinString, $detailedQueryParams);
        $calendarEventData = array_merge($calendarEventData, $additionalCalendarEventData);

        dump($calendarEventData);exit;

        CalendarEventChecker::check(
            $userAccount,
            $calendarEventData
        );
    }

    // Checker method in the project
    public static function getTodaysOneTimeCalendarEventData(UserAccount $userAccount, string $additionalSelectString, string $additionalJoinString, array $detailedQueryParams, \DateTime $requestedDateTimeObject = null)
    {
        App::getContainer()->wireService('EventPackage/entity/CalendarEvent');
        App::getContainer()->wireService('EventPackage/repository/CalendarEventRepository');

        $todayDateTimeObject = new \DateTime();
        if ($requestedDateTimeObject === null || $requestedDateTimeObject > $todayDateTimeObject) {
            $requestedDateTimeObject = $todayDateTimeObject;
        }

        $todayDate = $todayDateTimeObject->format('Y-m-d');
        $requestedDate = $requestedDateTimeObject->format('Y-m-d');

        App::getContainer()->wireService('EventPackage/entity/CalendarEvent');

        $additionalDetailedQueryParams = [
            ['refKey' => 'cal_ev.frequency_type', 'paramKey' => 'cal_ev_frequency_type', 'operator' => '=', 'value' => CalendarEvent::FREQUENCY_TYPE_ONE_TIME],
            ['refKey' => 'cal_ev.start_date', 'paramKey' => 'cal_ev_start_date', 'operator' => ($todayDate == $requestedDate ? '=' : '<='), 'value' => $requestedDate],
            ['refKey' => 'unit.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_ACTIVE]
        ];

        $detailedQueryParams = array_merge($detailedQueryParams, $additionalDetailedQueryParams);

        $calendarEventData = CalendarEventRepository::findCalendarEventData(
            $userAccount, 
            $additionalSelectString,
            $additionalJoinString,
            $detailedQueryParams,
            'todaysOneTimeCalendarEventData'
        );

        return $calendarEventData;
    }

    public static function getTodaysRecurringCalendarEventData(UserAccount $userAccount = null, string $additionalSelectString, string $additionalJoinString, array $detailedQueryParams, \DateTime $requestedDateObject = null)
    {
        App::getContainer()->wireService('EventPackage/repository/CalendarEventRepository');

        $recurrences = CalendarEventRepository::getActiveRecurrencesOfCalendarEventData();
        dump($recurrences);exit;

        $todayDateObject = new \DateTime();
        if ($requestedDateObject === null || $requestedDateObject > $todayDateObject) {
            $requestedDateObject = $todayDateObject;
        }
        $todayDate = $todayDateObject->format('Y-m-d');
        $requestedDate = $requestedDateObject->format('Y-m-d');

        $additionalDetailedQueryParams = [
            ['refKey' => 'cal_ev.frequency_type', 'paramKey' => 'cal_ev_frequency_type', 'operator' => '<>', 'value' => CalendarEvent::FREQUENCY_TYPE_ONE_TIME],
            ['refKey' => 'unit.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_ACTIVE],
            ['refKey' => 'cal_ev.start_date', 'paramKey' => 'cal_ev_start_date', 'operator' => ($todayDate == $requestedDate ? '=' : '<='), 'value' => $requestedDate]
        ];
        $detailedQueryParams = array_merge($detailedQueryParams, $additionalDetailedQueryParams);

        $calendarEventData = CalendarEventRepository::findCalendarEventData(
            $userAccount, 
            $additionalSelectString,
            $additionalJoinString,
            $detailedQueryParams,
            'todaysRecurringCalendarEventData',
            true
        );

        return $calendarEventData;
    }

    /**
     * @todo : ezt az AI írta, custom week-esre. Meg kéne nézni, hogy lehet-e salvage-elni valamit.
    */
    // public static function getTodaysRecurringCalendarEventData_OLD(UserAccount $userAccount = null, string $additionalSelectString, string $additionalJoinString, array $detailedQueryParams, \DateTime $requestedDateObject = null)
    // {
    //     App::getContainer()->wireService('EventPackage/repository/CalendarEventRepository');

    //     $todayDateObject = new \DateTime();
    //     if ($requestedDateObject === null || $requestedDateObject > $todayDateObject) {
    //         $requestedDateObject = $todayDateObject;
    //     }
    //     $todayDate = $todayDateObject->format('Y-m-d');
    //     $requestedDate = $requestedDateObject->format('Y-m-d');

    //     $additionalDetailedQueryParams = [
    //         ['refKey' => 'cal_ev.frequency_type', 'paramKey' => 'cal_ev_frequency_type', 'operator' => '<>', 'value' => CalendarEvent::FREQUENCY_TYPE_ONE_TIME],
    //         ['refKey' => 'unit.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_ACTIVE]
    //     ];
    //     $detailedQueryParams = array_merge($detailedQueryParams, $additionalDetailedQueryParams);
    
    //     App::getContainer()->wireService('StatisticsPackage/service/CustomWeekManager');
    //     /**
    //      * @method CustomWeekManager::listCustomWeekDates(): Lists all custom weeks from the @var $date until the current date.
    //     */
    //     $customWeeks = CustomWeekManager::listCustomWeekDates($requestedDate);
    //     $resultData = [];
    
    //     foreach ($customWeeks as $customWeek) {
    //         $weekStartDate = $customWeek['weekStartDate'];
    //         $weekEndDate = $customWeek['weekEndDate'];
    //         $loopAdditionalDetailedQueryParams = [];
    //         $loopAdditionalDetailedQueryParams[] = ['refKey' => 'cal_ev.start_date', 'paramKey' => 'cal_ev_start_date', 'operator' => ($todayDate == $requestedDate ? '=' : '<='), 'value' => $weekStartDate];
    //         $loopDetailedQueryParams = array_merge($detailedQueryParams, $loopAdditionalDetailedQueryParams);
    
    //         // dump($detailedQueryParams);
    //         // $data = self::findScaleCalendarEventData($userAccount, $ascScale, $additionalParameters);
    //         $calendarEventData = CalendarEventRepository::findCalendarEventData(
    //             $userAccount, 
    //             $additionalSelectString,
    //             $additionalJoinString,
    //             $loopDetailedQueryParams,
    //             'todaysRecurringCalendarEventData',
    //             true
    //         );
    
    //         foreach ($calendarEventData as $calendarEventDataRow) {
    //             $eventStartDate = new \DateTime($calendarEventDataRow['calendar_event_start_date']);
    //             $eventStartDate->setTime(0, 0, 0);
    //             $eventEndDate = new \DateTime($calendarEventDataRow['calendar_event_start_date']);
    //             $eventEndDate->setTime(23, 59, 59);
    
    //             if ($eventStartDate >= $weekStartDate && $eventEndDate <= $weekEndDate) {
    //                 $resultData[] = $calendarEventDataRow;
    //             }
    //         }
    //     }
    
    //     return $resultData;
    // }

    public static function getExpiredOneTimeCalendarEventData(UserAccount $userAccount = null, string $additionalSelectString, string $additionalJoinString, array $detailedQueryParams, \DateTime $requestedDateObject = null)
    {
        App::getContainer()->wireService('EventPackage/repository/CalendarEventRepository');

        $currentDate = new \DateTime();
        $additionalDetailedQueryParams = [
            ['refKey' => 'cal_ev.start_date', 'paramKey' => 'cal_ev_start_date', 'operator' => '<=', 'value' => $currentDate->format('Y-m-d')],
            ['refKey' => 'unit.status', 'paramKey' => 'status', 'operator' => '=', 'value' => CalendarEvent::STATUS_ACTIVE]
        ];
        $detailedQueryParams = array_merge($detailedQueryParams, $additionalDetailedQueryParams);

        $calendarEventData = CalendarEventRepository::findCalendarEventData(
            $userAccount, 
            $additionalSelectString, 
            $additionalJoinString,
            $detailedQueryParams,
            'expiredOneTimeCalendarEventData'
        );

        return $calendarEventData;
    }

    // public static function getExpiredRecurringCalendarEventData(UserAccount $userAccount = null, string $additionalJoinString, array $detailedQueryParams, \DateTime $requestedDateObject = null)
    // {
    //     return [];
    // }

    public static function getTodaysClosedCalendarEventData(UserAccount $userAccount = null, string $additionalSelectString, string $additionalJoinString, array $detailedQueryParams, \DateTime $requestedDateObject = null)
    {
        App::getContainer()->wireService('EventPackage/repository/CalendarEventRepository');

        $currentDate = new \DateTime();
        $additionalDetailedQueryParams = [
            ['refKey' => 'cal_ev.start_date', 'paramKey' => 'cal_ev_start_date', 'operator' => '<=', 'value' => $currentDate->format('Y-m-d')],
            ['refKey' => 'unit.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_CLOSED]
        ];
        $detailedQueryParams = array_merge($detailedQueryParams, $additionalDetailedQueryParams);

        $calendarEventData = CalendarEventRepository::findCalendarEventData(
            $userAccount, 
            $additionalSelectString,
            $additionalJoinString,
            $detailedQueryParams,
            'todaysClosedCalendarEventData'
        );

        return $calendarEventData;
    }

    public static function getPostponedCalendarEventData(UserAccount $userAccount = null, string $additionalSelectString, string $additionalJoinString, array $detailedQueryParams, \DateTime $requestedDateObject = null)
    {
        return [];

        // App::getContainer()->wireService('EventPackage/repository/CalendarEventRepository');
        // App::getContainer()->wireService('StatisticsPackage/service/CustomWeekManager');
        // $currentWeekDates = CustomWeekManager::getWeekDates(CustomWeekManager::getWeekNumber(date('Y-m-d')), date('Y'));

        // $additionalDetailedQueryParams = [
        //     ['refKey' => 'ced.start_date', 'paramKey' => 'ced_start_date', 'operator' => '<=', 'value' => $currentWeekDates['weekStartDate']],
        //     ['refKey' => 'unit.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_ACTIVE]
        // ];
        // $detailedQueryParams = array_merge($detailedQueryParams, $additionalDetailedQueryParams);

        // $calendarEventData = CalendarEventRepository::findCalendarEventData(
        //     $userAccount, 
        //     $additionalJoinString,
        //     $detailedQueryParams
        // );

        // return $calendarEventData;
    }
}