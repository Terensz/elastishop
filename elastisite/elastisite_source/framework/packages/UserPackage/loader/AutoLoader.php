<?php
namespace framework\packages\UserPackage\loader;

use framework\component\parent\PackageLoader;

class AutoLoader extends PackageLoader
{
    const CONFIG = array(
        'dependsFrom' => 'TranslatorPackage'
    );

    public function __construct()
    {
        $this->wireService('UserPackage/entity/FBSUser');
        $this->wireService('UserPackage/entity/User');

        $this->setService('UserPackage/repository/FBSUserRepository');

        $repo = $this->getService('FBSUserRepository');
        // $pathToFile = rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/'.'file_based_storage/users/FBSUsers.txt';
        $repo->setFilePath(rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/projects/_all/file_based_storage/users/FBSUsers.txt');
        // dump($pathToFile);exit;
        // $repo->setFilePath($pathToFile);
        $repo->setEmulateAutoIncrement('id');
        // $repo->setProperties(['id', 'name', 'username', 'password']);
        $repo->setProperties(array('id', 'name', 'username', 'password', 'email', 'mobile', 'permissionGroups', 'status'));
        $repo->setUniqueProperties(['username']);

        $this->setService('UserPackage/service/Permission');

        // $this->setService('UserPackage/service/UserFactory');
    }
}
