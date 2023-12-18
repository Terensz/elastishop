<?php 
namespace framework\packages\FrameworkPackage\entity; 

use framework\component\parent\FileBasedStorageEntity;

class OpenGraph extends FileBasedStorageEntity
{
    private $id;
    private $createdBy;
    private $createdAt;

    public function __construct()
    {
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

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setBody($body)
    {
        $body = (trim($body) == '&#60;br&#62;') ? '' : $body;
        $body = (trim($body) == '<br>') ? '' : $body;
        $body = (trim($body) == '"<br>"') ? '' : $body;
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
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