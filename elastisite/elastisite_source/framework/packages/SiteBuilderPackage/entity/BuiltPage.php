<?php
namespace framework\packages\SiteBuilderPackage\entity;

use App;
use framework\component\parent\DbEntity;
// use framework\packages\ToolPackage\entity\ImageHeader;

class BuiltPage extends DbEntity
{
	const AVAILABLE_VALUES = [
		'number_of_panels' => [1, 2]
	];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `built_page` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `route_name` varchar(250) DEFAULT NULL,
        `title` varchar(250) DEFAULT NULL,
        `structure` varchar(250) DEFAULT NULL,
		`number_of_panels` tinyint(1) DEFAULT NULL,
        `is_menu_item` smallint(1) DEFAULT NULL,
        `permission` varchar(30) DEFAULT NULL,
        `created_at` DATETIME DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=101000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $website;
	protected $builtPageWidget = [];
    protected $routeName;
    protected $title;
    protected $structure;
	protected $numberOfPanels;
    protected $isMenuItem;
    protected $permission;
    protected $createdAt;
    protected $status;

	public function __construct()
	{
        $this->website = App::getWebsite();
        $this->createdAt = $this->getCurrentTimestamp();
		$this->numberOfPanels = 2;
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

	public function addBuiltPageWidget(BuiltPageWidget $builtPageWidget)
	{
		$this->builtPageWidget[] = $builtPageWidget;
	}

	public function setAllBuiltPageWidgets($builtPageWidgets)
	{
		$this->builtPageWidget = $builtPageWidgets;
	}

	public function getBuiltPageWidget() : array
	{
		return $this->builtPageWidget;
	}

	public function setRouteName($routeName)
	{
		$this->routeName = $routeName;
	}

	public function getRouteName()
	{
		return $this->routeName;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

    public function setStructure($structure)
	{
		$this->structure = $structure;
	}

	public function getStructure()
	{
		return $this->structure;
	}

	public function setNumberOfPanels($numberOfPanels)
	{
		$this->numberOfPanels = $numberOfPanels;
	}

	public function getNumberOfPanels()
	{
		return $this->numberOfPanels;
	}

	public function setIsMenuItem($isMenuItem)
	{
		$this->isMenuItem = $isMenuItem;
	}

	public function getIsMenuItem()
	{
		return $this->isMenuItem;
	}

    public function setPermission($permission)
	{
		$this->permission = $permission;
	}

	public function getPermission()
	{
		return $this->permission;
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
