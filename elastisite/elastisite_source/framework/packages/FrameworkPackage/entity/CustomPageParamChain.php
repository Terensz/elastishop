<?php 
namespace framework\packages\FrameworkPackage\entity; 

use framework\component\parent\DbEntity;

class CustomPageParamChain extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `custom_page_param_chain` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `custom_page_id` int(11) NOT NULL,
        `param_chain` varchar(250) DEFAULT NULL,
        `locale` varchar(10) DEFAULT 'default',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=52000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        // 'active' => false
    ];

    private $id;
    private $customPage;
    private $paramChain;
    private $locale;

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

    public function setParamChain($paramChain)
    {
        $this->paramChain = $paramChain;
    }

    public function getParamChain()
    {
        return $this->paramChain;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }
}