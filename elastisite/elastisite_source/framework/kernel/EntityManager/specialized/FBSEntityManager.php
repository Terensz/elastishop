<?php
namespace framework\kernel\EntityManager\specialized;

use framework\kernel\component\Kernel;
use framework\kernel\base\Reflector;
use framework\kernel\DbManager\manager\QueryBuilder;
use framework\kernel\DbManager\manager\StatementBuilder;
use framework\component\exception\ElastiException;
use framework\kernel\utility\BasicUtils;
use framework\component\interfaces\EntityManagerInterface;

class FBSEntityManager extends Kernel implements EntityManagerInterface
{
	public function createEntityChain()
	{
		
	}

	public function findBy($repo, $filter = null, $limit = false, $queryType = 'result')
    {

	}
}
