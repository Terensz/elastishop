<?php
namespace framework\packages\ToolPackage\controller;

use App;
use framework\component\parent\AccessoryController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\packages\ToolPackage\service\ImageUploader;
use framework\kernel\utility\FileHandler;
use framework\packages\ToolPackage\service\ImageService;
use framework\packages\BackgroundPackage\entity\FBSBackground;
use framework\packages\BackgroundPackage\repository\FBSBackgroundRepository;
use framework\packages\BackgroundPackage\repository\FBSBackgroundImageRepository;

class ImageAccessoryController extends AccessoryController
{
    /**
    * Route: [name: image, paramChain: /image/{fileName}]
    */
    public function showUploadedImageAction($fileName)
    {
        $this->getContainer()->wireService('ToolPackage/service/ImageService');
        $imageService = new ImageService();
        $filePath =  'projects/'.App::getWebProject().'/upload/images';
        // $filePath =  FileHandler::completePath('projects/'.App::getWebProject().'/upload/images', 'dynamic', true);
// dump($filePath);exit;
        return $imageService->loadImage($filePath, $fileName, 'dynamic', true);
    }

    /**
    * Route: [name: background_image_big, paramChain: /image/background/big/{backgroundEngine}/{imageFileName}]
    */
    public function showBackgroundImageBigAction($backgroundEngine, $imageFileName)
    {
        // $backgroundTheme = BasicUtils::explodeAndRemoveElement($imageFileName, '.', 'last');
        // $extension = BasicUtils::explodeAndGetElement($imageFileName, '.', 'last');
        $this->setService('ToolPackage/service/ImageService');
        $imageService = $this->getService('ImageService');
        $extension = $imageService->determineExtension($imageFileName);

        $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackgroundImage');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundImageRepository');
        $imageRepo = new FBSBackgroundImageRepository();
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundRepository');
        $bgRepo = new FBSBackgroundRepository();
        $image = $imageRepo->findOneBy(['conditions' => [['key' => 'fileName', 'value' => $imageFileName]]]);
        if (!$image) {
            return null;
        }
        $theme = $image->getFbsBackgroundTheme();

        $FBSBackground = $bgRepo->findOneBy(['conditions' => [
            ['key' => 'theme', 'value' => $theme], 
            ['key' => 'extension', 'value' => $extension]
        ]]);
        // $engine = $FBSBackground->getEngine();
        if (!($FBSBackground instanceof FBSBackground)) {
            return null;
        }

        // dump($FBSBackground);exit;
        // $filePath = 'background/thumbnail/'.$FBSBackground->getEngine().'/upload/images';
        // $filePath = $FBSBackground->getPathToThumbnail();
        // return $imageService->loadImage($filePath, null, null);
        $this->setService('BackgroundPackage/service/BackgroundService');
        // dump('alma');
        return $imageService->loadImage($this->getService('BackgroundService')->getBasePath().'/image/'.$backgroundEngine, $imageFileName, 'dynamic', true);
    }

    /**
    * Route: [name: background_image_thumbnail, paramChain: /image/background/thumbnail/{thumbnailFileName}]
    */
    public function showBackgroundImageThumbnailAction($thumbnailFileName)
    {
        $this->setService('ToolPackage/service/ImageService');
        $imageService = $this->getService('ImageService');
        $extension = $imageService->determineExtension($thumbnailFileName);
        $backgroundTheme = BasicUtils::explodeAndRemoveElement($thumbnailFileName, '.', 'last');
        // $extension = BasicUtils::explodeAndGetElement($thumbnailFileName, '.', 'last');
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundRepository');
        $bgRepo = new FBSBackgroundRepository();
        $FBSBackground = $bgRepo->findOneBy(['conditions' => [
            ['key' => 'theme', 'value' => $backgroundTheme], 
            ['key' => 'extension', 'value' => $extension]
        ]]);
        // dump($bgRepo);
        // dump($bgRepo->findAll());
        // foreach ($all as $b) {
        //     $tp = $b->getPathToThumbnail();
        //     $tp = str_replace('/var/www/html/elastisite/elastisite_dynamic/', '', $tp);
        //     $b->setPathToThumbnail($tp);
        //     $bgRepo->store($b);
        // }
        // $all = $bgRepo->findAll();
        // dump($all);exit;
        // dump($FBSBackground instanceof FBSBackground);
        // dump($FBSBackground);exit;

        if (!($FBSBackground instanceof FBSBackground)) {
            return null;
        }

        // dump($FBSBackground instanceof FBSBackground);exit;

        $this->getContainer()->wireService('ToolPackage/service/ImageService');
        $imageService = new ImageService();
        // $filePath = 'background/thumbnail/'.$FBSBackground->getEngine().'/upload/images';
        // $filePath = $FBSBackground->getPathToThumbnail();
        // dump($FBSBackground);exit;
        // dump($FBSBackground);exit;

        $this->setService('BackgroundPackage/service/BackgroundService');
        $path = $this->getService('BackgroundService')->getBasePath().'/thumbnail/'.$FBSBackground->getEngine();
        $imageFileName = $FBSBackground->getTheme().'.'.$FBSBackground->getExtension();
        return $imageService->loadImage($path, $imageFileName, 'dynamic', true);

        // $filePath = FileHandler::completePath($filePath, 'dynamic');
        // $image = $imageService->loadImage($filePath, null, null);
        // return $image;
    }

    /**
    * Route: [name: project_image, paramChain: /image/{type}/{imageId}]
    */
    public function showProjectImageAction($type, $imageId)
    {
        $this->getContainer()->wireService('ToolPackage/service/ImageService');
        $imageService = new ImageService();
        $filePath =  'projects/'.App::getWebProject().'/view/image/'.$type;
        return $imageService->loadImage($filePath, $imageId, 'projects');
    }

    // public function findAndShowImage($filePath, $imageId)
    // {
    //     $fileNames = FileHandler::getAllFileNames($filePath, 'keep', 'projects');
    //     // dump($fileNames);exit;
    //     foreach ($fileNames as $fileName) {
    //         $fileId = BasicUtils::explodeAndRemoveElement($fileName, '.' ,'last');
    //         if ($fileId == $imageId) {
    //             $filePath = FileHandler::completePath($filePath, 'projects').'/'.$fileName;
    //             // dump($path);exit;
    //             return new ImageResponse($filePath);
    //         }
    //     }
    // }

    /**
    * Route: [name: upload_image, paramChain: /upload/image]
    */
    public function uploadImageAction()
    {
        // dump('alma');exit;
        $this->wireService('ToolPackage/service/ImageUploader');

        $uploader = new ImageUploader();
        $filePath =  FileHandler::completePath('projects/'.App::getWebProject().'/upload/images', 'dynamic');
        // $filePath =  FileHandler::completePath('upload/images', 'dynamic');
        // dump($filePath);exit;
        $uploader->setFilePath($filePath);
        $uploader->setImgurFormat(true);
        $uploadResult = $uploader->upload();

        return new JsonResponse($uploadResult);
    }
}
