<?php
namespace framework\packages\EventPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\EventPackage\entity\CalendarEvent;
use framework\packages\EventPackage\repository\CalendarEventRepository;

/**
 * Very important! Never use CalendarEvent repository or entity alone.
 * Calerndar handling and scheduling are complex processes, 
 * and you can make a mistake without the proper event handler.
*/
class CalendarEventFactory extends Service
{
    const WEEKDAY_ABBREVIATION_MONDAY = 'Mon';
    const WEEKDAY_ABBREVIATION_TUESDAY = 'Tue';
    const WEEKDAY_ABBREVIATION_WEDNESDAY = 'Wed';
    const WEEKDAY_ABBREVIATION_THURSDAY = 'Thu';
    const WEEKDAY_ABBREVIATION_FRIDAY = 'Fri';
    const WEEKDAY_ABBREVIATION_SATURDAY = 'Sat';
    const WEEKDAY_ABBREVIATION_SUNDAY = 'Sun';

    const WEEKDAY_ABBREVIATIONS = [
        self::WEEKDAY_ABBREVIATION_MONDAY,
        self::WEEKDAY_ABBREVIATION_TUESDAY,
        self::WEEKDAY_ABBREVIATION_WEDNESDAY,
        self::WEEKDAY_ABBREVIATION_THURSDAY,
        self::WEEKDAY_ABBREVIATION_FRIDAY,
        self::WEEKDAY_ABBREVIATION_SATURDAY,
        self::WEEKDAY_ABBREVIATION_SUNDAY
    ];

    // const DUE_TYPE_ONE_TIME = 'OneTime';
    // const DUE_TYPE_DAILY = 'Daily';
    // const DUE_TYPE_WEEKLY_MONDAY = 'WeeklyMonday';
    // const DUE_TYPE_MONTHLY_FIRST_MONDAY = 'MonthlyFirstMonday';
    // const DUE_TYPE_ANNUAL_MAY_1ST = 'AnnualMay1st';
    // const DUE_TYPE_WEEKDAYS = 'Weekdays';
    // const DUE_TYPE_CUSTOM_RECURRENCE = 'Custom';

    // const POSSIBLE_DUE_TYPES = [
    //     self::DUE_TYPE_ONE_TIME,
    //     self::DUE_TYPE_DAILY,
    //     self::DUE_TYPE_WEEKLY_MONDAY,
    //     self::DUE_TYPE_MONTHLY_FIRST_MONDAY,
    //     self::DUE_TYPE_ANNUAL_MAY_1ST,
    //     self::DUE_TYPE_WEEKDAYS,
    //     self::DUE_TYPE_CUSTOM_RECURRENCE
    // ];

    public $calendarEvent;

    public static $calendarEventRepository;

    public function __construct(CalendarEvent $calendarEvent = null)
    {
        App::getContainer()->wireService('EventPackage/entity/CalendarEvent');
        $this->calendarEvent = $calendarEvent;
    }

    public static function findCalendarEvent()
    {
        // return self::POSSIBLE_DUE_TYPES;
    }

    public static function getPossibleDueTypes()
    {
        App::getContainer()->wireService('EventPackage/entity/CalendarEvent');
        return CalendarEvent::POSSIBLE_FREQUENCY_TYPES;
    }

    public static function getRepository() : CalendarEventRepository
    {
        App::getContainer()->wireService('EventPackage/repository/CalendarEventRepository');

        if (self::$calendarEventRepository) {
            return self::$calendarEventRepository;
        }

        self::$calendarEventRepository = new CalendarEventRepository();
    }

    /**
     * @var $recurrenceDays: e.g.: ['Mon', 'Thu']
    */
    public function setRecurrenceDays(array $recurrenceDays = [])
    {
        foreach (self::WEEKDAY_ABBREVIATIONS as $weekdayAbbreviation) {
            $setter = 'setRecurrenceDay'.$weekdayAbbreviation;
            $value = in_array($weekdayAbbreviation, $recurrenceDays) ? 1 : 0;
            $this->calendarEvent->$setter($value);
        }
    }

    public function saveChanges()
    {
        $repo = self::getRepository();
        $this->calendarEvent = $repo->store($this->calendarEvent);
    }

    // public static function convertAbbreviationTo()
    // {

    // }

    public static function createEvent()
    {

    }

    public function getTitle()
    {
        return $this->calendarEvent ? $this->calendarEvent->getTitle() : null;
    }

    public function getStartDate()
    {
        return $this->calendarEvent ? $this->calendarEvent->getStartDate() : null;
    }

    public function getStartTime()
    {
        return $this->calendarEvent ? $this->calendarEvent->getStartTime() : null;
    }

    public function getStartTimeHours()
    {
        return $this->getStartTimeHoursAndMinutes()['hours'];
    }

    public function getStartTimeMinutes()
    {
        return $this->getStartTimeHoursAndMinutes()['minutes'];
    }

    public function getStartTimeHoursAndMinutes()
    {
        $startTime = $this->calendarEvent ? $this->calendarEvent->getStartTime() : null;
        if (!$startTime || !is_string($startTime)) {
            return [
                'hours' => null,
                'minutes' => null
            ];
        }

        $startTimeParts = explode(':', $startTime);

        return [
            'hours' => $startTimeParts[0],
            'minutes' => count($startTimeParts) >= 2 ? $startTimeParts[1] : null
        ];
    }

    public function getFrequencyType()
    {
        return $this->calendarEvent ? $this->calendarEvent->getFrequencyType() : null;
    }

    public function getRecurrenceUnit()
    {
        return $this->calendarEvent ? $this->calendarEvent->getRecurrenceUnit() : null;
    }

    public function getRecurrenceInterval()
    {
        return $this->calendarEvent ? $this->calendarEvent->getRecurrenceInterval() : null;
    }

    public function getRecurrenceDayMon()
    {
        return $this->calendarEvent ? $this->calendarEvent->getRecurrenceDayMon() : null;
    }

    public function getRecurrenceDayTue()
    {
        return $this->calendarEvent ? $this->calendarEvent->getRecurrenceDayTue() : null;
    }

    public function getRecurrenceDayWed()
    {
        return $this->calendarEvent ? $this->calendarEvent->getRecurrenceDayWed() : null;
    }

    public function getRecurrenceDayThu()
    {
        return $this->calendarEvent ? $this->calendarEvent->getRecurrenceDayThu() : null;
    }

    public function getRecurrenceDayFri()
    {
        return $this->calendarEvent ? $this->calendarEvent->getRecurrenceDayFri() : null;
    }

    public function getRecurrenceDaySat()
    {
        return $this->calendarEvent ? $this->calendarEvent->getRecurrenceDaySat() : null;
    }

    public function getRecurrenceDaySun()
    {
        return $this->calendarEvent ? $this->calendarEvent->getRecurrenceDaySun() : null;
    }
}