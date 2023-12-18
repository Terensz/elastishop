<?php
namespace framework\packages\EventPackage\repository;

use App;
use framework\component\helper\DateUtils;
use framework\component\helper\MathHelper;
use framework\component\parent\DbRepository;
use framework\kernel\DbManager\manager\DbManager;
use framework\packages\EventPackage\entity\CalendarEvent;
use framework\packages\StatisticsPackage\service\CustomWeekManager;
use framework\packages\UserPackage\entity\UserAccount;

/**
 * Never use this repository alone!
 * Always use the CalendarEventFactory!
*/
class CalendarEventRepository extends DbRepository
{

    // public static function getOneTimeCalendarEventData(UserAccount $userAccount = null, $additionalJoinString = '', $detailedQueryParams = [], \DateTime $requestedDateObject = null)
    // {
    //     $data = self::findCalendarEventData(
    //         $userAccount, 
    //         $additionalJoinString,
    //         $detailedQueryParams
    //     );

    //     return $data;
    // }

    // public static function getTodaysPostponedCalendarEventData(UserAccount $userAccount = null) : array
    // {
    //     App::getContainer()->wireService('StatisticsPackage/service/CustomWeekManager');
    //     $currentWeekDates = CustomWeekManager::getWeekDates(CustomWeekManager::getWeekNumber(date('Y-m-d')), date('Y'));
        
    //     // $queryParams = self::getQueryParamsBase();
    //     // $queryParams[] = ['refKey' => 'ced.start_date', 'paramKey' => 'ced_start_date', 'operator' => '<=', 'value' => $currentWeekDates['weekStartDate']];
    //     // $queryParams[] = ['refKey' => 'au.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_ACTIVE];

    //     $additionalDetailedQueryParameters = [
    //         ['refKey' => 'ced.start_date', 'paramKey' => 'ced_start_date', 'operator' => '<=', 'value' => $currentWeekDates['weekStartDate']],
    //         ['refKey' => 'au.status', 'paramKey' => 'status', 'value' => CalendarEvent::STATUS_ACTIVE]
    //     ];
        
    //     $data = self::findCalendarEventData($userAccount, $ascScale, $additionalDetailedQueryParameters);

    //     return $data;
    // }

    /*
    # Example usage:
    // caller method:

    $queryParams = [];
    // Example: $queryParams[] = ['refKey' => 'ced.start_date', 'paramKey' => 'ced_start_date', 'operator' => '<=', 'value' => $currentWeekDates['weekStartDate']],

    $queryParams = [
        'event_type' => AscUnit::DUE_CALENDAR_EVENT_TYPE,
    ];

    $queryParams[] = ['refKey' => 'cal_ev.event_type', 'paramKey' => 'cal_event_type', 'operator' => '=', 'value' => AscUnit::DUE_CALENDAR_EVENT_TYPE,

    if ($ascScale) {
        $queryParams['asc_scale_id'] = $ascScale->getId();
    }

    CalendarEventChecker::check(
        null,
        "
        JOIN asc_unit as unit ON unit.calendar_event_id = cal_ev.id
        JOIN asc_scale as scale ON scale.id = unit.asc_scale_id 
        JOIN user_account as user_acc ON user_acc.id = units_scale.user_account_id 
        ",
        "
        WHERE cal_ev.event_type = :cal_event_type 
        ".($ascScale ? "AND au.asc_scale_id = :asc_scale_id " : "")."
        ",
        $queryParams
    );
    class CalendarEventChecker {
        ...
        CalendarEventRepository::findScaleCalendarEventData(
            null,

        );
    */
    public static function findCalendarEventData(
        UserAccount $userAccount = null, 
        string $additionalSelectString = '', 
        string $additionalJoinString = '', 
        array $detailedQueryParams = [],
        string $recordMark = '',
        bool $debug = false
    )
    {
        // $debug = true;
        App::getContainer()->wireService('projects/ASC/entity/AscScale');
        App::getContainer()->wireService('EventPackage/entity/CalendarEvent');

        if (!$userAccount) {
            $userAccount = App::getContainer()->getUser()->getUserAccount();
            if (!$userAccount) {
                return [];
            }
        }
        $recordMark = $recordMark ? : 'emptyRecordMark';
        $stm = "SELECT 
            {$additionalSelectString}
            cal_ev.id as 'calendar_event_id',
            '{$recordMark}' as 'record_mark',
            cal_ev.frequency_type as 'calendar_event_frequency_type',
            cal_ev.start_date as 'calendar_event_start_date',
            cal_ev.start_time as 'calendar_event_start_time',
            cal_ev.end_date as 'calendar_event_end_date', -- Right now it's unused
            cal_ev.end_time as 'calendar_event_end_time', -- Right now it's unused
            cal_ev.recurrence_unit as 'recurrence_unit',
            cal_ev.recurrence_interval as 'recurrence_interval',
            cal_ev.recurrence_day_mon,
            cal_ev.recurrence_day_tue,
            cal_ev.recurrence_day_wed,
            cal_ev.recurrence_day_thu,
            cal_ev.recurrence_day_fri,
            cal_ev.recurrence_day_sat,
            cal_ev.recurrence_day_sun
        FROM calendar_event as cal_ev 
        ".$additionalJoinString."
        ";

        $dbm = App::getContainer()->getDbManager();


        $queryParams = [];
        $whereConditions = [];
        foreach ($detailedQueryParams as $detailedQueryParam) {
            $refKey = $detailedQueryParam['refKey'];
            $queryParamKey = $detailedQueryParam['paramKey'];
            $value = $detailedQueryParam['value'];
            $operator = isset($detailedQueryParam['operator']) ? $detailedQueryParam['operator'] : '=';
            $whereConditions[] = "$refKey $operator :$queryParamKey";
            $queryParams[$queryParamKey] = $value;
        }

        if (!empty($whereConditions)) {
            $stm .= "WHERE " . implode(" AND ", $whereConditions);
        }

        $result = $dbm->findAll($stm, $queryParams);

        if ($debug) {
            dump(nl2br($stm));
            dump($queryParams);
            dump($result);
            // exit;
        }

        return $result;
    }

