<?php
namespace framework\packages\SeoPackage\entity;

use App;
use framework\component\parent\DbEntity;

class PageKeyword extends DbEntity
{
	const KEYWORD_VARIETY_INCLUDING = 1;
	const KEYWORD_VARIETY_EXCLUDING = 2;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `page_keyword` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
		`website` varchar(250) DEFAULT NULL,
		`route_name` varchar(250) COLLATE utf8_hungarian_ci DEFAULT NULL,
		`name` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=42000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $website;
    protected $routeName;
	protected $name;

	public function __construct()
	{
		$this->website = App::getWebsite();
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

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}
}
