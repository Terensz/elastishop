<?php
namespace framework\component\parent;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\kernel\base\Reflector;
use framework\component\exception\ElastiException;

abstract class DynamicDbEntity extends Kernel
{
    const ENTITY_ATTRIBUTES = null;

    public function set($searchedPropertyCode, $value)
    {
        foreach ($this->getEntityAttributes()['propertyMap'] as $propertyCode => $propertyAttributes) {
            if ($searchedPropertyCode == $propertyCode) {
                $setter = $propertyAttributes['setter'];
                return $this->$setter($value);
            }
        }
        throw new ElastiException('Property ('.$searchedPropertyCode.') not existing.', ElastiException::ERROR_TYPE_SECRET_PROG);
    }

    public function get($searchedPropertyCode)
    {
        foreach ($this->getEntityAttributes()['propertyMap'] as $propertyCode => $propertyAttributes) {
            if ($searchedPropertyCode == $propertyCode) {
                $getter = $propertyAttributes['getter'];
                return $this->$getter();
            }
        }
        throw new ElastiException(
            $searchedPropertyCode.' propertyCode not existing in entity '
            .(BasicUtils::explodeAndGetElement(get_class($this), '\\', 'last')), 
            ElastiException::ERROR_TYPE_SECRET_PROG
        );
    }

    public function getIdValue()
    {
        $getter = 'get'.ucfirst($this->getIdFieldName());
        return $this->$getter();
    }

    public function setIdValue($id)
    {
        $setter = 'set'.ucfirst($this->getIdFieldName());
        $this->$setter($id);
    }

    public function getIdFieldName()
    {
        if (static::ENTITY_ATTRIBUTES && isset(static::ENTITY_ATTRIBUTES['idFieldName'])) {
            return static::ENTITY_ATTRIBUTES['idFieldName'];
        } else {
            return $this->getRepository()->getPrimaryKeyField();
        }
    }

    public function isActive() : bool
    {
        if (static::ENTITY_ATTRIBUTES) {
            $entityAttributes = static::ENTITY_ATTRIBUTES;
            // dump($entityAttributes);
            if (isset($entityAttributes) && isset($entityAttributes['active'])) {
                if ($entityAttributes['active'] === false) {
                    return false;
                }
            }
        }
        
        return true;
    }

    public function getEntityAttributes()
    {
        // dump($alma);
        if (!$this->isActive()) {
            // dump($this);
            return array('active' => false);
        }
        $entityAttributes = array();
        $encryptedProperties = array();
        // $onStoreFunctionCalls = array();
        if (static::ENTITY_ATTRIBUTES) {
            $entityAttributes = static::ENTITY_ATTRIBUTES;
            // dump($entityAttributes);
            if (isset($entityAttributes) && isset($entityAttributes['encryptedProperties'])) {
                $encryptedProperties = $entityAttributes['encryptedProperties'];
            }
            // if (isset($entityAttributes) && isset($entityAttributes['onStoreFunctionCalls'])) {
            //     $onStoreFunctionCalls = $entityAttributes['onStoreFunctionCalls'];
            // }
        } else {

        }
        if (isset($entityAttributes['active']) && $entityAttributes['active'] === false) {
            return array('active' => false);
        }
        if (!isset($entityAttributes['repositoryPath'])) {
            $entityAttributes['repositoryPath'] = $this->guessRepositoryPath();
        }

        $entityAttributes['class'] = get_class($this);
        $entityAttributes['className'] = BasicUtils::explodeAndGetElement($entityAttributes['class'], '\\', 'last');

        $cached = $this->getContainer()->getFromCache('entityAttributes', $entityAttributes['className']);
        if ($cached) {
            return $cached;
        }

        $entityAttributes['propertyMap'] = $this->getPropertyMap();
        $entityAttributes['relations'] = $this->getERM()->getProcessedRelations(
            $entityAttributes['className'],
            $entityAttributes['propertyMap']
        );

        $entityAttributes['active'] = isset($entityAttributes['active']) ? (bool) $entityAttributes['active'] : true;
        ksort($entityAttributes);
        // if (!is_array($entityAttributes['propertyMap'])) {
        //     dump($this);
        //     dump($entityAttributes);
        // }
        if (is_array($entityAttributes['propertyMap'])) {
            foreach ($entityAttributes['propertyMap'] as $propertyCode => $propertyAttributes) {
                if (in_array($propertyCode, $encryptedProperties)) {
                    $entityAttributes['propertyMap'][$propertyCode]['toBeEncryted'] = true;
                } else {
                    $entityAttributes['propertyMap'][$propertyCode]['toBeEncryted'] = false;
                }
            }
        }
        $this->getContainer()->addToCache('entityAttributes', $entityAttributes['className'], $entityAttributes);
        // dump($alma);
        // dump($entityAttributes);
        return $entityAttributes;
    }

