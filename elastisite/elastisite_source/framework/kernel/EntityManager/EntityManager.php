<?php
namespace framework\kernel\EntityManager;

use framework\kernel\component\Kernel;
use framework\kernel\base\Reflector;
use framework\kernel\EntityManager\specialized\DbEntityManager;
use framework\kernel\EntityManager\specialized\FBSEntityManager;
// use framework\kernel\EntityManager\entity\EntityPlaceholderCollector;
// use framework\kernel\EntityManager\entity\EntityPlaceholder;
// use framework\kernel\DbManager\manager\QueryBuilder;
// use framework\kernel\DbManager\manager\StatementBuilder;
use framework\kernel\EntityManager\entity\EntityCollector;
use framework\component\exception\ElastiException;
use framework\component\parent\Repository;
use framework\kernel\utility\BasicUtils;

class EntityManager extends Kernel
{
	// public function getERM() : EntityRelationMapper
	// {
	// 	return (object)$this->getContainer()->getKernelObject('EntityRelationMapper');
	// }

	public function getSpecEntityManager($repositoryType)
	{
		if ($repositoryType == 'database') {
			$this->getContainer()->wireService('framework/kernel/EntityManager/specialized/DbEntityManager');
			return new DbEntityManager();
		} elseif ($repositoryType == 'FBS') {
			$this->getContainer()->wireService('framework/kernel/EntityManager/specialized/FBSEntityManager');
			return new FBSEntityManager();
		} else {
			throw new ElastiException('Specialized entity manager ('.$repositoryType.') is missing', ElastiException::ERROR_TYPE_SECRET_PROG);
		}
	}

	public function findBy($repo, $filter = null, $queryType = 'result', $debug = false)
    {		
		$specEntityManager = $this->getSpecEntityManager($repo->getRepositoryType());
		// dump($repo);
		// dump($filter);
		// dump($specEntityManager);
		return $specEntityManager->findBy($repo, $filter, $queryType, $debug);
	}

    public function createOriginChain($entityCollectionElement, $entityCollector)
    {
        $origins = $this->stackOrigins($entityCollectionElement, $entityCollector);
		// dump($origins);
        $originChainArray = array();

        foreach (array_reverse($origins) as $origin) {
            // dump($origin['multiple']);
            if ($origin['multiple']) {
                $originChainArray[] = $origin['entityName'].'_'.$origin['childCounter'];
            }
        }
		// dump($originChainArray);
        return implode('_', $originChainArray);
    }

    public function stackOrigins($entityCollectionElement, $entityCollector, $origins = array())
    {
        // dump($entityCollectionElement);
        $origins[] = array(
            'entityName' => $entityCollectionElement['entityName'],
            'childCounter' => $entityCollectionElement['childCounter'],
            'multiple' => $entityCollectionElement['multiple']
        );
        if ($entityCollectionElement['parentId'] != 0) {
            $origins = $this->stackOrigins(
                $entityCollector->getCollection()[$entityCollector->find($entityCollectionElement['parentId'])], 
                $entityCollector,
                $origins
            );
        }
        return $origins;
    }

    public function assembleEntity(EntityCollector $entityCollector)
    {
		$result = array();
		// if ($entityCollector->getCollection()) {
		// 	dump($entityCollector->getCollection());exit;
		// }
		$rawResult = $this->combineEntityParts($entityCollector->getCollection());
        foreach ($rawResult as $collectionElement) {
            if ($collectionElement['parentId'] == 0) {
				$result[] = $collectionElement['entity'];
            }
		}
		// dump($entityCollector);dump($rawResult);dump($result);
		// if (count($result) == 1) {
		// 	return $result[0];
		// } elseif (count($result) == 0) {
		// 	return null;
		// } else {
		// 	return $result;
		// }
        return $result;
	}

    // public function combineEntityParts_OLD($originalCollection, $collection = null, $round = 0)
    // {
	// 	if (!$collection) {
	// 		$collection = $originalCollection;
	// 	}
	// 	$children = $this->getChildIndexes($collection); # Those elements which are not parents of any other element
	// 	foreach ($children as $childIndex => $child) {
	// 		unset($collection[$childIndex]);
	// 	}
	// 	foreach ($collection as $index => $collectionElement) {
	// 		foreach ($children as $child) {
	// 			if ($child['parentId'] == $collectionElement['collectionId']) {
	// 				$childRefPropertyCode = lcfirst($child['entityName']);
	// 				($collectionElement['entity'])->set($childRefPropertyCode, $child['entity']);
	// 				$originalCollection[$index]['entity'] = $collectionElement['entity'];
	// 			}
	// 		}
	// 	}
	// 	if (count($collection) == 0) {
	// 		return $originalCollection;
	// 	}
	// 	return $this->combineEntityParts($originalCollection, $collection, ($round + 1));
	// }

