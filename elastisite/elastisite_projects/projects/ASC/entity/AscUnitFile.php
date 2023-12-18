<?php

namespace projects\ASC\entity;

use App;
use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class AscUnitFile extends DbEntity
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

    const TYPE_IMAGE = 'Image';
    const TYPE_PDF = 'PDF';
    const TYPE_VIDEO = 'Video';

    const ALLOWED_TYPES = [
        self::TYPE_IMAGE, self::TYPE_PDF, self::TYPE_VIDEO
    ];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `asc_unit_file` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `code` varchar(250) DEFAULT NULL,
    `asc_unit_id` int(11) DEFAULT NULL,
    `title` varchar(250) DEFAULT NULL,
    -- `dynamic_path` varchar(250) DEFAULT NULL,
    -- `relative_large_image_path` varchar(250) DEFAULT NULL,
    -- `relative_thumbnail_path` varchar(250) DEFAULT NULL,
    `file_name` varchar(250) DEFAULT NULL,
    `extension` varchar(250) DEFAULT NULL,
    `sub_type` varchar(250) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `status` int(2) DEFAULT 1,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=216000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $code;
    protected $ascUnit;
    protected $title;
    // protected $relativeLargeImagePath;
    // protected $relativeThumbnailPath;
    protected $fileName;
    protected $extension;
    protected $subType;
    protected $createdAt;
    protected $status;

	public function __construct()
	{
        $this->createdAt = $this->getCurrentTimestamp();
        $this->status = self::STATUS_ACTIVE;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setCode($code)
	{
		$this->code = $code;
	}

	public function getCode()
	{
		return $this->code;
	}

    public function setAscUnit(AscUnit $ascUnit)
    {
        $this->ascUnit = $ascUnit;
    }

    public function getAscUnit()
    {
        return $this->ascUnit;
    }

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

    // public function setRelativeLargeImagePath($relativeLargeImagePath)
	// {
	// 	$this->relativeLargeImagePath = $relativeLargeImagePath;
	// }

	public function getRelativeLargeImagePath()
	{
        $relativeLargeImagePath = 'projects/'.App::getWebProject().'/upload/userImages/large/';

		return $relativeLargeImagePath;
	}

    // public function setRelativeThumbnailPath($relativeThumbnailPath)
	// {
	// 	$this->relativeThumbnailPath = $relativeThumbnailPath;
	// }

	public function getRelativeThumbnailPath()
	{
        $relativeLargeImagePath = 'projects/'.App::getWebProject().'/upload/userImages/thumbnail/';

		return $relativeLargeImagePath;
	}

	public function setFileName($fileName)
	{
		$this->fileName = $fileName;
	}

	public function getFileName()
	{
		return $this->fileName;
	}

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setSubType($subType)
    {
        $this->subType = $subType;
    }

    public function getSubType()
    {
        return $this->subType;
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