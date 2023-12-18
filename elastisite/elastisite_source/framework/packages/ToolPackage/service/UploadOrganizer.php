<?php
namespace framework\packages\ToolPackage\service;

use App;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;

class UploadOrganizer
{
    const FILE_MAIN_TYPE_MEDIA = 'Media';
    const FILE_MAIN_TYPE_DOCUMENT = 'Document';
    const FILE_MAIN_TYPE_UNCATEGORIZED = 'Uncategorized';
    const FILE_SUBTYPE_IMAGE = 'Image';
    const FILE_SUBTYPE_PDF = 'PDF';
    const FILE_SUBTYPE_AUDIO = 'Audio';
    const FILE_SUBTYPE_VIDEO = 'Video';
    const FILE_SUBTYPE_UNCATEGORIZED = 'Uncategorized';

    const FILE_EXTENSION_AVI = 'avi';
    const FILE_EXTENSION_JPG = 'jpg';

    const IMAGE_SIZE_TYPE_ORIGINAL = 'original';
    const IMAGE_SIZE_TYPE_LARGE = 'large';
    const IMAGE_SIZE_TYPE_THUMBNAIL = 'thumbnail';

    const MEDIA_SUBTYPES = [
        self::FILE_SUBTYPE_IMAGE,
        self::FILE_SUBTYPE_VIDEO,
        self::FILE_SUBTYPE_AUDIO
    ];

    const DOCUMENT_SUBTYPES = [
        self::FILE_SUBTYPE_PDF
    ];

    const DEFAULT_IMAGE_WIDTH_LARGE = 800;

    const MIME_TYPE_CONVERSIONS = [
        'video/x-msvideo' => [
            'fileMainType' => self::FILE_MAIN_TYPE_MEDIA,
            'fileSubtype' => self::FILE_SUBTYPE_VIDEO,
            'fileExtension' => self::FILE_EXTENSION_AVI
        ],
        'image/jpeg' => [
            'fileMainType' => self::FILE_MAIN_TYPE_MEDIA,
            'fileSubtype' => self::FILE_SUBTYPE_IMAGE,
            'fileExtension' => self::FILE_EXTENSION_JPG
        ]
    ];

    // const MIME_MAIN_TYPE_CONVERSIONS = [
    //     'image' => [
    //         'fileMainType' => self::FILE_MAIN_TYPE_MEDIA,
    //         'fileSubtype' => self::FILE_SUBTYPE_IMAGE
    //     ],
    //     'video' => [
    //         'fileMainType' => self::FILE_MAIN_TYPE_MEDIA,
    //         'fileSubtype' => self::FILE_SUBTYPE_VIDEO
    //     ],
    //     'audio' => [
    //         'fileMainType' => self::FILE_MAIN_TYPE_MEDIA,
    //         'fileSubtype' => self::FILE_SUBTYPE_AUDIO
    //     ]
    // ];

    /**
     * Automatically set
    */
    public $mimeType;
    public $pathToTempFile;
    public $fileMainType;
    public $fileSubtype;
    public $fileName;
    public $extension;
    public $uploadObject;
    public $subtypeUploaderObject;
    public $uploadResult;

    /**
     * Manually set
    */
    public $allowedMainTypes = [self::FILE_MAIN_TYPE_MEDIA];
    public $allowedSubtypes = [self::FILE_SUBTYPE_IMAGE];
    public $resizeOriginalToWidth = self::DEFAULT_IMAGE_WIDTH_LARGE;
    public $createThumbnail = false;
    public $newFileName;
    // "projects/ProjectNeve/"
    public $dynamicFilePathBase;
    // ""
    public $dynamicFileSubPath = '/upload/userImages';
    public $originalImageSubPath = '/original';
    public $largeImageSubPath = '/large';
    public $thumbnailSubPath = '/thumbnail';

    public $returnImgurFormat = false;


    public function __construct()
    {
        $mimeType = null;
        $this->dynamicFilePathBase = 'projects/'.App::getWebProject();
        $upload = App::getContainer()->getUploadRequest()->get();
        if ($upload) {
            $this->uploadObject = $upload;
            // var_dump($upload);
            $this->pathToTempFile = $upload->getTmpName();
            $this->fileName = BasicUtils::explodeAndRemoveElement($upload->getName(), '.', 'last');
            $mimeType = $upload->getMime();
            $this->mimeType = $mimeType;
            $mimeTypeParts = self::getMimeTypePart($mimeType);
            if ($mimeTypeParts['fileMainType'] == 'image') {
                $this->fileMainType = self::FILE_MAIN_TYPE_MEDIA;
                $this->fileSubtype = self::FILE_SUBTYPE_IMAGE;
                $this->extension = self::getExtensionFromMimeType($mimeType);
            }
            if ($mimeTypeParts['fileMainType'] == 'video') {
                $this->fileMainType = self::FILE_MAIN_TYPE_MEDIA;
                $this->fileSubtype = self::FILE_SUBTYPE_VIDEO;
                $this->extension = self::getExtensionFromMimeType($mimeType);
            }
            
            // $mime = $fileInfo;
            // $this->upload();
        }
    }

    private function uploadByFileSubtype()
    {
        if ($this->fileSubtype == self::FILE_SUBTYPE_IMAGE) {
            $this->uploadOriginalImage();
        }
    }

