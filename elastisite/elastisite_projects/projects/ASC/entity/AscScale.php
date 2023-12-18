<?php

namespace projects\ASC\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class AscScale extends DbEntity
{
    const IS_SAMPLE_TRUE = 1;
    const IS_SAMPLE_FALSE = 0;

	const STATUS_UNDER_CONSTRUCTION = 10;
	const STATUS_WAITING_FOR_APPROVAL = 20;
	const STATUS_APPROVED = 1;
    const STATUS_INACTIVE = 0;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `asc_scale` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `app_user_id` int(11) DEFAULT NULL,
    `user_account_id` int(11) DEFAULT NULL,
    `asc_entry_head_id` int(11) DEFAULT NULL,
    `asc_subscription_offer_id` int(11) DEFAULT NULL,
    `situation` varchar(100) DEFAULT NULL,
    `is_sample` int(1) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `status` int(2) DEFAULT 1,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=213000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        'passOverMissingFields' => ['created_by_id'],
        'passOverUnnecessaryFields' => ['created_by'],
    ];

    protected $id;
    protected $appUserId;
    protected $userAccount;
    protected $ascEntryHead;
    protected $ascSubscriptionOffer;
    protected $situation;
    protected $isSample;
    protected $createdBy;
    protected $createdAt;
    protected $status;

    public function __construct()
    {
        $this->isSample = false;
        $this->createdAt = $this->getCurrentTimestamp();
        $this->status = self::STATUS_UNDER_CONSTRUCTION;
    }

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setAppUserId($appUserId)
	{
		$this->appUserId = $appUserId;
	}

	public function getAppUserId()
	{
		return $this->appUserId;
	}

    public function setUserAccount(UserAccount $userAccount = null)
    {
        $this->userAccount = $userAccount;
    }

    public function getUserAccount()
    {
        return $this->userAccount;
    }

    public function setAscEntryHead(AscEntryHead $ascEntry)
    {
        $this->ascEntryHead = $ascEntry;
    }

    public function getAscEntryHead()
    {
        return $this->ascEntryHead;
    }

	public function setAscSubscriptionOffer(AscSubscriptionOffer $ascSubscriptionOffer)
	{
		$this->ascSubscriptionOffer = $ascSubscriptionOffer;
	}

	public function getAscSubscriptionOffer()
	{
		return $this->ascSubscriptionOffer;
	}

	public function setSituation($situation)
	{
		$this->situation = $situation;
	}

	public function getSituation()
	{
		return $this->situation;
	}

	public function setIsSample($isSample)
	{
		$this->isSample = $isSample === false ? 0 : ($isSample === true ? 1 : (in_array($isSample, [0, 1]) ? $isSample : 0));
	}

	public function getIsSample()
	{
		return $this->isSample === 0 ? false : true;
	}

    // public function setCreatedBy(UserAccount $createdBy)
    // {
    //     $this->createdBy = $createdBy;
    // }

    // public function getCreatedBy()
    // {
    //     return $this->createdBy;
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