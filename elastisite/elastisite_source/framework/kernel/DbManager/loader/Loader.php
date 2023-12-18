<?php
namespace framework\kernel\DbManager\loader;

use framework\kernel\component\Kernel;
use framework\kernel\DbManager\connection\PDOConnect;
use framework\kernel\DbManager\manager\DbManager;
use framework\kernel\DbManager\connection\DbConnectionFactory;
use framework\kernel\utility\FileHandler;
// use framework\kernel\DbManager\manager\QueryBuilder;
// use framework\kernel\DbManager\manager\StatementBuilder;

class Loader extends Kernel
{
    public function __construct()
    {
        $this->getContainer()->wireService('framework/kernel/DbManager/StatementAnalyzer');
        $this->getContainer()->wireService('framework/kernel/DbManager/entity/DbConnection');
        $this->getContainer()->wireService('framework/kernel/DbManager/connection/PDOConnect');
        $this->getContainer()->wireService('framework/kernel/DbManager/manager/DbManager');
        $this->getContainer()->wireService('framework/kernel/DbManager/connection/DbConnectionFactory');
        // $this->getContainer()->wireService('framework/kernel/DbManager/entity/DbQuery');
        // $this->getContainer()->wireService('framework/kernel/DbManager/manager/QueryBuilder');
        // $this->getContainer()->wireService('framework/kernel/DbManager/manager/StatementBuilder');

        $this->getContainer()->setKernelObject(new PDOConnect());
    	$this->getContainer()->setKernelObject(new DbManager());
    	$this->getContainer()->setKernelObject(new DbConnectionFactory());

        // $qb = $this->getContainer()->getKernelObject('QueryBuilder');
        // $qb->create();
    }
}
