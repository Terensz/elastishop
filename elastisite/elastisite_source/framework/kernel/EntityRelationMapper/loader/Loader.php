<?php
namespace framework\kernel\EntityRelationMapper\loader;

use framework\kernel\component\Kernel;
use framework\kernel\EntityRelationMapper\EntityRelationMapper;

class Loader extends Kernel
{
    public function __construct()
    {
        $this->getContainer()->wireService('framework/kernel/EntityRelationMapper/EntityRelationMapper');
        $this->getContainer()->setKernelObject(new EntityRelationMapper());
    }
}
