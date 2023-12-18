<?php
namespace framework\packages\BackgroundPackage\service;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\packages\ToolPackage\service\ImageService;
use framework\packages\BackgroundPackage\entity\FBSBackground;
use framework\packages\BackgroundPackage\entity\FBSBackgroundImage;
use framework\packages\BackgroundPackage\entity\FBSPageBackground;
use framework\packages\BackgroundPackage\repository\FBSBackgroundRepository;
use framework\packages\BackgroundPackage\repository\FBSBackgroundImageRepository;
use framework\packages\BackgroundPackage\repository\FBSPageBackgroundRepository;
use framework\kernel\utility\FileHandler;

class BackgroundMaker extends Service
{
    private $rawBgTempDir = 'temp/rawBgImage';
    private $finalPathRoot;
    private $stripeHeight = 160;
    private $stripeGap = 10;
    private $maxStripes = 10;
    private $backgroundTheme;
    private $extension;

    public function __construct()
    {
        $this->wireService('BackgroundPackage/entity/FBSBackground');
        $this->wireService('BackgroundPackage/repository/FBSBackgroundRepository');
        $this->wireService('BackgroundPackage/entity/FBSBackgroundImage');
        $this->wireService('BackgroundPackage/repository/FBSBackgroundImageRepository');
        $this->setService('BackgroundPackage/service/BackgroundService');
        $this->finalPathRoot = $this->getService('BackgroundService')->getCompleteBasePath();
    }

    private function getFinalPath($engine, $isThumbnail = false)
    {
        return $this->finalPathRoot.'/'.($isThumbnail ? 'thumbnail' : 'image').'/'.$engine;
    }

    public function haveCroppedAndSaved($stripeNo = null)
    {
        $this->setService('ToolPackage/service/ImageService');
        $imageService = $this->getService('ImageService');

        if ($stripeNo) {
            $cropParams = [
                'y' => (($stripeNo - 1) * ($this->stripeHeight + $this->stripeGap))
            ];
        } else {
            $cropParams = null;
        }

        $newImageName = $this->createName($stripeNo);

        $cropResult = $imageService->cropAndSaveImage(
            FileHandler::completePath($this->rawBgTempDir, 'dynamic'),
            $this->getRawBgImageName(),
            $this->getFinalPath($stripeNo ? 'SlidingStripes' : 'Simple'),
            $newImageName,
            $cropParams
        );
        // dump($cropResult);exit;
        return $cropResult ? array(
            'name' => $newImageName,
            'height' => $cropResult['height'],
            'width' => $cropResult['width']
        ) : null;
    }

    public function createTheme($title)
    {
        $repo = new FBSBackgroundRepository();
        return $repo->createTheme(BasicUtils::slugify($title));
    }

    public function makeFBSBackground($title, $engine)
    {
        $repo = new FBSBackgroundRepository();
        $FBSBackground = new FBSBackground();
        $FBSBackground->setTitle($title);
        $FBSBackground->setTheme($this->createTheme($title));
        $this->backgroundTheme = $FBSBackground->getTheme();
        $FBSBackground->setEngine($engine);
        $pathToThumbnail = $this->moveThumbnail($engine);
        $FBSBackground->setExtension(BasicUtils::explodeAndGetElement($pathToThumbnail, '.', 'last'));
        // dump($pathToThumbnail);
        // dump($FBSBackground);exit;
        // $FBSBackground->setPathToThumbnail(FileHandler::getShortPath($pathToThumbnail, 'dynamic'));

        $repo->setFilePath(rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/projects/'.App::getWebProject().'/file_based_storage/backgrounds/FBSBackground.txt');

        $id = $repo->store($FBSBackground);
        $FBSBackground->setId($id);
        return $FBSBackground;
    }

    public function makeFBSBackgroundImage($name, $engine, $backgroundTheme, $width, $heigh, $sequence = null)
    {
        $FBSBackgroundImage = new FBSBackgroundImage();
        $FBSBackgroundImage->setFileName($name.'.'.$this->extension);
        $FBSBackgroundImage->setFbsBackgroundTheme($backgroundTheme);
        $FBSBackgroundImage->setWidth($width);
        $FBSBackgroundImage->setHeight($heigh);
        $FBSBackgroundImage->setSequence($sequence);
        $repo = new FBSBackgroundImageRepository();
        $repo->store($FBSBackgroundImage);
        return $FBSBackgroundImage;
    }

    public function createName($stripeNo = null)
    {
        return 'bgimage_'.time().'_'.($stripeNo ? $stripeNo : '0');
    }

    public function makeSimple($title)
    {
        // dump('makeSimple');exit;
        $FBSBackground = $this->makeFBSBackground($title, 'Simple');
        $imageParams = $this->haveCroppedAndSaved();
        if ($imageParams) {
            $this->makeFBSBackgroundImage($imageParams['name'], 'Simple', $FBSBackground->getTheme(),
            $imageParams['width'], $imageParams['height']);
        }
    }

    public function makeSlidingStripes($title)
    {
        $FBSBackground = $this->makeFBSBackground($title, 'SlidingStripes');
        for ($i = 0; $i < $this->maxStripes; $i++) {
            $imageParams = $this->haveCroppedAndSaved($i + 1);
            if ($imageParams) {
                $this->makeFBSBackgroundImage($imageParams['name'], 'SlidingStripes', $FBSBackground->getTheme(),
                $imageParams['width'], $imageParams['height'], $i);
            }
        }
    }

    public function moveThumbnail($engine)
    {
        $this->setService('ToolPackage/service/ImageService');
        $imageService = $this->getService('ImageService');
        $thumbName = $this->getRawBgImageName('thumbnail');
        $extension = $imageService->determineExtension($thumbName, 'BackgroundMaker@moveThumbnail');
        $this->extension = $extension;
        $newPath = $this->finalPathRoot.'/thumbnail/'.$engine.'/'.$this->backgroundTheme.'.'.$extension;
        $tempPath = FileHandler::completePath($this->rawBgTempDir, 'dynamic');
        // dump($tempPath);
        // dump($newPath);
        copy($tempPath.'/'.$thumbName, $newPath);
        unlink($tempPath.'/'.$thumbName);
        // return 'public_folder/background/thumbnail/'.$engine.'/'.$this->backgroundTheme.'.'.$extension;
        return $newPath;
    }

    public function getRawBgImageName($type = 'image')
    {
        $files = FileHandler::getAllFileNames($this->rawBgTempDir, 'keep', 'dynamic');
        $fileName = null;
        // dump($this->rawBgTempDir);
        // dump($files);exit;
        foreach ($files as $fileName) {
            $imageThumbPos = strpos($fileName, $this->getSession()->get('userId').'_image_thumb');
            $imageFullPos = strpos($fileName, $this->getSession()->get('userId').'_image_full');
            if (($type == 'image' && $imageFullPos !== false) || ($type == 'thumbnail' && $imageThumbPos !== false)) {
                return $fileName;
            }
        }
        return null;
    }
}
