<?php
namespace framework\packages\BackgroundPackage\entity;

use framework\component\parent\FileBasedStorageEntity;
use framework\kernel\utility\BasicUtils;

class FBSBackground extends FileBasedStorageEntity
{
    private $id;
    private $title;
    private $engine;
    private $theme;
    private $extension;
    // private $pathToThumbnail;

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

    public function setEngine($engine)
    {
        $this->engine = $engine;
    }

    public function getEngine()
    {
        return $this->engine;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    public function getExtension()
    {
        // return BasicUtils::explodeAndGetElement($this->pathToThumbnail, '.', 'last');
        return $this->extension;
    }

    // public function setPathToThumbnail($pathToThumbnail)
    // {
    //     $this->pathToThumbnail = $pathToThumbnail;
    // }

    // public function getPathToThumbnail()
    // {
    //     return $this->pathToThumbnail;
    // }
}
