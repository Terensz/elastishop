<?php
namespace framework\packages\BackgroundPackage\entity;

use framework\component\parent\FileBasedStorageEntity;

class FBSBackgroundImage extends FileBasedStorageEntity
{
    private $id;
    private $fileName;
    private $fbsBackgroundTheme;
    private $width;
    private $height;
    private $sequence;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFbsBackgroundTheme($fbsBackgroundTheme)
    {
        $this->fbsBackgroundTheme = $fbsBackgroundTheme;
    }

    public function getFbsBackgroundTheme()
    {
        return $this->fbsBackgroundTheme;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    public function getSequence()
    {
        return $this->sequence;
    }
}
