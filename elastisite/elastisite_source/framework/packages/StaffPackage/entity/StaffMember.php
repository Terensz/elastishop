<?php
namespace framework\packages\StaffPackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\packages\StaffPackage\repository\StaffMemberRepository;
use framework\packages\UserPackage\entity\Person;

// use framework\packages\ToolPackage\entity\ImageHeader;

class StaffMember extends DbEntity
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
	
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `staff_member` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `code` varchar(250) DEFAULT NULL,
        `person_id` int(11) DEFAULT NULL,
        `organization` varchar(250) DEFAULT NULL,
		`division` varchar(250) DEFAULT NULL,
		`position` varchar(250) DEFAULT NULL,
		`hired_at` DATETIME DEFAULT NULL,
		`trained_at` DATETIME DEFAULT NULL,
        `created_at` DATETIME DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=750000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $website;
    protected $code;
    protected $person;
    protected $organization;
    protected $division;
    protected $position;
	protected $hiredAt;
	protected $trainedAt;
    protected $createdAt;
    protected $status;

	public function __construct()
	{
        App::getContainer()->wireService('StaffPackage/repository/StaffMemberRepository');
        $this->website = App::getWebsite();
        $this->code = StaffMemberRepository::createCode(StaffMemberRepository::TABLE_STAFF_MEMBER);
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

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getWebsite()
    {
        return $this->website;
    }

	public function setCode($code)
	{
		$this->code = $code;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function setPerson(Person $person)
	{
		$this->person = $person;
	}

	public function getPerson()
	{
		return $this->person;
	}

	public function setOrganization($organization)
	{
		$this->organization = $organization;
	}

	public function getOrganization()
	{
		return $this->organization;
	}

	public function setDivision($division)
	{
		$this->division = $division;
	}

	public function getDivision()
	{
		return $this->division;
	}

	public function setPosition($position)
	{
		$this->position = $position;
	}

	public function getPosition()
	{
		return $this->position;
	}

    public function setHiredAt($hiredAt)
    {
        $this->hiredAt = $hiredAt;
    }

    public function getHiredAt()
    {
        return $this->hiredAt;
    }

    public function setTrainedAt($trainedAt)
    {
        if (empty($trainedAt)) {
            $trainedAt = null;
        }
        $this->trainedAt = $trainedAt;
    }

    public function getTrainedAt()
    {
        return $this->trainedAt;
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