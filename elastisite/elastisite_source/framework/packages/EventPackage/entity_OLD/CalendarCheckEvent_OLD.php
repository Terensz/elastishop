<?php
namespace framework\packages\EventPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class CalendarCheckEvent extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `calendar_check_event` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `calendar_check_id` int(11) DEFAULT NULL,
        `calendar_event_id` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=312000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

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
    protected $calendarCheck;
    protected $calendarEvent;

    public function __construct()
	{

	}

    public function setId($id)
    {
        $this->id = $id;
    }

	public function getId()
	{
		return $this->id;
	}

    public function setCalendarCheck(CalendarCheck $calendarCheck)
    {
        $this->calendarCheck = $calendarCheck;
    }

	public function getCalendarCheck()
	{
		return $this->calendarCheck;
	}

    public function setCalendarEvent(CalendarEvent $calendarEvent)
    {
        $this->calendarEvent = $calendarEvent;
    }

	public function getCalendarEvent()
	{
		return $this->calendarEvent;
	}
}