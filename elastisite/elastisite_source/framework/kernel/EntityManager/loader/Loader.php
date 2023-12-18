<?php
namespace framework\kernel\EntityManager\loader;

use framework\kernel\component\Kernel;
use framework\kernel\EntityManager\EntityManager;

class Loader extends Kernel
{
    public function __construct()
    {
        $this->getContainer()->wireService('framework/kernel/EntityManager/EntityManager');
        $this->getContainer()->setKernelObject(new EntityManager());
    }
}
