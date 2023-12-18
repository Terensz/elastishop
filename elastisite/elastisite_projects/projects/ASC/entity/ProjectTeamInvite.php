<?php

namespace projects\ASC\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class ProjectTeamInvite extends DbEntity
{
    const EXPITATION_INTERVAL_IN_MIN = 600;
    const STATUS_ACTIVE = 1;
    const STATUS_BANNED = 30;
    const STATUS_USED = 40;
    const STATUS_EXPIRED = 50;

    const STATUS_ACTIVE_TEXT = 'Active';
    const STATUS_BANNED_TEXT = 'Banned';
    const STATUS_USED_TEXT = 'Used';
    const STATUS_EXPIRED_TEXT = 'Used';

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `project_team_invite` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_team_id` int(11) DEFAULT NULL,
    `project_user_full_name` varchar(255) DEFAULT NULL,
    `project_user_email` varchar(255) DEFAULT NULL,
	`invite_token` varchar(255) DEFAULT NULL,
	`invite_token_redeemed_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `status` int(2) DEFAULT 1,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=410000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        'encryptedProperties' => array('name', 'email')
    ];

    protected $id;
    protected $projectTeam;
    protected $projectUserFullName;
    protected $projectUserEmail;
    protected $inviteToken;
    protected $inviteTokenRedeemedAt;
    protected $createdAt;
    protected $status;

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

    public function setProjectTeam(ProjectTeam $projectTeam = null)
    {
        $this->projectTeam = $projectTeam;
    }

    public function getProjectTeam()
    {
        return $this->projectTeam;
    }

    public function setProjectUserFullName($projectUserFullName)
    {
        $this->projectUserFullName = $this->decrypt($projectUserFullName);
    }

    public function getProjectUserFullName()
    {
        return $this->projectUserFullName;
    }

    public function setProjectUserEmail($projectUserEmail)
    {
        $this->projectUserEmail = $this->decrypt($projectUserEmail);
    }

    public function getProjectUserEmail()
    {
        return $this->projectUserEmail;
    }

	public function setInviteToken($inviteToken)
	{
		$this->inviteToken = $inviteToken;
	}

	public function getInviteToken()
	{
		return $this->inviteToken;
	}

	public function setInviteTokenRedeemedAt($inviteTokenRedeemedAt)
	{
		$this->inviteTokenRedeemedAt = $inviteTokenRedeemedAt;
	}

	public function getInviteTokenRedeemedAt()
	{
		return $this->inviteTokenRedeemedAt;
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
