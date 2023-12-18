<?php
namespace framework\packages\EventPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class CalendarEventActuality extends DbEntity
{
    const STATUS_ACTIVE = 1;
    // const STATUS_EXPIRED = 30;
    // const STATUS_EXPIRES_TODAY = 40;
    const STATUS_CLOSED_SUCCESSFUL = 10;
    const STATUS_CLOSED_FAILED = 20;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `calendar_event_actuality` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_account_id` int(11) DEFAULT NULL,
        `calendar_event_id` int(11) DEFAULT NULL,
        `calendar_event_delay_id` int(11) DEFAULT NULL,
        `status_changed_at` DATETIME DEFAULT NULL,
        `status` int(2) DEFAULT 1,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=311000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

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
    protected $userAccount;
    protected $calendarEvent;
    protected $calendarEventDelay;
    protected $statusChangedAt;
    protected $status;

    public function __construct()
	{
        $this->status = self::STATUS_ACTIVE;
	}

    public function setId($id)
    {
        $this->id = $id;
    }

	public function getId()
	{
		return $this->id;
	}

    public function setUserAccount(UserAccount $userAccount = null)
    {
        $this->userAccount = $userAccount;
    }

    public function getUserAccount()
    {
        return $this->userAccount;
    }

    public function setCalendarEvent(CalendarEvent $calendarEvent)
    {
        $this->calendarEvent = $calendarEvent;
    }

	public function getCalendarEvent()
	{
		return $this->calendarEvent;
	}

    public function setCalendarEventDelay(CalendarEventDelay $calendarEventDelay = null)
    {
        $this->calendarEventDelay = $calendarEventDelay;
    }

	public function getCalendarEventDelay()
	{
		return $this->calendarEventDelay;
	}

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatusChangedAt($statusChangedAt)
    {
        $this->statusChangedAt = $statusChangedAt;
    }

    public function getStatusChangedAt()
    {
        return $this->statusChangedAt;
    }
}