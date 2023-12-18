<?php
namespace framework\packages\ToolPackage\entity;

use framework\component\parent\TechnicalEntity;

class TechnicalFile extends TechnicalEntity
{
    const ENTITY_ATTRIBUTES = [
        'repositoryPath' => 'framework/packages/ToolPackage/repository/FileRepository',
        'relations' => [],
        'active' => true
    ];

    protected $id;
    protected $title;
    protected $path;
    protected $name;
    protected $type;
    protected $mime;
    protected $originalName;
    protected $extension;
    protected $category;
    protected $createdAt;
    protected $removedAt;
    protected $active;

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

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    public function getMime()
    {
        return $this->mime;
    }

    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;
    }

    public function getOriginalName()
    {
        return $this->originalName;
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setRemovedAt($removedAt)
    {
        $this->removedAt = $removedAt;
    }

    public function getRemovedAt()
    {
        return $this->removedAt;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function getActive()
    {
        return $this->active;
    }
}
