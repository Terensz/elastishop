<?php 
namespace framework\packages\FrameworkPackage\entity;

use App;
use framework\component\parent\DbEntity;

class CustomPage extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `custom_page` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `route_name` varchar(250) DEFAULT NULL,
        `description` varchar(250) DEFAULT NULL,
        `title` varchar(250) DEFAULT NULL,
        `title_en` varchar(250) DEFAULT NULL,
        UNIQUE KEY `website_route` (`website`,`route_name`),
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=51000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        // 'active' => false
    ];

    protected $id;
    protected $website;
    protected $routeName;
    protected $description;
    protected $title;
    protected $titleEn;

    public function __construct()
    {
        $this->website = App::getWebsite();
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

    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitleEn($titleEn)
    {
        $this->titleEn = $titleEn;
    }

    public function getTitleEn()
    {
        return $this->titleEn;
    }
}