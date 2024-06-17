<?php
namespace framework\component\parent;

use framework\component\parent\Repository;
use framework\component\exception\ElastiException;
use framework\kernel\utility\FileHandler;
use framework\kernel\utility\BasicUtils;
use framework\kernel\base\Reflector;

class FileBasedStorageRepository extends Repository
{
    protected $entityClass;
    protected $filePath;
    protected $emulateAutoIncrement = 'id';
    protected $properties;
    protected $uniqueProperties = [];
    protected $orderBy;
    protected $permReqPropName = 'permission';
    protected $positionField = 'position';
    protected $encryptFile;

    public function __construct()
    {

    }

    public function isActive() : bool
    {
        return true;
    }

    public function isDeletable($id)
    {
        return true;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    public function initRepository($debug = false)
    {
        // dump('initRepository!!!!');exit;
        return false;
    }

    public function getGridData($filter, $dataArrayRequired = true)
    {
        $filter = $this->transformFilter($filter);
        $gridData = [];
        // $result = $dbm->findAll($query['statement'], $query['params']);
        foreach ($this->findBy($filter) as $object) {
            $gridDataRow = [];
            foreach ($this->properties as $prop) {
                $getter = 'get'.ucfirst($prop);
                $val = $object->$getter();
                $gridDataRow[BasicUtils::camelToSnakeCase($prop)] = $val;
            }
            $gridData[] = $gridDataRow;
        }
        return [
            //'columnParams' => $dbm->getColumnParams("SELECT * FROM (".$query['innerStatement'].") table2 LIMIT 0 "),
            // 'totalRowsCount' => $dbm->findOne($query['countStatement'], $query['params'])['count'],
            'dataArray' => $gridData,
            'query' => null,
            'tableFieldNames' => $this->properties,
            'filteredQueryClassSource' => null,
            'queryClassSource' => null
        ];
        // dump($query); exit;
    }

    public function getGridColumnParams()
    {
        return $this->getColumnParams();
    }

	public function getColumnParams()
	{
		$columnParams = [];
		foreach ($this->properties as $property) {
			$columnParams[] = [
				'name' => BasicUtils::camelToSnakeCase($property),
				'type' => 'string',
				'length' => 50
			];
		}
		return $columnParams;
	}

    public function getGridDataFieldUniqueValues($fieldName)
    {
        $queryRes = $this->getGridData(null);
        // dump($queryRes);
        $res = [];
        foreach ($queryRes['dataArray'] as $row) {
            $res[] = $row[$fieldName];
        }
        return $res;
    }

    public function getPrimaryKeyField()
    {
        return $this->emulateAutoIncrement ? $this->emulateAutoIncrement : 'id';
    }

    public function createNewEntity()
    {
        $entityClass = $this->entityClass ? $this->entityClass : $this->guessEntityClass();
        $entityPath = str_replace('\\', '/', $entityClass);
        $this->wireService($entityPath);

        return new $entityClass();
        // dump('createNewEntity');exit;
    }

    public function guessEntityClass()
    {
        $entityClass = str_replace('\\repository\\', '\\entity\\', static::class);
        // dump($entityClass);
        // $entityClass = trim($entityClass, 'Repository');
        $entityClass = substr($entityClass, 0, -(strlen('Repository')));
        // dump($entityClass);
        return $entityClass;
    }

    public function getRepositoryType()
    {
        return 'FBS';
    }

    public function getEntityName()
    {
        $childRepoName = BasicUtils::explodeAndGetElement(get_called_class(), '\\', 'last');
        $childEntityName = preg_replace('/Repository$/', '', $childRepoName);
        // dump($childEntityName);exit;
        return $childEntityName;
    }

    public function collectRecordData($filter, $queryType = 'result', $forceCollection = false, $debug = false)
    {
        // dump('collectRecordData');
        // dump($filter);
        // dump($queryType);
        // dump($forceCollection);
        // exit;

        return false;
    }

    public function callInitRepository($debug = false)
    {
        // if ($debug) {
        //     dump(get_class($this));
        // }
        // $repoClass = get_class($this);
        // $repo = new $repoClass();
        // $repo->initRepository($debug);
        $this->initRepository();

        // if (method_exists($this, 'initRepository')){
        //     // dump('ALMA+');
        //     $this->initRepository();
        //     // static::initRepository();
        // }
        // $this->initRepository();
    }

    public function createEmptyRecordData()
    {
        dump('createEmptyRecordData');exit;
    }

    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    public function setEmulateAutoIncrement($emulateAutoIncrement)
    {
        $this->emulateAutoIncrement = $emulateAutoIncrement;
    }

    public function getEmulateAutoIncrement()
    {
        return $this->emulateAutoIncrement;
    }

    public function setProperties($properties)
    {
        $this->properties = $properties;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function setUniqueProperties($uniqueProperties)
    {
        $this->uniqueProperties = $uniqueProperties;
    }

    public function getUniqueProperties()
    {
        return $this->uniqueProperties;
    }

    public function setEncryptFile($encryptFile)
    {
        $this->encryptFile = $encryptFile;
    }

    public function permissionGranted($entity)
    {
        $permReqGetter = 'get'.ucfirst($this->permReqPropName);
        // if ($entity instanceof \__PHP_Incomplete_Class) {
        //     $this->getContainer()->wireService(str_replace('\\', '/', $this->guessEntityClass()));
        //     $entity = unserialize(serialize($entity));
        //     // $this->getContainer()->setService('BackgroundPackage/entity/FBSBackgroundImage');
        //     // $a = $this->getContainer()->getService('FBSBackgroundImage');
        //     // dump(get_class($a));
        //     dump($entity);
        //     // dump(unserialize(serialize($entity)));
        //     // exit;
        // }
        if (method_exists($entity, $permReqGetter)) {
            if (!$this->getContainer()->isGranted($entity->$permReqGetter())) {
                return false;
            }
        }
        return true;
    }

    public function wireEntity()
    {
        $this->getContainer()->wireService(str_replace('\\', '/', $this->guessEntityClass()));
    }

    // public function alma($object)
    // {
    //     $arr = $this->getArrayColectionFromFile();
    //     dump($arr);
    //     foreach ($arr as $object) {
    //         if ($object instanceof \__PHP_Incomplete_Class) {
    //             dump(serialize($object));
    //         }
    //     }
    // }

    public function getFilteredResult($filter = array())
    {
        return $this->findBy($filter, 'result');
    }

    // public function getFilteredResult($filter = null, $options = null)
    // {
    //     $queryType = isset($options['queryType']) ? $options['queryType'] : 'result';
    //     $page = isset($options['page']) ? $options['page'] : 1;
    //     $gridMaxResult = $this->getProjectData('gridMaxResult');
    //     $limit = isset($options['limit']) ? $options['limit'] : ($gridMaxResult ? $gridMaxResult : 10);
    //     $pageFirstIndex = (($page - 1) * $limit);
    //     return $this->findBy($filter, "{$pageFirstIndex}, {$limit}", $queryType);
    // }

    public function findOneBy($filter = array())
    {
        // dump($filter);
        $filter['maxResults'] = 1;
        // dump($filter);
        return $this->findBy($filter, 'result');
    }

    public function findByInCollection($arrayCollection, $filter = null, $queryType = 'result')
    {
        // dump($arrayCollection);
        return $this->getFindByResult($filter, $queryType, $arrayCollection);
    }

    public function findBy($filter = null, $queryType = 'result')
    {
        return $this->getFindByResult($filter, $queryType, null);
    }

    public function getFindByResult($filter = null, $queryType = 'result', $arrayCollection = null, $round = 0)
    {
        // dump('getFindByResult START');
        if ($round >= 10) {
            dump($filter);exit;
        }
        // dump($filter);exit;
        $filterConditions = isset($filter['conditions']) ? $filter['conditions'] : null;
        $filterOrderBy = isset($filter['orderBy']) ? $filter['orderBy'] : null;
        $filterLimit = isset($filter['maxResults']) ? $filter['maxResults'] : null;

        if (!$this->filePath && !$arrayCollection) {
            // $this->callInitRepository();
        }

        if ($arrayCollection) {
            // dump($arrayCollection);
        }
        // $this->callInitRepository(true);
        // dump($filter);
        // dump('$this->filePath: '.$this->filePath);
        // if (array_keys($filter)[0] == 'id') {
        //     dump('$filter:');
        //     dump($filter);
        // }
        $return = [];
        if (!$arrayCollection) {
            $getArrayCollection = $this->getArrayColectionFromFile();
            $arrayCollection = !$getArrayCollection ? [] : $getArrayCollection;
        }


        // dump($filterConditions);

        for ($i = 0; $i < count($arrayCollection); $i++) {
            $entity = $arrayCollection[$i];
            if ($entity instanceof \__PHP_Incomplete_Class) {
                throw new ElastiException(
                    $this->wrapExceptionParams(array(
                    )), 
                    1617
                );

                echo '<pre>';
                var_dump($entity);
                echo '</pre>';
                $entity = $this->wireEntity();
                return $this->getFindByResult($filter, $queryType, $arrayCollection, ($round + 1));
                
                
                
                
                // dump($entity);
            }
            // dump($entity);
            $errorCounter = 0;
            $foundObj = false;

            if (!$this->permissionGranted($entity)) {
                continue;
            }

            $filterConditions = !$filterConditions ? array() : $filterConditions;
            if ($filterConditions == array()) {
                $foundObj = true;
            } else {
                $meetsAllConditions = true;
                $conditionCounter = 0;
                foreach ($filterConditions as $index => $filterCondition) {
                    // dump($filterCondition);
                    $prop1 = $filterCondition['key'];
                    $value1 = $filterCondition['value'];
                    // $foundValue = null;
                    $like = false;
                    if ($value1 && $value1 != trim($value1, '%')) {
                        $like = true;
                    }
                    $getProp = 'get'.ucfirst($prop1);

                    // if ($prop1 == 'email') {
                    //     dump('---------------');
                    //     dump($value1);
                    //     dump($like);
                    //     dump($foundObj);
                    // }


                    if ($like) {
                        if (strpos(strtolower($entity->$getProp()), strtolower(trim($value1, '%'))) === false) {
                            // $foundObj = true;
                            $meetsAllConditions = false;
                        }
                    } else {
                        if ($entity->$getProp() != $value1) {
                            // $foundObj = true;
                            $meetsAllConditions = false;
                            // foreach ($filterConditions as $filterCondition2) {
                            //     $prop2 = $filterCondition2['key'];
                            //     $value2 = $filterCondition2['value'];
                            //     $getProp = 'get'.ucfirst($prop2);
                            //     if ($entity->$getProp() != $value2) {
                            //         $errorCounter++;
                            //     }
                            // }
                        }
                    }


                    // if ($foundObj) {
                    //     foreach ($filterConditions as $filterCondition2) {
                    //         $prop2 = $filterCondition2['key'];
                    //         $value2 = $filterCondition2['value'];
                    //         $getProp = 'get'.ucfirst($prop2);
                    //         if ($entity->$getProp() != $value2) {
                    //             $errorCounter++;
                    //         }
                    //     }
                    // }
                    $conditionCounter++;
                }
                if ($meetsAllConditions == true) {
                    $foundObj = true;
                }
            }
            
            // if ($foundObj) {
            //     dump($errorCounter);
            //     dump($entity);
            // }
            if ($errorCounter === 0 && $foundObj === true) {
                if ($queryType == 'result') {
                    $return[] = $entity;
                } elseif ($queryType == 'indexArray') {
                    $return[] = $i;
                } else {

                }
            }
            // dump('return: ');
            // if (isset($filter['conditions']) && isset($filter['conditions'][0]) && $filter['conditions'][0]['key'] == 'username') {
            //     dump($filter);
            //     dump($return);exit;
            // }
            
        }
        // dump('getFindByResult END');
        // dump($return);
        if ($filterLimit == 1) {
            if (is_array($return)) {
                return (is_array($return) && count($return) > 0) ? $return[0] : null;
            } else {
                return null;
            }
        } else {
            return $return;
        }
    }

    public function findAll($debug = null)
    {
        // if ($debug) {
        //     dump('path: '.$debug['path']);
        // }
        $arrayCollection = $this->getArrayColectionFromFile(array('findAll'));
        if (!is_array($arrayCollection)) {
            return false;
        }
        $this->callInitRepository();
        // dump('alma!!!');exit;
        $returnArrayCollection = array();
        // dump($arrayCollection);
        // if ($debug) {
        //     dump('path: '.$debug['path']);
        //     dump($arrayCollection);
        // }
        foreach ($arrayCollection as $entity) {
            if ($entity instanceof \__PHP_Incomplete_Class) {
                $this->wireEntity();
                return $this->findAll();
                // $entity = $this->mendEntity($entity);
                // dump($arrayCollection);
            } else {
                // dump($arrayCollection); exit;
            }
            if ($this->permissionGranted($entity)) {
                $returnArrayCollection[] = $entity;
            } else {
                // dump('!$this->permissionGranted($entity)');exit;
            }
        }
        return $returnArrayCollection;
    }

    public static function autoTransformValue($value)
    {
        if ($value == '*false*') {
            $value = false;
        }
        if ($value == '*null*') {
            $value = null;
        }
        if ($value == '*true*') {
            $value = true;
        }
        return $value;
    }

    public function store($entity)
    {
        // dump($entity);
        $reflector = new Reflector();
        $properties = $reflector->getProperties($entity);
        // dump($properties);
        foreach ($properties as $reflectionProperty) {
            $property = $reflectionProperty->name;
            $getter = 'get'.ucfirst($property);
            $setter = 'set'.ucfirst($property);
            $entity->$setter(self::autoTransformValue($entity->$getter()));
        }
        $result = $entity->getId() ? $this->update($entity) : $this->insert($entity);

        if ($result && isset($result['result']) && $result['result'] == true && isset($result['entity'])) {
            return $result['entity'];
        } else {
            return $result;
        }
    }

    protected function insert($entity)
    {
        if ($this->emulateAutoIncrement && !in_array($this->emulateAutoIncrement, $this->properties)) {
            throw new ElastiException('Auto increment must be element of properties', ElastiException::ERROR_TYPE_SECRET_PROG);
        }
        $arrayCollection = $this->getArrayColectionFromFile();
        $greatestKey = 0;
        $propOccurrences = [];
        $duplication = false;
        $missingUnique = false;
        $arrayCollection = (!$arrayCollection || !is_array($arrayCollection)) ? [] : $arrayCollection;
        foreach ($arrayCollection as $entity1) {
            if (!is_array($this->properties)) {
                throw new ElastiException('You should define properties for this FBS-repo', ElastiException::ERROR_TYPE_SECRET_PROG);
            }
            foreach ($this->properties as $prop) {
                if (!isset($propOccurrences[$prop])) {
                    $propOccurrences[$prop] = [];
                }
                $getPropMethod = 'get'.ucfirst($prop);
                if (!method_exists($entity1, $getPropMethod)) {
                    dump($entity1);
                    dump($this);
                }
                $value = $entity1->$getPropMethod();
                if ($prop == $this->emulateAutoIncrement) {
                    $greatestKey = ($value > $greatestKey) ? $value : $greatestKey;
                }
                if (in_array($prop, $this->uniqueProperties)) {
                    if (!in_array($value, $propOccurrences[$prop])) {
                        $propOccurrences[$prop][] = $value;
                    }
                }
            }
        }

        $duplicationMessage = '';
        foreach ($this->uniqueProperties as $uniqueProperty) {
            $getPropMethod = 'get'.ucfirst($uniqueProperty);
            if (isset($propOccurrences[$uniqueProperty])
                && in_array($entity->$getPropMethod(), $propOccurrences[$uniqueProperty])) {
                    $duplication = true;
                    $duplicationMessage = $uniqueProperty;
            }
        }

        if ($this->emulateAutoIncrement) {
            $idSetMethod = 'set'.ucfirst($this->emulateAutoIncrement);
            $entity->$idSetMethod($greatestKey + 1);
        }
        if (!$duplication && !$missingUnique) {
            $arrayCollection[] = $entity;
            $data = serialize($arrayCollection);
            $this->writeToFile($data);
            return [
                'result' => true,
                'message' => null,
                'entity' => $entity
            ];
        } else {
            $message = 'Errors: duplication: '.(int)$duplication.'
                , msg: '.$duplicationMessage.'
                , missingUnique: '.(int)$missingUnique;

            return [
                'result' => true,
                'message' => $message,
                'entity' => $entity
            ];
        }
    }

    protected function update($entity)
    {
        if (!$this->emulateAutoIncrement) {
            throw new ElastiException('You cannot use modify() if emulateAutoIncrement is not set', ElastiException::ERROR_TYPE_SECRET_PROG);
        }

        $getIdMethod = 'get'.ucfirst($this->emulateAutoIncrement);
        if ($entity->$getIdMethod()) {
            $id = $entity->$getIdMethod();
            $arrayCollection = $this->getArrayColectionFromFile();

            for ($i = 0; $i < count($arrayCollection); $i++) {
                $currentObject = $arrayCollection[$i];
                if ($currentObject->$getIdMethod() == $id) {
                    $arrayCollection[$i] = $entity;
                    $data = serialize($arrayCollection);
                    $this->writeToFile($data);
                    // dump('saved');
                    // dump($arrayCollection);
                    return [
                        'result' => true,
                        'message' => null,
                        'entity' => $entity
                    ];
                }
            }
        } else {
            throw new ElastiException($getIdMethod.' method was not found in the object, or has no value');
        }
        return [
            'result' => false,
            'message' => null,
            'entity' => $entity
        ];;
    }

    public function sortBy($arrayCollection, $sortByArray, $autoUpdatePosition = true)
    {
        if (!is_array($arrayCollection)) {
            return false;
        }
        foreach (array_reverse($sortByArray) as $property => $direction) {
            $arrayCollection = $this->sortLoopBy($arrayCollection, $property, $direction);
            if ($property == $this->positionField && $autoUpdatePosition) {
                $positionGetter = 'get'.ucfirst($this->positionField);
                $positionSetter = 'set'.ucfirst($this->positionField);
                $counter = 0;
                for ($i = 0; $i < count($arrayCollection); $i++) {
                    if ($arrayCollection[$i]->$positionGetter() != $counter) {
                        $arrayCollection[$i]->$positionSetter($counter);
                        $this->store($arrayCollection[$i]);
                    }
                    $counter++;
                }
            }
        }
        return $arrayCollection;
    }

    private function sortLoopBy($arrayCollection, $property, $direction)
    {
        if (strtoupper($direction) == 'ASC') {
            return $this->ascSortBy($arrayCollection, $property);
        }
        if (strtoupper($direction) == 'DESC') {
            return $this->descSortBy($arrayCollection, $property);
        }
    }

    private function ascSortBy($arrayCollection, $property)
    {
        if (!is_array($arrayCollection)) {
            return false;
        }
        usort($arrayCollection, function($a, $b) use ($property) {
            $getter = 'get'.ucfirst($property);
            if ($a->$getter() == $b->$getter()) return 0;
            return ($a->$getter() < $b->$getter()) ? -1 : 1;
        });
        return $arrayCollection;
    }

    private function descSortBy($arrayCollection, $property)
    {
        if (!is_array($arrayCollection)) {
            return false;
        }
        usort($arrayCollection, function($a, $b) use ($property) {
            $getter = 'get'.ucfirst($property);
            if ($a->$getter() == $b->$getter()) return 0;
            return ($a->$getter() < $b->$getter()) ? 1 : -1;
        });
        return $arrayCollection;
    }

    public function removeBy($filter)
    {
        $filter = $this->transformFilter($filter);
        $indexes = $this->findBy($filter, 'indexArray', null);
        $arrayCollection = $this->getArrayColectionFromFile();
        if (!is_array($arrayCollection)) {
            // dump($arrayCollection);
        }
        foreach ($indexes as $index) {
            unset($arrayCollection[$index]);
        }
        if (empty($arrayCollection)) {
            return false;
        }
        $arrayCollection = array_values($arrayCollection);
        $data = serialize($arrayCollection);
        // dump($this->filePath);
        // dump($arrayCollection);
        // dump($data);exit;
        $this->writeToFile($data);
    }

    // public function sortObjectsBy($property)
    // {
    //     $arrayCollection = $this->getArrayColectionFromFile();
    //     foreach ($arrayCollection as $entity1) {
    //         $getPropMethod = 'get'.ucfirst($property);
    //         $value = $entity1->$getPropMethod();
    //
    //     }
    // }

    public function removeAllObjects()
    {
        $this->writeToFile(serialize([]));
    }

    public function getArrayColectionFromFile($debug = null)
    {
        if (!$this->filePath || $this->filePath == '') {
            // if (!$debug) {
            //     return false;
            // }
            throw new ElastiException(
                $this->wrapExceptionParams(array()), 
                1616
            );
        }
        $rawData = file_get_contents($this->filePath);
        $data = $rawData;
        if ($this->encryptFile) {
            $data = $this->decrypt($rawData);
        }

        $array = [];
        try {
            // $array = $data;
            // $data = 'sdasdasd';
            // dump($data);
            $array = @unserialize($data);
            // dump($array);exit;
            // if (isset($array[0]) && is_object($array[0])) {
            //     // dump($array);exit;
            //     dump(get_class($array[0]));exit;
            // }
        } catch (\Exception $e) {
            $data1 = $this->decrypt($rawData, true); // Decrypted with old method
            // $encryptedData = $this->encrypt($rawData);
            // $this->writeToFile($encryptedData);
            // dump($this->decrypt($rawData));
            // dump($rawData);
            // dump($data);
            // dump($encryptedData);exit;
            // if (!empty($data))
            // dump($encryptedData);
            // dump($rawData);// exit;
            // dump($data1);exit;
            $array = unserialize($data1);
            // dump($array);exit; 
            // dump(file_get_contents($this->filePath));
            // dump($this->decrypt(file_get_contents($this->filePath), false));
            // dump($this->decrypt(file_get_contents($this->filePath), true));
            // $decryptedDataOldMethod = $this->decrypt(file_get_contents($this->filePath), true);
            // dump($data); 
            // dump($rawData); 

            // $enc = $this->encrypt('Alma alma piros alma!!!!');
            // $decr = $this->decrypt($enc);
            // dump($decr);
            // dump($e); exit;
        }

        if (!is_array($array)) {
            dump($array);exit;
        }
        
        return $array;
    }

    public function writeToFile($data, $handleExistingData = 'overwrite')
    {
        $filePath = $this->filePath;
        if ($this->encryptFile) {
            $data = $this->encrypt($data);
        }
        $file = @fopen($filePath, "w");
        if (!$filePath) {
            throw new ElastiException(
                $this->wrapExceptionParams(array(
                    'filePath' => $filePath
                )), 
                (FileHandler::determineCompetence($filePath, $this->getContainer()) == 2 ? 1411 : 1611)
            );
            // throw new ElastiException('Unable to open file. Error: '.error_get_last()['message'], ElastiException::ERROR_TYPE_SECRET_PROG);
        }
        if (!FileHandler::fileExists($this->filePath)) {
            throw new ElastiException(
                $this->wrapExceptionParams(array(
                    'filePath' => $filePath
                )), 
                (FileHandler::determineCompetence($filePath, $this->getContainer()) == 2 ? 1412 : 1612)
            );
            // throw new ElastiException('Unable to create file: '.$filePath, ElastiException::ERROR_TYPE_SECRET_PROG);
        }
        if ($handleExistingData == 'overwrite') {
            if (!@file_put_contents($filePath, $data, LOCK_EX)) {
                throw new ElastiException(
                    $this->wrapExceptionParams(array(
                        'filePath' => $filePath
                    )), 
                    (FileHandler::determineCompetence($filePath, $this->getContainer()) == 2 ? 1413 : 1613)
                );
            }
        }
        if ($handleExistingData == 'append') {
            if (!@file_put_contents($filePath, $data, FILE_APPEND | LOCK_EX)) {
                throw new ElastiException(
                    $this->wrapExceptionParams(array(
                        'filePath' => $filePath
                    )), 
                    (FileHandler::determineCompetence($filePath, $this->getContainer()) == 2 ? 1414 : 1614)
                );
                // throw new ElastiException('Unable to write file2: '.$filePath, ElastiException::ERROR_TYPE_SECRET_PROG);
            }
        }
    }

    public function makeEntityFromRecordData($recordData, $entity = null)
    {
        return false;
    }

    public function getTableName()
    {
        return false;
    }
}