    public function getDynamicFilePath($absolutePathRequired = false)
    {
        $path = $this->dynamicFilePathBase . $this->dynamicFileSubPath;
        // $relativePath =
        if ($absolutePathRequired) {
            $path = FileHandler::completePath($path, 'dynamic');
        }

        return $path;
    }

    public function getPathToFile($imageSizeType, $absolutePathRequired = false, $original = true)
    {
        return $this->getFilePath($imageSizeType, $absolutePathRequired).$this->getFullFileName($original);
    }

    public function getFilePath($imageSizeType, $absolutePathRequired = false)
    {
        $lastSubPath = '';
        if ($imageSizeType == self::IMAGE_SIZE_TYPE_ORIGINAL) {
            $lastSubPath = $this->originalImageSubPath;
        } elseif ($imageSizeType == self::IMAGE_SIZE_TYPE_LARGE) {
            $lastSubPath = $this->largeImageSubPath;
        } elseif ($imageSizeType == self::IMAGE_SIZE_TYPE_THUMBNAIL) {
            $lastSubPath = $this->thumbnailSubPath;
        }

        return $this->getDynamicFilePath($absolutePathRequired).$lastSubPath.'/';
    }

    private function getFullFileName($original = true)
    {
        $fileName = $this->fileName;
        if (!$original && $this->newFileName) {
            $fileName = $this->newFileName;
        }
        return $fileName.'.'.$this->extension;
    }

    private function uploadOriginalImage()
    {
        App::getContainer()->wireService('ToolPackage/service/ImageUploader');
        $this->subtypeUploaderObject = new ImageUploader();
        $this->subtypeUploaderObject->setImgurFormat($this->returnImgurFormat);

        $filePath = $this->getFilePath(self::IMAGE_SIZE_TYPE_ORIGINAL, true);
        $this->subtypeUploaderObject->setFilePath($filePath);
        $this->subtypeUploaderObject->setFileName($this->fileName);
        $this->subtypeUploaderObject->setExtension($this->extension);
        // var_dump($filePath);
        // var_dump($this);
        $uploadResult = $this->subtypeUploaderObject->upload();
        $this->uploadResult = $uploadResult;
        // var_dump($uploadResult);exit;
        if ($uploadResult['success']) {
            App::getContainer()->wireService('ToolPackage/service/ImageService');
            $imageService = new ImageService();

            if ($this->resizeOriginalToWidth) {
                /**
                 * Large size
                */
                $imageService->createResizedImage(
                    $this->getPathToFile(self::IMAGE_SIZE_TYPE_ORIGINAL),
                    $this->getPathToFile(self::IMAGE_SIZE_TYPE_LARGE, true, false),
                    self::DEFAULT_IMAGE_WIDTH_LARGE,
                    null
                );
            }

            /**
             * Thumbnail
            */
            $imageService->createThumbnail(
                $this->getPathToFile(self::IMAGE_SIZE_TYPE_ORIGINAL),
                $this->getPathToFile(self::IMAGE_SIZE_TYPE_THUMBNAIL, true, false),
                ImageService::$thumbnailSizes[ImageService::IMAGE_TYPE_THUMBNAIL_W120]['width'],
                null
            );

            $pathToOriginalFile = $this->getPathToFile(self::IMAGE_SIZE_TYPE_ORIGINAL, true);
            if (is_file($pathToOriginalFile)) {
                unlink($pathToOriginalFile);
            }
        }
    }

    public function upload()
    {
        return $this->uploadByFileSubtype();
    }

    public static function getExtensionFromMimeType($mimeType)
    {
        return isset(self::MIME_TYPE_CONVERSIONS[$mimeType]) ? self::MIME_TYPE_CONVERSIONS[$mimeType]['fileExtension'] : self::getMimeTypePart($mimeType, 'fileSubtype');
    }

    public static function getMimeTypePart($mime, $requiredPart = null)
    {
        $mimeParts = explode('/', $mime);
        if (count($mimeParts) != 2) {
            return [
                'fileMainType' => null,
                'fileSubtype' => null
            ];
        }

        if (!$requiredPart) {
            return [
                'fileMainType' => $mimeParts[0],
                'fileSubtype' => $mimeParts[1]
            ];
        } else {
            return $requiredPart == 'fileMainType' ? $mimeParts[0] : $mimeParts[1];
        }
    }

    // public static function detectFileType($filePath)
    // {
    //     $fileInfo = self::getMimeType($filePath);
    // }

    // public static function getMimeType($filePath)
    // {
    //     if (function_exists('finfo_open')) {
    //         $finfoOpen = finfo_open(FILEINFO_MIME_TYPE);
    //         $fileInfo = finfo_file($finfoOpen, $filePath);
    //         finfo_close($finfoOpen);
    //         return $fileInfo;
    //     } else {
    //         throw new \Exception("A finfo bővítmény nem található a szerveren.");
    //     }
    // }

    // public static function getMimeType($filePath)
    // {
    //     if (function_exists('finfo_open')) {
    //         $finfoOpen = finfo_open(FILEINFO_MIME_TYPE);
    //         $fileInfo = finfo_file($finfoOpen, $filePath);
    //         finfo_close($finfoOpen);
    //         return $fileInfo;
    //     } else {
    //         throw new \Exception("A finfo bővítmény nem található a szerveren.");
    //     }
    // }
}
