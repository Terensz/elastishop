<?php
namespace framework\packages\BackgroundPackage\loader;

use framework\component\parent\PackageLoader;

class AutoLoader extends PackageLoader
{
    const CONFIG = array(
        'dependsFrom' => 'TranslatorPackage'
    );

    public function __construct()
    {
        include('framework/packages/BackgroundPackage/entity/FBSPageBackground.php');
        // $this->wireService('UserPackage/entity/FBSUser');
        // $this->wireService('UserPackage/entity/User');
    }
}
