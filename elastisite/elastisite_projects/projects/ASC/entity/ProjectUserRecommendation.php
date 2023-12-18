<?php

namespace projects\ASC\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class ProjectUserRecommendation extends DbEntity
{
	const STATUS_CREATED = 2;
	const STATUS_SENT = 1;
	const STATUS_USED = 0;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `project_user_recommendation` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_user_id` int(11) DEFAULT NULL,
	`code` varchar(255) DEFAULT NULL,
    `language_code` varchar(5) DEFAULT NULL,
	`email` varchar(255) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `status` int(2) DEFAULT 1,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=440000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $projectUser;
	protected $code;
    protected $languageCode;
	protected $email;
    protected $createdAt;
    protected $status;

	public function __construct()
	{
        $this->createdAt = $this->getCurrentTimestamp();
		$this->status = self::STATUS_CREATED;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setProjectUser(ProjectUser $projectUser)
	{
		$this->projectUser = $projectUser;
	}

	public function getProjectUser()
	{
		return $this->projectUser;
	}

	public function setCode($code)
	{
		$this->code = $code;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function setLanguageCode($languageCode)
	{
		$this->languageCode = $languageCode;
	}

	public function getLanguageCode()
	{
		return $this->languageCode;
	}

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
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