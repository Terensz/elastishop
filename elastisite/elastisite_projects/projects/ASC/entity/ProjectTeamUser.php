<?php

namespace projects\ASC\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class ProjectTeamUser extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `project_team_user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_team_id` int(11) DEFAULT NULL,
    `project_user_id` int(11) DEFAULT NULL,
    `membership_type` varchar(30) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=420000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    // const MEMBERSHIP_TYPE_OWNER = 'Owner';
    const MEMBERSHIP_TYPE_ADMIN = 'Admin';
    const MEMBERSHIP_TYPE_MEMBER = 'Member';
    
    protected $id;
    protected $projectTeam;
	protected $projectUser;
    protected $membershipType;

    public function __construct()
    {
        $this->membershipType = self::MEMBERSHIP_TYPE_MEMBER;
    }

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

    public function setProjectTeam(ProjectTeam $projectTeam = null)
    {
        $this->projectTeam = $projectTeam;
    }

    public function getProjectTeam()
    {
        return $this->projectTeam;
    }

	public function setMembershipType($membershipType)
	{
		$this->membershipType = $membershipType;
	}

	public function getMembershipType()
	{
		return $this->membershipType;
	}

    public function setProjectUser(ProjectUser $projectUser = null)
    {
        $this->projectUser = $projectUser;
    }

    public function getProjectUser()
    {
        return $this->projectUser;
    }
}