<?php
namespace framework\packages\StaffPackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\packages\StaffPackage\repository\StaffMemberRepository;

// use framework\packages\ToolPackage\entity\ImageHeader;

class StaffMemberStatPage extends DbEntity
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `staff_member_stat_page` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `staff_member_stat_id` int(11) DEFAULT NULL,
        `code` varchar(250) DEFAULT NULL,
        `created_at` DATETIME DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=251000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $staffMemberStat;
    protected $code;
    protected $createdAt;
    protected $status;

	public function __construct()
	{
        App::getContainer()->wireService('StaffPackage/repository/StaffMemberRepository');
        $this->code = StaffMemberRepository::createCode(StaffMemberRepository::TABLE_STAFF_MEMBER_STAT_PAGE);
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

    public function setStaffMemberStat(StaffMemberStat $staffMemberStat = null)
    {
        $this->staffMemberStat = $staffMemberStat;
    }

    public function getStaffMemberStat()
    {
        return $this->staffMemberStat;
    }

	public function setCode($code)
	{
		$this->code = $code;
	}

	public function getCode()
	{
		return $this->code;
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