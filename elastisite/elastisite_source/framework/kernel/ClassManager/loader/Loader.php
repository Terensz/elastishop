<?php
namespace framework\kernel\ClassManager\loader;

use framework\kernel\component\Kernel;
use framework\kernel\utility\FileHandler;
use framework\kernel\ClassManager\FileMapper;
use framework\kernel\ClassManager\PackageAutoLoader;

class Loader extends Kernel
{
    public function __construct()
    {
        $this->getContainer()->wireService('framework/kernel/ClassManager/FileMapper');
        $this->getContainer()->wireService('framework/kernel/ClassManager/PackageAutoLoader');
        $this->getContainer()->setKernelObject(new FileMapper());
        $this->getContainer()->setKernelObject(new PackageAutoLoader());
    }
}
