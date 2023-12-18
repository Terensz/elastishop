<?php 
namespace framework\packages\ToolPackage\entity; 

use framework\component\parent\DbEntity;

class File extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `file` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(100) DEFAULT NULL,
        `gallery_name` varchar(100) DEFAULT NULL,
        `path_base_type` varchar(14) DEFAULT NULL,
        `path` varchar(250) DEFAULT NULL,
        `file_type` varchar(10) DEFAULT NULL,
        `file_name` varchar(50) DEFAULT NULL,
        `extension` varchar(10) DEFAULT NULL,
        `mime` varchar(30) DEFAULT NULL,
        `height` varchar(10) DEFAULT NULL,
        `width` varchar(10) DEFAULT NULL,
        `size` varchar(50) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `created_by` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=57000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $website; // e.g.: ElastiSite
    protected $galleryName; // e.g.: openGraph
    protected $pathBaseType; // source, dynamic, projects
    protected $path; // e.g.: upload/images
    protected $fileType; // image, video
    protected $fileName; // e.g.: alma 
    protected $extension; // e.g.: jpg
    protected $mime;
    protected $height; // (in pixels)
    protected $width; // (in pixels)
    protected $size; // (in bytes)
    protected $createdAt;
    protected $createdBy;

    public function __construct()
    {
        $this->createdAt = $this->getCurrentTimestamp();
        // $this->status = 1;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setGalleryName($galleryName)
    {
        $this->galleryName = $galleryName;
    }

    public function getGalleryName()
    {
        return $this->galleryName;
    }

    public function setPathBaseType($pathBaseType)
    {
        $this->pathBaseType = $pathBaseType;
    }

    public function getPathBaseType()
    {
        return $this->pathBaseType;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
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

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    // public function setStatus($status)
    // {
    //     $this->status = $status;
    // }

    // public function getStatus()
    // {
    //     return $this->status;
    // }
}