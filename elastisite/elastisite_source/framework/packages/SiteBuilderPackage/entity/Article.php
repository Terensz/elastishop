<?php
namespace framework\packages\SiteBuilderPackage\entity;

use App;
use framework\component\parent\DbEntity;
// use framework\packages\ToolPackage\entity\ImageHeader;

class Article extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `article` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `route_name` varchar(250) DEFAULT NULL,
        `title` varchar(250) DEFAULT NULL,
        `lead_paragraph` text DEFAULT NULL,
        `created_at` DATETIME DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=111000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $website;
    protected $routeName;
    protected $title;
    protected $leadParagraph;
    protected $articleParagraph = [];
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

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setAllArticleParagraphs($articleParagraphs)
	{
		$this->articleParagraph = $articleParagraphs;
	}

	public function addArticleParagraph(ArticleParagraph $articleParagraph)
	{
		$this->articleParagraph[] = $articleParagraph;
	}

	public function getArticleParagraph()
	{
		return $this->articleParagraph;
	}

	public function setLeadParagraph($leadParagraph)
	{
		$this->leadParagraph = $leadParagraph;
	}

	public function getLeadParagraph()
	{
		return $this->leadParagraph;
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