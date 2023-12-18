<?php
namespace framework\packages\ToolPackage\service;

use framework\kernel\utility\BasicUtils;
use framework\component\parent\Service;
use framework\kernel\utility\FileHandler;
use framework\component\parent\ImageResponse;

class ImageService extends Service
{
    const PATH_BASE_TYPE_DYNAMIC = 'dynamic';
    const PATH_BASE_TYPE_FULL = 'full';

    public const IMAGE_TYPE_FULL_SIZE = 'fullSize';
    public const IMAGE_TYPE_THUMBNAIL_W16 = 'thumbnail_16';
    public const IMAGE_TYPE_THUMBNAIL_W120 = 'thumbnail_w120';
    public const IMAGE_TYPE_THUMBNAIL_H260 = 'thumbnail_h260';
    public const IMAGE_TYPE_THUMBNAIL_H400 = 'thumbnail_h400';
    public const IMAGE_TYPE_THUMBNAIL_W550 = 'thumbnail_w550';

    public const DEFAULT_THUMBNAIL_TYPE = 'thumbnail_w120';

    const IMAGE_PIXELS_16 = 16;
    const IMAGE_PIXELS_120 = 120;
    const IMAGE_PIXELS_160 = 160;
    const IMAGE_PIXELS_260 = 260;
    const IMAGE_PIXELS_400 = 400;
    const IMAGE_PIXELS_550 = 550;

    public static $thumbnailSizes = [
        'thumbnail_w16' => ['width' => self::IMAGE_PIXELS_16],
        'thumbnail_w120' => ['width' => self::IMAGE_PIXELS_120],
        'thumbnail_h260' => ['height' => self::IMAGE_PIXELS_260],
        'thumbnail_h400' => ['height' => self::IMAGE_PIXELS_400],
        'thumbnail_w550' => ['width' => self::IMAGE_PIXELS_550],
    ];

    public const SUPPORTED_UPLOAD_MIMES = array(
        'image/jpeg', 'image/png', 'image/gif'
    );

    public const SUPPORTED_EXTENSIONS = array(
        'jpeg', 'jpg', 'gif', 'png', 'bmp', 'svg'
    );

    public function cropAndSaveImage($pathFrom, $oldImageName, $pathTo, $newImageName, $cropParams = null)
    {   
        $extension = $this->determineExtension($oldImageName, 'cropAndSaveImage');
        $newImageName = $newImageName.'.'.$extension;
        $mime = $extension == 'jpg' ? 'jpeg' : $extension;
        if (!$mime || $mime == '') {
            dump($oldImageName);
        }
        $imageMethod = 'image'.$mime;
        $creatorMethod = 'imagecreatefrom'.$mime;

        // dump($creatorMethod);dump($oldImageName);dump($pathFrom);dump($pathTo);exit; 
        $image = $creatorMethod($pathFrom.'/'.$oldImageName);
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        if ($cropParams) {
            $height = isset($cropParams['height']) ? $cropParams['height'] : self::IMAGE_PIXELS_160;
            $width = isset($cropParams['width']) ? $cropParams['width'] : $originalWidth;
            $x = isset($cropParams['x']) ? $cropParams['x'] : 0;

            if (($cropParams['y'] + $height) > $originalHeight) {
                return false;
            }

            $image2 = imagecrop($image, [
                'x' => $x,
                'y' => $cropParams['y'],
                'width' => $width,
                'height' => $height
            ]);
            $imageMethod($image2, $pathTo.'/'.$newImageName);
            imagedestroy($image2);
        } else {
            $imageMethod($image, $pathTo.'/'.$newImageName);
            $height = $originalHeight;
            $width = $originalWidth;
        }

        imagedestroy($image);
        return array('height' => $height, 'width' => $width);
    }

    public function determineExtension($imageName, $calledBy = null)
    {
        $extension = BasicUtils::explodeAndGetElement($imageName, '.', 'last');
        if (!$extension) {
            dump($imageName);
            dump($calledBy);exit;
        }
        $extension = strtolower($extension);
        $extension = ($extension == 'jpeg') ? 'jpg' : $extension;
        return (in_array($extension, self::SUPPORTED_EXTENSIONS)) ? $extension : null;
    }

