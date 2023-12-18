<?php
namespace framework\packages\WordClearingPackage\entity;

use framework\component\parent\DbEntity;

class WordExplanation extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `word_explanation` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `key_text` varchar(250) DEFAULT NULL,
        `explanation` text DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=30000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $keyText;
    protected $explanation;
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = $this->getCurrentTimestamp();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setKeyText($keyText)
    {
        $this->keyText = $keyText;
    }

    public function getKeyText()
    {
        return $this->keyText;
    }

    public function setExplanation($explanation)
    {
        $this->explanation = $explanation;
    }

    public function getExplanation()
    {
        return $this->explanation;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
