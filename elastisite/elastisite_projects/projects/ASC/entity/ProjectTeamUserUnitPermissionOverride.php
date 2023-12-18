<?php

namespace projects\ASC\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class ProjectTeamUserUnitPermissionOverride extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `project_team_user_unit_permission_override` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_team_user_id` int(11) DEFAULT NULL,
    `asc_unit_id` int(11) DEFAULT NULL,
    `permission_name` varchar(250) DEFAULT NULL,
    `new_value` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=410000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";
    
    protected $id;
    protected $projectTeamUser;
    protected $ascUnit;
    protected $permissionName;
    protected $newValue;

    const ENTITY_ATTRIBUTES = [
        'passOverMissingFields' => ['lead_by_id'],
        'passOverUnnecessaryFields' => ['lead_by'],
    ];

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

	public function setProjectTeamUser(ProjectTeamUser $projectTeamUser)
	{
		$this->projectTeamUser = $projectTeamUser;
	}

	public function getProjectTeamUser()
	{
		return $this->projectTeamUser;
	}

    public function setAscUnit(AscUnit $ascUnit = null)
    {
        $this->ascUnit = $ascUnit;
    }

    public function getAscUnit()
    {
        return $this->ascUnit;
    }

	public function setPermissionName($permissionName)
	{
		$this->permissionName = $permissionName;
	}

	public function getPermissionName()
	{
		return $this->permissionName;
	}

    public function setNewValue($newValue)
    {
        $this->newValue = $newValue;
    }

    public function getNewValue()
    {
        return $this->newValue;
    }
}