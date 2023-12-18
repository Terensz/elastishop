<?php
namespace framework\packages\EventPackage\entity;

use framework\component\parent\DbEntity;

/**
 * Never use this entity alone!
 * Always use the CalendarEventFactory!
*/
class CalendarEvent extends DbEntity
{
    const FREQUENCY_TYPE_ONE_TIME = 'OneTime';
    const FREQUENCY_TYPE_DAILY = 'Daily';
    const FREQUENCY_TYPE_WEEKLY_MONDAY = 'WeeklyMonday';
    const FREQUENCY_TYPE_MONTHLY_FIRST_MONDAY = 'MonthlyFirstMonday';
    const FREQUENCY_TYPE_ANNUAL_MAY_1ST = 'AnnualMay1st';
    const FREQUENCY_TYPE_WEEKDAYS = 'Weekdays';
    const FREQUENCY_TYPE_CUSTOM_RECURRENCE = 'Custom';

    const POSSIBLE_FREQUENCY_TYPES = [
        self::FREQUENCY_TYPE_ONE_TIME,
        self::FREQUENCY_TYPE_DAILY,
        self::FREQUENCY_TYPE_WEEKLY_MONDAY,
        self::FREQUENCY_TYPE_MONTHLY_FIRST_MONDAY,
        self::FREQUENCY_TYPE_ANNUAL_MAY_1ST,
        self::FREQUENCY_TYPE_WEEKDAYS,
        self::FREQUENCY_TYPE_CUSTOM_RECURRENCE
    ];

    const RECURRENCE_UNIT_DAY = 'Day';
    const RECURRENCE_UNIT_WEEK = 'Week';
    const RECURRENCE_UNIT_MONTH = 'Month';
    const RECURRENCE_UNIT_YEAR = 'Year';

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 10;

    // Szerintem ezt megsem itt kene, hanem ott, ahol hasznalom ezt az entitast, hogy egyedi legyen, pl. AscDueDate
    // const EVENT_TYPE_DUE_DATE = 'DueDate';

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `calendar_event` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        -- `calendar_event_delay_id` int(11) DEFAULT NULL,
        `event_type` varchar(50) DEFAULT NULL,
        `title` varchar(250) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `frequency_type` varchar(250) DEFAULT NULL,
        `start_date` DATE DEFAULT NULL,
        `start_time` TIME DEFAULT NULL,
        `end_date` DATE DEFAULT NULL,
        `end_time` TIME DEFAULT NULL,
        `recurrence_unit` VARCHAR(50) DEFAULT NULL, -- Day, Week, Month, Year
        `recurrence_interval` INT DEFAULT NULL, -- Pl. 2
        `recurrence_day_mon` TINYINT(1) DEFAULT NULL, -- Mon
        `recurrence_day_tue` TINYINT(1) DEFAULT NULL, -- Tue
        `recurrence_day_wed` TINYINT(1) DEFAULT NULL, -- Wed
        `recurrence_day_thu` TINYINT(1) DEFAULT NULL, -- Thu
        `recurrence_day_fri` TINYINT(1) DEFAULT NULL, -- Fri
        `recurrence_day_sat` TINYINT(1) DEFAULT NULL, -- Sat
        `recurrence_day_sun` TINYINT(1) DEFAULT NULL, -- Sun
        `status` tinyint(1) DEFAULT ".self::STATUS_INACTIVE.",
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=300000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    // const ENTITY_ATTRIBUTES = [
    //     'repositoryPath' => 'framework/packages/SchedulePackage/repository/EventRepository',
    //     'relations' => [
    //         'owner' => [
    //             'type' => 'thisToOne',
    //             'targetEntity' => 'framework/packages/UserPackage/entity/Person'
    //         ],
    //     ],
    //     'active' => true
    // ];

