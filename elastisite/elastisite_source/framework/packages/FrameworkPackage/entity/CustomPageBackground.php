<?php 
namespace framework\packages\FrameworkPackage\entity; 

use framework\component\parent\DbEntity;

class CustomPageBackground extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `custom_page_background` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `custom_page_id` int(11) NOT NULL,
        `background_id` int(11) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=52000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        'active' => false
    ];

    private $id;
    private $customPage;
    private $background;

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

    public function setCustomPage(CustomPage $customPage)
    {
        $this->customPage = $customPage;
    }

    public function getCustomPage()
    {
        return $this->customPage;
    }

    public function setBackground($background)
    {
        $this->background = $background;
    }

    public function getBackground()
    {
        return $this->background;
    }
}