    public function combineEntityParts($originalCollection, $collection = null, $round = 0)
    {
		if (!$collection) {
			$collection = $originalCollection;
		}

		$children = $this->getChildIndexes($collection); # Those elements which are not parents of any other element
		foreach ($children as $childIndex => $child) {
			unset($collection[$childIndex]);
		}

		foreach ($collection as $index => $collectionElement) {
			foreach ($children as $child) {
				if ($child['parentId'] == $collectionElement['collectionId']) {
					// $id = ($collectionElement['entity'])->get(($collectionElement['entity'])->getIdFieldName());



					/**
					 * Hiba!
					*/
					// $childRefPropertyCode = lcfirst($child['entityName']);

					/**
					 * Ez lesz jo
					*/
					// $childRefPropertyCode = lcfirst($child['entityAlias']);

					/**
					 * A mentesnel nem allitodott be az entityAlias
					*/
					$childRefPropertyCode = $child['entityAlias'] ? lcfirst($child['entityAlias']) : lcfirst($child['entityName']);


					// if ($childRefPropertyCode == 'userAccount') {
					// 	dump($child);
					// 	dump($collectionElement);
					// }

					$childInEntity = ($collectionElement['entity'])->get($childRefPropertyCode);
					if (is_array($childInEntity)) {
						$childId = ($child['entity'])->get(($child['entity'])->getIdFieldName());
						$childAlreadyFound = false;
						foreach ($childInEntity as $childInEntityElement) {
							if ($childInEntityElement->get(($child['entity'])->getIdFieldName()) == $childId) {
								$childAlreadyFound = true;
							}
						}
						if ($childAlreadyFound == false) {
							($collectionElement['entity'])->set($childRefPropertyCode, $child['entity']);
							$originalCollection[$index]['entity'] = $collectionElement['entity'];
						}
					} else {
						($collectionElement['entity'])->set($childRefPropertyCode, $child['entity']);
						$originalCollection[$index]['entity'] = $collectionElement['entity'];
					}
					// if ($originalCollection && $originalCollection[0]['entityName'] == 'Shipment' && $round == 1 && $child['entityName'] == 'ProductImage') {
					// 	dump('====================');
					// 	dump($childId);
					// 	dump($childAlreadyFound);
					// 	dump($collectionElement);
					// 	dump($child);
					// }
				}
			}
		}
		if (count($collection) == 0) {
			return $originalCollection;
		}
		// if ($children) {
		// 	dump($children);exit;
		// }
		return $this->combineEntityParts($originalCollection, $collection, ($round + 1));
	}

    public function getChildIndexes($collection)
    {
		// dump($collection);// exit;
		$result = array();
		foreach ($collection as $index => $collectionElement) {
			// dump($collectionElement);//exit;
			if ($this->isChild($collection, $collectionElement['collectionId'])) {
				$result[$index] = $collectionElement;
			}
		}
		return $result;
	}

    public function isChild($collection, $id)
    {
		foreach ($collection as $collectionElement) {
			if ($collectionElement['parentId'] == $id) {
				return false;
			}
		}
		return true;
	}

    public function getGrandparents($collection, $entityCollector)
    {
		// dump($collection);//exit;
		$result = array();
		foreach ($collection as $index => $collectionElement) {
			if ($this->isGrandparent($collection, $collectionElement['collectionId'], $entityCollector)) {
				$result[$index] = $collectionElement;
			}
		}
		return $result;
	}

    public function isGrandparent($collection, $id, $entityCollector)
    {
		// if (!$collection[$id]['parentId']) {
		// 	return true;
		// }
		foreach ($collection as $collectionElement) {
			if ($collectionElement['collectionId'] == $collection[$entityCollector->find($id, $collection)]['parentId']) {
				return false;
			}
		}
		return true;
	}

    public function disassembleEntity($entity)
    {
		$this->getContainer()->wireService('framework/kernel/EntityManager/entity/EntityCollector');
		// dump($entity);
		// $entityCollector = new EntityCollector();
		$entityCollector = $this->collapseToCollection($entity);
		// dump($entityCollector);
		// dump($entityCollector->getCollection());exit;
		// dump('');exit;
		// add($id, $entityKey, $parentId, $entity)
		return $entityCollector;
	}

    // public function getRelation($entityAttributes, $className)
    // {
	// 	$relations = $this->getRelations($entityAttributes);
	// 	// dump($relations);exit;
	// 	return isset($relations[$className]) ? $relations[$className] : null;
	// }

    public function collapseToCollection($entity, $entityAlias = null, EntityCollector $entityCollector = null, $parentId = null, $parentName = null, $entityNames = array(), $processedEntities = array(), $counter = 0)
    {
		$counter++;
		$finalRound = $this->findInProcessedEntities($processedEntities, $entity);

		// if ($finalRound) {
		// 	dump($entity);
		// }
		// if ($counter >= 10) {
		// 	return $entityCollector;
		// }

		// dump($entity);

		if (!$entityCollector) {
			$entityCollector = new EntityCollector();
		}
		$collection = $entityCollector->getCollection();
		$parent = $parentId ? $collection[$entityCollector->find($parentId)] : null;
		$entityName = BasicUtils::explodeAndGetElement(get_class($entity), '\\', 'last');
		$childEntityNames = $entityNames;
		$childEntityNames[] = $entityName;

		if (!$entityAlias) {
			$entityAlias = $entityName;
		}

		// $refObj = new \ReflectionObject($entity);
		// $incompleteClassName = $refObj->getProperty('__PHP_Incomplete_Class_Name');
		// return false;

		// if (get_class($entity) == '__PHP_Incomplete_Class') {
		// 	var_dump(unserialize($entity));exit;
		// 	return false;
		// }

		// if ($incompleteClassName) {
		// 	dump($incompleteClassName);exit;
		// }

		// dump($collection);

		// $entityAlias = null;

		$collectionId = $entityCollector->addElement(
			$entity->getIdValue(), // id
			($parent ? $parent['entityAlias'] : '0').'-'.$entityName, // entityKey
			$parent ? $parent['collectionId'] : null, // parentId
			$parent ? $parent['entityKey'] : null, // parentEntityKey
			$entity, // entity
			$entityAlias
		);

		// dump($entity);
		// dump($finalRound);

		$newProcessedEntity = array(
			'class' => get_class($entity),
			'object' => $entity
		);

		$processedEntities[] = $newProcessedEntity;

		// dump($entityCollector);
		// $propertyDetailCollection = $this->getEntityPropertyDetails($entity->getRepository());
		$entityAttributes = $entity->getEntityAttributes();
		// $relations = $this->getProcessedRelations($entityAttributes);
		// dump($relations);

		// if ($entityAttributes['relations'] && $finalRound == false) {
		if ($entityAttributes['relations'] && $finalRound == false) {
			foreach ($entityAttributes['relations'] as $entityAlias => $relation) {
				// dump($relation);
				$childEntityStack = $entity->get($relation['propertyCode']);
				if ($childEntityStack) {
					if (!is_array($childEntityStack)) {
						$childEntityStack = array($childEntityStack);
					}
					foreach ($childEntityStack as $childEntity) {
						if (!is_object($childEntity)) {

						}
						// $childEntityName = BasicUtils::explodeAndGetElement(get_class($childEntity), '\\', 'last');

						// if (!in_array($childEntityName, $entityNames) || (in_array($childEntityName, $entityNames) && $childEntityName == $entityName)) {
						// 	$entityCollector = $this->collapseToCollection($childEntity, $entityCollector, $collectionId, $entityName, $childEntityNames);
						// }

						// if ($this->findInProcessedEntities($processedEntities, $childEntity)) {
						// 	$finalRound = true;
						// }
						$entityCollector = $this->collapseToCollection($childEntity, $entityAlias, $entityCollector, $collectionId, $entityName, $childEntityNames, $processedEntities, $counter++);
					}
				}
			}
		}
		$entityCollector->modifyCollectionElement($collectionId, 'entity', $this->removeSubentitiesFromEntity($entity));
		// $entityCollector->sortElements();
		return $entityCollector;
	}

