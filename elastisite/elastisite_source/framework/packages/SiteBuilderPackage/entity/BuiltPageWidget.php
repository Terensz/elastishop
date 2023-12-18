<?php
namespace framework\packages\SiteBuilderPackage\entity;

use framework\component\parent\DbEntity;
// use framework\packages\ToolPackage\entity\ImageHeader;

class BuiltPageWidget extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `built_page_widget` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
		`built_page_id` int(11) DEFAULT NULL,
        `position` varchar(50) DEFAULT NULL,
		`element_index` smallint(3) DEFAULT NULL,
		`widget` varchar(200) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=102000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;

	/**
	 * @var BuiltPage
	 * Parent.
	*/
    protected $builtPage;

	/**
	 * e.g. main, left
	*/
    protected $position;

	/**
	 * e.g. mainContent, left1
	*/
	protected $elementIndex;

	/**
	 * e.g. WrappedSplashWidget
	*/
	protected $widget;

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

	public function getBuiltPage() : ? BuiltPage
	{
		return $this->builtPage;
	}

	public function setPosition($position)
	{
		$this->position = $position;
	}

	public function getPosition()
	{
		return $this->position;
	}

	public function setElementIndex($elementIndex)
	{
		$this->elementIndex = $elementIndex;
	}

	public function getElementIndex()
	{
		return $this->elementIndex;
	}

	public function setWidget($widget)
	{
		$this->widget = $widget;
	}

	public function getWidget()
	{
		return $this->widget;
	}
}
