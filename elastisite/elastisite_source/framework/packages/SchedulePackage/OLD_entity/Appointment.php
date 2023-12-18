<?php
namespace framework\packages\SchedulePackage\entity;

use framework\component\parent\DbEntity;

class Appointment extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `appointment` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `booked_by` int(11) NOT NULL,
        `time_unit_id` int(11) NOT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=30000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        'repositoryPath' => 'framework/packages/SchedulePackage/repository/EventRepository',
        'relations' => [
            'owner' => [
                'type' => 'thisToOne',
                'targetEntity' => 'framework/packages/UserPackage/entity/Person'
            ],
        ],
        'active' => false
    ];

    protected $id;
    protected $bookedBy;
    protected $timeUnits = array();
    protected $createdAt;
    protected $remark;
    protected $active;

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

    public function setBookedBy($bookedBy)
    {
        $this->bookedBy = $bookedBy;
    }

	public function getBookedBy()
	{
		return $this->bookedBy;
	}

    public function setTimeUnits($timeUnits)
    {
        $this->timeUnits = $timeUnits;
    }

	public function getTimeUnits()
	{
		return $this->timeUnits;
	}

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

	public function getCreatedAt()
	{
		return $this->createdAt;
	}

    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

	public function getRemark()
	{
		return $this->remark;
	}

    public function setActive($active)
    {
        $this->active = $active;
    }

	public function getActive()
	{
		return $this->active;
	}
}