    public function findInProcessedEntities($objects, $newObject)
    {
		// dump('-------------------------------');
		// dump($objects);
		// dump($newObject);
		foreach ($objects as $object) {
			if (!is_object($newObject)) {
				dump($newObject);
			}
			if ($object['class'] == get_class($newObject) && $object['object'] == $newObject) {
				return true;
			}
		}
		return false;
	}

    public function store($entity)
    {
		// dump($entity);
		$pdo = $this->getContainer()->getKernelObject('DbManager')->getConnection();
		if (!$pdo) {
			return false;
		}
		if ($pdo->inTransaction()) {
			$pdo->rollback();
		}
		// $pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0);
		// dump($pdo->inTransaction());
		$pdo->beginTransaction();
		// dump('begin');
		// dump($pdo->inTransaction());
		// dump($entity);
		try {
			$storedCollection = null;
			if ($entity->getIdValue()) {
				$storedEntity = $entity->getRepository()->findOneBy(['conditions' => [
					['key' => $entity->getIdFieldName(), 'value' => $entity->getId()]
				]]);
				$storedEntityCollector = $this->disassembleEntity($storedEntity);
				$storedCollection = $storedEntityCollector->getCollection();
			}
			$entityCollector = $this->disassembleEntity($entity);
			// $entityCollector1 = clone $entityCollector;
			$entityCollector = $this->removeBlankEntityElements($entityCollector);
			// $entityCollector2 = clone $entityCollector;
			$newCollection = $this->storeCollectionElements($entity->getRepository(), $entityCollector->getCollection(), null, 0, $entityCollector);

			// dump($entity);exit;
			/**
			 * @todo: ElastiSite does not handle this feature well.
			*/
			if ($entity->getRepository()->cleanUpOrphans()) {

				dump($entity);
				// dump($entityCollector1);
				// dump($entityCollector2);
				dump($entityCollector);
				dump($newCollection);
				exit;


				$newCollection = $this->deleteOrphans($storedCollection, $newCollection);
			}
			$newEntityCollector = new EntityCollector();
			$newEntityCollector->setCollection($newCollection);
			$newEntity = $this->assembleEntity($newEntityCollector)[0];
			$pdo->commit();
			// $pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 1);
			// $pdo->endTransaction();
			// dump('commit!');
			// dump($pdo->inTransaction());
			return $newEntity;
		} catch(\Exception $e) {
			// dump($e);exit;
			$pdo->rollback();
			throw $e;
		}
	}

	public function removeBlankEntityElements($entityCollector)
	{
		$counter = 0;
		foreach ($entityCollector->getCollection() as $collectionElement) {
			$class = get_class($collectionElement['entity']);
			$nullEntity = new $class();
			if ($collectionElement['entity'] == $nullEntity && $counter > 0) {
				// dump($collectionElement);
				$entityCollector->remove($collectionElement['collectionId']);
			}
			$counter++;
		}
		return $entityCollector;
	}

	public function deleteOrphans($storedCollection, $newCollection)
	{
		// dump($storedCollection);exit;
		if (!$storedCollection || !$newCollection) {
			return false;
		}
		$resultCollection = $newCollection;
		foreach ($storedCollection as $storedCollectionElement) {
			$storedIdFound = false;
			foreach ($newCollection as $newCollectionElement) {
				if ($storedCollectionElement['id'] == $newCollectionElement['id']) {
					$storedIdFound = true;
				}
			}
			if (!$storedIdFound) {
				($storedCollectionElement['entity'])->getRepository()->remove($storedCollectionElement['id']);
				$resultCollection = $this->removeCollectionElement($resultCollection, $storedCollectionElement['id']);
			}
		}
		return $resultCollection;
	}

	public function removeCollectionElement($collection, $id)
	{
		$resultCollection = array();
		foreach ($collection as $collectionElement) {
			if ($collectionElement['id'] != $id) {
				$resultCollection[] = $collectionElement;
			}
		}
		return $resultCollection;
	}

