<?php

namespace projects\ASC\entity;

use framework\component\parent\DbEntity;
// use framework\packages\UserPackage\entity\UserAccount;

class AscEntry extends DbEntity
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

	const DEFAULT_LANGUAGE_TRUE = 1;
	const DEFAULT_LANGUAGE_FALSE = 0;

	const CREATE_TABLE_STATEMENT = "CREATE TABLE `asc_entry` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `asc_entry_head_id` int(11) DEFAULT NULL,
	`language_code` varchar(2) DEFAULT NULL,
	`default_language` tinyint(1) DEFAULT 1,
    `title` varchar(250) DEFAULT NULL,
    `description` text DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `status` int(2) DEFAULT 1,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=222000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	const ENTITY_ATTRIBUTES = [
		'passOverMissingFields' => ['title_translation_group', 'description_translation_group'],
		'passOverUnnecessaryFields' => ['title_translation_group_id', 'description_translation_group_id'],
	];

    protected $id;
    protected $ascEntryHead;
	protected $languageCode;
	protected $defaultLanguage;
    protected $title;
    protected $description;
    protected $createdAt;
    protected $status;

	public function __construct()
	{
        $this->createdAt = $this->getCurrentTimestamp();
		$this->defaultLanguage = self::DEFAULT_LANGUAGE_TRUE;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setAscEntryHead(AscEntryHead $ascEntryHead)
	{
		$this->ascEntryHead = $ascEntryHead;
	}

	public function getAscEntryHead()
	{
		return $this->ascEntryHead;
	}

	public function setLanguageCode($languageCode)
	{
		$this->languageCode = $languageCode;
	}

	public function getLanguageCode()
	{
		return $this->languageCode;
	}

	public function setDefaultLanguage($defaultLanguage)
	{
		if ($defaultLanguage === true) {
			$defaultLanguage = self::DEFAULT_LANGUAGE_TRUE;
		}
		if ($defaultLanguage === false) {
			$defaultLanguage = self::DEFAULT_LANGUAGE_FALSE;
		}
		$this->defaultLanguage = $defaultLanguage;
	}

	public function getDefaultLanguage()
	{
		$defaultLanguage = $this->defaultLanguage;
		if ($defaultLanguage === self::DEFAULT_LANGUAGE_TRUE) {
			$defaultLanguage = true;
		}
		if ($defaultLanguage === self::DEFAULT_LANGUAGE_FALSE) {
			$defaultLanguage = false;
		}
		return $this->defaultLanguage;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function getDescription()
	{
		return $this->description;
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