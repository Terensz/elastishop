<?php
namespace framework\kernel\EntityRelationMapper;

use framework\kernel\component\Kernel;
use framework\kernel\base\Reflector;
use framework\kernel\DbManager\manager\QueryBuilder;
use framework\kernel\DbManager\manager\StatementBuilder;
use framework\component\exception\ElastiException;
use framework\kernel\utility\BasicUtils;

class EntityRelationMapper extends Kernel
{
	const RELATION_PARAMS = array(
		'targetClass',
		'storageType',
		'association',
		'relationBinderTable',
		'targetIdField',
		'referencedIdField',
		'allowNewChild',
		'multiple'
	);

	// public function getEntityRelationMap($entity, $parentEntity = null, $relationKeys = array(), $repeatCounter = array())
	// {
	// 	$entityRelationMap = array();
	// 	$entityAttributes = $entity->getEntityAttributes();
	// 	$relations = $this->getEntityManager()->getRelations($entityAttributes);
	// 	$thisClassName = BasicUtils::explodeAndGetElement(get_class($entity), '\\', 'last');

	// 	foreach ($relations as $targetRelation) {
	// 		$targetClass = $targetRelation['targetClass'];
	// 		$targetClassName = BasicUtils::explodeAndGetElement($targetClass, '\\', 'last');
	// 		$targetPath = str_replace('\\', '/', $targetClass);
	// 		$this->getContainer()->wireService($targetPath);
	// 		$relationKey = $thisClassName.'-'.$targetClassName;
	// 		// dump($thisClassName.'-'.$targetClassName);
	// 		if (!in_array($relationKey, $relationKeys)) {
	// 			$relationKeys[] = $relationKey;
	// 			$targetEntity = new $targetClass();
	// 			$targetEntityAttributes = $targetEntity->getEntityAttributes();
	// 			$reverseRelation = $this->getEntityManager()->getRelation($targetEntityAttributes, $thisClassName);
	// 			$entityRelationMap[$relationKey]['targetRelation'] = $targetRelation;
	// 			$entityRelationMap[$relationKey]['reverseRelation'] = $reverseRelation;
	// 			# Hagyd benne!!!
	// 			# $entityRelationMap[$relationKey]['entityRelationMap'] = $this->getEntityRelationMap($targetEntity, $entity, $relationKeys, $repeatCounter);
	// 		}
	// 	}
	// 	// dump($entityRelationMap);
	// 	return $entityRelationMap;
	// }

    public function getProcessedRelations($entity, $propertyMap, $debug = false)
    {
		$dbm = $this->getContainer()->getKernelObject('DbManager');
		if ($dbm->getErrorMessage()) {
			return false;
		}
		$className = $entity->getClassName();
		$relations = array();
		// $className = $className;
		// dump($className);
		// dump($propertyMap);
		if (!is_array($propertyMap)) {
			return false;
			// dump($className);
			// dump($propertyMap);
		}
		foreach ($propertyMap as $propertyCode => $propertyAttributes) {
			if ($propertyAttributes['isObject']) {
				$targetClassName = $propertyAttributes['targetRelation']['targetClassName'];
				$targetClass = $propertyAttributes['targetRelation']['targetClass'];
				$targetPath = str_replace('\\', '/', $targetClass);
				$this->getContainer()->wireService($targetPath);
				$reverseRelation = $propertyAttributes['reverseRelation'];
				$relationLabel = $className.'-'.ucfirst($propertyCode);
				$targetClassName = $propertyAttributes['targetRelation'] 
					? $propertyAttributes['targetRelation']['targetClassName'] : null;
				$relationDetails = $this->processRelationDetails(
					$className,
					$targetClassName,
                    $propertyAttributes['targetRelation'],
                    $reverseRelation,
                    $relationLabel
                );
				// dump($propertyAttributes['targetRelation']);
				// dump($reverseRelation);

				// $relations[$propertyAttributes['targetRelation']['targetClassName']] = array(
				$relations[ucfirst($propertyAttributes['targetRelation']['propertyCode'])] = array(
					'storageType' => $relationDetails['storageType'],
					'thisClass' => $relationDetails['thisClass'],
					'propertyCode' => $propertyCode,
					'propertyName' => $propertyAttributes['propertyName'],
					'targetClass' => $relationDetails['targetClass'],
					'multiple' => $propertyAttributes['multiple'],
					'targetClassName' => $targetClassName,
					'targetClass' => $targetClass,
					'association' => $propertyAttributes['targetRelation']['association'],
					'relationBinderTable' => $propertyAttributes['targetRelation']['relationBinderTable'],
					// 'targetIdField' => $propertyAttributes['targetRelation']['targetIdField'],
					// 'referencedIdField' => $propertyAttributes['targetRelation']['referencedIdField'],
					'referenceContainerTable' => $relationDetails['referenceContainerTable'],
					'associationIdField' => $relationDetails['targetIdField'],
					'associationReferencedField' => $relationDetails['referencedIdField'],
					'allowNewChild' => $relationDetails['allowNewChild']
				);
			}
		}
// dump($className);
// dump($relations);
// dump($relationDetails); 
// exit;


		return $relations == array() ? null : $relations;
	}