    public function getRepository()
    {
        $repoPath = $this->getRepositoryPath();
        $repoName = BasicUtils::explodeAndGetElement($repoPath, '/', 'last');
        if ($repoName) {
            $this->getContainer()->setService($repoPath, null, BasicUtils::explodeAndGetElement(static::class, '\\', 'last'));
            return $this->getContainer()->getService($repoName);
        }
        else {
            return null;
        }
    }

    public function getPropertyMap()
    {
        if (!$this->isActive()) {
            return null;
        }
        $tableName = $this->getRepository()->getTableName();
        $cached = $this->getContainer()->getFromCache('propertyMap', $tableName);
        if (!$cached) {
            $result = $this->assemblePropertyMap($tableName);
            $this->getContainer()->addToCache('propertyMap', $tableName, $result);
        } else {
            $result = $cached;
        }
        return $result;
    }

    public function assemblePropertyMap()
    {
        // dump('assemblePropertyMap-----------------------------------------');
        // dump($this);
        $className = BasicUtils::explodeAndGetElement(get_class($this), '\\', 'last');
        $reflector = new Reflector();
        $propertyNames = $this->getEntityManager()->getPredefinedPropertyNames($this);
        $propertyMap = array();
        // dump($propertyNames);
        foreach ($propertyNames as $propertyName) {
            $technical = true;
            $propertyParams = $this->getPropertyParams($propertyName);
            $argumentReflections = $reflector->getPropertyReflections($this, $propertyParams['setter'], $propertyParams['propertyName']);
            if ($argumentReflections === false) {
                continue;
            }

            $propertyCode = $propertyParams['singularPropertyName'];
            $propertyMap[$propertyCode] = $propertyParams;
            $propertyMap[$propertyCode]['isObject'] = false;
            foreach ($argumentReflections as $argumentReflection) {
                if ($argumentReflection['class']) {
                    $targetClass = $argumentReflection['class'];
                    $technical = false;
                    $targetClassName = BasicUtils::explodeAndGetElement($argumentReflection['class'], '\\', 'last');
                    $targetClassPath = str_replace('\\', "/", $targetClass);
                    $this->getContainer()->wireService($targetClassPath);
                    $targetEntity = new $targetClass();
                    if ($targetEntity->isActive() === false) {
                        throw new ElastiException(
                            $this->wrapExceptionParams(array(
                                'thisClassName' => $className,
                                'targetClassName' => $targetClassName
                            )), 
                            1654
                        );
                    }
                    $referenceParams = $this->detectReferenceParams($targetEntity);
                    if ($propertyParams['setter'] == $propertyParams['setterPre'].$targetClassName) {
                        $propertyMap[$propertyCode]['isObject'] = true;
                        # Fontos: akkor multiple, ha van hozza add metodus
                        $isThisMultiple = $this->isThisMultiple($targetEntity);
                        $isTargetMultiple = $targetEntity->isThisMultiple($this);
                        $reverseReferenceParams = $targetEntity->detectReferenceParams($this);
                        $targetAssociation = ($isThisMultiple ? 'many' : 'one').'To'.($isTargetMultiple ? 'Many' : 'One');

                        $propertyMap[$propertyCode]['targetRelation'] = array(
                            'targetClassName' => $targetClassName,
                            'targetClass' => $argumentReflection['class'],
                            'association' => $targetAssociation,
                            'relationBinderTable' => $this->detectRelationBinderTable($targetClassName),
                            'targetIdField' => $referenceParams['targetIdField'],
                            'referencedIdField' => $referenceParams['referencedIdField'],
                            'allowNewChild' => isset($referenceParams['allowNewChild']) ? $referenceParams['allowNewChild'] : true
                        );
                        $propertyMap[$propertyCode]['reverseRelation'] = array(
                            'targetClassName' => $className,
                            'targetClass' => get_class($this),
                            'association' => $this->getEntityManager()->getERM()->getOppositeAssociation($targetAssociation),
                            'relationBinderTable' => $targetEntity->detectRelationBinderTable($targetClassName),
                            'targetIdField' => $reverseReferenceParams['targetIdField'],
                            'referencedIdField' => $reverseReferenceParams['referencedIdField'],
                            'allowNewChild' => isset($reverseReferenceParams['allowNewChild']) ? $reverseReferenceParams['allowNewChild'] : true
                        );
                    }
                }
            }

            if ($technical) {
                $fieldNames = $this->getDbManager()->getFieldNames($this->getRepository()->getTableName());
                if (in_array(BasicUtils::camelToSnakeCase($propertyCode), $fieldNames)) {
                    $technical = false;
                }
            }

            $propertyMap[$propertyCode]['technical'] = $technical;

            if (!$propertyMap[$propertyCode]['isObject']) {
                $propertyMap[$propertyCode]['className'] = null;
            }
        }
        // dump($propertyMap);
        return $propertyMap;
    }

