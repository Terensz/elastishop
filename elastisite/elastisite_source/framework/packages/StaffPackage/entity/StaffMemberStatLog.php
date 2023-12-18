<?php
namespace framework\packages\StaffPackage\entity;

use App;
use framework\component\parent\DbEntity;
// use framework\packages\ToolPackage\entity\ImageHeader;

class StaffMemberStatLog extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `staff_member_stat_log` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `staff_member_stat_id` int(11) DEFAULT NULL,
        `old_value` int(11) DEFAULT NULL,
        `new_value` int(11) DEFAULT NULL,
        `created_at` DATETIME DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=252000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $staffMemberStat;
    protected $oldValue;
    protected $newValue;
    protected $createdAt;

	public function __construct()
	{
        $this->createdAt = $this->getCurrentTimestamp();
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setStaffMemberStat(StaffMemberStat $staffMemberStat)
	{
		$this->staffMemberStat = $staffMemberStat;
	}

	public function getStaffMemberStat()
	{
		return $this->staffMemberStat;
	}

	public function setOldValue($oldValue)
	{
		$this->oldValue = $oldValue;
	}

	public function getOldValue()
	{
		return $this->oldValue;
	}

	public function setNewValue($newValue)
	{
		$this->newValue = $newValue;
	}

	public function getNewValue()
	{
		return $this->newValue;
	}

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}