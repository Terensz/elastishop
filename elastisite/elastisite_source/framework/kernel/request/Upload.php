<?php
namespace framework\kernel\request;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;

class Upload extends Kernel
{
    protected $error;
    protected $name;
    protected $size;
    protected $tmpName;
    protected $width;
    protected $height;
    protected $mime;
    protected $extension;

    public function setError($error)
    {
        $this->error = $error;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setName($name)
    {
        $this->name = $name;
        $this->setService('ToolPackage/service/ImageService');
        $imageService = $this->getService('ImageService');
        $extension = $imageService->determineExtension($name, 'Upload@setName');
        if (!$extension) {
            $this->setService('VideoPackage/service/VideoService');
            $videoService = $this->getService('VideoService');
            $extension = $videoService->determineExtension($name, 'Upload@setName');
        }
        // $extension = BasicUtils::explodeAndGetElement($name, '.', 'last');
        // $extension = $extension == 'jpeg' ? 'jpg' : $extension;
        $this->extension = $extension;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setTmpName($tmpName)
    {
        $this->tmpName = $tmpName;
    }

    public function getTmpName()
    {
        return $this->tmpName;
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

    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    public function getMime()
    {
        return $this->mime;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function saveTo(string $destination)
    {
        return FileHandler::moveUploadedFile($this->tmpName, $destination);
    }
}