    public function isThisMultiple($targetEntity)
    {
        // dump('isThisMultiple');
        // dump($this);
        // dump($targetEntity);
        $thisClassName = BasicUtils::explodeAndGetElement(get_class($this), '\\', 'last');
        $propertyNames = $this->getEntityManager()->getPredefinedPropertyNames($targetEntity);
        foreach ($propertyNames as $propertyName) {
            $propertyParams = $targetEntity->getPropertyParams($propertyName, true);
            if ($propertyParams['className'] == $thisClassName) {
                // dump('res: '.(int)$propertyParams['multiple']);
                // dump($propertyParams);
                $result = $propertyParams['multiple'];
                // dump('multiple:'); dump($result);
                return $result;
            }
        }
        // dump('res: false');
        return false;
    }

    // public function isThisMultiple($targetEntity)
    // {
    //     if (get_class($targetEntity) == 'framework\packages\UserPackage\entity\Address') {
    //         dump('isThisMultiple');
    //         dump(get_class($this));
    //         dump(get_class($targetEntity));
    //     }
    //     $thisClassName = BasicUtils::explodeAndGetElement(get_class($this), '\\', 'last');
    //     $propertyNames = $this->getEntityManager()->getPredefinedPropertyNames($targetEntity);
    //     foreach ($propertyNames as $propertyName) {
    //         $propertyParams = $targetEntity->getPropertyParams($propertyName, true);
    //         if ($propertyParams['className'] == $thisClassName) {
    //             if (get_class($targetEntity) == 'framework\packages\UserPackage\entity\Address') {
    //                 dump('propertyParams');
    //                 dump($propertyParams);
    //             }
    //             dump('res: true');
    //             dump($propertyParams);
    //             return $propertyParams['multiple'];
    //         }
    //     }
    //     dump('res: false');
    //     return false;
    // }


