<?php
namespace framework\component\parent;

use framework\component\parent\Repository;
use framework\kernel\utility\BasicUtils;
use framework\kernel\base\Reflector;
use framework\component\exception\ElastiException;
use framework\component\helper\StringHelper;

class DynamicDbRepository extends Repository
{
    protected $entity;
    protected $connectionId = 'default';

    public function __construct()
    {

    }

    public function isActive() : bool
    {
        return $this->createNewEntity()->isActive();
    }

    public function isDeletable($id)
    {
        return true;
    }

    public function cleanUpOrphans()
    {
        return false;
    }

    public function getRepositoryType()
    {
        return 'database';
    }

    public function getEntity()
    {
        if (!$this->entity) {
            $this->entity = $this->createNewEntity();
        }
        return $this->entity;
    }

    public function getPrimaryKeyField()
    {
        $autoIncremented = null;
        $first = null;
        $hasFieldCalledId = false;
        foreach ($this->getFieldAttributes() as $tableFieldParams) {
            if ($tableFieldParams['Field'] == 'id') {
                $hasFieldCalledId = true;
            }
            if (!$first) {
                $first = $tableFieldParams['Field'];
            }
            if (isset($tableFieldParams['Extra']) && $tableFieldParams['Extra'] == 'auto_increment') {
                $autoIncremented = $tableFieldParams['Field'];
            }
        }
        return $autoIncremented ? $autoIncremented : ($hasFieldCalledId ? 'id' : $first);
    }

    public function setConnectionId($connectionId)
    {
        $this->connectionId = $connectionId;
    }

    public function getConnectionId()
    {
        return $this->connectionId;
    }

    public function findOneBy($filter = array())
    {
        $filter['maxResults'] = 1;
        $found = $this->findBy($filter);
        // if (count($found) != 1) {
        //     dump($filter);
        //     dump($found);
        // }
        return count($found) == 1 ? $found[0] : null;
    }

    public function getTotalCount($filter)
    {
        return $this->findBy($filter, 'count');
    }

