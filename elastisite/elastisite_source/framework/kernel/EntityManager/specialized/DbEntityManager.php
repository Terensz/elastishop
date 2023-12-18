<?php
namespace framework\kernel\EntityManager\specialized;

use framework\kernel\component\Kernel;
use framework\component\exception\ElastiException;
use framework\kernel\utility\BasicUtils;
use framework\component\interfaces\EntityManagerInterface;
use framework\kernel\EntityManager\entity\EntityCollector;

class DbEntityManager extends Kernel implements EntityManagerInterface
{
    public function findBy($repo, $filter = null, $queryType = 'result', $debug = false)
    {
		// if (get_class($repo) == 'framework\packages\VisitorPackage\repository\VisitRepository') {
		// 	dump(get_class($repo)); dump($filter);exit;
		// }
		// dump($repo); dump($filter);
        if ($debug) {
            dump($this); dump($filter);
        }

		$entityCollector = $this->getEntityManager()->createEntityCollection(
			$repo, 
			$filter, 
			'0', // parentEntityName
			null, // parentEntityKey 
			false, // limit
			'result', // queryType
			false, // forceCollection
			null, // uniqueIdForTesting
			$debug
		);
        // $repo,
        // $mainEntityFilter = null,
        // $parentEntityName = '0',
		// $parentEntityKey = null,
        // $limit = false,
        // $queryType = 'result',
        // $forceCollection = false,
		// $uniqueIdForTesting = null

		if ($debug) {
			dump("====== /debug ======"); exit;
			dump($entityCollector);
		}
		
		// exit;
		// if (!empty($entityCollector->getCollection())) {
		// 	dump($entityCollector->getCollection());
		// 	dump('findby!!!');exit;
		// }
        return $this->getEntityManager()->assembleEntity($entityCollector);
    }

	// public function findProperty($entity, $searchedPropertyName)
	// {
	// 	$propertyNames = $this->getEntityManager()->getPredefinedPropertyNames($entity);
	// 	foreach ($propertyNames as $propertyName) {
	// 		if ($propertyName == $searchedPropertyName) {
	// 			return true;
	// 		}
	// 	}
	// 	return false;
    // }
}