    public function detectReferenceParams($targetEntity)
    {
        // dump($this);
        $targetTableName = $targetEntity->getRepository()->getTableName();
        $thisTableName = $this->getRepository()->getTableName();
        $targetFields = $this->getDbManager()->getFieldAttributes($targetTableName);
        $thisFields = $this->getDbManager()->getFieldAttributes($thisTableName);
        $targetIdField = null;
        $referencedIdField = null;
        $fieldNamedId = null;
        // dump($targetFields);exit;
        foreach ($targetFields as $targetField) {
            if ($targetField['Field'] == 'id') {
                $fieldNamedId = $targetField['Field'];
            }
            if ($targetField['Key'] == 'PRI') {
                $targetIdField = $targetField['Field'];
            }
        }

        foreach ($thisFields as $thisField) {
            if ($thisField['Field'] == $targetTableName.'_id') {
                $referencedIdField = $thisField['Field'];
            }
        }

        $targetIdField = $targetIdField 
            ? $targetIdField 
            : ($fieldNamedId 
                ? $fieldNamedId 
                : (count($targetFields) > 0 ? $targetFields[0]['Field'] : null));
        return array(
            'targetIdField' => $referencedIdField ? $targetIdField : null,
            'referencedIdField' => $referencedIdField
        );
    }

    public function getPropertyParams($propertyName, $debug = false)
    {
        $reflector = new Reflector();
        $setterPre = null;
        $addMethod = null;
        $defaultValue = $reflector->getDefaultValue($this, $propertyName);
        $hasAddMethod = method_exists($this, 'add'.ucfirst($propertyName));
        $lastChar = strtolower(substr($propertyName, -1));
        $last2Chars = strtolower(substr($propertyName, -2));
        $singularPropertyName = $propertyName;
        if (!$hasAddMethod && $last2Chars == 'es' || $lastChar == 's') {
            $addMethod = substr('add'.ucfirst($propertyName), 0, ($last2Chars == 'es' ? -2 : -1));
            $hasAddMethod = method_exists($this, $addMethod);
            if ($hasAddMethod) {
                $setterPre = 'add';
                $singularPropertyName = lcfirst(ltrim($addMethod, 'add'));
            } else {
                $addMethod = null;
            }
        }
        $hasAddMethod = $hasAddMethod ? $hasAddMethod : (method_exists($this, 'add'.ucfirst($singularPropertyName)));
        $setterPre = $setterPre ? $setterPre : ($hasAddMethod ? 'add' : 'set');
        $multiple = $hasAddMethod ? true : ($defaultValue === array() ? true : false);
        // if ($defaultValue === array()) {

        //     throw new ElastiException(
        //         $this->wrapExceptionParams(array(
		// 			'callingClassName' => $callingClassName,
		// 			'targetClassName' => $targetClassName
        //         )), 
        //         1652
        //     );
        // }
        $setter = $addMethod ? $addMethod : $setterPre.ucfirst($propertyName);
        $result = array(
            'propertyName' => $propertyName,
            'className' => ucfirst($singularPropertyName),
            'singularPropertyName' => $singularPropertyName,
            'multiple' => $multiple,
            'getter' => 'get'.ucfirst($propertyName),
            'setterPre' => $setterPre,
            'setter' => $setter
        );
        // dump($result);
        return $result;
    }

    public function detectRelationBinderTable($targetClassName)
    {
        /**
         * @todo
         */
        return false;
    }

    // public function getFieldNames($tableName)
    // {
    //     $cached = $this->getContainer()->getFromCache('fieldList', $tableName);
    //     if (!$cached) {
    //         $fieldNames = $this->getDbManager()->getFieldNames($tableName);
    //         $this->getContainer()->addToCache('fieldList', $tableName, $fieldNames);
    //     } else {
    //         $fieldNames = $cached;
    //     }
    //     return $fieldNames;
    // }

    // public function getFieldNames_2($tableName)
    // {
    //     $result = array();
    //     $fieldList = $this->getFieldList($tableName);
    //     if (!$fieldList) {
    //         return false;
    //     }
    //     foreach ($fieldList as $fieldListRow) {
    //         $result[] = $fieldListRow['Field'];
    //     }
    //     return $result;
    // }

    public function guessRepositoryPath()
    {
        $repoClass = str_replace('\\entity\\', '\\repository\\', static::class).'Repository';
        $repoPath = str_replace('\\', '/', $repoClass);
        return $repoPath;
    }

    public function getRepositoryPath()
    {
        return isset(static::ENTITY_ATTRIBUTES['repositoryPath']) 
            ? static::ENTITY_ATTRIBUTES['repositoryPath'] : $this->guessRepositoryPath();
    }
}
