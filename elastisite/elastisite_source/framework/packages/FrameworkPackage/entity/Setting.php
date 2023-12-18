<?php 
namespace framework\packages\FrameworkPackage\entity;

use App;
use framework\component\parent\DbEntity;

class Setting extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `setting` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250)  DEFAULT NULL,
        `param` varchar(100)  DEFAULT NULL,
        `value` varchar(200)  DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $website;
    protected $param;
    protected $value;
    protected $createdAt;

    public function __construct()
    {
        $this->website = App::getWebsite();
        $this->createdAt = $this->getCurrentTimestamp();
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

    public function setParam($param)
    {
        $this->param = $param;
    }

    public function getParam()
    {
        return $this->param;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}