	// public function storeCollectionElements($repository, $originalCollection, $collection = null, $round = 0, $entityCollector = null, $alreadyStored = array())
    // {
	// 	// dump('-----------------------------------------------------');
	// 	// dump('Round: '.$round);
	// 	if (!$collection) {
	// 		$collection = $originalCollection;
	// 	}
	// 	$grandParents = $this->getGrandparents($collection, $entityCollector);
    //     foreach ($grandParents as $index => $collectionElement) {
    //         $entityAttributes = ($collectionElement['entity'])->getEntityAttributes();
    //         $parentRelation = null;
    //         if ($collectionElement['parentId']) {
	// 			$originalIndex = $entityCollector->find($collectionElement['parentId'], $originalCollection);
	// 			$parent = $originalCollection[$originalIndex];
	// 			if (isset($entityAttributes['relations'][$parent['entityName']])) {
	// 				$parentRelation = $entityAttributes['relations'][$parent['entityName']];
	// 			}
    //         }
	// 		$fieldValues = array();
    //         foreach ($entityAttributes['propertyMap'] as $propertyCode => $propertyAttributes) {
    //             if (!$propertyAttributes['technical']) {
    //                 if ($propertyAttributes['isObject']) {
    //                     if (isset($parent) && $propertyAttributes['targetRelation']['targetClassName'] == $parent['entityName']) {
	// 						if (in_array($parentRelation['referenceContainerTable'], array('this'))) {
	// 							$parentId = ($parent['entity'])->getIdValue();
	// 							$fieldValues[$parentRelation['associationReferencedField']] = $parentId;
	// 						}
    //                     } else {
	// 						$childRelation = $entityAttributes['relations'][$propertyAttributes['targetRelation']['targetClassName']];
	// 						if (in_array($childRelation['referenceContainerTable'], array('this', 'selfReferenced'))) {
	// 							$childEntityName = BasicUtils::explodeAndGetElement($propertyAttributes['targetRelation']['targetClassName'], '/', 'last');
	// 							foreach ($originalCollection as $originalCollectionElement) {
	// 								if ($originalCollectionElement['entityName'] == $childEntityName
	// 								&& $originalCollectionElement['parentId'] == $collectionElement['collectionId']) {
	// 									$childId = ($originalCollectionElement['entity'])->getIdValue();
	// 									$fieldValues[$childRelation['associationReferencedField']] = $childId;
	// 									break;
	// 								} else {
	// 									$fieldValues[$childRelation['associationReferencedField']] = null;
	// 								}
	// 							}
	// 						}
    //                     }
    //                     # Else-branch would be: 1: zero parent (parent of main entity), 2: childs.
    //                 } else {
	// 					# Non-object properties
	// 					$value = ($collectionElement['entity'])->get($propertyCode);
	// 					$fieldValues[$propertyCode] = $propertyAttributes['toBeEncryted'] && $value && $value != '' 
	// 						? $this->encrypt($value) : $value;
    //                 }
    //             }
	// 		}
	// 		# Itt kell elmenteni
	// 		$id = ($collectionElement['entity'])->getIdValue();
	// 		// dump($collectionElement['entity']);
	// 		if (!$id || ($id && $this->notStoredYet($alreadyStored, $collectionElement['entityName'], $id))) {
	// 			$repo = ($collectionElement['entity'])->getRepository();

	// 			if (($collectionElement['entity'])->getIdValue()) {
	// 				$id = $repo->updateRecord($fieldValues, ($collectionElement['entity'])->getIdFieldName());
	// 			} else {
	// 				// if ((int)$collectionElement['parentId'] > 0 && !isset($grandParents[$collectionElement['parentId']])) {
	// 				// 	// throw new ElastiException(
	// 				// 	// 	$this->wrapExceptionParams(array()), 
	// 				// 	// 	1672
	// 				// 	// );
	// 				// 	// dump($originalCollection);exit;
	// 				// 	dump('=================Error===========');
	// 				// 	dump($collectionElement);
	// 				// 	dump($grandParents);
	// 				// 	dump($entityCollector);
	// 				// 	dump($this->assembleEntity($entityCollector));exit;
	// 				// }
	// 				if ((int)$collectionElement['parentId'] > 0) {
	// 					$parenCollectionIndex = $entityCollector->find((int)$collectionElement['parentId']);
	// 					$parentCollectionElement = $entityCollector->getCollection()[$parenCollectionIndex];
	// 					$parentEntity = $parentCollectionElement['entity'];
	// 				} else {
	// 					$parentEntity = null;
	// 				}
	// 				$id = $repo->insertRecord(
	// 					$fieldValues, 
	// 					// ((int)$collectionElement['parentId'] > 0 ? $grandParents[$collectionElement['parentId']]['entity'] : null)
	// 					$parentEntity
	// 				);
	// 				$originalCollection = $this->setIdToCollectionElement($originalCollection, $collectionElement['entity'], $id);
	// 				$collection = $this->setIdToCollectionElement($collection, $collectionElement['entity'], $id);
	// 			}

	// 			$alreadyStored[] = array(
	// 				'entityName' => $collectionElement['entityName'],
	// 				'id' => $id
	// 			);
	// 		} else {
	// 			unset($collection[$index]);
	// 			continue;
	// 		}

	// 		// if (isset($originalIndex) && !($collectionElement['entity'])->getIdValue()) {
	// 		// 	$originalCollection = $this->setIdToCollectionElement($originalCollection, $originalCollection[$originalIndex]['entity'], $id);
	// 		// 	// $originalCollection[$originalIndex]['id'] = $id;
	// 		// 	// ($originalCollection[$originalIndex]['entity'])->setIdValue($id);
	// 		// }
    //         unset($collection[$index]);
    //     }

	// 	if (count($collection) == 0) {
	// 		return $originalCollection;
	// 	}
	// 	return $this->storeCollectionElements($repository, $originalCollection, $collection, ($round + 1), $entityCollector, $alreadyStored);
    // }

