<?php
namespace projects\ASC\controller;

use App;
use framework\component\parent\AccessoryController;
use framework\component\parent\ImageResponse;
use framework\kernel\utility\FileHandler;
use projects\ASC\repository\AscUnitFileRepository;

class AscImageController extends AccessoryController 
{
    /**
    * Route: [name: asc_unitImage, paramChain: /asc/unitImage/{sizeType}/{code}]
    */
    public function ascUnitImageAction($sizeType, $code)
    {
        // dump('alma');exit;
        App::getContainer()->wireService('projects/ASC/repository/AscUnitFileRepository');
        $ascUnitFileRepository = new AscUnitFileRepository();
        $ascUnitFile = $ascUnitFileRepository->findOneBy(['conditions' => [['key' => 'code', 'value' => $code]]]);
        // dump($code);
        // dump($ascUnitFile);exit;
        if ($ascUnitFile) {
            $extension = $ascUnitFile->getExtension();
            $fullFileName = $code . '.' . $extension;
            $filePath =  'projects/'.App::getWebProject().'/upload/userImages/'.$sizeType.'/';
            $pathToFile = FileHandler::completePath($filePath.'/'.$fullFileName, 'dynamic');

            return new ImageResponse($pathToFile);
        }
    }
}