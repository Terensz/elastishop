<?php
namespace framework\packages\BackgroundPackage\entity;

use App;
use framework\component\parent\FileBasedStorageEntity;

class FBSPageBackground extends FileBasedStorageEntity
{
    private $id;
    private $website;
    private $routeName;
    private $fbsBackgroundTheme;
    private $backgroundColor;

    public function __construct()
    {
        $this->website = App::getWebsite();
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

    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    public function setFbsBackgroundTheme($fbsBackgroundTheme)
    {
        $this->fbsBackgroundTheme = $fbsBackgroundTheme;
    }

    public function getFbsBackgroundTheme()
    {
        return $this->fbsBackgroundTheme;
    }

    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
    }

    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }
}
