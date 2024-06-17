<?php
namespace framework\packages\WebshopPackage\loader;

use App;
use framework\component\parent\PackageLoader;
use framework\packages\WebshopPackage\service\WebshopCartService;
use framework\packages\WebshopPackage\service\WebshopService;

class AutoLoader extends PackageLoader
{
    public function __construct()
    {
        App::getContainer()->wireService('WebshopPackage/dataProvider/interfaces/PackInterface');
        App::getContainer()->wireService('WebshopPackage/dataProvider/interfaces/PackItemInterface');
    }
}