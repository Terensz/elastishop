<?php
namespace framework\packages\FrameworkPackage\controller;

use App;
use framework\component\parent\AccessoryController;
use framework\kernel\utility\FileHandler;
use framework\component\parent\ImageResponse;

class PublicAccessoryController extends AccessoryController
{
    /**
    * Route: [name: elastisite_image, paramChain: /elastisite/image/{type}/{fileName}]
    */
    public function elastiSiteImageAction($type, $fileName)
    {
        $this->getContainer()->wireService('ToolPackage/service/ImageService');
        $filePath =  'framework/packages/ElastiSitePackage/view/image/'.$type;

        $pathToFile = FileHandler::completePath($filePath.'/'.$fileName, 'source');
        return new ImageResponse($pathToFile);
    }

    /**
    * Route: [name: logo_image, paramChain: /logo/{fileName}]
    */
    public function logoImageAction($fileName)
    {
        // dump('alma');exit;
        $this->getContainer()->wireService('ToolPackage/service/ImageService');
        $filePath =  'projects/'.App::getWebProject().'/view/image/logo';
        $pathToFile = FileHandler::completePath($filePath.'/'.$fileName, 'projects');

        return new ImageResponse($pathToFile);
    }
}
