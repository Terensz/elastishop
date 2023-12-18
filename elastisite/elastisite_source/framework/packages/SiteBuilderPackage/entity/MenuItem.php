<?php
namespace framework\packages\SiteBuilderPackage\entity;

use App;
use framework\component\parent\DbEntity;

class MenuItem extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `menu_item` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `route_name` varchar(250) DEFAULT NULL,
        `route_path` text DEFAULT NULL,
		`title` varchar(250) DEFAULT NULL,
		`sequence_number` smallint(2) DEFAULT NULL,
        `created_at` DATETIME DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=110000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
	protected $website;
	protected $routeName;
    protected $routePath;
	protected $title;
	protected $sequenceNumber;
	protected $createdAt;
	protected $status;

	public function __construct()
	{
        $this->website = App::getWebsite();
        $this->createdAt = $this->getCurrentTimestamp();
        $this->status = 1;
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

	public function setRouteName($routeName)
	{
		$this->routeName = $routeName;
	}

	public function getRouteName()
	{
		return $this->routeName;
	}

	public function setRoutePath($routePath)
	{
		$this->routePath = $routePath;
	}

	public function getRoutePath()
	{
		return $this->routePath;
	}

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setSequenceNumber($sequenceNumber)
    {
        $this->sequenceNumber = $sequenceNumber;
    }

    public function getSequenceNumber()
    {
        return $this->sequenceNumber;
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
