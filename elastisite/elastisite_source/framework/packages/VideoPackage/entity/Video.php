<?php
namespace framework\packages\VideoPackage\entity;

use framework\component\parent\DbEntity;

class Video extends DbEntity
{
    const STATUS_CODE_CONVERSIONS = [
        '0' => 'disabled',
        '1' => 'active'
    ];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `video` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(100) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `code` varchar(50) DEFAULT NULL,
        `extension` varchar(10) DEFAULT NULL,
        `mime` varchar(30) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` int(2) DEFAULT 1,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=35000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $title;
    protected $description;
    protected $code;
    protected $extension;
    protected $mime;
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

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    public function getMime()
    {
        return $this->mime;
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
