<?php
namespace framework\packages\SchedulePackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class Event extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `event` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(250) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `description` text DEFAULT NULL,
        `start_date` datetime DEFAULT NULL,
        `end_date` datetime DEFAULT NULL,
        `max_subscribers` int(11) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `user_account_id` int(11) DEFAULT NULL,
        `status` int(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=31000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    // const ENTITY_ATTRIBUTES = [
    //     'repositoryPath' => 'framework/packages/SchedulePackage/repository/EventRepository',
    //     'relations' => [
    //         'owner' => [
    //             'type' => 'thisToOne',
    //             'targetEntity' => 'framework/packages/UserPackage/entity/Person'
    //         ],
    //     ],
    //     'active' => true
    // ];

    protected $id;
    protected $title;
    protected $description;
    protected $startDate;
    protected $endDate;
    protected $maxSubscribers;
    protected $createdAt;
    protected $userAccount;
    protected $status;

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

    public function setTitle($title)
    {
        $this->title = $title;
    }

	public function getTitle()
	{
		return $this->title;
	}

    public function setDescription($description)
    {
        $this->description = $description;
    }

	public function getDescription()
	{
		return $this->description;
	}

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

	public function getStartDate()
	{
		return $this->startDate;
	}

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

	public function getEndDate()
	{
		return $this->endDate;
	}

    public function setMaxSubscribers($maxSubscribers)
    {
        $this->maxSubscribers = $maxSubscribers;
    }

	public function getMaxSubscribers()
	{
		return $this->maxSubscribers;
	}

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

	public function getCreatedAt()
	{
		return $this->createdAt;
	}

    public function setUserAccount(UserAccount $userAccount)
    {
        $this->userAccount = $userAccount;
    }

	public function getUserAccount()
	{
		return $this->userAccount;
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
