<?php
namespace framework\packages\BackgroundPackage\controller;

use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\component\parent\BackgroundController;
use framework\component\parent\JsonResponse;
use framework\component\parent\ImageResponse;
use framework\packages\BackgroundPackage\entity\FBSBackground;
use framework\packages\BackgroundPackage\repository\FBSBackgroundRepository;
use framework\packages\BackgroundPackage\entity\FBSBackgroundImage;
use framework\packages\BackgroundPackage\repository\FBSBackgroundImageRepository;

class SimpleController extends BackgroundController
{
    /**
    * Route: [name: background_simple, paramChain: /background/Simple/{theme}]
    */
    public function simpleAction($theme)
    {
        if ($theme != 'empty') {
            $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackground');
            $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundRepository');
            $bgRepo = new FBSBackgroundRepository();
            $background = $bgRepo->findOneBy(['conditions' => [['key' => 'theme', 'value' => $theme]]]);
            // dump($bgRepo->findAll());
            $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackgroundImage');
            $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundImageRepository');
            $bgImageRepo = new FBSBackgroundImageRepository();
            $backgroundImage = $bgImageRepo->findOneBy(['conditions' => [['key' => 'fbsBackgroundTheme', 'value' => $background->getTheme()]]]);
        } else {
            $backgroundImage = null;
        }

        $viewPath = 'framework/packages/BackgroundPackage/view/background/Simple/Simple.php';

        $response = [
            'view' => $this->renderBackground($viewPath, [
                'container' => $this->getContainer(),
                'theme' => $theme,
                'backgroundImage' => $backgroundImage
            ]),
            'data' => []
        ];

        return new JsonResponse($response);
    }

    /**
    * Route: [name: background_image_simple, paramChain: /background/image/Simple/{fileName}]
    */
    public function simpleImageAction($fileName)
    {
        $this->setService('BackgroundPackage/service/BackgroundService');
        $basePath = $this->getService('BackgroundService')->getCompleteBasePath();
        $pathToFile = $basePath.'/image/Simple/'.$fileName;
        // dump($pathToFile);exit;
        // $backgroundImageList = FileHandler::getAllFileNames('var/image/backgroundEngine/Simple/'.$theme);
        // if (count($backgroundImageList) == 1) {
        //      $path = '......var/image/backgroundEngine/Simple/'.$theme.'/'.$backgroundImageList[0];
        //      return new ImageResponse($path);
        // }
        return new ImageResponse($pathToFile);
    }
}
