<?php
namespace framework\packages\BackgroundPackage\service;

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

class SlidingStripesService extends Service
{
    // public function removeBackground($background, $backgroundImageRepository)
    // {
    //     $path = FileHandler::completePath('background', 'dynamic');
    //     unlink($path.'/thumbnail/'.$background->getEngine().'/'.$background->getTheme());

    //     $images = $backgroundImageRepository->findBy(['conditions' => [['key' => 'fbsBackgroundTheme', 'value' => $background->getTheme()]]]);
    //     foreach ($images as $image) {
    //         unlink($path.'/image/'.$background->getEngine().'/'.$image->getFileName());
    //     }
    // }
}