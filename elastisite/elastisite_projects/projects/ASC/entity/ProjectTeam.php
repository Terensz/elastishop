<?php

namespace projects\ASC\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class ProjectTeam extends DbEntity
{
    const STATUS_ACTIVE = 1;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `project_team` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `asc_scale_id` int(11) DEFAULT NULL,
    `asc_unit_id` int(11) DEFAULT NULL,
    `children_included` int(1) DEFAULT NULL,
    `name` varchar(250) DEFAULT NULL,
    -- `lead_by` int(11) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `status` int(2) DEFAULT 1,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=410000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";
    
    protected $id;
    protected $ascScale;
    protected $ascUnit;
    protected $childrenIncluded;
    protected $name;
    protected $createdAt;
    protected $status;

    const ENTITY_ATTRIBUTES = [
        'passOverMissingFields' => ['lead_by_id'],
        'passOverUnnecessaryFields' => ['lead_by'],
    ];

    public function __construct()
    {
        $this->createdAt = $this->getCurrentTimestamp();
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

    public function setAscScale(AscScale $ascScale = null)
    {
        $this->ascScale = $ascScale;
    }

    public function getAscScale()
    {
        return $this->ascScale;
    }

    public function setAscUnit(AscUnit $ascUnit = null)
    {
        $this->ascUnit = $ascUnit;
    }

    public function getAscUnit()
    {
        return $this->ascUnit;
    }

	public function setChildrenIncluded($childrenIncluded)
	{
		$this->childrenIncluded = $childrenIncluded;
	}

	public function getChildrenIncluded()
	{
		return $this->childrenIncluded;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
        // if ($this->ascScale) {
        //     $entryHead = $this->ascScale->getAscEntryHead();
        //     return $entryHead->findTitle();
        // }
		return $this->name;
	}

    // public function setLeadBy(ProjectUser $leadBy = null)
    // {
    //     $this->leadBy = $leadBy;
    // }

    // public function getLeadBy()
    // {
    //     return $this->leadBy;
    // }

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