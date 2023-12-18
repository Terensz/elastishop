<?php
namespace framework\packages\AppearancePackage\repository;

use App;
use framework\component\parent\FileBasedStorageRepository;

class FBSOpenGraphRepository extends FileBasedStorageRepository
{
    public function __construct()
    {
        $this->getContainer()->wireService('AppearancePackage/entity/FBSOpenGraph');
        $this->setEncryptFile(false);
        $this->setFilePath(rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/projects/'.App::getWebProject().'/file_based_storage/backgrounds/FBSOpenGraph.txt');
        $this->setProperties(array('id', 'title', 'description'));
    }
}