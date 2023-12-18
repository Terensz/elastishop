<?php
namespace framework\packages\EventPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class CalendarEventDelay extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `calendar_event_delay` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `start_date` DATE DEFAULT NULL,
        `start_time` TIME DEFAULT NULL,
        `end_date` DATE DEFAULT NULL,
        `end_time` TIME DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=301000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

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
    protected $startDate;
    protected $startTime;
    protected $endDate;
    protected $endTime;

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
}
