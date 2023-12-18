<?php

namespace projects\ASC\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class AscEntryFile extends DbEntity
{
    const TYPE_IMAGE = 'Image';
    const TYPE_PDF = 'PDF';
    const TYPE_VIDEO = 'Video';

    const ALLOWED_TYPES = [
        self::TYPE_IMAGE, self::TYPE_PDF, self::TYPE_VIDEO
    ];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `asc_entry_file` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `asc_entry_id` int(11) DEFAULT NULL,
    `title` varchar(250) DEFAULT NULL,
    -- `dynamic_path` varchar(250) DEFAULT NULL,
    `name` varchar(250) DEFAULT NULL,
    `extension` varchar(250) DEFAULT NULL,
    `type` varchar(250) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `status` int(2) DEFAULT 1,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=216000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $ascEntry;
    protected $title;
    protected $name;
    protected $extension;
    protected $type;
    protected $createdAt;
    protected $status;

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

    public function setAscEntry(AscEntry $ascEntry)
    {
        $this->ascEntry = $ascEntry;
    }

    public function getAscEntry()
    {
        return $this->ascEntry;
    }

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
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