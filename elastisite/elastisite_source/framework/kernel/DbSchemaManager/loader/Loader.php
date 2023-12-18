<?php
namespace framework\kernel\DbSchemaManager\loader;

use framework\kernel\component\Kernel;
use framework\kernel\utility\FileHandler;
use framework\kernel\DbSchemaManager\DbSchemaManager;

class Loader extends Kernel
{
    public function __construct()
    {
        $this->getContainer()->wireService('framework/kernel/DbSchemaManager/TableSchema');
        $this->getContainer()->wireService('framework/kernel/DbSchemaManager/FieldSchema');
        $this->getContainer()->wireService('framework/kernel/DbSchemaManager/DbSchemaManager');
        $this->getContainer()->setKernelObject(new DbSchemaManager());
    }
}
