<?php
namespace framework\packages\BackgroundPackage\service;

use App;
use framework\component\parent\Service;
// use framework\kernel\utility\BasicUtils;
// use framework\packages\ToolPackage\service\ImageService;
// use framework\packages\BackgroundPackage\entity\FBSBackground;
// use framework\packages\BackgroundPackage\entity\FBSBackgroundImage;
// use framework\packages\BackgroundPackage\entity\FBSPageBackground;
// use framework\packages\BackgroundPackage\repository\FBSBackgroundRepository;
// use framework\packages\BackgroundPackage\repository\FBSBackgroundImageRepository;
// use framework\packages\BackgroundPackage\repository\FBSPageBackgroundRepository;
use framework\kernel\utility\FileHandler;

class BackgroundService extends Service
{
    public function getBasePath()
    {
        return 'projects/'.App::getWebProject().'/background';
    }

    public function getCompleteBasePath()
    {
        return FileHandler::completePath($this->getBasePath(), 'dynamic');
    }
}