    public function checkFilter($filter)
    {
        if (is_array($filter)) {
            foreach ($filter as $key => $value) {
                if ($value === null) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    // public function getFilteredResult($filter = null, $options = null)
    // {
        
    // }

    public function findAll()
    {
        return $this->findBy(null, false, 'result');
    }

    public function findBy($filter = null, $limit = false, $queryType = 'result')
    {
        // dump($this); dump($filter);
        $findBy = $this->getEntityManager()->findBy($this, $filter, $limit, $queryType);
        // dump($filter);//exit;
        // if ($queryType == 'result') {
        //     dump($findBy);exit;
        // }
        return $findBy;
    }

	public function makeEntityFromRecordData($recordData, $entity = null)
    {
        // dump($recordData);
        if (!$recordData || (is_array($recordData) && count($recordData) == 0)) {
            return null;
        }

        $entity = $entity ? $entity : $this->createNewEntity();
        $idFieldName = $entity->getIdFieldName();
        $recordDataIdValue = null;
        foreach ($recordData as $fieldName => $value) {
            if ($fieldName == $idFieldName) {
                $recordDataIdValue = $value;
            }
        }
        // dump($recordData);
        // dump($recordDataIdValue);

        if ($recordDataIdValue) {
            foreach ($recordData as $fieldName => $value) {
                $propertyCode = BasicUtils::snakeToCamelCase($fieldName);
                $entity->set($propertyCode, $value);
            }
        }
        // dump($entity);
        return $entity;
    }

    public function getTableFieldNames()
    {
// dump('getTableFieldNames');
        $tableFieldNames = array();
        $entityAttributes = $this->getEntity()->getEntityAttributes();
        
        if (!isset($entityAttributes['propertyMap'])) {
            // dump($this);
            // dump($entityAttributes);exit;
            return false;
        }
        foreach ($entityAttributes['propertyMap'] as $propertyAttributes) {
            if (!$propertyAttributes['multiple'] && !$propertyAttributes['isObject'] && !$propertyAttributes['technical']) {
                $tableFieldNames[] = BasicUtils::camelToSnakeCase($propertyAttributes['singularPropertyName']);
            }
        }
// dump($tableFieldNames);
        return $tableFieldNames;
    }

    public function createSelectQuery($filter, $limit, $queryType)
    {
        // dump($filter);
        $tableFieldNames = $this->getTableFieldNames();

        $params = array();
        $findCounter = 0;
        $whereStr = '';
        foreach ($filter as $findProp => $findValue) {
            $operator = '=';
            if (is_array($findValue) && count($findValue) == 2) {
                $operator = $findValue[0];
                $findValue = $findValue[1];
            }
            if (!is_array($findValue)) {
                $findValue = trim($findValue);
            }
            $likeMarkPos = StringHelper::getAllOccurrencies($findValue, '%');
            $like = $likeMarkPos === false ? false : true;
            if ($findValue !== null) {
                $whereAnd = $findCounter > 0 ? " AND " : " ";
                if (is_array($findValue)) {
                    $paramNameArray = array();
                    for ($i = 0; $i < count($findValue); $i++) {
                        $paramName = ":".$findProp.$i;
                        $paramNameArray[] = $paramName;
                        $params[$paramName] = $findValue[$i];
                    }
                    $whereStr .= $whereAnd.$findProp." IN (".implode(',', $paramNameArray).")";
                } else {
                    if ($findValue == '*null*') {
                        $whereStr .= $whereAnd.$findProp." is null ";
                    } else {
                        $whereStr .= $whereAnd.$findProp." ".($like ? "LIKE" : $operator)." :".$findProp;
                        $params[$findProp] = $findValue;
                    }
                }
                $findCounter++;
            } else {
                return false;
            }
        }
        # Ez azer van benne, hogy ha nincs tabla, akkor egyertelmu exception-t dobjon.
        $select = $tableFieldNames ? "SELECT ".($queryType == 'result' ? implode(', ', $tableFieldNames) : ' count(*) as count') : "SELECT *";
        $statement = $select."
                FROM ".$this->getTableName()
                .($whereStr != '' ? "\n WHERE ".$whereStr : '').($limit ? " LIMIT {$limit} " : "");
        // dump($statement);
        // dump($params);
        return array(
            'statement' => $statement,
            'params' => $params
        );
    }

    public function getIdByBindingTable($otherEntity, $targetReferencedIdField, $registering)
    {
        $thisTableName = $this->getTableName();
        $otherTableName = $otherEntity->getRepository()->getTableName();

        $statement = "SELECT a.id
        FROM ".$thisTableName." a
        INNER JOIN ".$otherTableName." b
            ON ".($registering == 'other' ? 'b' : 'a').".".$targetReferencedIdField."
            = ".($registering == 'other' ? 'a' : 'b').".id AND b.id = :otherId
        ";

        $params = array(':otherId' => $otherEntity->getId());
        $rawIds = $this->getDbManager()->findAll($statement, $params);
        $ids = array();
        foreach ($rawIds as $rawId) {
            $ids[] = $rawId['id'];
        }
        return $ids;
    }

    // public function getChildIds($parentEntity, $parentReferencedThisId)
    // {
    //     $statement = "SELECT a.id
    //                 FROM ".$this->getTableName()." a
    //                 INNER JOIN ".$parentEntity->getRepository()->getTableName()." p
    //                     ON p.".$parentReferencedThisId." = a.id
    //                     AND p.id = :parentId
    //                 ";
    //     $params = array(':parentId' => $parentEntity->getId());
    //     $rawIds = $this->getDbManager()->findAll($statement, $params);
    //     $ids = array();
    //     foreach ($rawIds as $rawId) {
    //         $ids[] = $rawId['id'];
    //     }
    //     dump($parentEntity);
    //     dump($statement);
    //     dump($params);
    //     dump($ids);exit;
    //     return $ids;
    // }

    public function createEmptyRecordData()
    {
        foreach ($this->getTableFieldNames() as $tableFieldName) {
            $recordData[0][$tableFieldName] = null;
        }
        return $recordData;
    }

    public function collectRecordData($filter, $limit = null, $queryType = 'result', $forceCollection = false, $debug = false)
    {
        // dump($filter);
        $query = $this->createSelectQuery($filter, $limit, $queryType);
        // dump($filter); dump($query); //exit;
        return !$query ? array() : $this->getDbManager()->findAll($query['statement'], $query['params']);
    }

    public function store($entity)
    {
        return $this->getEntityManager()->store($entity);
    }

    public function updateRecord($fieldValues, $idFieldName = 'id')
    {
        // dump($fieldValues);exit;
        $testEntity = $this->createNewEntity();
        $idFieldName = $testEntity->getIdFieldName();
        $statement = "UPDATE ".$this->getTableName()." SET ".$this->getUpdateFieldList($fieldValues, $idFieldName)."
                      WHERE ".$idFieldName." = :".$idFieldName;
        $params = $this->getStoreParamArray($fieldValues, $idFieldName);
        $params[$idFieldName] = $fieldValues[$idFieldName];
        // dump($statement);dump($params);//exit;
        $result = $this->getDbManager()->execute($statement, $params);
        // dump($result);exit;
        return $fieldValues[$idFieldName];
    }

    public function getUpdateFieldList($fieldValues, $idFieldName)
    {
        $result = '';
        // $counter = 0;
        foreach ($fieldValues as $fieldName => $fieldValue) {
            if ($fieldName != $idFieldName) {
                $updateFieldListElement = BasicUtils::camelToSnakeCase($fieldName).' = :'.$fieldName;
                $result .= $result == '' ? $updateFieldListElement : ', '.$updateFieldListElement;
            }
        }
        return $result;
    }

    public function insertRecord($fieldValues, $parentEntity = null)
    {
        // dump($this->createNewEntity());
        // dump($fieldValues);exit;
        $testEntity = $this->createNewEntity();
        $idFieldName = $testEntity->getIdFieldName();
        // dump($fieldValues);
        $statement = "INSERT INTO ".$this->getTableName()." ".$this->getInsertFieldList($fieldValues, $idFieldName)."
                      VALUES ".$this->getInsertFieldList($fieldValues, $idFieldName, true);
        $params = $this->getStoreParamArray($fieldValues, $idFieldName);
        // dump($statement);
        // dump($params);
        // exit;
        return $this->getDbManager()->execute($statement, $params);
    }

    public function getInsertFieldList($fieldValues, $idFieldName, $forParamToken = false)
    {
        $result = '(';
        foreach ($fieldValues as $fieldName => $fieldValue) {
            if ($fieldName != $idFieldName) {
                $fieldNameStr = $forParamToken ? ' :'.$fieldName.' ' : BasicUtils::camelToSnakeCase($fieldName);
                $result .= $result == '(' ? $fieldNameStr : ','.$fieldNameStr;
            }
        }
        $result .= ')';
        return $result;
    }

    public function getStoreParamArray($fieldValues, $idFieldName)
    {
        $result = array();
        foreach ($fieldValues as $fieldName => $fieldValue) {
            if ($fieldName != $idFieldName) {
                $result[$fieldName] = $fieldValue;
            }
        }
        return $result;
    }

    public function sortBy($array)
    {

    }

    public function remove($id)
    {
        if (!$id) {
            return false;
        }
        $blankEntity = $this->createNewEntity();
        $stm = "DELETE FROM ".$this->getTableName()." WHERE ".$blankEntity->getIdFieldName()." = :id ";
        $params['id'] = $id;
        $dbm = $this->getDbManager();
        $dbm->execute($stm, $params);
    }

    public function removeBy($array, $child = false)
    {
        $entities = $this->findBy($array);
        if (!is_array($entities) || count($entities) == 0 || (!$this->cleanUpOrphans() && $child)) {
            return false;
        }
        $propertyMap = $entities[0]->getPropertyMap();
        foreach ($entities as $entity) {
            foreach ($propertyMap as $propertyCode => $propertyAttributes) {
                if ($propertyAttributes['isObject']) {
                    $getter = $propertyAttributes['getter'];
                    $targetEntities = $entity->$getter();
                    if (is_array($targetEntities)) {
                        foreach ($targetEntities as $targetEntity) {
                            $targetEntity->getRepository()->removeBy(array($targetEntity->getIdFieldName() => $targetEntity->getIdValue()));
                        }
                    }
                }
            }
            $this->remove($entity->getIdValue());
        }
        return true;
    }

    public function getFieldAttributes()
    {
        $dbm = $this->getDbManager();
        $repoData = $this->getRepositoryData();
        $tableName = BasicUtils::camelToSnakeCase($repoData['entityClassName']);
        return $dbm->getFieldAttributes($tableName);
    }

    public function getTableName()
    {
        $repoData = $this->getRepositoryData();
        return BasicUtils::camelToSnakeCase($repoData['entityClassName']);
    }

    public function getEntityName()
    {
        return ucfirst(BasicUtils::snakeToCamelCase($this->getTableName()));
    }

    public function createNewEntity()
    {
        // $dbm = $this->getDbManager();
        $repoData = $this->getRepositoryData();
        // dump($repoData);
        $this->getContainer()->wireService($repoData['entityPath']);
        $entityClass = $repoData['entityClass'];

        return new $entityClass();
    }

    // protected function update(object $entity)
    // {
    //     $setStr = '';
    //     $counter = 0;
    //     $reflector = new Reflector();
    //     $propertiesArray = $reflector->getProperties($entity);
    //     $tableFields = $this->getTableFields();
    //     foreach ($propertiesArray as $reflectionProperty) {
    //         $property = $reflectionProperty->getName();
    //         $getter = 'get'.ucfirst($property);
    //         $value = $entity->$getter();
    //         if (!is_object($value)) {
    //             $fieldName = BasicUtils::camelToSnakeCase($property);
    //             $setStr .= ($counter == 0 ? ' ' : ' , ').$fieldName.' = :'.$fieldName;
    //             $params[$fieldName] = $value;
    //             $counter++;
    //         } else {
    //             $fieldNameSupposition = BasicUtils::camelToSnakeCase($property).'_id';
    //             foreach ($tableFields as $tableField) {
    //                 if ($tableField['Field'] == $fieldNameSupposition) {
    //                     $setStr .= ($counter == 0 ? ' ' : ' , ').$tableField['Field'].' = :'.$tableField['Field'];
    //                     $params[$tableField['Field']] = $value->getId();
    //                     $counter++;
    //                 }
    //             }
    //         }
    //     }

    //     $stm = "UPDATE ".$this->getTableName()." SET ".$setStr." WHERE id = :id ";
    //     $params['id'] = $entity->getId();
    //     $dbm = $this->getDbManager();
    //     $dbm->execute($stm, $params);
    // }
}
