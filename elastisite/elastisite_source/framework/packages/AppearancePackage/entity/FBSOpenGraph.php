<?php
namespace framework\packages\AppearancePackage\entity;

use framework\component\parent\FileBasedStorageEntity;

class FBSOpenGraph extends FileBasedStorageEntity
{
    private $id;
    private $title;
    private $description;

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

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
