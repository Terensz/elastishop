<?php
namespace framework\packages\FrameworkPackage\service;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\packages\FrameworkPackage\repository\OpenGraphRepository;
use framework\packages\FrameworkPackage\entity\OpenGraph;
use framework\packages\FrameworkPackage\repository\CustomPageRepository;
use framework\packages\FrameworkPackage\repository\CustomPageOpenGraphRepository;
use framework\packages\FrameworkPackage\entity\CustomPage;

class OpenGraphService extends Service
{
    public function __construct()
    {
        $this->wireService('FrameworkPackage/repository/CustomPageOpenGraphRepository');
        $this->wireService('FrameworkPackage/repository/OpenGraphRepository');
        $this->wireService('FrameworkPackage/entity/OpenGraph');
        $this->wireService('FrameworkPackage/repository/CustomPageRepository');
        $this->wireService('FrameworkPackage/entity/CustomPage');
    }

    private function getRepository()
    {
        $this->setService('FrameworkPackage/repository/OpenGraphRepository');
        return $this->getService('OpenGraphRepository');
    }

    public function removeOpenGraphImageHeaders($openGraphId)
    {
        $this->getRepository()->removeOpenGraphImageHeaders($openGraphId);
    }

    // public function getOpenGraphImageSrc()
    // {
    //     $fileNames = FileHandler::getAllFileNames($this->getOpenGraphAbsoluteImageDir());
    //     // dump($fileNames);
    //     if ($fileNames == array()) {
    //         return null;
    //     }
    //     foreach ($fileNames as $fileName) {
    //         return $this->getOpenGraphAbsoluteImageDir().'/'.$fileName;
    //     }
    //     return null;
    // }

    public function getOpenGraphImageLink(OpenGraph $openGraph)
    {
        $link = '';
        $imageFile = $openGraph->getMainImageFile();
        if ($imageFile && $imageFile->getFile()) {
            $link = $this->getContainer()->getUrl()->getHttpDomain().'/openGraph/image/'.$imageFile->getFile()->getFileName().'.'.$imageFile->getFile()->getExtension();
        }

        return $link;
    }

    public function getOpenGraphAbsoluteImageDir($webProject = null)
    {
        $filePath = FileHandler::completePath('projects/'.($webProject ? $webProject : App::getWebProject()).'/upload/openGraph/image', 'dynamic');
        // $filePath = rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/projects/'.App::getWebProject().'/upload/openGraph/image';
        return $filePath;
    }

    public function getOpenGraphRelativeImageDir($webProject = null)
    {
        $filePath = 'projects/'.($webProject ? $webProject : App::getWebProject()).'/upload/openGraph/image';
        // $filePath = rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/projects/'.App::getWebProject().'/upload/openGraph/image';
        return $filePath;
    }

    private function getCustomPage($routeName)
    {
        $customPage = null;
        // $this->wireService('FrameworkPackage/repository/CustomPageRepository');
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

    private function getCustomPageOpenGraph(CustomPage $customPage)
    {
        $repo = new CustomPageOpenGraphRepository();
        $customPageOpenGraph = $repo->findOneBy(['conditions' => [
            ['key' => 'custom_page_id', 'value' => $customPage->getId()]
        ]]);

        return $customPageOpenGraph;
    }

    public function getOpenGraphObject($routeName)
    {
        $dbm = $this->getContainer()->getKernelObject('DbManager');
        $openGraph = null;
        $customPageOpenGraph = null;

        if ($dbm->getConnection()) {
            $customPage = $this->getCustomPage($routeName);
            if ($customPage) {
                $customPageOpenGraph = $this->getCustomPageOpenGraph($customPage);
                if ($customPageOpenGraph) {
                    $openGraph = $customPageOpenGraph->getOpenGraph();
                }
            }

            // dump($customPageOpenGraph);//exit;
            
            if (!$customPageOpenGraph) {
                $defaultCustomPage = $this->getCustomPage('reserved_default_route');
                if ($defaultCustomPage) {
                    $defaultCustomPageOpenGraph = $this->getCustomPageOpenGraph($defaultCustomPage);
                    if ($defaultCustomPageOpenGraph) {
                        $openGraph = $defaultCustomPageOpenGraph->getOpenGraph();
                    }
                }
            }
            // dump($openGraph);exit;
        }

        if (!$openGraph) {
            $openGraph = $this->getRepository()->createNewEntity();
        }
        // dump($openGraph);//exit;
        return $openGraph;
    }

    public function cleanUpUnusedFiles($exceptCode, $exceptExtension)
    {
        return false;


        
        $dbm = $this->getContainer()->getKernelObject('DbManager');
        if ($dbm->getConnection()) {
            $this->wireService('FrameworkPackage/repository/OpenGraphRepository');
            $repo = new OpenGraphRepository();
    
            $files = FileHandler::getAllFileNames($this->getOpenGraphAbsoluteImageDir());
            foreach ($files as $file) {
                $fileName = BasicUtils::explodeAndGetElement($file, '/', 'last');
                $code = BasicUtils::explodeAndRemoveElement($fileName, '.', 'last');
                $extension = BasicUtils::explodeAndGetElement($fileName, '.', 'last');
                $codeParts = explode('_', $code);
                if ($codeParts[0] == $this->getSession()->get('visitorCode') && ($code != $exceptCode || $extension != $exceptExtension)) {
                    $video = $repo->findOneBy(['conditions' => [['key' => 'code', 'value' => $code], ['key' => 'extension', 'value' => $extension]]]);
                    if (!$video) {
                        $pathToFile = $this->getOpenGraphAbsoluteImageDir().'/'.$fileName;
                        unlink($pathToFile);
                    }
                }
            }
        }
    }
}
