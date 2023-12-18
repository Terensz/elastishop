<?php 
namespace framework\packages\FrameworkPackage\entity; 

use framework\component\parent\DbEntity;

class PageOpenGraph extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `page_open_graph` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `open_graph_id` int(11) DEFAULT NULL,
        `route_name` varchar(250) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=47000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    private $id;
    private $openGraphId;
    private $routeName;

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

    public function setOpenGraphId($openGraphId)
    {
        $this->openGraphId = $openGraphId;
    }

    public function getOpenGraphId()
    {
        return $this->openGraphId;
    }

    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }
}