    public function storeCollectionElements($repository, $originalCollection, $collection = null, $round = 0, $entityCollector = null, $alreadyStored = array())
    {
		// dump('-----------------------------------------------------');
		// dump('Round: '.$round);
		if (!$collection) {
			$collection = $originalCollection;
		}
		$grandParents = $this->getGrandparents($collection, $entityCollector);
        foreach ($grandParents as $index => $collectionElement) {
            $entityAttributes = ($collectionElement['entity'])->getEntityAttributes();
            $parentRelation = null;
            if ($collectionElement['parentId']) {
				$originalIndex = $entityCollector->find($collectionElement['parentId'], $originalCollection);
				$parent = $originalCollection[$originalIndex];
				if (isset($entityAttributes['relations'][$parent['entityAlias']])) {
					// if (!isset($entityAttributes['relations'][$parent['entityName']])) {
					// 	dump($entityAttributes['relations']);
					// 	exit;
					// }
					$parentRelation = $entityAttributes['relations'][$parent['entityAlias']];
				}
            }
			$fieldValues = array();
			// dump($entityAttributes['propertyMap']);

			/**
			 * Debug!
			*/
			$debug = false;
			$debugLoop = false;
			$debugPropertyCode = 'createdBy';

            foreach ($entityAttributes['propertyMap'] as $propertyCode => $propertyAttributes) {

				/**
				 * Debug!
				*/
				$debugLoop = false;
				########################################################
				# Uncomment this below to set the property to debug!!!!
				########################################################
				// if ($propertyCode == $debugPropertyCode) {
				// 	$debug = true;
				// 	$debugLoop = true;
				// }

                if (!$propertyAttributes['technical']) {
					if ($debugLoop) {
						dump($debugPropertyCode.': not technical');
					}
                    if ($propertyAttributes['isObject']) {
						if ($debugLoop) {
							dump($debugPropertyCode.': isObject');
							// dump($parent);
						}
                        if (isset($parent) && $propertyAttributes['targetRelation']['targetEntityAlias'] == $parent['entityAlias']) {


							if ($debugLoop) {
								dump($debugPropertyCode.': has parent');
							}

							if (isset($parentRelation['referenceContainerTable']) && in_array($parentRelation['referenceContainerTable'], array('this'))) {
								$parentId = ($parent['entity'])->getIdValue();
								$fieldValues[$parentRelation['associationReferencedField']] = $parentId;
							}
                        } else {
							if ($debugLoop) {
								dump($debugPropertyCode.': has no parent');
							}

							$childRelation = $entityAttributes['relations'][$propertyAttributes['targetRelation']['targetEntityAlias']];
							if ($debugLoop) {
								dump($debugPropertyCode.': childRelation: ');
								dump($childRelation);
							}

							if (in_array($childRelation['referenceContainerTable'], array('this', 'selfReferenced'))) {

								/**
								 * Debug: childRelation contains reference to parent, so we will create a fieldValue for the referenced table here.
								*/
								if ($debugLoop) {
									dump($debugPropertyCode.': childRelation contains reference (this or selfReferenced) ');
									dump($childRelation);
								}

								// $foundChildId = null;
								// $childEntityName = BasicUtils::explodeAndGetElement($propertyAttributes['targetRelation']['targetEntityAlias'], '/', 'last');
								// foreach ($originalCollection as $originalCollectionElement) {
								// 	if ($originalCollectionElement['entityName'] == $childEntityName
								// 	&& $originalCollectionElement['parentId'] == $collectionElement['collectionId']) {
								// 		$foundChildId = ($originalCollectionElement['entity'])->getIdValue();

								// 		/**
								// 		 * Child id found
								// 		*/
								// 		if ($debugLoop) {
								// 			dump($debugPropertyCode.': Child id found: '.$foundChildId);
								// 		}
								// 	}
								// }



								/**
								 * Debug
								*/
								$foundChildId = null;

								$childEntityAlias = BasicUtils::explodeAndGetElement($propertyAttributes['targetRelation']['targetEntityAlias'], '/', 'last');

								/**
								 * Debug
								*/
								if ($debugLoop) {
									dump($debugPropertyCode.': childEntityAlias: '.$childEntityAlias);
								}

								foreach ($originalCollection as $originalCollectionElement) {
									if ($originalCollectionElement['entityAlias'] == $childEntityAlias
									&& $originalCollectionElement['parentId'] == $collectionElement['collectionId']) {
										$childId = ($originalCollectionElement['entity'])->getIdValue();
										$fieldValues[$childRelation['associationReferencedField']] = $childId;

										/**
										 * Debug
										*/
										$foundChildId = $childId;
										break;
									} else {
										$fieldValues[$childRelation['associationReferencedField']] = null;
									}
								}

								/**
								 * Debug: Child id NOT found
								*/
								if ($debugLoop && !$foundChildId) {
									dump($debugPropertyCode.': Child id NOT found ');
								}

								// $fieldValues[$childRelation['associationReferencedField']] = $fieldValues;
							}
                        }
                        # Else-branch would be: 1: zero parent (parent of main entity), 2: childs.
                    } else {
						# Non-object properties
						$value = ($collectionElement['entity'])->get($propertyCode);
						$fieldValues[$propertyCode] = $propertyAttributes['toBeEncryted'] && $value && $value != '' 
							? $this->encrypt($value) : $value;
                    }
                }
			}

			/**
			 * Debug: @var array $fieldValues: this array contains the association of keys to values which will be saved into the database.
			*/
			if ($debug) {
				dump($fieldValues);
			}

			# Itt kell elmenteni
			$id = ($collectionElement['entity'])->getIdValue();
			// dump($collectionElement['entity']);
			if (!$id || ($id && $this->notStoredYet($alreadyStored, $collectionElement['entityName'], $id))) {
				$repo = ($collectionElement['entity'])->getRepository();

				if (($collectionElement['entity'])->getIdValue()) {
					$id = $repo->updateRecord($fieldValues, ($collectionElement['entity'])->getIdFieldName());
				} else {
					// if ((int)$collectionElement['parentId'] > 0 && !isset($grandParents[$collectionElement['parentId']])) {
					// 	// throw new ElastiException(
					// 	// 	$this->wrapExceptionParams(array()), 
					// 	// 	1672
					// 	// );
					// 	// dump($originalCollection);exit;
					// 	dump('=================Error===========');
					// 	dump($collectionElement);
					// 	dump($grandParents);
					// 	dump($entityCollector);
					// 	dump($this->assembleEntity($entityCollector));exit;
					// }
					if ((int)$collectionElement['parentId'] > 0) {
						$parenCollectionIndex = $entityCollector->find((int)$collectionElement['parentId']);
						$parentCollectionElement = $entityCollector->getCollection()[$parenCollectionIndex];
						$parentEntity = $parentCollectionElement['entity'];
					} else {
						$parentEntity = null;
					}
					$id = $repo->insertRecord(
						$fieldValues, 
						// ((int)$collectionElement['parentId'] > 0 ? $grandParents[$collectionElement['parentId']]['entity'] : null)
						$parentEntity
					);
					$originalCollection = $this->setIdToCollectionElement($originalCollection, $collectionElement['entity'], $id);
					$collection = $this->setIdToCollectionElement($collection, $collectionElement['entity'], $id);
				}

				$alreadyStored[] = array(
					'entityName' => $collectionElement['entityName'],
					'id' => $id
				);
			} else {
				unset($collection[$index]);
				continue;
			}

			// if (isset($originalIndex) && !($collectionElement['entity'])->getIdValue()) {
			// 	$originalCollection = $this->setIdToCollectionElement($originalCollection, $originalCollection[$originalIndex]['entity'], $id);
			// 	// $originalCollection[$originalIndex]['id'] = $id;
			// 	// ($originalCollection[$originalIndex]['entity'])->setIdValue($id);
			// }
            unset($collection[$index]);
        }

		if (count($collection) == 0) {
			return $originalCollection;
		}
		return $this->storeCollectionElements($repository, $originalCollection, $collection, ($round + 1), $entityCollector, $alreadyStored);
    }