	public function processRelationDetails($callingClassName, $targetClassName, $targetRelation, $reverseRelation, $relationLabel = null)
	{
		$relationCode = $callingClassName.'/'.$targetClassName;
        $cached = $this->getContainer()->getFromCache('entityRelations', $relationCode);
        if ($cached) {
            return $cached;
		}
		$selfReferenced = false;
		$relations = $this->mendRelations($targetRelation, $reverseRelation, $relationLabel);
		// $targetClassName = BasicUtils::explodeAndGetElement($relations['targetRelation']['targetClass'], '\\', 'last');
		$targetTargetIdField = $relations['targetRelation']['targetIdField'];
		$targetReferencedIdField = $relations['targetRelation']['referencedIdField'];
		$reverseTargetIdField = $relations['reverseRelation']['targetIdField'];
		$reverseReferencedIdField = $relations['reverseRelation']['referencedIdField'];
		$bothTargetIdReferenced = $targetTargetIdField && $reverseTargetIdField ? true : false;
		$bothRefIdReferenced = $targetReferencedIdField && $reverseReferencedIdField ? true : false;
		$noTargetIdReferenced = !$targetTargetIdField && !$reverseTargetIdField ? true : false;
		$noRefIdReferenced = !$targetReferencedIdField && !$reverseReferencedIdField ? true : false;
		if ($bothTargetIdReferenced || $bothRefIdReferenced) {
			if ($targetRelation['targetClassName'] == $reverseRelation['targetClassName']) {
				$selfReferenced = true;
			} else {
				throw new ElastiException(
					$this->wrapExceptionParams(array(
						'callingClassName' => $callingClassName,
						'targetClassName' => $targetClassName
					)), 
					1651
				);
			}
			// return false;
			// dump($targetRelation);
			// dump($reverseRelation);
			// throw new ElastiException($errorText, ElastiException::ERROR_TYPE_SECRET_PROG);
		}
// dump($relations);
		$processed = array(
			'thisClass' => $relations['reverseRelation']['targetClass'],
			'targetClass' => $relations['targetRelation']['targetClass'],
			'storageType' => ($relations['targetRelation']['storageType']
				? $relations['targetRelation']['storageType'] : 'database'),
			'targetTargetIdField' => $relations['targetRelation']['targetIdField'],
			'targetReferencedIdField' => $relations['targetRelation']['referencedIdField'],
			'reverseTargetIdField' => $relations['reverseRelation']['targetIdField'],
			'reverseReferencedIdField' => $relations['reverseRelation']['referencedIdField'],
			'relationBinderTable' => $relations['targetRelation']['relationBinderTable'],
			'multiple' => (in_array($relations['targetRelation']['association'], array('oneToMany', 'manyToMany'))
				? true : false),
			'referenceContainerTable' => $selfReferenced ? 'selfReferenced' : ($targetReferencedIdField ? 'this' : 'target'),
			'targetIdField' => ($targetTargetIdField ? $targetTargetIdField : $reverseTargetIdField),
			'referencedIdField' => ($targetReferencedIdField ? $targetReferencedIdField : $reverseReferencedIdField),
			'allowNewChild' => $relations['targetRelation']['allowNewChild']
		);

		// dump($relations);
		// dump($processed);

		if ($processed['multiple'] && $processed['referenceContainerTable'] == 'this') {
			// dump($targetRelation);
			// dump($reverseRelation);
			// dump($processed);
			throw new ElastiException(
				$this->wrapExceptionParams(array(
					'callingClassName' => $callingClassName,
					'targetClassName' => $targetClassName
				)), 
				1653
			);
		}
		// dump($processed);
		if ($noTargetIdReferenced || $noRefIdReferenced) {
			// dump($targetRelation);
			// dump($reverseRelation);
			// dump($processed);
            // throw new ElastiException(
            //     $this->wrapExceptionParams(array(
			// 		'callingClassName' => $callingClassName,
			// 		'targetClassName' => $targetClassName
            //     )), 
            //     1652
            // );
		}
		$this->getContainer()->addToCache('entityRelations', $relationCode, $processed);
		return $processed;
	}

