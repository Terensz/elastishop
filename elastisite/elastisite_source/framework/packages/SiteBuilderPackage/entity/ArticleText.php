<?php
namespace framework\packages\SiteBuilderPackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\packages\ToolPackage\entity\ImageFile;

class ArticleText extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `article_text` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `article_block_id` int(11) DEFAULT NULL,
        `description` MEDIUMTEXT DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=116000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	protected $id;
    protected $articleBlock;
    protected $description;

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

    public function setArticleBlock(ArticleBlock $articleBlock)
    {
        $this->articleBlock = $articleBlock;
    }

    public function getArticleBlock() : ? ArticleBlock
    {
        return $this->articleBlock;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }
}