<?php 
namespace framework\packages\ToolPackage\entity; 

use framework\component\parent\DbEntity;

class ImageHeader extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `image_header` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `code` varchar(200) DEFAULT NULL,
        `title` varchar(100) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `status` int(2) DEFAULT 1,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=59000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $code;
    protected $title;
    protected $description;
    protected $imageFile = array();
    protected $status;

    public function __construct()
    {
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

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
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

    public function addImageFile(ImageFile $imageFile)
    {
        $this->imageFile[] = $imageFile;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getFullSizeImageFile() : ? ImageFile
    {
        foreach ($this->imageFile as $imageFile) {
            if ($imageFile->getImageType() == ImageFile::IMAGE_TYPE_FULL_SIZE) {
                return $imageFile;
            }
        }

        return null;
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