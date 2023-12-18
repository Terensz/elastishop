<?php
namespace framework\kernel\ClassManager;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\component\exception\ElastiException;
use framework\kernel\utility\FileHandler;

class PackageAutoLoader extends Kernel
{
    public function __construct()
    {
        $this->getContainer()->getKernelObject('AutoLoaderFactory')->initAutoLoaders('AutoLoader');
    }
}
