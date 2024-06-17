<?php

namespace projects\ASC\entity;

use framework\component\parent\DbEntity;
use framework\component\parent\ProjectUserBase;
use framework\packages\UserPackage\entity\UserAccount;

// class ProjectUser extends ProjectUserBase
class ProjectUser extends DbEntity
{
	const RANK_DATA = [
		'Rookie' => [
			'titles' => [
				'Enthusiastic Rookie',
				'Super Rookie'
			],
			'commentArticles' => true,
			'createArticles' => false,
			'requiredRankPoints' => 0,
			'grantAutomatically' => true,
			'requiredRank' => null,
			'permissions' => [
				'promoteRookies' => false,
				'promoteAdvisors' => false,
				'promoteVeterans' => false,
				'promoteMasters' => false,
				'createSampleScales' => false,
				'superviseSampleScales' => false
			]
		],
		'Advisor' => [
			'titles' => [
				'Bronze Advisor',
				'Silver Advisor',
				'Gold Advisor'
			],
			'commentArticles' => true,
			'createArticles' => false,
			'requiredRankPoints' => 100,
			'grantAutomatically' => true,
			'requiredRank' => 'Rookie',
			'permissions' => [
				'promoteRookies' => false,
				'promoteAdvisors' => false,
				'promoteVeterans' => false,
				'promoteMasters' => false,
				'createSampleScales' => false,
				'superviseSampleScales' => false
			]
		],
		'Veteran' => [
			'titles' => [
				'Bronze Veteran',
				'Silver Veteran',
				'Gold Veteran',
				'Platinum Veteran'
			],
			'commentArticles' => true,
			'createArticles' => true,
			'requiredRankPoints' => 1000,
			'grantAutomatically' => true,
			'requiredRank' => 'Advisor',
			'permissions' => [
				'promoteRookies' => true,
				'promoteAdvisors' => false,
				'promoteVeterans' => false,
				'promoteMasters' => false,
				'createSampleScales' => true,
				'superviseSampleScales' => false
			]
		],
		'Master' => [
			'titles' => [
				'Bronze Master',
				'Silver Master',
				'Gold Master',
				'Platinum Master'
			],
			'commentArticles' => true,
			'createArticles' => true,
			'requiredRankPoints' => 10000, // 10.000 
			'grantAutomatically' => true,
			'requiredRank' => 'Veteran',
			'permissions' => [
				'promoteRookies' => true,
				'promoteAdvisors' => true,
				'promoteVeterans' => false,
				'promoteMasters' => false,
				'createSampleScales' => true,
				'superviseSampleScales' => false
			]
		],
		'Grandmaster' => [
			'titles' => [
				'Bronze Grandmaster',
				'Silver Grandmaster',
				'Gold Grandmaster',
				'Platinum Grandmaster'
			],
			'commentArticles' => true,
			'createArticles' => true,
			'requiredRankPoints' => 200000, // 200.000 
			'grantAutomatically' => true,
			'requiredRank' => 'Master',
			'permissions' => [
				'promoteRookies' => true,
				'promoteAdvisors' => true,
				'promoteVeterans' => true,
				'promoteMasters' => false,
				'createSampleScales' => true,
				'superviseSampleScales' => true
			]
		],
	];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `project_user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_account_id` int(11) DEFAULT NULL,
	`primary_language_code` varchar(4) DEFAULT NULL,
	`title` varchar(255) DEFAULT NULL,
    `rank_points` int(11) DEFAULT NULL,
    `ranking` varchar(30) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `status` int(2) DEFAULT 1,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=430000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $userAccount;
	protected $primaryLanguageCode;
	protected $title;
    protected $rankPoints;
    protected $ranking;
    protected $createdAt;
    protected $status;

	public function __construct()
	{
        $this->createdAt = $this->getCurrentTimestamp();
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setUserAccount(UserAccount $userAccount = null)
	{
		$this->userAccount = $userAccount;
	}

	public function getUserAccount()
	{
		return $this->userAccount;
	}

	public function setPrimaryLanguageCode($primaryLanguageCode)
	{
		$this->primaryLanguageCode = $primaryLanguageCode;
	}

	public function getPrimaryLanguageCode()
	{
		return $this->primaryLanguageCode;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setRankPoints($rankPoints)
	{
		$this->rankPoints = $rankPoints;
	}

	public function getRankPoints()
	{
		return $this->rankPoints;
	}

	public function setRanking($ranking)
	{
		$this->ranking = $ranking;
	}

	public function getRanking()
	{
		return $this->ranking;
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