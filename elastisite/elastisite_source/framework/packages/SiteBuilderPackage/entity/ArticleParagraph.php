<?php
namespace framework\packages\SiteBuilderPackage\entity;

use App;
use framework\component\parent\DbEntity;

class ArticleParagraph extends DbEntity
{
    const MAXIMUM_COLUMNS = 4;
    
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `article_paragraph` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `article_id` int(11) DEFAULT NULL,
        `sequence_number` int(5) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=112000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $article;
    protected $sequenceNumber;

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

    public function setArticle(Article $article)
    {
        $this->article = $article;
    }

    public function getArticle() : ? Article
    {
        return $this->article;
    }

    public function setSequenceNumber($sequenceNumber)
    {
        $this->sequenceNumber = $sequenceNumber;
    }

    public function getSequenceNumber()
    {
        return $this->sequenceNumber;
    }
}