	public function mendRelations($targetRelation, $reverseRelation, $relationLabel = null)
	{
        if (!is_array($targetRelation) && !is_array($reverseRelation)) {
			throw new ElastiException('Both entity relations are missing of label '.$relationLabel,
				ElastiException::ERROR_TYPE_SECRET_PROG);
		}

		if (isset($targetRelation['association']) && isset($reverseRelation['association'])
		&& ($this->getOppositeAssociation($targetRelation['association']) != $reverseRelation['association']
		|| $this->getOppositeAssociation($reverseRelation['association']) != $targetRelation['association'])) {
			throw new ElastiException('Mutually exclusive associations ('.$targetRelation['association']
				.' against '.$reverseRelation['association'].') of label '.$relationLabel,
				ElastiException::ERROR_TYPE_SECRET_PROG);
		}

		if (!$targetRelation) {
			$mendedReverseRelation = $this->mendRelation($reverseRelation);
			$targetRelation = $this->createOppositeRelation($mendedReverseRelation);
		}

		if (!$reverseRelation) {
			$mendedTargetRelation = $this->mendRelation($targetRelation);
			$reverseRelation = $this->createOppositeRelation($mendedTargetRelation);
		}

		return array(
			'targetRelation' => isset($mendedTargetRelation) ? $mendedTargetRelation : $this->mendRelation($targetRelation),
			'reverseRelation' => isset($mendedReverseRelation) ? $mendedReverseRelation : $this->mendRelation($reverseRelation)
		);
	}

	public function mendRelation($relation)
	{
		$mendedRelation = array();
		foreach (self::RELATION_PARAMS as $relationParam) {
			$mendedRelation[$relationParam] = isset($relation[$relationParam]) ? $relation[$relationParam] : false;
		}
		return $mendedRelation;
	}

	public function createOppositeRelation($existingRelation)
	{
		$oppositeRelation = array();
		foreach ($existingRelation as $existingRelationParam) {
			if ($existingRelationParam == 'storageType') {
				$oppositeRelation['storageType'] = 'technical';
			} elseif ($existingRelationParam == 'association') {
				$oppositeRelation['association'] = $this->getOppositeAssociation($existingRelation['association']);
			} else {
				$oppositeRelation[$existingRelationParam] = false;
			}
		}
		return $oppositeRelation;
	}

