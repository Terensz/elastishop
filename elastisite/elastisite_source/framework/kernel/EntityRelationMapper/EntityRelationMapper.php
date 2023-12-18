<?php
namespace framework\kernel\EntityRelationMapper;

use App;
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

    public static function getProcessedRelations($entity, $propertyMap, $debug = false)
    {
		$dbm = App::getContainer()->getKernelObject('DbManager');
		if ($dbm->getErrorMessage()) {
			return false;
		}
		$className = $entity->getClassName();
		$relations = array();

		if (!is_array($propertyMap)) {
			return false;
		}
		foreach ($propertyMap as $propertyCode => $propertyAttributes) {
			if ($propertyAttributes['isObject']) {
				$targetClassName = $propertyAttributes['targetRelation']['targetClassName'];
				$targetClass = $propertyAttributes['targetRelation']['targetClass'];
				$targetPath = str_replace('\\', '/', $targetClass);
				App::getContainer()->wireService($targetPath);
				$reverseRelation = $propertyAttributes['reverseRelation'];
				$relationLabel = $className.'-'.ucfirst($propertyCode);
				$targetClassName = $propertyAttributes['targetRelation'] 
					? $propertyAttributes['targetRelation']['targetClassName'] : null;
				$relationDetails = self::processRelationDetails(
					$className,
					$targetClassName,
                    $propertyAttributes['targetRelation'],
                    $reverseRelation,
                    $relationLabel
                );

				$relations[ucfirst($propertyCode)] = array(
				// $relations[$propertyAttributes['targetRelation']['targetClassName']] = array(
					'storageType' => $relationDetails['storageType'],
					'thisClass' => $relationDetails['thisClass'],
					'propertyCode' => $propertyCode,
					'propertyName' => $propertyAttributes['propertyName'],
					'targetClass' => $relationDetails['targetClass'],
					'multiple' => $propertyAttributes['multiple'],
					'targetClassName' => $targetClassName,
					'targetEntityAlias' => ucfirst($propertyAttributes['propertyName']),
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

		return $relations == array() ? null : $relations;
	}

	public static function processRelationDetails($callingClassName, $targetClassName, $targetRelation, $reverseRelation, $relationLabel = null)
	{
		$relationCode = $callingClassName.'/'.$targetClassName;
        $cached = App::getContainer()->getFromCache('entityRelations', $relationCode);
        if ($cached) {
            return $cached;
		}
		$selfReferenced = false;
		$relations = self::mendRelations($targetRelation, $reverseRelation, $relationLabel);
		$targetTargetIdField = $relations['targetRelation']['targetIdField'];
		$targetReferencedIdField = $relations['targetRelation']['referencedIdField'];
		$reverseTargetIdField = $relations['reverseRelation']['targetIdField'];
		$reverseReferencedIdField = $relations['reverseRelation']['referencedIdField'];
		$bothTargetIdReferenced = $targetTargetIdField && $reverseTargetIdField ? true : false;
		$bothRefIdReferenced = $targetReferencedIdField && $reverseReferencedIdField ? true : false;
		// $noTargetIdReferenced = !$targetTargetIdField && !$reverseTargetIdField ? true : false;
		// $noRefIdReferenced = !$targetReferencedIdField && !$reverseReferencedIdField ? true : false;
		if ($bothTargetIdReferenced || $bothRefIdReferenced) {
			if ($targetRelation['targetClassName'] == $reverseRelation['targetClassName']) {
				$selfReferenced = true;
			} else {
				throw new ElastiException(
					App::getContainer()->wrapExceptionParams(array(
						'callingClassName' => $callingClassName,
						'targetClassName' => $targetClassName
					)), 
					1651
				);
			}
		}

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

		if ($processed['multiple'] && $processed['referenceContainerTable'] == 'this') {
			throw new ElastiException(
				App::getContainer()->wrapExceptionParams(array(
					'callingClassName' => $callingClassName,
					'targetClassName' => $targetClassName
				)), 
				1653
			);
		}
		App::getContainer()->addToCache('entityRelations', $relationCode, $processed);

		return $processed;
	}

	public static function mendRelations($targetRelation, $reverseRelation, $relationLabel = null)
	{
        if (!is_array($targetRelation) && !is_array($reverseRelation)) {
			throw new ElastiException('Both entity relations are missing of label '.$relationLabel,
				ElastiException::ERROR_TYPE_SECRET_PROG);
		}

		if (isset($targetRelation['association']) && isset($reverseRelation['association'])
		&& (self::getOppositeAssociation($targetRelation['association']) != $reverseRelation['association']
		|| self::getOppositeAssociation($reverseRelation['association']) != $targetRelation['association'])) {
			throw new ElastiException('Mutually exclusive associations ('.$targetRelation['association']
				.' against '.$reverseRelation['association'].') of label '.$relationLabel,
				ElastiException::ERROR_TYPE_SECRET_PROG);
		}

		if (!$targetRelation) {
			$mendedReverseRelation = self::mendRelation($reverseRelation);
			$targetRelation = self::createOppositeRelation($mendedReverseRelation);
		}

		if (!$reverseRelation) {
			$mendedTargetRelation = self::mendRelation($targetRelation);
			$reverseRelation = self::createOppositeRelation($mendedTargetRelation);
		}

		return array(
			'targetRelation' => isset($mendedTargetRelation) ? $mendedTargetRelation : self::mendRelation($targetRelation),
			'reverseRelation' => isset($mendedReverseRelation) ? $mendedReverseRelation : self::mendRelation($reverseRelation)
		);
	}

	public static function mendRelation($relation)
	{
		$mendedRelation = array();
		foreach (self::RELATION_PARAMS as $relationParam) {
			$mendedRelation[$relationParam] = isset($relation[$relationParam]) ? $relation[$relationParam] : false;
		}
		return $mendedRelation;
	}

	public static function createOppositeRelation($existingRelation)
	{
		$oppositeRelation = array();
		foreach ($existingRelation as $existingRelationParam) {
			if ($existingRelationParam == 'storageType') {
				$oppositeRelation['storageType'] = 'technical';
			} elseif ($existingRelationParam == 'association') {
				$oppositeRelation['association'] = self::getOppositeAssociation($existingRelation['association']);
			} else {
				$oppositeRelation[$existingRelationParam] = false;
			}
		}
		return $oppositeRelation;
	}

	public static function getOppositeAssociation($assoc)
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
}
