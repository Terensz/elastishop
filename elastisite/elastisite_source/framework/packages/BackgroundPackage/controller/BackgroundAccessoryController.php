<?php
namespace framework\packages\BackgroundPackage\controller;

use framework\kernel\utility\BasicUtils;
use framework\component\parent\AccessoryController;
use framework\packages\ToolPackage\service\ImageService;

class BackgroundAccessoryController extends AccessoryController
{
    /**
    * name: admin_rawBgImage, paramChain: /admin/rawBgImage/{imageId}
    */
    public function adminRawBgImageAction($imageId)
    {
        $this->getContainer()->wireService('ToolPackage/service/ImageService');
        $imageService = new ImageService();
        
        return $imageService->loadImage('temp/rawBgImage', $imageId, 'dynamic');
    }
}