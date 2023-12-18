<?php
namespace framework\packages\FrameworkPackage\service;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\packages\FrameworkPackage\repository\OpenGraphRepository;
use framework\packages\FrameworkPackage\repository\CustomPageRepository;
use framework\packages\FrameworkPackage\repository\CustomPageOpenGraphRepository;
use framework\packages\FrameworkPackage\entity\CustomPage;
use framework\packages\BackgroundPackage\entity\FBSPageBackground;
use framework\packages\BackgroundPackage\repository\FBSPageBackgroundRepository;
use framework\packages\BackgroundPackage\entity\FBSBackground;
use framework\packages\BackgroundPackage\repository\FBSBackgroundRepository;

class CustomPageService extends Service
{
    const RESERVED_DEFAULT_ROUTE = 'reserved_default_route';

    public function __construct()
    {
        $this->wireService('FrameworkPackage/repository/CustomPageOpenGraphRepository');
        $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        $this->wireService('FrameworkPackage/repository/OpenGraphRepository');
        $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        $this->wireService('FrameworkPackage/entity/CustomPage');
    }

    /**
     * @todo  
    */
    public function getCustomPageKeywords($routeName)
    {
        
    }

    public function getBackgroundParams($routeName)
    {   
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSPageBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSPageBackgroundRepository');
        $pageBackgroundRepo = new FBSPageBackgroundRepository();
        $pageBackground = $pageBackgroundRepo->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'routeName', 'value' => $routeName]
        ]]);

        if (!$pageBackground) {
            $pageBackground = $pageBackgroundRepo->findOneBy(['conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                ['key' => 'routeName', 'value' => self::RESERVED_DEFAULT_ROUTE]
            ]]);
        }

        if (!$pageBackground) {
            return null;
        }

        $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundRepository');
        $backgroundRepo = new FBSBackgroundRepository();
        $background = $backgroundRepo->findOneBy(['conditions' => [
            ['key' => 'theme', 'value' => $pageBackground->getFbsBackgroundTheme()]
        ]]);
        // dump($pageBackground);
        // dump($background);
        return [
            'color' => $pageBackground->getBackgroundColor(),
            'engine' => $background ? $background->getEngine() : 'Simple',
            'theme' => $background ? $background->getTheme() : 'empty'
        ];
    }

    public function getCustomPageDescription($routeName)
    {
        $customPage = $this->getCustomPage($routeName);
        if (!$customPage || !$customPage->getDescription() || $customPage->getDescription() == '') {
            $customPage = $this->getCustomPage(self::RESERVED_DEFAULT_ROUTE);
        }
        if (!$customPage) {
            return null;
        }
        // dump($customPage);
        return $customPage->getDescription();
    }

    public function getCustomPage($routeName)
    {
        $customPage = null;
        $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        $customPageRepo = new CustomPageRepository();
        $customPages = $customPageRepo->findBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'route_name', 'value' => $routeName]
        ]]);
        if (is_array($customPages) && count($customPages) == 1) {
            $customPage = $customPages[0];
        }

        return $customPage;
    }
}