    public function setIdToCollectionElement($collection, $entity, $id)
    {
		$newEntity = clone $entity;
		$newEntity->setIdValue($id);
		$newCollection = array();
		foreach ($collection as $collectionElement) {
			if ($collectionElement['entity'] == $entity) {
				$collectionElement['id'] = $id;
				$collectionElement['entity'] = $newEntity;
			}
			$newCollection[] = $collectionElement;
		}
		return $newCollection;
	}

    public function notStoredYet($alreadyStored, $entityName, $id)
    {
		// dump($entityName.'-'.$id);
		foreach ($alreadyStored as $alreadyStoredLoop) {
			if ($alreadyStoredLoop['entityName'] == $entityName && $alreadyStoredLoop['id'] == $id) {
				return false;
			}
		}
		return true;
	}

    public function removeSubentitiesFromEntity($entity)
    {
		$entityCopy = $entity->getRepository()->createNewEntity();
        $entityAttributes = $entity->getEntityAttributes();
        foreach ($entityAttributes['propertyMap'] as $propertyCode => $propertyAttributes) {
            if (!$propertyAttributes['isObject']) {
				$entityCopy->set($propertyCode, $entity->get($propertyCode));
            }
		}
        return $entityCopy;
    }

	public function getRelationPropertyNames($relations)
	{
		$propertyNames = array();
		foreach ($relations as $relation) {
			$propertyNames[] = $relation['propertyName'];
		}
		return $propertyNames;
	}

	public function getEntityNamespace(string $entityPath)
	{
		return (string)$this->getContainer()->getServiceLinkParams($entityPath)['objectNamespace'];
	}

	public function getEntityPathFromConfig($entityConfig, $entityName)
	{
		if (isset($entityConfig[$entityName]['entityPath'])) {
			return (string)$entityConfig[$entityName]['entityPath'];
		}
		else {
			throw new ElastiException('Missing entitypath for '.$entityName, ElastiException::ERROR_TYPE_SECRET_PROG);
		}
	}

	public function getPredefinedPropertyNames($entity)
	{
		$className = BasicUtils::explodeAndGetElement(get_class($entity), '\\', 'last');
        $cachedPropertyNames = $this->getContainer()->getFromCache('propertyNames', $className);
        if (!$cachedPropertyNames) {
			$reflector = new Reflector();
			$propertyNames = $reflector->getPredefinedPropertyNames($entity);
            $this->getContainer()->addToCache('propertyNames', $className, $propertyNames);
        } else {
            $propertyNames = $cachedPropertyNames;
        }
		return $propertyNames;
	}

	/**
	 * @todo: az if ($repo->getRepositoryType() == 'FBS') elagazas helyett meg kell csinalni a FileBasedStorageRepository-ban
	 * is a collectRecordData()-t. Egyelore nem akartam tul sokat kezdeni a FBS entity-kkel, vagyis nem akartam, hogy
	 * szetszedhetok legyenek, de ebben kesobb nagy lehetoseg lesz.
	 * 
	 * @todo #2: meg kell csinalni ide az exception-t, ha nem talalod meg az entity-t, akkor ne csinaljon belole collection-t.
	*/
    public function createEntityCollection(
        $repo,
        $mainEntityFilter = null,
        $parentEntityName = '0',
		$parentEntityKey = null,
        $limit = false,
        $queryType = 'result',
        $forceCollection = false,
		$uniqueIdForTesting = null,
		$debug = false
    )
    {	
		// if (get_class($repo) == 'framework\packages\VisitorPackage\repository\VisitRepository') {
		// 	dump(get_class($repo)); dump($mainEntityFilter);exit;
		// }
		//exit;
        $this->getContainer()->wireService('framework/kernel/EntityManager/entity/EntityCollector');
		$entityCollector = new EntityCollector();

		if ($debug === true) {
			// dump('Debug!!!');exit;
			dump($debug);
			dump($uniqueIdForTesting);
			dump($mainEntityFilter);
			dump($repo);
			dump($repo->getRepositoryType()); exit;
		}
		
		if ($repo->getRepositoryType() == 'FBS') {
			$entity = $repo->findOneBy($mainEntityFilter);
			$id = null;
			// dump($mainEntityFilter);
			foreach ($mainEntityFilter['conditions'] as $mainEntityFilterRow) {
				// dump($mainEntityFilterRow);
				if ($mainEntityFilterRow['key'] == 'id') {
					$id = $mainEntityFilterRow['value'];
				}
			}
			if (!is_object($entity)) {
				$entity = $repo->createNewEntity();
			}
			$entityAlias = null;
			$entityCollector->addElement($id, '0-'.$repo->getEntityName(), 0, null, $entity, $entityAlias);
		} elseif ($repo->getRepositoryType() == 'database') {
			$entityCollector = $this->collectEntities(
				$entityCollector,
				$repo,
				$mainEntityFilter,
				$parentEntityName,
				$limit,
				$queryType,
				null, // initialEntityName - this will be automatically set in the method, once, at start.
				null, // entityAlias - this will be set in every loop. Assumint the fisrt loop is not an alias (can't be)
				0, // parentEntityCollectionId
				null, // parentEntityKey
				$forceCollection,
				1,
				array(),
				false,
				$uniqueIdForTesting,
				$debug
			);
			// dump($entityCollector);
		} elseif ($repo->getRepositoryType() == 'technical') {
			$id = null;
			$sampleEntity = $repo->createNewEntity();
			$class = get_class($sampleEntity);
			$className = BasicUtils::explodeAndGetElement($class, '\\', 'last');
			if ($mainEntityFilter && isset($mainEntityFilter['conditions']) && isset($mainEntityFilter['conditions'][0]) && isset($mainEntityFilter['conditions'][0]['key']) && $mainEntityFilter['conditions'][0]['key'] == 'id') {
				$id = $mainEntityFilter['conditions'][0]['value'];
			}

			// dump($mainEntityFilter);
			// dump($id);
			// dump(func_get_args());

			$entity = $repo->find($id);
			if (!$entity) {
				$id = null;
				$entity = $sampleEntity;
			}
			// dump($id);
			// dump(func_get_args());exit;
			$entityCollector->addElement(
				$id, // $id,
				'0-'.$className, // $entityKey,
				0, // $parentId,
				null, // $parentEntityKey,
				$entity, // $entity,
				$className, // $entityAlias,
				false // $isDummyEntity,
			);

			// dump($entityCollector);exit;

			// $entityCollector->addElement(
			// 	$id, 
			// 	string $entityKey, 
			// 	$parentId, 
			// 	$parentEntityKey, 
			// 	$entity,
			// 	$entityAlias,
			// 	$isDummyEntity = false, 
			// 	$test = false
			// );
		}

        return $entityCollector;
    }

