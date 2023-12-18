<?php
namespace framework\packages\BackgroundPackage\repository;

use App;
use framework\component\parent\FileBasedStorageRepository;

class FBSBackgroundImageRepository extends FileBasedStorageRepository
{
    public function __construct()
    {
        $this->setEncryptFile(false);
        $this->setFilePath(rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/projects/'.App::getWebProject().'/file_based_storage/backgrounds/FBSBackgroundImage.txt');
        $this->setProperties(array('id', 'fileName', 'fbsBackgroundTheme', 'width', 'height', 'sequence'));
    }

    public function remove($background)
    {
        $this->setService('BackgroundPackage/service/BackgroundService');
        $path = $this->getService('BackgroundService')->getCompleteBasePath();
        // $path = FileHandler::completePath('background', 'dynamic');
        
        $pathToThumb = $path.'/thumbnail/'.$background->getEngine().'/'.$background->getTheme().'.'.$background->getExtension();
        if (is_file($pathToThumb)) {
            unlink($pathToThumb);
        }

        $images = $this->findBy(['conditions' => [['key' => 'fbsBackgroundTheme', 'value' => $background->getTheme()]]]);
        foreach ($images as $image) {
            $pathToImage = $path.'/image/'.$background->getEngine().'/'.$image->getFileName();
            if (is_file($pathToImage)) {
                unlink($pathToImage);
            }
        }

        $this->removeBy(['fbsBackgroundTheme' => $background->getTheme()]);
    }
}
