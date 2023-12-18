<?php
namespace framework\packages\DocumentationPackage\controller;

use framework\component\parent\AccessoryController;
use framework\kernel\utility\FileHandler;
use framework\component\parent\ImageResponse;

class DocumentationAccessoryController extends AccessoryController
{
    /**
    * Route: [name: documentation_image, paramChain: /documentation/image/docImage/{fileName}]
    */
    public function documentationImageAction($fileName)
    {
        $this->getContainer()->wireService('ToolPackage/service/ImageService');
        $pathToFile =  'framework/packages/ElastiSitePackage/view/image/docImage/'.$fileName;
        $pathToFile = FileHandler::completePath($pathToFile, 'source');

        return new ImageResponse($pathToFile);
    }
}
