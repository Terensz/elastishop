<?php 
namespace framework\packages\FrameworkPackage\entity; 

use framework\component\parent\DbEntity;
use framework\packages\ToolPackage\entity\ImageHeader;

class OpenGraphImageHeader extends DbEntity
{
    const GALLERY_NAME = 'openGraphImages';

    const THUMBNAIL_IMAGE_TYPE = 'thumbnail_w120';

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `open_graph_image_header` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `open_graph_id` int(11) DEFAULT NULL,
        `image_header_id` int(11) DEFAULT NULL,
        `primary_image` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=49000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $openGraph;
    protected $imageHeader;
    protected $primaryImage;

    public function __construct()
    {
        // $this->createdAt = $this->getCurrentTimestamp();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setOpenGraph(OpenGraph $openGraph)
    {
        $this->openGraph = $openGraph;
    }

    public function getOpenGraph()
    {
        return $this->openGraph;
    }

    public function setImageHeader(ImageHeader $imageHeader)
    {
        $this->imageHeader = $imageHeader;
    }

    public function getImageHeader()
    {
        return $this->imageHeader;
    }

    public function setPrimaryImage($primaryImage)
    {
        $this->primaryImage = $primaryImage;
    }

    public function getPrimaryImage()
    {
        return $this->primaryImage;
    }
}