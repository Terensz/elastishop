<?php
namespace framework\packages\EventPackage\service;

use App;
use framework\component\parent\Service;
use framework\kernel\DbManager\manager\DbManager;
use framework\packages\EventPackage\entity\CalendarCheck;
use framework\packages\EventPackage\entity\CalendarCheckEventActuality;
use framework\packages\EventPackage\entity\CalendarEvent;
use framework\packages\EventPackage\entity\CalendarEventActuality;
use framework\packages\EventPackage\repository\CalendarCheckEventActualityRepository;
use framework\packages\EventPackage\repository\CalendarCheckRepository;
use framework\packages\EventPackage\repository\CalendarEventActualityRepository;
use framework\packages\EventPackage\repository\CalendarEventRepository;
use framework\packages\StatisticsPackage\service\CustomWeekManager;
use framework\packages\UserPackage\entity\UserAccount;
use projects\ASC\entity\AscScale;
use projects\ASC\entity\AscUnit;

class CalendarEventChecker extends Service
{
    // public static function getTodaysPostponedCalendarEventData()
    // {
    //     CalendarEventChecker::check(
    //         null,
    //         "
    //         JOIN asc_unit as unit ON unit.calendar_event_id = cal_ev.id
    //         JOIN asc_scale as scale ON scale.id = unit.asc_scale_id 
    //         JOIN user_account as user_acc ON user_acc.id = units_scale.user_account_id 
    //         ",
    //         $detailedQueryParams
    //     );
    // }

    public static function check(
        UserAccount $userAccount,
        array $calendarEventActualityData = null,
        // string $additionalJoinString = null, 
        // array $detailedQueryParams = [], 
        bool $debug = false
    )
    {
        if (empty($calendarEventActualityData)) {
            return null;
        }
        /**
         * This is the header record of one run of a check.
        */
        App::getContainer()->wireService('EventPackage/entity/CalendarCheck');
        $calendarCheck = new CalendarCheck();
        $calendarCheck->setRunDate(date('Y-m-d'));
        $calendarCheck = $calendarCheck->getRepository()->store($calendarCheck);

        App::getContainer()->wireService('EventPackage/repository/CalendarEventRepository');
        $calendarEventRepository = new CalendarEventRepository();

        App::getContainer()->wireService('EventPackage/repository/CalendarEventActualityRepository');
        $calendarEventActualityRepository = new CalendarEventActualityRepository();

        // if (!$calendarEventData) {
        //     $calendarEventData = CalendarEventRepository::findCalendarEventData(
        //         $userAccount, 
        //         $additionalJoinString,
        //         $detailedQueryParams, 
        //         $debug
        //     );
        // }

        App::getContainer()->wireService('EventPackage/repository/CalendarCheckEventActualityRepository');
        $calendarCheckEventActualityRepository = new CalendarCheckEventActualityRepository();
        
        App::getContainer()->wireService('EventPackage/entity/CalendarEventActuality');

        App::getContainer()->wireService('EventPackage/repository/CalendarCheckRepository');
        $calendarCheckRepository = new CalendarCheckRepository();

        $dbm = App::getContainer()->getDbManager();

        /**
         * Looping through @var array $todaysCalendarEventData, which contains 'calendar_event_id'. 
         * ---
         * 1.: The current today's @var CalendarEvent $calendarEvent is not checked yet, than we create a new
         * - @var EventActuality , which represents an instance of every alert of an event (recurring due dates create an EventActuality every day the reach the due)
         * - @var CalendarCheckEventActuality , which binds @var CalendarCheck to @var CalendarEventActuality
        */
        foreach ($calendarEventActualityData as $calendarEventActualityDataRow) {
            $calendarEventId = $calendarEventActualityDataRow['eventData']['calendar_event_id'];
            // dump($calendarEventId);
            $calendarEvent = $calendarEventRepository->find($calendarEventId);
            $calendarCheckEventActuality = $calendarCheckEventActualityRepository->findByEventAndDate($dbm, $calendarEventId, date('Y-m-d'));
            // $isAlreadyChecked = self::isAlreadyChecked($dbm, $calendarEventId);
            $isAlreadyChecked = $calendarCheckEventActuality ? true : false;

            if ($isAlreadyChecked) {
                // $calendarEventActuality = $calendarEventActualityRepository->findExisting($dbm, $calendarCheckEventActuality);
                $calendarEventActuality = $calendarCheckEventActuality->getCalendarEventActuality();
                $calendarEventActuality = self::createOrUpdateCalendarEventActuality($userAccount, $calendarEvent, $calendarEventActuality);
            } else {
                $calendarEventActuality = self::createOrUpdateCalendarEventActuality($userAccount, $calendarEvent, null);
                // dump($calendarEventActuality);
                $calendarCheckEventActuality = self::createCalendarCheckEventActuality($calendarCheck, $calendarEventActuality);
            }
        }
    }

    // public static function isAlreadyChecked(DbManager $dbm, int $calendarEventId)
    // {
    //     $checkedEvent = $calendarCheckEventRepository->findCheckedEvent($dbm, $calendarEventId, date('Y-m-d'));

    //     return $checkedEvent ? true : false;
    // }

    public static function createOrUpdateCalendarEventActuality(UserAccount $userAccount, CalendarEvent $calendarEvent, CalendarEventActuality $existingCalendarEventActualityInstance = null) : CalendarEventActuality
    {
        // $calendarEvent = $calendarEventRepository->find($calendarEventId);
        $calendarEventDueDate = strtotime($calendarEvent->getStartDate().' '.$calendarEvent->getStartTime());
        $now = time();
    
        App::getContainer()->wireService('EventPackage/entity/CalendarEventActuality');
        $calendarEventActuality = $existingCalendarEventActualityInstance ? : new CalendarEventActuality();
        $calendarEventActuality->setUserAccount($userAccount);
        $calendarEventActuality->setCalendarEvent($calendarEvent);
        $calendarEventActuality->setStatusChangedAt(date('Y-m-d H:i:s'));
        $calendarEventActuality->setStatus(CalendarEventActuality::STATUS_ACTIVE);
        // $calendarEventActuality->setStatus($calendarEventDueDate < $now ? CalendarEventActuality::STATUS_EXPIRED : CalendarEventActuality::STATUS_EXPIRES_TODAY);
        $calendarEventActuality = $calendarEventActuality->getRepository()->store($calendarEventActuality);

        return $calendarEventActuality;
    }
    

    public static function createCalendarCheckEventActuality(CalendarCheck $calendarCheck, CalendarEventActuality $calendarEventActuality)
    {
        App::getContainer()->wireService('EventPackage/entity/CalendarCheckEventActuality');
        $calendarCheckEventActuality = new CalendarCheckEventActuality();
        $calendarCheckEventActuality->setCalendarCheck($calendarCheck);
        $calendarCheckEventActuality->setCalendarEventActuality($calendarEventActuality);
        $calendarCheckEventActuality = $calendarCheckEventActuality->getRepository()->store($calendarCheckEventActuality);

        return $calendarCheckEventActuality;
    }
}