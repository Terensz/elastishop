<?php
namespace framework\packages\ArticlePackage\entity;

use framework\component\parent\FileBasedStorageEntity;

class Article extends FileBasedStorageEntity
{
    const TEASER_TYPE_NORMAL = 1;
    const TEASER_TYPE_HARD_CODED = 2;

    private $id;
    private $permission;
    private $title;
    // private $hardCodedOverTeaser;
    private $teaser;
    private $teaserType;
    private $teaserImageLink;
    private $slug;
    private $createdBy;
    private $createdAt;
    private $hardCodedSlug;
    private $body;
    private $mainRoute;
    private $position;
    private $active;

    public function __construct()
    {
        $this->createdAt = $this->getCurrentTimestamp();
        $this->teaserType = self::TEASER_TYPE_NORMAL;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPermission($permission)
    {
        $this->permission = $permission;
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    // public function setHardCodedOverTeaser($hardCodedOverTeaser)
    // {
    //     $this->hardCodedOverTeaser = $hardCodedOverTeaser;
    // }
    //
    // public function getHardCodedOverTeaser()
    // {
    //     return $this->hardCodedOverTeaser;
    // }

    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;
    }

    public function getTeaser()
    {
        return $this->teaser;
    }

    public function setTeaserType($teaserType)
    {
        $this->teaserType = $teaserType;
    }

    public function getTeaserType()
    {
        return $this->teaserType;
    }

    public function setTeaserImageLink($teaserImageLink)
    {
        $this->teaserImageLink = $teaserImageLink;
    }

    public function getTeaserImageLink()
    {
        return $this->teaserImageLink;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
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

    public function setHardCodedSlug($hardCodedSlug)
    {
        $this->hardCodedSlug = $hardCodedSlug;
    }

    public function getHardCodedSlug()
    {
        return $this->hardCodedSlug;
    }

    public function setBody($body)
    {
        if ($body) {
            $body = (trim($body) == '&#60;br&#62;') ? '' : $body;
            $body = (trim($body) == '<br>') ? '' : $body;
            $body = (trim($body) == '"<br>"') ? '' : $body;
        }
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setMainRoute($mainRoute)
    {
        $this->mainRoute = $mainRoute;
    }

    public function getMainRoute()
    {
        return $this->mainRoute;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
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
