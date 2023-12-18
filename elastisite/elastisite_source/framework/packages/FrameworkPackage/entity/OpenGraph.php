<?php 
namespace framework\packages\FrameworkPackage\entity;

use App;
use framework\component\parent\DbEntity;

class OpenGraph extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `open_graph` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `route_name` varchar(250) DEFAULT NULL,
        `title` varchar(100) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` int(2) DEFAULT 1,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=47000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $openGraphImageHeader = array();
    protected $website;
    protected $routeName;
    protected $title;
    protected $description;
    protected $createdAt;
    protected $status;

    public function __construct()
    {
        $this->website = App::getWebsite();
        $this->createdAt = $this->getCurrentTimestamp();
        $this->status = 0;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function resetOpenGraphImageHeader()
    {
        return $this->openGraphImageHeader = [];
    }

    public function addOpenGraphImageHeader(OpenGraphImageHeader $openGraphImageHeader = null)
    {
        $this->openGraphImageHeader[] = $openGraphImageHeader;
    }

    public function getOpenGraphImageHeader()
    {
        return $this->openGraphImageHeader;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($description = null)
    {
        $this->description = $description ? strip_tags(html_entity_decode($description)) : null;
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

    public function hasImageHeader()
    {
        $result = false;
        if (isset($this->openGraphImageHeader[0])) {
            if (($this->openGraphImageHeader[0])->getImageHeader()->getCode()) {
                $result = true;
            }
        }
        return $result;
    }

    public function getMainOpenGraphImageHeader()
    {
        $result = null;
        if (isset($this->openGraphImageHeader[0])) {
            $result = $this->openGraphImageHeader[0];
        }

        return $result;
    }

    public function getMainImageHeader()
    {
        $result = null;
        if (isset($this->openGraphImageHeader[0])) {
            if (($this->openGraphImageHeader[0])->getImageHeader()) {
                $result = ($this->openGraphImageHeader[0])->getImageHeader();
            }
        }
        return $result;
    }

    public function getMainImageFile($imageType = 'fullSize')
    {
        $result = null;
        $mainImageHeader = $this->getMainImageHeader();
        // dump($mainImageHeader);
        if ($mainImageHeader) {
            $imageFiles = $mainImageHeader->getImageFile();
            foreach ($imageFiles as $imageFile) {
                if ($imageFile->getImageType() == $imageType) {
                    return $imageFile;
                }
            }
            return $result;
        }

        return null;
    }

    public function setMainImageHeader($imageHeader)
    {
        $this->openGraphImageHeader = array($imageHeader);
    }
}