    public function collectEntities(
        EntityCollector $entityCollector,
        Repository $repository,
        array $filter = null,
        string $parentEntityName = '0',
        bool $limit = false,
        string $queryType = 'result',
		string $initialEntityName = null,
		string $entityAlias = null,
        int $parentEntityCollectionId = 0,
		string $parentEntityKey = null,
        bool $forceCollection = false,
        int $round = 1,
        $entitiesInThisChain = array(),
		$isDummyEntity = false,
		$uniqueIdForTesting = null,
		$debug = false
    )
    {
		if ($debug) {
			dump('====== EntityManager->collectEntities() debug ======');// exit;
			dump('Filter:');
			dump($filter);
		}
		// if (get_class($repository) == 'framework\packages\VisitorPackage\repository\VisitRepository') {
		// 	dump(get_class($repository)); dump($filter);exit;
		// }
		// dump($uniqueIdForTesting);
		// if ($uniqueIdForTesting) {
		// 	dump($parentEntityName);
		// }

		// dump($filter);
		// dump($forceCollection);
		// if ($parentEntityName == '0') {
		// 	dump('==================================================');
		// 	dump($repository);dump($filter);
		// }
		// $filter0 = $filter;
		// dump($filter0); dump($filter); //exit;
		// $entityName = ucfirst(BasicUtils::snakeToCamelCase($repository->getTableName()));

		$entityName = $repository->getEntityName();

		if (!$initialEntityName) {
			$initialEntityName = $entityName;
		}
		if (!$entityAlias) {
			$entityAlias = $entityName;
		}


		/**
		 * DEBUG
		*/
		// $debug = false;
		if ($initialEntityName == 'AscSampleScale') {
			// $debug = true;
			// dump($repository);
		}


		if (!$repository->isActive()) {
			throw new ElastiException(
				$this->wrapExceptionParams(array(
					'entityName' => $entityName
				)), 
				1655
			);
        }

        // $entitiesInThisChain[] = array(
		// 	'entityName' => $entityName,
		// 	'id' => $entity->getIdValue()
		// );
        // $entityKey = $parentEntityName.'-'.$entityName;
		$entityKey = $parentEntityName.'-'.$entityAlias;
		$filter = !$filter ? array() : $filter;
		
		// dump($entityName);//exit;
        // dump('collectEntities! ('.$entityName.')');

        if ($round > 30) {
			dump($round);
			dump($entityName);
			dump($entityCollector->getCollection());
            exit;
        }

		// if ($uniqueIdForTesting) {
		// 	dump('==============');
		// 	dump($entityName);
		// }

		if (!is_object($repository)) {
            if (is_array($repository)) {
                $repository = BasicUtils::arrayToString($repository);
            }
			throw new ElastiException('Repository is not an object '.$repository, ElastiException::ERROR_TYPE_SECRET_PROG);
        }
		// dump('$debug:');
		// dump($debug);
		// dump($entityKey);
		$recordData = $repository->collectRecordData($filter, $queryType, $forceCollection, $debug);

		if ($debug) {
			// dump('====== EntityManager->collectEntities() debug ======');// exit;
			dump('$recordData:');
			dump($recordData);
		}

        if (!$recordData) {
            $recordData = array();
		}

        if ($forceCollection && count($recordData) == 0) {
			$recordData = $repository->createEmptyRecordData();
		}

        if ($queryType == 'count') {
            return isset($recordData[0]) ? $recordData[0]['count'] : '0';
		}

		$dummyEntity = $repository->createNewEntity();
		$entityAttributes = $dummyEntity->getEntityAttributes();
		$propertyMap = $entityAttributes['propertyMap'];

        for ($i = 0; $i < count($recordData); $i++) {
			// $processedRecordData = array();
			$idPropertyName = BasicUtils::snakeToCamelCase($dummyEntity->getIdFieldName());

			$entityInCollection = null;
			if ($recordData[$i][$idPropertyName]) {
				$entityInCollection = $this->findEntityInCollection($entityCollector, $entityName, $recordData[$i][$idPropertyName]);
				foreach ($recordData[$i] as $recordDataProperty => $recordDataRowValue) {
					if (isset($propertyMap[$recordDataProperty]) && $propertyMap[$recordDataProperty]['toBeEncryted']) {
						$recordData[$i][$recordDataProperty] = $recordDataRowValue == '' 
							? '' : $this->decrypt($recordDataRowValue);
					}
				}
			}

			if ($entityInCollection) {
				$entity = $entityInCollection;
			} else {
				$entity = $repository->makeEntityFromRecordData($recordData[$i]);
			}

            $id = $entity->getIdValue();

			$notInEntityChain = $this->notInEntityChain($entitiesInThisChain, $entityName, $id);
			// dump($id); dump($entityName);
			$entitiesInThisChain = $this->addToEntityChain($entitiesInThisChain, $entityName, $id);

			// $entityAlias = 'Alma';

			if ($debug) {
				dump('=======================');
				// dump($parentEntityKey);
				// dump($entity);
			}

			/**
			 * Vedd eszre: a @var $entityAlias mindig az argumentumbol jon, vagyis a lentebbi foreach loopoknal valoban az fog erkezni ide, mert ez a metodus a foreach-ben rekurzivan hivodik meg.
			*/
            $entityCollectionId = $entityCollector->addElement($id, $entityKey, $parentEntityCollectionId, $parentEntityKey, $entity, $entityAlias, $isDummyEntity);
            // $entityAttributes = $entity->getEntityAttributes();

            if ($entityAttributes['relations']) {
                foreach ($entityAttributes['relations'] as $targetClassName => $relation) {

					if ($debug) {
						dump($targetClassName);
						dump($relation);
					}

					$entityAlias = $relation['targetEntityAlias'];

                    if ($relation['referenceContainerTable']) {
                        $childEntityNamespace = $relation['targetClass'];
                        $this->getContainer()->wireService(str_replace('\\', '/', $childEntityNamespace));
                        $childEntity = new $childEntityNamespace();
                        $childRepository = $childEntity->getRepository();
						$childEntityName = $childRepository->getEntityName();
                        $allowMultipleChild = $relation['multiple'];

                        if ($relation['associationIdField'] && $relation['associationReferencedField']) {
                            $childIds = $childRepository->getIdByBindingTable(
                                $entity,
                                $relation['associationReferencedField'],
                                ($relation['referenceContainerTable'] == 'target' ? 'self' : 'other')
							);
							// dump($this->notInEntityChain($entitiesInThisChain, $entityName, $id));
                            if (($childIds != array() || $childIds == array() && $forceCollection) 
							&& ($notInEntityChain || ($entityName == $childEntityName))
							// && (!in_array($childEntityName, $entitiesInThisChain) || ($entityName == $childEntityName))
							) {
							// || ($entityName == $childEntityName && $parentEntityName != $entityName))) {
                                if (!$allowMultipleChild && count($childIds) > 1) {
                                    $errorMessage = 'Not allowed multiple childs for relation '.$entityName.'/'.$childEntityName
                                    .'. '.count($childIds).' records were found with parent id '.$entity->getIdValue().' with ids: '.implode(', ', $childIds);
                                    throw new ElastiException($errorMessage, ElastiException::ERROR_TYPE_SECRET_PROG);
								}
								$isDummyChildEntity = false;
								if ($childIds == array() && $forceCollection) {
									$childIds[] = null;
									$isDummyChildEntity = true;
								}
								// dump($childRepository);
								// dump($childIds);
								// if ($uniqueIdForTesting) {
								// 	dump($childIds);
								// }
                                foreach ($childIds as $childId) {

									// if (BasicUtils::explodeAndGetElement(get_class($repository), '\\', 'last') == 'CartRepository') {
									// 	dump($recordData[$i]); dump($filter); dump($entityCollector->getCollection());exit;
									// }

									if ((($entityName == $childEntityName) && ($parentEntityName != $entityName 
									|| $parentEntityName == $entityName && $childId)) || $entityName != $childEntityName) {
										$this->collectEntities(
											$entityCollector,
											$childRepository, // repository
											['conditions' => [['key' => $relation['associationIdField'], 'value' => $childId]]], // filter
											$entityName, // parentEntityName
											false, // limit
											'result', // queryType
											$initialEntityName,
											$entityAlias,
											$entityCollectionId, // parentEntityCollectionId
											$entityKey,
											// $multipleChild,
											$forceCollection, // forceCollection
											($round + 1), // round
											$entitiesInThisChain, // entitiesInThisChain
											$isDummyChildEntity,
											$uniqueIdForTesting
										);
									}

                                }
                            }
                        }
                    }
                }
            }
        }

        return $entityCollector;
	}