	public function getOppositeAssociation($assoc)
	{
		if (in_array($assoc, array('oneToOne', 'manyToMany'))) {
			return $assoc;
		}

		$snakeAssoc = BasicUtils::camelToSnakeCase($assoc);
		$assocParts = explode('_', $snakeAssoc);
		if (count($assocParts) != 3) {
			throw new ElastiException('Bad association name: '.$assoc, ElastiException::ERROR_TYPE_SECRET_PROG);
		}

		$return = [];
		foreach ($assocParts as $assocPart) {
			if ($assocPart == 'one') {
				$return[] = 'many';
			} elseif ($assocPart == 'many') {
				$return[] = 'one';
			} elseif ($assocPart == 'to') {
				$return[] = 'to';
			} else {
				throw new ElastiException('Bad association name: '.$assoc, ElastiException::ERROR_TYPE_SECRET_PROG);
			}
		}
		return BasicUtils::snakeToCamelCase(implode('_', $return));
	}

	// public function getEntityRelationMap($entity, $parentEntity = null, $chain = array(), $repeatCounter = array())
	// {
	// 	$entityRelationMap = array();
	// 	$reflector = new Reflector();
	// 	$propertyNames = $reflector->getPropertyNames($entity);
	// 	$entityConfig = $entity::ENTITY_ATTRIBUTES;
	// 	$parentClassName = $parentEntity ? BasicUtils::explodeAndGetElement(get_class($parentEntity), '\\', 'last') : null;
	// 	$thisClassName = BasicUtils::explodeAndGetElement(get_class($entity), '\\', 'last');
	// 	$chain[] = $thisClassName;
	// 	if ($parentClassName == $thisClassName) {
	// 		$repeatCounter[$thisClassName] = isset($repeatCounter[$thisClassName]) ? $repeatCounter[$thisClassName] + 1 : 1;
	// 	}
	// 	// dump($parentClass);
	// 	foreach ($propertyNames as $propertyName) {
	// 		$targetRelation = $this->getRelation($entityConfig, $propertyName);
	// 		$entityRelationMap[$propertyName]['select'] = true;
	// 		$entityRelationMap[$propertyName]['store'] = true;
	// 		if ($targetRelation) {
	// 			$targetClass = $targetRelation['targetClass'];
	// 			$targetClassName = BasicUtils::explodeAndGetElement($targetClass, '\\', 'last');
	// 			$targetRole = isset($targetRelation['targetRole']) ? $targetRelation['targetRole'] : 'child';
	// 			$multipleTargetRecords = isset($targetRelation['multipleTargetRecords'])
	// 				? $targetRelation['multipleTargetRecords'] : false;
	// 			$targetIdField = isset($targetRelation['targetIdField']) ? $targetRelation['targetIdField'] : 'id';
	// 			$targetPath = str_replace('\\', '/', $targetClass);
	// 			$this->getContainer()->wireService($targetPath);

	// 			$entityRelationMap[$propertyName]['store'] = true;

	// 			if (!in_array($targetClassName, $chain) || ($targetClassName == $thisClassName && !isset($repeatCounter[$thisClassName]))) {
	// 				$targetEntity = new $targetClass();
	// 				$targetEntityConfig = $targetEntity::ENTITY_ATTRIBUTES;
	// 				$reverseRelation = $this->getRelation($targetEntityConfig, $thisClassName);
	// 				$entityRelationMap[$propertyName]['entity'] = true;

    //                 $entityRelationMap[$propertyName]['targetRelation'] = $targetRelation;
    //                 $entityRelationMap[$propertyName]['reverseRelation'] = $reverseRelation;

	// 				$entityRelationMap[$propertyName]['crossRelationDetails'] = $this->
	// 					getCrossRelationDetails($targetRelation, $targetClassName, $reverseRelation, $thisClassName);

	// 				$entityRelationMap[$propertyName]['entityRelationMap'] = $this->getEntityRelationMap($targetEntity, $entity, $chain, $repeatCounter);
	// 			} else {
	// 				# $entityRelation[$propertyName]['entity'] = false;
	// 				$entityRelationMap[$propertyName]['select'] = false;
	// 			}
	// 		} else {
	// 			$entityRelationMap[$propertyName]['entity'] = false;
	// 		}
	// 	}
	// 	return $entityRelationMap;
	// }
}
