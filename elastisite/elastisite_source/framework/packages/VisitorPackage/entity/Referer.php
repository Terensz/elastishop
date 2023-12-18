<?php
namespace framework\packages\VisitorPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class Referer extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `referer` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `es_route_param_chain` varchar(250) COLLATE utf8_hungarian_ci DEFAULT NULL,
		`transfer_protocol` varchar(6) COLLATE utf8_hungarian_ci DEFAULT NULL,
		`host` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
		`path` varchar(255) COLLATE utf8_hungarian_ci DEFAULT NULL,
		`search_string` text COLLATE utf8_hungarian_ci DEFAULT NULL,
		`visitor_code` varchar(64) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `user_account_id` int(11) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=33000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;

	protected $esRouteParamChain;

	// protected $refererUrl;

	protected $transferProtocol;

	protected $host;

	protected $path;

	protected $searchString;

	protected $visitorCode;

	protected $userAccount;

	protected $createdAt;

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

	public function setEsRouteParamChain($esRouteParamChain)
	{
		$this->esRouteParamChain = $esRouteParamChain;
	}

	public function getEsRouteParamChain()
	{
		return $this->esRouteParamChain;
	}

	public function setTransferProtocol($transferProtocol)
	{
		$this->transferProtocol = $transferProtocol;
	}

	public function getTransferProtocol()
	{
		return $this->transferProtocol;
	}

	public function setHost($host)
	{
		$this->host = $host;
	}

	public function getHost()
	{
		return $this->host;
	}

	public function setPath($path)
	{
		$this->path = $path;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function setSearchString($searchString)
	{
		$this->searchString = $searchString;
	}

	public function getSearchString()
	{
		return $this->searchString;
	}

	public function setVisitorCode($visitorCode)
	{
		$this->visitorCode = $visitorCode;
	}

	public function getVisitorCode()
	{
		return $this->visitorCode;
	}

	public function setUserAccount(UserAccount $userAccount)
	{
		$this->userAccount = $userAccount;
	}

	public function getUserAccount()
	{
		return $this->userAccount;
	}

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