    public static function getOneTimeCalendarEventActualityData(
        UserAccount $userAccount = null,
        string $additionalSelectString = '', 
        string $additionalJoinString = '', 
        array $detailedQueryParams = [],
        string $recordMark = '',
        bool $debug = false
    )
    {
        $events = self::findCalendarEventData(
            $userAccount,
            $additionalSelectString,
            $additionalJoinString,
            $detailedQueryParams,
            $recordMark, 
            $debug
        );
    
        $foundEvents = [];
    
        foreach ($events as $event) {
            $startDate = new \DateTime($event['calendar_event_start_date']);
            $foundEvents[] = [
                'eventData' => $event,
                'actualityDate' => $startDate->format('Y-m-d')
            ];
        }

        return $foundEvents;
    }
    
    public static function getRecurringCalendarEventActualityData(
        UserAccount $userAccount = null,
        string $additionalSelectString = '', 
        string $additionalJoinString = '', 
        array $detailedQueryParams = [],
        string $recordMark = '',
        bool $debug = false
    )
    {
        $events = self::findCalendarEventData(
            $userAccount,
            $additionalSelectString,
            $additionalJoinString,
            $detailedQueryParams,
            $recordMark, 
            $debug
        );

        // dump($recurringEvents);exit;
    
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
    
        $foundEvents = [];
    
        foreach ($events as $event) {
            $foundEventData = null;

            /**
             * First: let's determine if this event should trigger on specific days of the week
            */
            $recurrenceOnlyOnDefinedDays = (
                $event['recurrence_day_mon'] ||
                $event['recurrence_day_tue'] ||
                $event['recurrence_day_wed'] ||
                $event['recurrence_day_thu'] ||
                $event['recurrence_day_fri'] ||
                $event['recurrence_day_sat'] ||
                $event['recurrence_day_sun']
            );
    
            $startDate = new \DateTime($event['calendar_event_start_date']);
            $startDateString = $event['calendar_event_start_date'];
            $startYear = date('Y', strtotime($startDateString));
            /**
             * We distinguish the year bound to the week, because on some dates, end of December slips to next year.
            */
            $weeksYear = $startYear;
            $startMonth = (int)date('m', strtotime($startDateString));
            $startWeek = date('W', strtotime($startDateString));
            $startDayOfMonth = DateUtils::getDayOfMonth($startDateString);
            if ($startWeek > 52) {
                $startWeek = 1;
                $weeksYear++;
            }

            /**
             * The starting week is which week of the start month? (1-5)
            */
            $startWeekIsWeekOfMonth = DateUtils::getWeekOfMonthFromDate($startDateString);

            // dump('startYear: '.$startYear.', startMonth: '.$startMonth.', startWeek: '.$startWeek.', startWeekIsWeekOfMonth: '.$startWeekIsWeekOfMonth.', startDay: '.$startDate->format('Y-m-d'));

            $loopedDate = clone $startDate;
            /**
             * We are looping through on each and every day from the first day of this recurring event, until now.
            */
            $daysPassed = 0;
            $weeksPassed = 0;
            $thisWeekIsWeekOfMonth = 1;
            while ($loopedDate <= $today) {
                $loopedDateString = $loopedDate->format('Y-m-d');
                $loopDayOfWeek = strtolower($loopedDate->format('D'));
                $loopDayOfMonth = DateUtils::getDayOfMonth($loopedDateString);
                $loopField = "recurrence_day_" . substr($loopDayOfWeek, 0, 3);
                $loopYear = date('Y', strtotime($loopedDateString));
                $loopWeeksYear = $loopYear;
                $loopMonth = (int)date('m', strtotime($startDateString));
                $loopWeek = date('W', strtotime($loopedDateString));
                if ($loopWeek > 52) {
                    $loopWeek = 1;
                    $loopWeeksYear++;
                }

                $thisWeekIsWeekOfMonth = DateUtils::getWeekOfMonthFromDate($loopedDateString);

                // dump('loopYear: '.$loopYear.', loopMonth: '.$loopMonth.', loopWeek: '.$loopWeek.', loopDay: '.$loopedDate->format('Y-m-d'));

                /**
                 * Weekly recurrence
                */
                if ($event['recurrence_unit'] == 'Week') {
                    /**
                     * If the actual week is in the recurrence interval
                    */
                    if (MathHelper::isWholeNumber($weeksPassed / $event['recurrence_interval'])) {
                        /**
                         * If the event recurs on specific days 
                        */
                        if ($recurrenceOnlyOnDefinedDays) {

                            if ($event[$loopField]) {
                                $foundEventData = [
                                    'eventData' => $event,
                                    'actualityDate' => $loopedDate->format('Y-m-d')
                                ];
                            }
                        }
                        /**
                         * If no recurrence day specified, than the event triggers on 
                         * the day exactly whole weeks after it was created.
                        */
                        elseif (!$recurrenceOnlyOnDefinedDays) {
                            if (MathHelper::isWholeNumber($daysPassed / 7)) {
                                $foundEventData = [
                                    'eventData' => $event,
                                    'actualityDate' => $loopedDate->format('Y-m-d')
                                ];
                            }
                        }
                    }
                }

                /**
                 * Monthly recurrence
                */
                if ($event['recurrence_unit'] == 'Month') {
                    /**
                     * If the actual week is the same of this month, as the starting day's week
                     */
                    if ($thisWeekIsWeekOfMonth == $startWeekIsWeekOfMonth) {
                        /**
                         * If the event recurs on specific days
                         */
                        if ($recurrenceOnlyOnDefinedDays) {
                            if ($event[$loopField]) {
                                $foundEventData = [
                                    'eventData' => $event,
                                    'actualityDate' => $loopedDate->format('Y-m-d')
                                ];
                            }
                        }
                        /**
                         * If no recurrence day specified, then the event triggers on
                         * the day exactly whole months after it was created.
                         */
                        elseif (!$recurrenceOnlyOnDefinedDays) {
                            if ($loopDayOfMonth == $startDayOfMonth) {
                                $foundEventData = [
                                    'eventData' => $event,
                                    'actualityDate' => $loopedDate->format('Y-m-d')
                                ];
                            }
                        }
                    }
                }

                /**
                 * Annual recurrence
                */
                if ($event['recurrence_unit'] == 'Year') {
                    if ($startMonth == $loopMonth && $thisWeekIsWeekOfMonth == $startWeekIsWeekOfMonth) {
                        if ($recurrenceOnlyOnDefinedDays) {
                            if ($event[$loopField]) {
                                $foundEventData = [
                                    'eventData' => $event,
                                    'actualityDate' => $loopedDate->format('Y-m-d')
                                ];
                            }
                        }
                        elseif (!$recurrenceOnlyOnDefinedDays) {
                            if ($loopDayOfMonth == $startDayOfMonth) {
                                $foundEventData = [
                                    'eventData' => $event,
                                    'actualityDate' => $loopedDate->format('Y-m-d')
                                ];
                            }
                        }
                    }
                }
                
                $loopedDate->add(new \DateInterval("P1D"));
                $daysPassed++;
                if (MathHelper::isWholeNumber($daysPassed / 7)) {
                    $weeksPassed++;
                }


            } // end of while

            if ($foundEventData) {
                $foundEvents[] = $foundEventData;
            }
        }

        // dump($foundEvents);exit;
    
        return $foundEvents;
    }
    
