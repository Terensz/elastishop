<?php
namespace framework\packages\SiteBuilderPackage\entity;

use App;
use framework\component\parent\DbEntity;

class ArticleColumn extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `article_column` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `article_paragraph_id` int(11) DEFAULT NULL,
        `sequence_number` int(5) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=113000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $articleParagraph;
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

    public function setArticleParagraph(ArticleParagraph $articleParagraph)
    {
        $this->articleParagraph = $articleParagraph;
    }

    public function getArticleParagraph() : ? ArticleParagraph
    {
        return $this->articleParagraph;
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