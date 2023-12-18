<?php
namespace framework\packages\StaffPackage\entity;

use App;
use framework\component\parent\DbEntity;
// use framework\packages\ToolPackage\entity\ImageHeader;

class StaffMemberStat extends DbEntity
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
	
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `staff_member_stat` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `staff_member_id` int(11) DEFAULT NULL,
        `frequency` varchar(250) DEFAULT NULL,
        `year_of_relevance` int(4) DEFAULT NULL,
        `period_serial` int(11) DEFAULT NULL,
		`points` int(11) DEFAULT NULL,
        `created_at` DATETIME DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=251000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $staffMember;
    protected $frequency;
    protected $yearOfRelevance;
    protected $periodSerial;
    protected $points;
    protected $createdAt;
    protected $status;

	public function __construct()
	{
        $this->createdAt = $this->getCurrentTimestamp();
        $this->status = 1;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

    public function setStaffMember(StaffMember $staffMember = null)
    {
        $this->staffMember = $staffMember;
    }

    public function getStaffMember()
    {
        return $this->staffMember;
    }

	public function setFrequency($frequency)
	{
		$this->frequency = $frequency;
	}

	public function getFrequency()
	{
		return $this->frequency;
	}

	public function setYearOfRelevance($yearOfRelevance)
	{
		$this->yearOfRelevance = $yearOfRelevance;
	}

	public function getYearOfRelevance()
	{
		return $this->yearOfRelevance;
	}

	public function setPeriodSerial($periodSerial)
	{
		$this->periodSerial = $periodSerial;
	}

	public function getPeriodSerial()
	{
		return $this->periodSerial;
	}

	public function setPoints($points)
	{
		$this->points = $points;
	}

	public function getPoints()
	{
		return $this->points;
	}

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
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