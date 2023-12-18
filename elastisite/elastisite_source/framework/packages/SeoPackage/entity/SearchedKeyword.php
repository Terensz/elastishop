<?php
namespace framework\packages\SeoPackage\entity;

use App;
use framework\component\parent\DbEntity;

class SearchedKeyword extends DbEntity
{
	const KEYWORD_VARIETY_INCLUDING = 1;
	const KEYWORD_VARIETY_EXCLUDING = 2;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `searched_keyword` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `name` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
		`search_string` varchar(250) COLLATE utf8_hungarian_ci DEFAULT NULL,
		`variety` int(11) DEFAULT NULL,
        `quantity` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=34000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $website;
	protected $name;
	protected $searchString;
	protected $variety;
	protected $quantity = 0;

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

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setSearchString($searchString)
	{
		$this->searchString = $searchString;
	}

	public function getSearchString()
	{
		return $this->searchString;
	}

	public function setVariety($variety)
	{
		$this->variety = $variety;
	}

	public function getVariety()
	{
		return $this->variety;
	}

	public function setQuantity($quantity)
	{
		$this->quantity = $quantity;
	}

	public function getQuantity()
	{
		return $this->quantity;
	}
}
