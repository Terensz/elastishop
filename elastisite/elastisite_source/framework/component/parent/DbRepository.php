<?php
namespace framework\component\parent;

use App;
use framework\component\parent\Repository;
use framework\kernel\utility\BasicUtils;
use framework\kernel\base\Reflector;
use framework\component\exception\ElastiException;
use framework\component\helper\StringHelper;
use framework\kernel\DbManager\StatementAnalyzer;

class DbRepository extends Repository
{
    protected $entity;
    protected $connectionId = 'default';

    public function __construct()
    {

    }

    public static function objectToArray($object, $reflector = null)
    {
        if (!$reflector) {
            $reflector = new Reflector();
        }
        $properties = $reflector->getPredefinedPropertyNames($object);
        $propertyArray = [];
        foreach ($properties as $property) {
            $value = $object->get($property);
            if (is_object($value)) {
                $value = self::objectToArray($value, $reflector);
            }
            $propertyArray[$property] = $value;
        }

        return $propertyArray;
    }

    public static function objectArrayToArray($objectArray)
    {
        if (!is_array($objectArray)) {
            return null;
        }
        $returnArray = [];
        foreach ($objectArray as $object) {
            $propertyArray = self::objectToArray($object);
            $returnArray[] = $propertyArray;
        }

        return $returnArray;
    }

