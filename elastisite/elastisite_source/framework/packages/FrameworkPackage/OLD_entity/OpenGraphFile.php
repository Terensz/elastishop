<?php 
namespace framework\packages\FrameworkPackage\entity; 

use framework\component\parent\DbEntity;

class OpenGraphFile extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `open_graph_file` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `file_type` varchar(10) DEFAULT NULL,
        `file_name` varchar(50) DEFAULT NULL,
        `extension` varchar(10) DEFAULT NULL,
        `mime` varchar(30) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` int(2) DEFAULT 1,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=47000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $fileType;
    protected $fileName;
    protected $extension;
    protected $mime;
    protected $createdAt;
    protected $status;

    public function __construct()
    {
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

    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
    }

    public function getFileType()
    {
        return $this->fileType;
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
        // return BasicUtils::explodeAndGetElement($this->pathToThumbnail, '.', 'last');
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