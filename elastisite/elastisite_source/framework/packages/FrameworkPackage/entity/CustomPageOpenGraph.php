<?php 
namespace framework\packages\FrameworkPackage\entity; 

use framework\component\parent\DbEntity;

class CustomPageOpenGraph extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `custom_page_open_graph` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `custom_page_id` int(11) NOT NULL,
        `open_graph_id` int(11) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=52000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        // 'active' => false
    ];

    private $id;
    private $customPage;
    private $openGraph;

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

    public function setOpenGraph(OpenGraph $openGraph)
    {
        $this->openGraph = $openGraph;
    }

    public function getOpenGraph()
    {
        return $this->openGraph;
    }

}