    public function findFirst()
    {
        $entity = $this->createNewEntity();
        $stm = "SELECT ".$entity->getIdFieldName()." as id FROM ".$this->getTableName()." LIMIT 1 ";
        $dbm = $this->getDbManager();
        $idRes = $dbm->findOne($stm);
        $id = $idRes ? $idRes['id'] : null;
        if ($id && $id > 0) {
            return $this->find($id);
        }
        
        return null;
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

            // if (!isset($tableFieldParams['Field'])) {
            //     dump('getPrimaryKeyField!!');
            //     dump($this->getFieldAttributes());
            // }

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

    public function findOneBy($filter = array(), $debug = false)
    {
        $filter['maxResults'] = 1;
        // dump('findOneBy!!!');dump($filter);
        $found = $this->findBy($filter, 'result', $debug);
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

    // public function mendFilter($filter)
    // {
    //     // dump($filter);//exit;
    //     $result = array();
    //     if (is_array($filter)) {
    //         foreach ($filter as $key => $value) {
    //             if ($value !== null) {
    //                 $result[$key] = $value;
    //                 // unset($filter[$key]);
    //             }
    //             // if ($value === '*null*') {
    //             //     $result[$key] = null;
    //             // }
    //         }
    //         // dump($result);exit;
    //         return $result;
    //     } else {
    //         return array();
    //     }
    // }

    /**
     * @var filter: 
     *  - conditions
     *  - maxResults
     *  - orderBy
     *  - currentPage
    */
    public function getFilteredResult($filter = null, $options = null)
    {
        // dump($filter);
        // $repo = get_class($this);
        // dump($repo);exit;
		// if (get_class($repo) == 'framework\packages\VisitorPackage\repository\VisitRepository') {
		// 	dump(get_class($repo)); dump($filter);exit;
		// }

        $filter = $this->transformFilter($filter);
        // dump($filter); dump($options); exit;  //dump($this->mendFilter($filter)); //exit;
        $queryType = isset($options['queryType']) ? $options['queryType'] : 'result';
        $page = isset($filter['currentPage']) ? $filter['currentPage'] : 1;
        $gridMaxResults = $this->getProjectData('gridMaxResults');
        $maxResults = isset($filter['maxResults']) ? $filter['maxResults'] : ($gridMaxResults ? $gridMaxResults : 10);
        $pageFirstIndex = (($page - 1) * $maxResults);
        // $filter = $this->mendFilter($filter);
        $filter['limitStr'] = "{$pageFirstIndex}, {$maxResults}";
        $filter['orderBy'] = isset($options['orderBy']) ? $options['orderBy'] : (isset($filter['orderBy']) ? $filter['orderBy'] : null);
        $result = $this->findBy($filter, $queryType);
        //dump($filter);dump($result);exit;
        return $result;
    }

    public function findAll()
    {
        return $this->findBy(null, 'result');
    }

    public function findBy($filter = null, $queryType = 'result', $debug = false)
    {
        // dump('findBy debug:');
        // dump($debug);
        // dump($filter);
        $filter = $this->transformFilter($filter);
        // if ($debug) {
        //     dump($this); dump($filter);
        // }
        $findBy = $this->getEntityManager()->findBy($this, $filter, $queryType, $debug);
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
            if (!isset($propertyAttributes['technical'])) {
                $propertyAttributes['technical'] = false;
            }
            if (!$propertyAttributes['multiple'] && !$propertyAttributes['isObject'] && !$propertyAttributes['technical']) {
                $tableFieldNames[] = BasicUtils::camelToSnakeCase($propertyAttributes['singularPropertyName']);
            }
        }
// dump($tableFieldNames);
        return $tableFieldNames;
    }

    public function getGridDataFilteredQuery($filter)
    {
        $whereClause = $this->createWhereClauseFromFilter($filter && isset($filter['conditions']) ? $filter['conditions'] : null);
        return array(
            'statement' => "SELECT * FROM ".$this->getTableName()." maintable ".$whereClause['whereStr'],
            'params' => $whereClause['params'],
            'filteredQueryClassSource' => 'parent'
        );
    }

    public function getGridDataQuery($filter)
    {
        $filteredQuery = $this->getGridDataFilteredQuery($filter);
        // dump($filteredQuery);
        $table0Pos = strpos($filteredQuery['statement'], ' table0');
        if ($table0Pos === false) {
            $table0Statement = "SELECT * FROM (".$filteredQuery['statement'].") table0";
        } else {
            $table0Statement = $filteredQuery['statement'];
        }
        
        return array(
            // 'countStatement' => "SELECT count(*) as count FROM (".$filteredQuery['statement'].") table2 ",
            'innerStatement' => $table0Statement,
            'statement' => "SELECT * FROM (".$filteredQuery['statement'].") table2 ".$filter['orderByStr'].$filter['limitStr'],
            'params' => $filteredQuery['params'],
            'filteredQueryClassSource' => (isset($filteredQuery['filteredQueryClassSource']) && $filteredQuery['filteredQueryClassSource'] == 'parent') ? 'parent' : 'child',
            'queryClassSource' => 'parent'
        );
    }

    public function getGridData($filter, $dataArrayRequired = true)
    {
        $filter = $this->transformFilter($filter);
        $query = $this->getGridDataQuery($filter);
        // dump($query);
        $dbm = $this->getDbManager();
        // $result = $dbm->findAll($query['statement'], $query['params']);
        $innerStatementParts = explode(' table0', $query['innerStatement']);
        $innerStatementBase = $innerStatementParts[0].' table0';

        $statementAnalyzer = new StatementAnalyzer($innerStatementBase);
        // $innerSelectStatements = $statementAnalyzer->getInnerSelectStatements();

        $mostInnerStatement = $statementAnalyzer->getMostInnerStatement();

        // $usedFieldNames = [];
        // if (count($innerSelectStatements) == 1) {
        //     $innerStatementBaseParts = explode('WHERE', $innerSelectStatements[0]);
        //     $innerStatementBase = $innerStatementBaseParts[0];
        // }

        // if (count($innerSelectStatements) < 2) {
        //     $usedFieldNames = $this->getUsedFieldNames($innerStatementBase, $statementAnalyzer);
        // }

        $usedFieldNames = $this->getUsedFieldNames($mostInnerStatement, 'getGridData');

        $return = [
            //'columnParams' => $dbm->getColumnParams("SELECT * FROM (".$query['innerStatement'].") table2 LIMIT 0 "),
            // 'totalRowsCount' => $dbm->findOne($query['countStatement'], $query['params'])['count'],
            'dataArray' => ($dataArrayRequired ? $dbm->findAll($query['statement'], $query['params']) : null),
            'query' => $query,
            'usedFieldNames' => $usedFieldNames,
            'tableFieldNames' => $this->getTableFieldNames(),
            'filteredQueryClassSource' => (isset($query['filteredQueryClassSource']) && $query['filteredQueryClassSource'] == 'parent') ? 'parent' : 'child',
            'queryClassSource' => (isset($query['queryClassSource']) && $query['queryClassSource'] == 'parent') ? 'parent' : 'child'
        ];
        // var_dump($dataArrayRequired);
        if ($dataArrayRequired) {
            // var_dump($return);exit;
        }
        return $return;
    }

    public function getUsedFieldNames($statement, $from = 'dontknow')
    {
        // dump($from);
        // if (!$statementAnalyzer) {
        //     $statementAnalyzer = new StatementAnalyzer($statement);
        // }
        
        $statementAnalyzer = new StatementAnalyzer($statement);
        // $analyzerStatement = $statementAnalyzer->getAnalyzerStatement();
        $mostInnerStatement = $statementAnalyzer->getMostInnerStatement();
        $mostInnerStatementParts = explode(' where ', $mostInnerStatement);
        if (count($mostInnerStatementParts) > 1) {
            $mostInnerStatement = $mostInnerStatementParts[0];
            // dump($statementAnalyzer);
            // dump($statement);exit;
        }

        $result = [];
        $dbm = $this->getDbManager();
        $columnParams = $dbm->getColumnParams($mostInnerStatement);
        foreach ($columnParams as $columnParamRow) {
            $result[] = $columnParamRow['name'];
        }
        return $result;
    }

    public function getGridDataFieldUniqueValues($fieldName)
    {
        $dbm = $this->getDbManager();
        $query = $this->getGridDataFilteredQuery(null);
        $queryRes = $dbm->findAll("SELECT DISTINCT {$fieldName} FROM (".$query['statement'].") table2");
        $res = [];
        foreach ($queryRes as $row) {
            $res[] = $row[$fieldName];
        }
        return $res;
    }

    // public function getGridDataFieldUniqueValues($fieldName, $fieldId = null)
    // {
    //     $dbm = $this->getDbManager();
    //     $query = $this->getGridDataFilteredQuery(null);
    //     $stm = "SELECT DISTINCT ".($fieldId ? "{$fieldId} ," : "")." {$fieldName} FROM (".$query['statement'].") table2";
    //     $queryRes = $dbm->findAll($stm);
    //     $res = [];
    //     foreach ($queryRes as $row) {
    //         $res[] = [
    //             'fieldId' => ($fieldId ? $row[$fieldId] : null),
    //             'fieldName' => $row[$fieldName],
    //             'stm' => $stm 
    //         ];
    //     }
    //     return $res;
    // }

    public function getGridColumnParams()
    {
        $dbm = $this->getDbManager();
        $query = $this->getGridDataFilteredQuery(null);
        return $dbm->getColumnParams("SELECT * FROM (".$query['statement'].") table2 LIMIT 0 ");
    }

    public function createWhereClauseFromFilter($filterConditions, $debug = null)
    {
        //$findCounter = 0;
        $whereStr = "";
        $params = array();
        $dataIndex = 0;
        if ($filterConditions && is_array($filterConditions)) {
            $conditionCounter = 0;
            foreach ($filterConditions as $filterCondition) {
                $findProp = $filterCondition['key'];
                $findValue = $filterCondition['value'];
                $operator = isset($filterCondition['operator']) ? $filterCondition['operator'] : '=';

                if ($conditionCounter > 0) {
                    $whereStr .= " AND ";
                }

                $whereStr .= $findProp;

                if (!is_array($findValue)) {
                    $findValues = array($findValue);
                }

                /**
                 * We make array of the searched value anyway. This will be: $findValues.
                */
                $findValues = !is_array($findValue) ? array($findValue) : $findValue;
                $paramNameArray = array();
                $aValueContainsLikeMark = false;
                for ($i = 0; $i < count($findValues); $i++) {
                    /**
                     * From this point: $findValue CANNOT be array!
                    */
                    //dump($findValues[$i]);
                    // if (!isset($findValues[$i])) {
                    //     dump($filterConditions);
                    // }
                    // if (!isset($findValues[$i])) {
                    //     // dump($filterConditions);
                    //     dump($findValues);
                    //     dump($i);
                    //     // exit;
                    // }
                    $findValue = $findValues[$i];
                    $findValue = $findValue ? trim($findValue) : $findValue;

                    $likeMarkPos = StringHelper::getAllOccurrencies($findValue, '%');
                    // $like = $likeMarkPos === array() ? false : true;
                    $like = false;
                    if ($likeMarkPos != array()) {
                        $like = true;
                        $aValueContainsLikeMark = true;
                    }
                    if (!$findProp) {
                        dump($filterConditions);
                        dump($this);
                    }
                    $paramName = ":p".$dataIndex.'_'.str_replace('.', '_', $findProp);
                    $paramNameArray[] = $paramName;
                    if (!in_array($findValue, array(null, '*null*'))) {
                        $params[$paramName] = $findValue;
                    }
                    $findValues[$i] = $findValue;
                    $dataIndex++;
                }

                if (count($findValues) == 1) {
                    $loopWhereStrAdd = '';
                    if ($findValue !== '' && $findValue !== 0 && in_array($findValue, array(null, '*null*'))) {

                        // if ($debug == 'VisitRepository') {
                        //     dump($findValue);
                        //     dump($like);
                        //     // dump($return);
                        // }
                        // dump('$findValue:');
                        // dump($findValue);
                        $whereStr .= " IS NULL ";
                    } else {
                        if ($operator == '<>') {
                            $loopWhereStrAdd = " <> ".$paramName;
                        } else {
                            $loopWhereStrAdd = ($like ? " LIKE " : " ".$operator)." ".$paramName;
                        }

                        $whereStr .= $loopWhereStrAdd;
                        $params[$paramName] = $findValue;

                        // if ($debug == 'VisitRepository' && $findValue === '') {
                        //     dump($findValue);
                        //     dump($loopWhereStrAdd);
                        //     dump($whereStr);
                        // }
                    }


                } else {
                    if (!$aValueContainsLikeMark) {
                        $whereStr .= " IN (".implode(',', $paramNameArray).")";
                    } else {
                        /**
                         * @todo: when value is multiple, and contains LIKE-mark
                        */
                    }
                }

                $conditionCounter++;
            }
        }
        $whereStr = $whereStr == "" ? "" : " WHERE ".$whereStr;
        //dump($whereStr);
        $return = array(
            'params' => $params,
            'whereStr' => $whereStr
        );

        // if ($debug == 'VisitRepository') {
        //     dump($filterConditions);
        //     dump($return); exit;
        // }

        return $return;
    }

    // public function createWhereClauseFromFilter_OLD($filterConditions)
    // {
    //     $findCounter = 0;
    //     $whereStr = '';
    //     $params = array();
    //     if ($filterConditions && is_array($filterConditions)) {
    //         foreach ($filterConditions as $filterCondition) {
    //             $findProp = $filterCondition['key'];
    //             $findValue = $filterCondition['value'];
    //             $operator = isset($filterCondition['operator']) ? $filterCondition['operator'] : '=';
    //             if (is_array($findValue) && count($findValue) == 2) {
    //                 $operator = $findValue[0];
    //                 $findValue = $findValue[1];
    //             }
    //             if (!is_array($findValue)) {
    //                 $findValue = trim($findValue);
    //             }

    //             $likeMarkPos = StringHelper::getAllOccurrencies($findValue, '%');
    //             $like = $likeMarkPos === array() ? false : true;
    //             $paramName = ":".str_replace('.', '_', $findProp);
    //             if ($findValue !== null) {
    //                 $whereAnd = $findCounter > 0 ? " AND " : " ";
    //                 if (is_array($findValue)) {
    //                     $paramNameArray = array();
    //                     for ($i = 0; $i < count($findValue); $i++) {
    //                         $paramName = ":".str_replace('.', '_', $findProp.$i);
    //                         $paramNameArray[] = $paramName;
    //                         $params[$paramName] = $findValue[$i];
    //                     }
    //                     $whereStr .= $whereAnd.$findProp." IN (".implode(',', $paramNameArray).") ";
    //                 } else {
    //                     if ($findValue == '*null*') {
    //                         $whereStr .= $whereAnd.$findProp." IS NULL ";
    //                     } else {
    //                         $whereStr .= $whereAnd.$findProp." ".($like ? " LIKE " : $operator)." ".$paramName;
    //                         $params[$paramName] = $findValue;
    //                     }
    //                 }
    //                 $findCounter++;
    //             } else {
    //                 return false;
    //             }
    //         }
    //     }
    //     $whereStr = $whereStr == '' ? '' : " WHERE ".$whereStr;
    //     return array(
    //         'params' => $params,
    //         'whereStr' => $whereStr
    //     );
    // }

    public function createSelectQuery(array $filter = null, $queryType, $debug = null)
    {
        $filterConditions = isset($filter['conditions']) ? $filter['conditions'] : null;
        $filterOrderBy = isset($filter['orderBy']) ? $filter['orderBy'] : null;
        $filterLimitStr = isset($filter['limitStr']) ? $filter['limitStr'] : null;
        $entity = $this->createNewEntity();
        //dump($filter); 

        $tableFieldNames = $this->getTableFieldNames();
        $selectedFields = array();
        $joinTablesStr = '';
        $joinedTables = array();
        $orderByStr = '';
        $orderByCounter = 0;
        $outerSelectedFields = array();

        foreach ($tableFieldNames as $tableFieldName) {
            $selectedField = 'maintable.'.$tableFieldName.' AS maintable_'.$tableFieldName;
            $selectedFields[] = $selectedField;
            $outerSelectedFields[] = 'maintable_'.$tableFieldName.' AS '.$tableFieldName;
        }

        if (is_array($filterOrderBy)) {
            $orderByStr = "\n ORDER BY ";
            foreach ($filterOrderBy as $filterOrderByRow) {
                $orderByStr .= ($orderByCounter > 0 ? " , " : "");
                $dotPos = strpos($filterOrderByRow['field'], '.');
                if ($dotPos === false) {
                    $orderByStr .= $filterOrderByRow['field']." ".$filterOrderByRow['direction'];
                } else {
                    /**
                     * In this case, the orderBy string is in a joined table.
                    */
                    $orderTemp = explode('.', $filterOrderByRow['field']);
                    $joinedPropertyName = $orderTemp[0];
                    $joinedFieldName = $orderTemp[1];
                    // dump($entity);exit;
                    $propertyMapElement = $entity->getPropertyMap()[$joinedPropertyName];
                    $joinedClassName = $propertyMapElement['targetRelation']['targetClass'];
                    $relation = $this->getEntityManager()->getERM()->processRelationDetails($entity->getClassName(), $joinedClassName, $propertyMapElement['targetRelation'], $propertyMapElement['reverseRelation']);
                    $joinedTableAlias = 'joined'.count($joinedTables);
                    $selectedFields[] = $joinedTableAlias.'.'.$joinedFieldName;

                    //dump($relation);

                    $joinTablesStr .= "
                    LEFT JOIN ".BasicUtils::camelToSnakeCase($joinedPropertyName)." ".$joinedTableAlias." ON "
                    .($relation['referenceContainerTable'] == 'this' 
                        ? 'maintable.'.$relation['referencedIdField'].' = '.$joinedTableAlias.'.'.$relation['targetIdField'] 
                        : $joinedTableAlias.'.'.$relation['referencedIdField'].' = maintable.'.$relation['targetIdField']
                    );
                    $orderByStr .= $joinedTableAlias.".".$joinedFieldName." ".$filterOrderByRow['direction'];

                    $joinedTables[$joinedPropertyName] = [
                        'relation' => $relation,
                        'alias' => $joinedTableAlias
                    ];
                    // dump($joinedTables['referenceContainerTable']);
                    // dump($selectedFields);
                }

                $orderByCounter++;
            }

            //dump($orderByStr);exit;
        }
        
        $whereClause = $this->createWhereClauseFromFilter($filterConditions, $debug);
        $params = $whereClause['params'];
        $whereStr = $whereClause['whereStr'];

        # Ez azer van benne, hogy ha nincs tabla, akkor egyertelmu exception-t dobjon.
        $select = $tableFieldNames ? "SELECT ".($queryType == 'result' ? implode(', ', $selectedFields) : ' count(*) as count') : "SELECT * ";

        // ." ".BasicUtils::snakeToCamelCase($this->getTableName())
        $innerStatement = $select."
                FROM ".$this->getTableName()." maintable "
                .$joinTablesStr
                .($whereStr != '' ? "\n ".$whereStr : '')." ".$filterLimitStr;
        //dump($filter); dump($statement); dump($params); //exit;

        // $statement = count($joinedTables) > 0 ? "SELECT ".implode(', ', $outerSelectedFields)." FROM (".$innerStatement.") table2 " : $innerStatement;
        $statement = "SELECT ".implode(', ', $outerSelectedFields)." FROM (".$innerStatement.") table2 ".($orderByStr);

        // if (count($joinedTables) > 0) {
        //     dump($filter); 
        //     dump($joinedTables);
        //     dump($statement);
        // }
        //dump($statement);

        $return = array(
            'statement' => $statement,
            'params' => $params
        );

        // if (isset($filter['orderBy'])) {
        //     dump($return);exit;
        // }

        return $return;
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

    // abstract public function collectRecordData($filter, $queryType = 'result', $forceCollection = false, $debug = false);
    public function collectRecordData($filter, $queryType = 'result', $forceCollection = false, $debug = false)
    {
        // dump($debug);
        $query = $this->createSelectQuery($filter, $queryType, $debug);
        // $query['statement'] = nl2br($query['statement']);
        if ($debug) {
            dump($query);
        }
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

    public function removeAll($truncate = false)
    {
        $dbm = $this->getDbManager();
        $stm1 = "SELECT count(*) as 'found_tables'
        FROM information_schema.tables
        WHERE table_schema = '".$this->getContainer()->getDbConnection()->getName()."' 
            AND table_name = '".$this->getTableName()."'
        LIMIT 1";
        $foundTables = $dbm->findOne($stm1, array())['found_tables'];
        if ($foundTables == 1) {
            $stm2 = $truncate ? "TRUNCATE TABLE ".$this->getTableName() : "DELETE FROM ".$this->getTableName();
            $dbm->execute($stm2, array());
        }
    }

    public function remove($id)
    {
        // dump($this->isDeletable($id));
        if (!$id || ($id && !$this->isDeletable($id))) {
            return false;
        }
        $blankEntity = $this->createNewEntity();
        $params = [];
        $whereWebsiteAdd = '';
        if (property_exists($blankEntity, 'getWebsite')) {
            $whereWebsiteAdd = " AND website = :website ";
            $params['website'] = App::getWebsite();
        }
        $stm = "DELETE FROM ".$this->getTableName()." WHERE ".$blankEntity->getIdFieldName()." = :id ".$whereWebsiteAdd;
        $params['id'] = $id;
        // dump($stm);
        $dbm = $this->getDbManager();
        $dbm->execute($stm, $params);
    }

    public function removeBy($filter, $child = false)
    {
        $filter = $this->transformFilter($filter);
        $entities = $this->findBy($filter);
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

    public function find_iterate_do($action, $fieldName = null, $value = null)
    {
        $result = [];
        $dbm = $this->getDbManager();
        if ($fieldName && $fieldName) {
            $stm = "SELECT id FROM ".$this->getTableName()." WHERE ".$fieldName." = :value ";
            $ret = $dbm->findAll($stm, ['value' => $value]);
        } else {
            $stm = "SELECT id FROM ".$this->getTableName();
            $ret = $dbm->findAll($stm, []);
        }

        if (is_array($ret)) {
            foreach ($ret as $row) {
                if ($action == 'remove') {
                    $this->remove($row['id']);
                    // $dbm->execute("DELETE FROM ".$this->getTableName()." WHERE id = :id ", ['id' => $row['id']]);
                    $result[] = $row['id'];
                }
                if ($action == 'getIds') {
                    $result[] = $row['id'];
                }
            }
        }
        // return $result == [] ? null : $result;
        return $result;
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
