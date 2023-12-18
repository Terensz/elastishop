<?php
namespace framework\packages\ToolPackage\service;

use framework\component\parent\Service;
use framework\packages\ToolPackage\entity\File;
use framework\packages\ToolPackage\repository\FileRepository;
use framework\kernel\utility\BasicUtils;
use framework\kernel\request\Upload;

class Uploader extends Service
{
    protected $title;
    // protected $fileName;
    protected $filePath;
    protected $fileName;
    protected $extension;
    // protected $extension;
    // protected $success;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    // public function setFileName($fileName)
    // {
    //     $this->fileName = $fileName;
    // }

    // public function getFileName()
    // {
    //     return $this->fileName;
    // }

    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    // public function setSuccess($success)
    // {
    //     $this->success = $success;
    // }

    // public function getSuccess()
    // {
    //     return $this->success;
    // }

    public function autoCreateFileName($uploadedName)
    {
        return $this->getSession()->get('visitorCode').'_'.BasicUtils::slugify(
            BasicUtils::explodeAndRemoveElement($uploadedName, '.', 'last')
        );
    }
}
