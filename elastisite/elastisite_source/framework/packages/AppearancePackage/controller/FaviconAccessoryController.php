<?php
namespace framework\packages\AppearancePackage\controller;

use App;
use framework\component\parent\AccessoryController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\component\parent\ImageResponse;
use framework\packages\ToolPackage\service\Uploader;
use framework\packages\ToolPackage\service\ImageService;

class FaviconAccessoryController extends AccessoryController
{
    public function getFilePath($relative = false)
    {
        $filePath = 'projects/'.App::getWebProject().'/favicon';
        return $relative ? $filePath : FileHandler::completePath($filePath, 'dynamic');
    }
    
    /**
    * Route: [name: accessory_favicon, paramChain: /accessory/favicon]
    */
    public function faviconAction()
    {
        $this->getContainer()->wireService('ToolPackage/service/ImageService');
        $fileNames = FileHandler::getAllFileNames($this->getFilePath(), 'keep');
        foreach ($fileNames as $fileName) {
            return new ImageResponse($this->getFilePath().'/'.$fileName);
        }
    }

    /**
    * Route: [name: accessory_favicon_random, paramChain: /accessory/favicon/{random}]
    */
    public function faviconRandomAction($random)
    {
        return $this->faviconAction();
        // // dump($random);exit;
        // $this->getContainer()->wireService('ToolPackage/service/ImageService');
        // $fileNames = FileHandler::getAllFileNames($this->getFilePath(), 'keep');
        // foreach ($fileNames as $fileName) {
        //     return new ImageResponse($this->getFilePath().'/'.$fileName);
        // }
    }
}
