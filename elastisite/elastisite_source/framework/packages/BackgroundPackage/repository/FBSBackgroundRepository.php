<?php
namespace framework\packages\BackgroundPackage\repository;

use App;
use framework\component\parent\FileBasedStorageRepository;
use framework\kernel\utility\BasicUtils;

class FBSBackgroundRepository extends FileBasedStorageRepository
{
    public function __construct()
    {
        $this->setEncryptFile(false);
        $this->setFilePath(rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/projects/'.App::getWebProject().'/file_based_storage/backgrounds/FBSBackground.txt');
        $this->setProperties(array('id', 'title', 'engine', 'theme'));
    }

    // public function store($alma)
    // {
    //     throw new ElastiException(
    //         $this->wrapExceptionParams(array(
    //         )), 
    //         1800
    //     );
    // }

    public function createTheme($themeAttempt)
    {
        $themeFound = $this->findOneBy(['conditions' => [['key' => 'theme', 'value' => $themeAttempt]]]);
        if ($themeFound) {
            return $this->createTheme(BasicUtils::increaseSequence($themeAttempt));
        } else {
            return $themeAttempt;
        }
    }
}
