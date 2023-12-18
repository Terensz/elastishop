<?php
namespace framework\packages\BackgroundPackage\repository;

use App;
use framework\component\parent\FileBasedStorageRepository;
use framework\kernel\utility\FileHandler;

class FBSPageBackgroundRepository extends FileBasedStorageRepository
{
    public function __construct()
    {
        $this->setEncryptFile(false);
        $this->setWebsitesFilepath();
        $this->setProperties(array('id', 'routeName', 'fbsBackgroundTheme'));
    }

    public function setWebsitesFilepath()
    {
        $this->setFilePath(rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/projects/'.App::getWebProject().'/file_based_storage/backgrounds/FBSPageBackground.txt');
    }

    public function findInEveryProject()
    {
        $allResult = [];
        $projects = FileHandler::getAllDirNames(rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/projects/');
        foreach ($projects as $project) {
            $pathToFile = rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/projects/'.$project.'/file_based_storage/backgrounds/FBSPageBackground.txt';
            if (is_file($pathToFile)) {
                $this->setFilePath($pathToFile);
                $result = $this->findAll();
                $result = is_array($result) ? $result : [];
                $allResult = array_merge($allResult, $result);
            }
        }
        // dump(rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/projects/');
        // dump($allResult);exit;
        $this->setWebsitesFilepath();
        return $allResult;
    }
}
