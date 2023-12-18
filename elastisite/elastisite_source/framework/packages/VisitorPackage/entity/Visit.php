<?php
namespace framework\packages\VisitorPackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class Visit extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `visit` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
		`website` varchar(250) DEFAULT NULL,
        `route_param_chain` varchar(250) COLLATE utf8_hungarian_ci DEFAULT NULL,
		`route_name` varchar(250) COLLATE utf8_hungarian_ci DEFAULT NULL,
		`visitor_code` varchar(64) COLLATE utf8_hungarian_ci DEFAULT NULL,
		`number_of_visits` int(11) DEFAULT NULL,
        -- `user_account_id` int(11) DEFAULT NULL,
        `visited_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=33000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
	protected $website;
	protected $routeParamChain;
	protected $routeName;
	protected $numberOfVisits;
	protected $visitorCode;
	// protected $userAccount;
	protected $visitedAt;

	public function __construct()
	{
		$this->website = App::getWebsite();
		$dateTime = $this->getCurrentTimestamp();
		$this->visitedAt = $dateTime->format('Y-m-d');
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

	public function setRouteParamChain($routeParamChain)
	{
		$this->routeParamChain = $routeParamChain;
	}

	public function getRouteParamChain()
	{
		return $this->routeParamChain;
	}

	public function setRouteName($routeName)
	{
		$this->routeName = $routeName;
	}

	public function getRouteName()
	{
		return $this->routeName;
	}

	public function setNumberOfVisits($numberOfVisits)
	{
		$this->numberOfVisits = $numberOfVisits;
	}

	public function getNumberOfVisits()
	{
		return $this->numberOfVisits;
	}

	public function setVisitorCode($visitorCode)
	{
		$this->visitorCode = $visitorCode;
	}

	public function getVisitorCode()
	{
		return $this->visitorCode;
	}

	// public function setUserAccount(UserAccount $userAccount)
	// {
	// 	$this->userAccount = $userAccount;
	// }

	// public function getUserAccount()
	// {
	// 	return $this->userAccount;
	// }

    public function setVisitedAt($visitedAt)
    {
        $this->visitedAt = $visitedAt;
    }

    public function getVisitedAt()
    {
        return $this->visitedAt;
    }
}
