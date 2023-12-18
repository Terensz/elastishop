<?php 
namespace framework\packages\ToolPackage\entity; 

use framework\component\parent\DbEntity;

class ImageFile extends DbEntity
{
    const IMAGE_TYPE_FULL_SIZE = 'fullSize';

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `image_file` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `image_header_id` int(11) DEFAULT NULL,
        `image_type` varchar(20) DEFAULT NULL,
        `file_id` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=58000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $imageHeader;
    protected $imageType;
    protected $file;

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

    public function setImageHeader(ImageHeader $imageHeader = null)
    {
        $this->imageHeader = $imageHeader;
    }

    public function getImageHeader()
    {
        return $this->imageHeader;
    }

    public function setImageType($imageType)
    {
        $this->imageType = $imageType;
    }

    public function getImageType()
    {
        return $this->imageType;
    }

    public function setFile(File $file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }
}