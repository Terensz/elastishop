<?php
namespace framework\packages\ToolPackage\service;

use App;
use framework\kernel\utility\BasicUtils;
use framework\component\parent\Service;
use framework\kernel\utility\FileHandler;

use framework\packages\ToolPackage\entity\File;
use framework\packages\ToolPackage\entity\ImageHeader;
use framework\packages\ToolPackage\entity\ImageFile;

class ImageProcessor extends Service
{
    protected $code;
    protected $fileNamePattern; // {code}_{imageType}. Placeholders: {code}, {imageType}, {camelCaseImageType}
    protected $thumbnailTypes = array();
    // protected $fileName;
    protected $title;
    protected $description;
    protected $galleryName;
    protected $pathBaseType = 'full'; // source, dynamic, projects
    protected $filePath; // e.g.: upload/images

    private $fileType = 'image'; // image, video
    private $extension;
    private $imageHeader;
    private $fullSizeFileName;

    public function __construct()
    {
        $this->wireService('ToolPackage/entity/File');
        $this->wireService('ToolPackage/entity/ImageHeader');
        $this->wireService('ToolPackage/entity/ImageFile');
    }

    public function getImageService()
    {   
        $this->wireService('ToolPackage/service/ImageService');
        return $this->getService('ImageService');
    }

    public function handleUpload() : ? ImageHeader
    {
        // dump($this);
        $this->wireService('ToolPackage/service/ImageUploader');
        $upload = $this->getContainer()->getKernelObject('UploadRequest')->get();
        $extension = BasicUtils::explodeAndGetElement($upload->getName(), '.', 'last');
        $extension = $this->getImageService()->determineExtension($extension);
        if (!$extension) {
            throw new \Exception('UploadFailed');
            // return null;
        }
        // dump($extension);exit;
        $this->extension = $extension;
        $uploader = new ImageUploader();
        $uploader->setImgurFormat(false);
        // $filePath = $this->pathBaseType == 'full' ? $this->filePath : FileHandler::completePath($this->filePath, $this->pathBaseType);
        $uploader->setFilePath($this->pathBaseType == 'full' ? $this->filePath : FileHandler::completePath($this->filePath, $this->pathBaseType));
        $fullSizeFileName = $this->createFileName('fullSize');
        $this->fullSizeFileName = $fullSizeFileName;
        $uploader->setFileName($fullSizeFileName);
        $uploader->setExtension($extension);

        // dump($uploader);

        $uploadResult = $uploader->upload();

        // dump('$fullSizeFileName: '.$fullSizeFileName);
        // dump('$this->fileNamePattern: '.$this->fileNamePattern);
        // dump('$this->code: '.$this->code);
        // dump($uploadResult);

        if ($uploadResult['success']) {
            $this->createAndStoreImageHeader();

            // dump($this->imageHeader);

            $imageFile = new ImageFile();
            $imageFile->setImageHeader($this->imageHeader);
            $imageFile->setImageType('fullSize');
            $file = $this->createAndStoreFile($fullSizeFileName);
            $imageFile->setFile($file);
            $imageFile = $imageFile->getRepository()->store($imageFile);

            // dump($imageFile);

            $this->createThumbnails();
        } else {
            throw new \Exception('UploadFailed');
            // return null;
        }

        return $this->imageHeader->getRepository()->find($this->imageHeader->getId());
    }

    private function getThumbnailSize($thumbnailType, $sizeType = null)
    {
        $thumbnailSizes = $this->getImageService()::$thumbnailSizes;
        if (!isset($thumbnailSizes[$thumbnailType])) {
            $thumbnailType = $this->getImageService()::DEFAULT_THUMBNAIL_TYPE;
        }
        $thumbnailSize = $thumbnailSizes[$thumbnailType];
        if (!isset($thumbnailSize['height'])) {
            $thumbnailSize['height'] = null;
        }
        if (!isset($thumbnailSize['width'])) {
            $thumbnailSize['width'] = null;
        }
        if ($sizeType) {
            return $thumbnailSize[$sizeType];
        }
        return [
            'height' => $thumbnailSize['height'],
            'width' => $thumbnailSize['width']
        ];
    }

    private function createThumbnails()
    {
        if ($this->thumbnailTypes == array()) {
            return false;
        }

        foreach ($this->thumbnailTypes as $thumbnailType) {
            $thumbnailSize = $this->getThumbnailSize($thumbnailType);
            $fileName = $this->createFileName($thumbnailType);
            // public function createThumbnail($pathToOriginalFile, $thumbName, $thumbWidth = 120, $thumbHeight = null, $pathBaseType = 'dynamic')
            $this->getImageService()->createThumbnail($this->filePath.'/'.$this->fullSizeFileName.'.'.$this->extension, $fileName, $thumbnailSize['width'], $thumbnailSize['height'], $this->pathBaseType);
            $imageFile = new ImageFile();
            $imageFile->setImageHeader($this->imageHeader);
            $imageFile->setImageType($thumbnailType);
            $file = $this->createAndStoreFile($fileName);
            $imageFile->setFile($file);
            $imageFile = $imageFile->getRepository()->store($imageFile);
        }
    }

    // private function createPathToFile($imageType)
    // {
    //     return $this->filePath.'/'.$this->createFileName($imageType);
    // }

    private function createFileName($imageType)
    {
        $fileName = $this->fileNamePattern;
        $fileName = str_replace('{code}', $this->code, $fileName);
        $fileName = str_replace('{camelCaseImageType}', BasicUtils::snakeToCamelCase($imageType), $fileName);
        $fileName = str_replace('{imageType}', $imageType, $fileName);

        return $fileName;
    }

    private function createAndStoreImageHeader()
    {
        $imageHeader = new ImageHeader();
        $imageHeader->setCode($this->code);
        $imageHeader->setTitle($this->title);
        $imageHeader->setDescription($this->description);
        $imageHeader->setStatus(1);
        $imageHeader = $imageHeader->getRepository()->store($imageHeader);

        $this->imageHeader = $imageHeader;
    }

    private function createAndStoreFile($fileName)
    {
        $file = new File();
        $file->setWebsite(App::getWebsite());
        $file->setGalleryName($this->galleryName);
        $file->setPathBaseType($this->pathBaseType);
        $file->setPath($this->filePath);
        $file->setFileType($this->fileType);
        $file->setFileName($fileName);
        $file->setExtension($this->extension);
        $file->setMime($this->extension);
        $file = $file->getRepository()->store($file);
        
        return $file;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setFileNamePattern($fileNamePattern)
    {
        $this->fileNamePattern = $fileNamePattern;
    }

    public function setThumbnailTypes($thumbnailTypes)
    {
        $this->thumbnailTypes = $thumbnailTypes;
    }

    // public function setExtension($extension)
    // {
    //     $this->extension = $extension;
    // }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setGalleryName($galleryName)
    {
        $this->galleryName = $galleryName;
    }

    public function setPathBaseType($pathBaseType)
    {
        $this->pathBaseType = $pathBaseType;
    }

    public function setFilePath($filePath)
    {
        $this->filePath = '/'.trim($filePath, '/');
    }
}
