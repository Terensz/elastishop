<?php
namespace framework\packages\SiteBuilderPackage\entity;

use App;
use framework\component\parent\DbEntity;

class ArticleBlock extends DbEntity
{
    const ELEMENT_TYPE_IMAGE = 'image';
    const ELEMENT_TYPE_TEXT = 'text';
    const ELEMENT_TYPE_UNIT = 'unit';

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `article_block` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `article_column_id` int(11) DEFAULT NULL,
        `element_type` varchar(20) DEFAULT NULL,
        `sequence_number` int(5) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=114000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $articleColumn;
    protected $elementType;
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

    public function setArticleColumn(ArticleColumn $articleColumn)
    {
        $this->articleColumn = $articleColumn;
    }

    public function getArticleColumn() : ? ArticleColumn
    {
        return $this->articleColumn;
    }

	public function setElementType($elementType)
	{
		$this->elementType = $elementType;
	}

	public function getElementType()
	{
		return $this->elementType;
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