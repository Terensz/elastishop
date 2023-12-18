<?php 
namespace framework\packages\WebshopPackage\entity; 

use framework\component\parent\DynamicDbEntity;

class DynamicProductProperties extends DynamicDbEntity
{
    private $id;
    private $params;

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
}