	public function findEntityInCollection($entityCollector, $entityName, $idValue)
	{
		foreach ($entityCollector->getCollection() as $collectionElement) {
			if ($collectionElement['entityName'] == $entityName && $collectionElement['id'] == $idValue) {
				return $collectionElement['entity'];
			}
		}
		return null;
	}

	public function notInEntityChain($entitiesInThisChain, $entityName, $id)
	{
		// dump('===========================');
		// dump($entitiesInThisChain);
		// dump($entityName);
		// dump($id);
		$result = true;
		foreach ($entitiesInThisChain as $entityInThisChain) {
			if ($entityInThisChain['entityName'] == $entityName && $entityInThisChain['id'] == $id) {
				$result = false;
			}
		}
		// dump($result);
		return $result;
	}

	public function addToEntityChain($entitiesInThisChain, $entityName, $id)
	{
		if ($this->notInEntityChain($entitiesInThisChain, $entityName, $id)) {
			$entitiesInThisChain[] = array(
				'entityName' => $entityName,
				'id' => $id
			);
		}
		return $entitiesInThisChain;
	}

	public function findEntityKeyInCollection($entityKey, $entityCollectionArray)
    {
        foreach ($entityCollectionArray as $entityCollectionElement) {
            if ($entityCollectionElement['entityKey'] == $entityKey) {
                return true;
            }
        }
        return false;
    }
}