    // public static function findCalendarEventData_OLD(
    //     UserAccount $userAccount = null, 
    //     string $additionalSelectString = '', 
    //     string $additionalJoinString = '', 
    //     array $detailedQueryParams = [],
    //     string $recordMark = '',
    //     bool $debug = false
    // )
    // {
    //     // $debug = true;
    //     App::getContainer()->wireService('projects/ASC/entity/AscScale');
    //     App::getContainer()->wireService('EventPackage/entity/CalendarEvent');

    //     if (!$userAccount) {
    //         $userAccount = App::getContainer()->getUser()->getUserAccount();
    //         if (!$userAccount) {
    //             return [];
    //         }
    //     }
    //     $recordMark = $recordMark ? : 'emptyRecordMark';
    //     $stm = "SELECT 
    //         -- au.id as 'asc_unit_id',
    //         {$additionalSelectString}
    //         cal_ev.id as 'calendar_event_id',
    //         -- CONCAT('{$recordMark}'),
    //         '{$recordMark}' as 'record_mark',
    //         -- cal_ev.calendar_event_delay_id as 'calendar_event_delay_id',
    //         -- cal_ev.title as 'calendar_event_title', -- Right now it's unused
    //         -- cal_ev.frequency_type as 'frequency_type',
    //         cal_ev.frequency_type as 'calendar_event_frequency_type',
    //         cal_ev.start_date as 'calendar_event_start_date',
    //         cal_ev.start_time as 'calendar_event_start_time',
    //         -- cal_ev_d.start_date as 'calendar_event_delay_start_date',
    //         -- cal_ev_d.start_time as 'calendar_event_delay_start_time',
    //         -- CASE WHEN cal_ev_d.start_date IS NOT NULL 
    //         --     THEN cal_ev_d.start_date 
    //         --     ELSE cal_ev.start_date 
    //         --     END as 'calendar_event_due_date',
    //         cal_ev.end_date as 'calendar_event_end_date', -- Right now it's unused
    //         cal_ev.end_time as 'calendar_event_end_time', -- Right now it's unused
    //         cal_ev.recurrence_unit as 'recurrence_unit',
    //         cal_ev.recurrence_interval as 'recurrence_interval',
    //         cal_ev.recurrence_day_mon,
    //         cal_ev.recurrence_day_tue,
    //         cal_ev.recurrence_day_wed,
    //         cal_ev.recurrence_day_thu,
    //         cal_ev.recurrence_day_fri,
    //         cal_ev.recurrence_day_sat,
    //         cal_ev.recurrence_day_sun
    //     FROM calendar_event as cal_ev 
    //     -- LEFT JOIN calendar_event_delay as cal_ev_d ON cal_ev_d.id = cal_ev.calendar_event_delay_id 
    //     ".$additionalJoinString."
    //     ";

    //     $dbm = App::getContainer()->getDbManager();


    //     $queryParams = [];
    //     $whereConditions = [];
    //     foreach ($detailedQueryParams as $detailedQueryParam) {
    //         $refKey = $detailedQueryParam['refKey'];
    //         $queryParamKey = $detailedQueryParam['paramKey'];
    //         $value = $detailedQueryParam['value'];
    //         $operator = isset($detailedQueryParam['operator']) ? $detailedQueryParam['operator'] : '=';
    //         $whereConditions[] = "$refKey $operator :$queryParamKey";
    //         $queryParams[$queryParamKey] = $value;
    //     }

    //     if (!empty($whereConditions)) {
    //         $stm .= "WHERE " . implode(" AND ", $whereConditions);
    //     }

    //     $result = $dbm->findAll($stm, $queryParams);

    //     if ($debug) {
    //         dump(nl2br($stm));
    //         dump($queryParams);
    //         dump($result);
    //         // exit;
    //     }

    //     return $result;
    // }
}