    protected $id;
    // protected $calendarEventDelay;
    protected $eventType;
    protected $title;
    protected $frequencyType;
    protected $startDate;
    protected $startTime;
    protected $endDate;
    protected $endTime;
    protected $recurrenceUnit;
    protected $recurrenceInterval;
    protected $recurrenceDayMon;
    protected $recurrenceDayTue;
    protected $recurrenceDayWed;
    protected $recurrenceDayThu;
    protected $recurrenceDayFri;
    protected $recurrenceDaySat;
    protected $recurrenceDaySun;
    protected $status;

    public function __construct()
	{
        $this->status = self::STATUS_INACTIVE;
	}

    public function setId($id)
    {
        $this->id = $id;
    }

	public function getId()
	{
		return $this->id;
	}

    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
    }

	public function getEventType()
	{
		return $this->eventType;
	}

    public function setTitle($title)
    {
        $this->title = $title;
    }

	public function getTitle()
	{
		return $this->title;
	}

	public function setFrequencyType($frequencyType)
	{
		$this->frequencyType = $frequencyType;
	}

	public function getFrequencyType()
	{
		return $this->frequencyType;
	}

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

	public function getStartDate()
	{
		return $this->startDate;
	}

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

	public function getStartTime()
	{
		return $this->startTime;
	}

    // public function setDueDate($dueDate)
    // {
    //     $this->dueDate = $dueDate;
    // }

    // public function getDueDate()
    // {
    //     return $this->dueDate;
    // }

    // public function setDueTime($dueTime)
    // {
    //     $this->dueTime = $dueTime;
    // }

    // public function getDueTime()
    // {
    //     return $this->dueTime;
    // }

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

	public function getEndDate()
	{
		return $this->endDate;
	}

    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

	public function getEndTime()
	{
		return $this->endTime;
	}

    // public function setRecurring($recurring)
    // {
    //     $this->recurring = $recurring;
    // }

	// public function getRecurring()
	// {
	// 	return $this->recurring;
	// }

    public function setRecurrenceUnit($recurrenceUnit)
    {
        $this->recurrenceUnit = $recurrenceUnit;
    }

	public function getRecurrenceUnit()
	{
		return $this->recurrenceUnit;
	}

    public function setRecurrenceInterval($recurrenceInterval)
    {
        $this->recurrenceInterval = $recurrenceInterval;
    }

	public function getRecurrenceInterval()
	{
		return $this->recurrenceInterval;
	}

    public function setRecurrenceDayMon($recurrenceDayMon)
    {
        $this->recurrenceDayMon = $recurrenceDayMon;
    }

	public function getRecurrenceDayMon()
	{
		return $this->recurrenceDayMon;
	}

    public function setRecurrenceDayTue($recurrenceDayTue)
    {
        $this->recurrenceDayTue = $recurrenceDayTue;
    }

	public function getRecurrenceDayTue()
	{
		return $this->recurrenceDayTue;
	}

    public function setRecurrenceDayWed($recurrenceDayWed)
    {
        $this->recurrenceDayWed = $recurrenceDayWed;
    }

	public function getRecurrenceDayWed()
	{
		return $this->recurrenceDayWed;
	}

    public function setRecurrenceDayThu($recurrenceDayThu)
    {
        $this->recurrenceDayThu = $recurrenceDayThu;
    }

	public function getRecurrenceDayThu()
	{
		return $this->recurrenceDayThu;
	}

    public function setRecurrenceDayFri($recurrenceDayFri)
    {
        $this->recurrenceDayFri = $recurrenceDayFri;
    }

	public function getRecurrenceDayFri()
	{
		return $this->recurrenceDayFri;
	}

    public function setRecurrenceDaySat($recurrenceDaySat)
    {
        $this->recurrenceDaySat = $recurrenceDaySat;
    }

	public function getRecurrenceDaySat()
	{
		return $this->recurrenceDaySat;
	}

    public function setRecurrenceDaySun($recurrenceDaySun)
    {
        $this->recurrenceDaySun = $recurrenceDaySun;
    }

	public function getRecurrenceDaySun()
	{
		return $this->recurrenceDaySun;
	}

    public function setStatus($status)
    {
        $this->status = $status;
    }

	public function getStatus()
	{
		return $this->status;
	}
}
