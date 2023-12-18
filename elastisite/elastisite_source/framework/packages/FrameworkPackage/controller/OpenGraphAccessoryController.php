<?php
namespace framework\packages\FrameworkPackage\controller;

use framework\component\parent\AccessoryController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\component\parent\ImageResponse;
use framework\packages\ToolPackage\service\Uploader;
use framework\packages\ToolPackage\service\ImageService;

class OpenGraphAccessoryController extends AccessoryController
{
    private function getOpenGraphService()
    {
        $this->setService('FrameworkPackage/service/OpenGraphService');
        return $this->getService('OpenGraphService');
    }

    /**
    * name: openGraph_image, paramChain: /openGraph/image/{fileName}
    */
    public function openGraphImageAction($fileName)
    {
        $pathToFile = $this->getOpenGraphService()->getOpenGraphAbsoluteImageDir().'/'.$fileName;
        if (is_file($pathToFile)) {
            return new ImageResponse($pathToFile);
        }
    }
}