    public function createThumbnail($pathToOriginalFile, $thumbNameOrPath, $resizedWidth = self::IMAGE_PIXELS_120, $resizedHeight = null, $pathBaseType = self::PATH_BASE_TYPE_DYNAMIC)
    {
        return $this->createResizedImage($pathToOriginalFile, $thumbNameOrPath, $resizedWidth, $resizedHeight, $pathBaseType);
    }

    public function createResizedImage($pathToOriginalFile, $resizedNameOrPathToFile, $resizedWidth, $resizedHeight = null, $pathBaseType = self::PATH_BASE_TYPE_DYNAMIC)
    {
        if ($resizedWidth && $resizedHeight) {
            $resizedWidth = null;
        }

        $fullPathToOriginalFile = $pathBaseType == self::PATH_BASE_TYPE_FULL ? $pathToOriginalFile : FileHandler::completePath($pathToOriginalFile, $pathBaseType);
        $extension = $this->determineExtension($pathToOriginalFile, 'createResizedImage');

        $mime = $extension == 'jpg' ? 'jpeg' : $extension;
        $imageMethod = 'image'.$mime;
        $creatorMethod = 'imagecreatefrom'.$mime;

        // if (1 == 1) {
        //     var_dump('createResizedImage');
        //     var_dump($pathToOriginalFile);
        //     var_dump($resizedNameOrPathToFile);
        //     var_dump($resizedWidth);exit;
        // }
        if (!$mime) {
            var_dump('No $mime!!!!');
            var_dump($pathToOriginalFile);
        }

        $resizedNameOrPathParts = explode('/', $resizedNameOrPathToFile);
        /**
         * fileName (to same path)
         * Format: alma
        */
        if (count($resizedNameOrPathParts) == 1) {
            $resizedFullFileName = $resizedNameOrPathToFile.'.'.$extension;
            $resizedFilePath = BasicUtils::explodeAndRemoveElement($fullPathToOriginalFile, '/', 'last').'/';
        }
        /**
         * pathToFile
         * Format: /var/www/Project/dynamic/alma.jpg
        */
        else {
            $resizedFullFileName = $resizedNameOrPathParts[count($resizedNameOrPathParts) - 1];
            $resizedFilePath = BasicUtils::explodeAndRemoveElement($resizedNameOrPathToFile, '/', 'last').'/';
        }

        $image = $creatorMethod($fullPathToOriginalFile);
        $width = imagesx($image);
        $height = imagesy($image);

        if (!$resizedHeight) {
            $resizedHeight = floor($height * ($resizedWidth / $width));
        } else {
            $resizedWidth = floor($width * ($resizedHeight / $height));
        }
        $tempImage = imagecreatetruecolor($resizedWidth, $resizedHeight);
        imagecopyresized($tempImage, $image, 0, 0, 0, 0, $resizedWidth, $resizedHeight, $width, $height);
        $imageMethod($tempImage, $resizedFilePath.$resizedFullFileName);
    }

    public function loadImage($filePath, $imageId, $pathBase = 'projects', $imageIdContainsExt = false)
    {
        if ($filePath && !$imageId && !$pathBase) {
            return new ImageResponse($filePath);
        }
        $fileNames = FileHandler::getAllFileNames($filePath, 'keep', $pathBase);
        // dump($imageId);
        // dump($fileNames);
        foreach ($fileNames as $fileName) {
            $fileId = $imageIdContainsExt ? $fileName : BasicUtils::explodeAndRemoveElement($fileName, '.' ,'last');
            if ($fileId == $imageId) {
                $pathToFile = FileHandler::completePath($filePath.'/'.$fileName, $pathBase);
                // dump('pathToFile: ' . $pathToFile);
                return new ImageResponse($pathToFile);
            }
        }
        // dump($imageId);dump($fileNames);exit;
        // dump($fileNames);exit;
    }

    public function loadPathToImage($pathToFile)
    {
        return new ImageResponse($pathToFile);
    }
}
