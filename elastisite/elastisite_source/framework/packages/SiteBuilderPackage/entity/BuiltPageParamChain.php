<?php
namespace framework\packages\SiteBuilderPackage\entity;

use framework\component\parent\DbEntity;

class BuiltPageParamChain extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `built_page_param_chain` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
		`built_page_id` int(11) DEFAULT NULL,
        `param_chain` varchar(250) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=103000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $builtPage;
    protected $paramChain;

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

	public function setBuiltPage(BuiltPage $builtPage)
	{
		$this->builtPage = $builtPage;
	}

	public function getBuiltPage() : BuiltPage
	{
		return $this->builtPage;
	}

	public function setParamChain($paramChain)
	{
		$this->paramChain = $paramChain;
	}

	public function getParamChain()
	{
		return $this->paramChain;
	}
}
