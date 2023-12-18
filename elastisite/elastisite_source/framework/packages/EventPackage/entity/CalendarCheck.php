<?php
namespace framework\packages\EventPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class CalendarCheck extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `calendar_check` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `run_date` DATE DEFAULT NULL,
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
    protected $runDate;

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

    public function setRunDate($runDate)
    {
        $this->runDate = $runDate;
    }

	public function getRunDate()
	{
		return $this->runDate;
	}
}