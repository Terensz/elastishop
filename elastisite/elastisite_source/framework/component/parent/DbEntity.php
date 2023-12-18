<?php
namespace framework\component\parent;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\kernel\base\Reflector;
use framework\component\exception\ElastiException;
use framework\kernel\EntityRelationMapper\EntityRelationMapper;

abstract class DbEntity extends Kernel
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

    public function get($searchedPropertyCode = null)
    {
        // if ($searchedPropertyCode == null) {
        //     throw new ElastiException(
        //         $this->wrapExceptionParams(array(
        //             'entity' => BasicUtils::explodeAndGetElement(get_class($this), '\\', 'last'),
        //             'propertyCode' => $searchedPropertyCode
        //         )),
        //         1648
        //     );
        // }
        foreach ($this->getEntityAttributes()['propertyMap'] as $propertyCode => $propertyAttributes) {
            if ($searchedPropertyCode == $propertyCode) {
                $getter = $propertyAttributes['getter'];
                return $this->$getter();
            }
        }
        // throw new ElastiException(
        //     $searchedPropertyCode.' propertyCode not existing in entity '
        //     .(BasicUtils::explodeAndGetElement(get_class($this), '\\', 'last')),
        //     ElastiException::ERROR_TYPE_SECRET_PROG
        // );
        throw new ElastiException(
            $this->wrapExceptionParams(array(
                'entity' => BasicUtils::explodeAndGetElement(get_class($this), '\\', 'last'),
                'propertyCode' => $searchedPropertyCode
            )),
            1649
        );
    }

    public function getIdValue()
    {
        if (!$this->getIdFieldName()) {
            dump($this); exit;
        }
        $getter = 'get'.ucfirst($this->getIdFieldName());
        return $this->$getter();
    }

    public function setIdValue($id)
    {
        $setter = 'set'.ucfirst($this->getIdFieldName());
        $this->$setter($id);
    }

    public function getClassName()
    {
        // dump(get_class($this));
        return BasicUtils::explodeAndGetElement(get_class($this), '\\', 'last');
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

    public function getEntityAttributes($getRelations = true, $debug = false)
    {
        // if ($debug) {
        //     dump($this);
        // }
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

        $entityAttributes['propertyMap'] = $this->getPropertyMap($debug);
        if ($getRelations) {
            $entityAttributes['relations'] = EntityRelationMapper::getProcessedRelations(
                $this,
                $entityAttributes['propertyMap']
            );
        }

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

        // if ($debug) {
        //     dump($entityAttributes);
        // }

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

    public function getPropertyMap($debug = false)
    {
        if (!$this->isActive()) {
            return null;
        }
        $tableName = $this->getRepository()->getTableName();
        $cached = $this->getContainer()->getFromCache('propertyMap', $tableName);

        // if ($debug) {
        //     dump($tableName);
        //     dump($cached);
        // }

        if (!$cached) {
            $result = $this->assemblePropertyMap($tableName);
            // dump($result);
            $this->getContainer()->addToCache('propertyMap', $tableName, $result);
        } else {
            $result = $cached;
        }
        return $result;
    }

    /**
     * 
    */
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
        $multiple = $defaultValue === array() ? true : false;
        $setter = $addMethod ? $addMethod : $setterPre.ucfirst($propertyName);
        $className = ucfirst($singularPropertyName);
        $classNamespace = null;

        /**
         * Eredeti elkepzeles az lett volna, hogy itt lehet configurálni, ha egy relacionak eltero a property-neve, mint a classneve.
         * De valojaban a $propertyReflections ezt gyonyoruen megmutatja.
         * Benne hagyom kicommentezve, hatha akarunk a jovoben valamit configuralni.
        */
        // if (isset(static::ENTITY_ATTRIBUTES['propertyConfig']) && is_array(static::ENTITY_ATTRIBUTES['propertyConfig'])) {
        //     if (isset(static::ENTITY_ATTRIBUTES['propertyConfig'][$propertyName])) {
        //         $configuredPropertyAttributes = static::ENTITY_ATTRIBUTES['propertyConfig'][$propertyName];
        //         if (isset($configuredPropertyAttributes['classNamespace'])) {
        //             $classNamespace = $configuredPropertyAttributes['classNamespace'];
        //             $className = BasicUtils::explodeAndGetElement($classNamespace, '\\', 'last');
        //         }
        //     }
        // }

        $result = array(
            'propertyName' => $propertyName,
            'predictedClassName' => $className,
            'classNamespace' => $classNamespace,
            'singularPropertyName' => $singularPropertyName,
            'multiple' => $multiple,
            'getter' => 'get'.ucfirst($propertyName),
            'setterPre' => $setterPre,
            'setter' => $setter,
            'defaultValue' => $defaultValue,
            'entityAttributes' => static::ENTITY_ATTRIBUTES
            // 'getPredefinedPropertyNames' => $reflector->getPredefinedPropertyNames($this)
        );

        // dump($result);

        return $result;
    }

    /**
     * 
    */
    public function assemblePropertyMap()
    {
        $debug = false;
        $debug2 = false;
        // if (get_class($this) == 'projects\ASC\entity\AscSampleScale') {
        //     $debug = true;
        //     dump('=============================================');
        // }

        // dump('assemblePropertyMap-----------------------------------------');
        // dump($this);
        $dbm = $this->getContainer()->getKernelObject('DbManager');
        $className = BasicUtils::explodeAndGetElement(get_class($this), '\\', 'last');
        $reflector = new Reflector();
        $propertyNames = $this->getEntityManager()->getPredefinedPropertyNames($this);
        $propertyMap = array();
        $technicalProperties = array();
        if (isset(static::ENTITY_ATTRIBUTES['technicalProperties'])) {
            $technicalProperties = static::ENTITY_ATTRIBUTES['technicalProperties'];
        }
        // dump($propertyNames);
        // if ($className == 'Referer') {
        //     dump($propertyNames);
        // }
        // if ($debug) {
        //     dump("/////**************");
        // }
        
        foreach ($propertyNames as $propertyName) {

            // if ($debug == true) {
            //     dump('------------------------------------------');
            // }

            // if ($debug && in_array($propertyName, ['createdBy'])) {
            //     $debug2 = true;
            // } else {
            //     $debug2 = false;
            // }

            $technical = false;
            if (in_array($propertyName, $technicalProperties)) {
                $technical = true;
            }

            $propertyParams = $this->getPropertyParams($propertyName, $debug);
            $propertyReflections = $reflector->getPropertyReflections($this, $propertyParams['setter'], $propertyParams['propertyName']);

            if ($propertyReflections === false) {
                continue;
            }

            $propertyCode = $propertyParams['singularPropertyName'];
            $propertyMap[$propertyCode] = $propertyParams;
            $propertyMap[$propertyCode]['isObject'] = false;

            foreach ($propertyReflections as $propertyReflection) {
                if ($propertyReflection['class']) {
                    $targetClass = $propertyReflection['class'];
                    $targetClassName = BasicUtils::explodeAndGetElement($propertyReflection['class'], '\\', 'last');
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

                    $referencePropertyName = null;
                    if (ucfirst($targetClassName) != ucfirst($propertyName)) {
                        $referencePropertyName = $propertyName;
                    }

                    // if ($debug && $debug2) {
                    //     dump(ucfirst($targetClassName));
                    //     dump(ucfirst($propertyName));
                    // }

                    // $propertyName

                    $referenceParams = $this->detectReferenceParams($targetEntity, $referencePropertyName, false, $debug2);
                    // if ($debug && $debug2) {
                    //     dump($referencePropertyName);
                    //     dump($referenceParams);
                    // }
                    /**
                     * Ha a property-be szepen bent van a target class a typehint-ben, akkor a $propertyReflections hozni fogja a target class-t.
                    */
                    $propertyMap[$propertyCode]['isObject'] = true;
                    # Fontos: akkor multiple, ha van hozza add metodus
                    $isThisMultiple = $this->isThisMultiple($targetEntity);
                    $isTargetMultiple = $targetEntity->isThisMultiple($this);
                    $reverseReferenceParams = $targetEntity->detectReferenceParams($this, $referencePropertyName, true, $debug2);
                    $targetAssociation = ($isThisMultiple ? 'many' : 'one').'To'.($isTargetMultiple ? 'Many' : 'One');

                    if ($dbm->getErrorMessage()) {
                        continue;
                    }
                    $propertyMap[$propertyCode]['targetRelation'] = array(
                        'targetClassName' => $targetClassName,
                        'targetEntityAlias' => $referencePropertyName ? ucfirst($referencePropertyName) : $targetClassName,
                        'targetClass' => $propertyReflection['class'],
                        'association' => $targetAssociation,
                        'relationBinderTable' => $this->detectRelationBinderTable($targetClassName),
                        'targetIdField' => $referenceParams['targetIdField'],
                        'referencedIdField' => $referenceParams['referencedIdField'],
                        'allowNewChild' => isset($referenceParams['allowNewChild']) ? $referenceParams['allowNewChild'] : true
                    );
                    $propertyMap[$propertyCode]['reverseRelation'] = array(
                        'targetClassName' => $className,
                        'targetEntityAlias' => $className,
                        'targetClass' => get_class($this),
                        'association' => $this->getEntityManager()->getERM()->getOppositeAssociation($targetAssociation),
                        'relationBinderTable' => $targetEntity->detectRelationBinderTable($targetClassName),
                        'targetIdField' => $reverseReferenceParams['targetIdField'],
                        'referencedIdField' => $reverseReferenceParams['referencedIdField'],
                        'allowNewChild' => isset($reverseReferenceParams['allowNewChild']) ? $reverseReferenceParams['allowNewChild'] : true
                    );
                }

                if ($debug && $debug2) {
                    dump($propertyMap[$propertyCode]);
                }
            }

            // if ($technical) {
            //     $fieldNames = $this->getDbManager()->getFieldNames($this->getRepository()->getTableName());
            //     if (in_array(BasicUtils::camelToSnakeCase($propertyCode), (!$fieldNames ? [] : $fieldNames))) {
            //         $technical = false;
            //     }
            // }

            $propertyMap[$propertyCode]['technical'] = $technical;

            if (!$propertyMap[$propertyCode]['isObject']) {
                $propertyMap[$propertyCode]['className'] = null;
            }
        }

        if ($debug) {
            // dump($referenceParams);
            dump($propertyMap);exit;
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
            if ($propertyParams['predictedClassName'] == $thisClassName) {
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

    /**
     * @var $checkBack - 2-szer hivjuk meg ezt a metodust az assemblePropertyMap() soran, 
     * 1: a lekerdezett entity-vel, 2: a target entity-vel. A $checkBack = true a target entity-s hívás.
    */
    public function detectReferenceParams($targetEntity, $searchedProperty = null, $checkBack = false, $debug = false)
    {
        $dbm = $this->getContainer()->getKernelObject('DbManager');
        if ($dbm->getErrorMessage()) {
            return false;
        }
        // dump($this);
        $targetTableName = $targetEntity->getRepository()->getTableName();
        $thisTableName = $this->getRepository()->getTableName();
        $targetFields = $this->getDbManager()->getFieldAttributes($targetTableName);
        $thisFields = $this->getDbManager()->getFieldAttributes($thisTableName);
        $targetIdField = null;
        $referencedIdField = null;
        $fieldNamedId = null;

        $searchedFieldNames = [];
        if ($searchedProperty) {
            $searchedFieldNames[] = BasicUtils::camelToSnakeCase($searchedProperty);
            $searchedFieldNames[] = BasicUtils::camelToSnakeCase($searchedProperty).'_id';
        }

        // dump($targetFields);exit;
        // if ($debug) {
        //     dump('XXXXXXXXXXXXXXXXXXXXXXXXX');
        //     dump($targetEntity);
        //     dump($searchedProperty);
        //     dump($searchedFieldNames);
        //     dump($targetFields);
        //     dump($thisFields);
        // }

        foreach ($targetFields as $targetField) {
            if ($targetField['Field'] == 'id') {
                $fieldNamedId = $targetField['Field'];
            }
            if ($targetField['Key'] == 'PRI') {
                $targetIdField = $targetField['Field'];
            }
        }

        foreach ($thisFields as $thisField) {
            if ((!$searchedProperty && $thisField['Field'] == $targetTableName.'_id') || ($searchedProperty && in_array($thisField['Field'], $searchedFieldNames)) ) {
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

    // public function detectReferenceParams($targetEntity, $searchedProperty = null, $debug = false)
    // {
    //     $dbm = $this->getContainer()->getKernelObject('DbManager');
    //     if ($dbm->getErrorMessage()) {
    //         return false;
    //     }
    //     // dump($this);
    //     $targetTableName = $targetEntity->getRepository()->getTableName();
    //     $thisTableName = $this->getRepository()->getTableName();
    //     $targetFields = $this->getDbManager()->getFieldAttributes($targetTableName);
    //     $thisFields = $this->getDbManager()->getFieldAttributes($thisTableName);
    //     $targetIdField = null;
    //     $referencedIdField = null;
    //     $fieldNamedId = null;
    //     // dump($targetFields);exit;
    //     if ($debug) {
    //         dump('XXXXXXXXXXXXXXXXXXXXXXXXX');
    //         dump($searchedProperty);
    //         dump($targetEntity);
    //         dump($targetFields);
    //         dump($thisFields);
    //     }

    //     foreach ($targetFields as $targetField) {
    //         if ($targetField['Field'] == 'id') {
    //             $fieldNamedId = $targetField['Field'];
    //         }
    //         if ($targetField['Key'] == 'PRI') {
    //             $targetIdField = $targetField['Field'];
    //         }
    //     }

    //     foreach ($thisFields as $thisField) {
    //         if ($thisField['Field'] == $targetTableName.'_id') {
    //             $referencedIdField = $thisField['Field'];
    //         }
    //     }

    //     if ($debug) {
    //         dump('YYYYY');
    //     }

    //     $targetIdField = $targetIdField
    //         ? $targetIdField
    //         : ($fieldNamedId
    //             ? $fieldNamedId
    //             : (count($targetFields) > 0 ? $targetFields[0]['Field'] : null));
    //     return array(
    //         'targetIdField' => $referencedIdField ? $targetIdField : null,
    //         'referencedIdField' => $referencedIdField
    //     );
    // }

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
