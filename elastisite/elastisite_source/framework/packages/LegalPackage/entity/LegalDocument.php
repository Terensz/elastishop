<?php
namespace framework\packages\LegalPackage\entity;

// use framework\component\parent\Service;

class LegalDocument
{
    private $version;
    private $title;
    private $active = true;
    private $category;
    private $body;

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setActive($active)
    {
        if ($active === 'true') {
            $active = true;
        }
        if ($active === 'false') {
            $active = false;
        }
        $this->active = $active;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }
}
